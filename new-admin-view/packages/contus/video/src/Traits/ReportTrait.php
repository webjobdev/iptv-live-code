<?php
/**
 * Report/Analytics Trait
 *
 * To manage the functionalities related to the report/analytics
 *
 * @vendor Contus
 *
 * @package Categories
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Traits;

use DateTime;
use MongoDB\BSON\UTCDateTime;

trait ReportTrait { /**
    * Class property to hold the default sorting field name
    *
    * @var string
    */
    protected $defaultSortFieldName = 'created_at';
     /**
     * Class property to hold the default sort type
     * Here ASC = 1, DESC = -1 (MongoDB need to set 1 or -1 for sroting. Not ASC, DESC)
     * That's why, here set -1.
     *
     * @var string
     */
    protected $defaultSortType = -1;
     /**
     * Class property to hold the related model aggregation array
     *
     * @var array
     */
    protected $aggregateModel = [];
    /**
     * Set gridmodel sorting filedname & type
     *
     * @param array 
     * @return $this
     */
    public function setSorting($fieldName = 'created_at', $sortType = -1){
        $this->defaultSortFieldName = $fieldName;
        $this->defaultSortType = $sortType;
        return $this;
    }

    /**
     * Set gridmodel related aggregation array
     *
     * @param array 
     * @return $this
     */
    protected function setAggregate($aggregateModel) {
        $this->aggregateModel = $aggregateModel;
        
        return $this;
    }

    /**
     * Function used to retrieve records with field sorting(asc/desc) from database
     *
     *
     * @return array
     * @throws \Exception
     */
    public function getRecords(){
        $page = $rowsPerPage = null;
        extract ( $this->request->all () );
        $recordsCount = 0;
        $records = [];
        $aggregate = $this->aggregateModel;
        try {
            $aggregate = $this->updateGridQuery ($aggregate);
            $aggregate = $this->searchFilter ( $aggregate );

            // To get the total records, not required the sorting & paination options in aggregation
            // That's why here, we ge the total records & then apply the sorting & pagination options to aggregation
            $recordsCount = $this->getTotalRecords($this->gridModel, $aggregate);

            $aggregate = $this->getQuerySort($aggregate);
            $aggregate = $this->getPagination($aggregate);

            $records = $this->gridModel::raw(function($collection) use($aggregate){
                return $collection->aggregate($aggregate,["allowDiskUse" => true]);
            });
        }
        catch ( Exception $e ) {
            $this->logger->error ( $e->getMessage () );
        }
        $recordsArr = !empty($records) ? $records->toArray() : $records;
        return ['current_page' => $page, 'per_page' => $rowsPerPage, 'total' => $recordsCount, 'data' => $recordsArr];
    }
    /**
     * Get the total records for given query model & aggregation
     * 
     * @param object
     * @param array
     * @return int
     */
    public function getTotalRecords($modelInst, $aggregateQuery){
        array_push($aggregateQuery, [
            '$group' => [
                '_id' => null,
                'total' => ['$sum' => 1]
            ]
        ]);

        $totalCount = $modelInst::raw(function($collection) use($aggregateQuery){
            return $collection->aggregate($aggregateQuery, ["allowDiskUse" => true]);
        });

        return $totalCount->isEmpty() ? 0 : $totalCount[0]->total;
    }
    /**
     * Get mongo aggrigation query sort
     * 
     * @param array
     * @param string
     * @param int
     * @return array
     */
    public function getQuerySort($builder){
        $orderByFieldName = $sortOrder = null;
        extract ( $this->request->all () );
        $sort = (is_null ( $orderByFieldName ) || is_null ( $sortOrder )) ? [$this->defaultSortFieldName => $this->defaultSortType] : [$orderByFieldName => strtolower($sortOrder) == 'desc' ? -1 : 1]; 
        
        // Here, find the $project key in aggregation
        // If $match appear in aggregation ,then $project index will be 3.
        // Otherwise, it will be 2.
        $projectIndex = array_key_exists('$match',$builder[0]) ? 3 : 2;
        
        // Now by default Web Report contains $group & $project, Excel Report $group, $project & $project in aggregation.
        // Here, we try to push the sort after first '$project' in aggregation.
        // Because when download excel we have two '$project' option, for excel report we are doing some concatination operation
        // in second $project array. Due to this avg_completion_rate field sorting not working properly in downloaded excel file.
        // To solve above written issue we are doing below process.
        $startBuilder = array_slice($builder, 0, $projectIndex, true);
        array_push($startBuilder, ['$sort' => $sort]);
        $endBuilder = array_slice($builder, $projectIndex, null, true);

        if(!empty($endBuilder) && !empty(array_values($endBuilder))){
            foreach(array_values($endBuilder) as $currentAggregate){
                array_push($startBuilder, $currentAggregate);
            }
        }

        return $startBuilder;
    }

    /**
     * Get mongo aggrigation query with pagination
     *@params rowsperpage , page and builder
     * 
     * @return array
     */
    Public function getPagination($builder){
        $rowsPerPage = $page = null;
        extract ( $this->request->all () );
        $pageLimit = (!empty($rowsPerPage) && is_numeric ( $rowsPerPage )) ? $rowsPerPage : 10;
        $pageLimit = (int) $pageLimit;
        $skip = !empty($page) ? $pageLimit*($page-1) : 0;
        array_push($builder, ['$skip' => $skip]);
        array_push($builder, ['$limit' => $pageLimit]);
        return  $builder;
    }
    /**
     * Get the UTC start date for given date by setting day start time as '00:00:00'
     * 
     * @param mixed
     * @return object
     */
    public function mongoStartDate($dateTime){
        $date = $this->getUTCDate($dateTime);
        return $this->getMongoUTCDateTime($date.' 00:00:00');
    }

    /**
     * Get the UTC end date for given date by setting day end time as '23:59:59'
     * 
     * @param mixed
     * @return object
     */
    public function mongoEndDate($dateTime){
        $date = $this->getUTCDate($dateTime);
        return $this->getMongoUTCDateTime($date.' 23:59:59');
    }

    /**
     * Get the utc timezone date & time based on given format for given dateTime
     * 
     * @param mixed
     * @param string
     * @return object
     */
    public function getUTCDate($dateTime, $format = 'Y-m-d'){
        // If the value is already a UTCDateTime instance, we don't need to parse it.
        if ($dateTime instanceof UTCDateTime) {
            return ($dateTime)->toDateTime()->format($format);
        }
        // Let Eloquent convert the value to a DateTime instance.
        if ($dateTime instanceof DateTime) {
            return (new UTCDateTime($dateTime))->toDateTime()->format($format);
        }
        return (new UTCDateTime(new DateTime($dateTime)))->toDateTime()->format($format);
    }

    /**
     * Get the utc timezone mongoDB date & time object for given dateTime
     * 
     * @param mixed
     * @param string
     * @return object
     */
    public function getMongoUTCDateTime($dateTime){
        // If the value is already a UTCDateTime instance, we don't need to parse it.
        if ($dateTime instanceof UTCDateTime) {
            return $dateTime;
        }
        // Let Eloquent convert the value to a DateTime instance.
        if ($dateTime instanceof DateTime) {
            return (new UTCDateTime($dateTime));
        }
        return (new UTCDateTime(new DateTime($dateTime)));
    }
    /**
     * Method to convert MongoDBdates
     * @param string #date
     * 
     * @return array
     */
    public function mongoDBDateConversion($date){
      $date = $this->getUTCDate($date);
      return $this->getMongoUTCDateTime($date);
    }
    /**
     * Method to return the aggregation required to perform the region wise video counts analytics
     * @param int $totalRecord
     * 
     * @return array
     */
    public function regionWiseVideoViewCountAggregateQuery($totalRecord){
        return [
            [ '$group'=> ['_id'=> '$country', 'count'=> [ '$sum'=> 1 ]]],
            [ '$project'=> [ 
                'count'=> 1, 
                'percentage'=> [
                                '$multiply'=> [ 
                                [  
                                    '$divide'=> [  
                                        '$count',
                                        ['$literal'=> $totalRecord ]
                                    ] 
                                ], 100 
                            ]
                    ]
                ]
            ],
            ['$sort' => ['percentage' => -1]]   
        ];
    }
    /**
     * Method to return the aggregation required to perform the platform wise video counts analytics
     * @param int $platformType
     * 
     * @return array
     */
    public function platformWiseVideoViewCountAggregateQuery($platformType){
        return [
            [ '$group'=> [
                '_id'=> '$platform', 
                'count'=> [ '$sum'=> 1 ]
                ]
            ],
            [ '$project'=> [ 'count'=> 1]
            ]    
        ];
    }

     /**
     * Method to return the aggregation required to perform the region wise video counts analytics
     * @param int $totalRecord
     * 
     * @return array
     */
    public function dateWiseVideoViewCountAggregateQuery($totalRecord,$video_id,$dateType){
        switch ($dateType){
            case '4' :
                return [
                    ['$match' => ['video_id'=> intval($video_id)]],            
                    [ '$group'=> ['_id'=> ['$dateToString'=> ['format'=> '%m-%d','date'=> '$created_at']],"sum"=> [ '$sum'=> 1 ]]],
                    [ '$project'=> ['date'=> '$_id', "sum"=> 1,"_id"=> 0]],
                    ['$sort' => ['date' =>1]]  
                ];    
          
            case '3' :
                return [
                    ['$match' => ['video_id'=> intval($video_id)]],            
                    [ '$group'=> ['_id'=> ['$dateToString'=> ['format'=> '%m-%d','date'=> '$created_at']],"sum"=> [ '$sum'=> 1 ]]],
                    [ '$project'=> ['date'=> '$_id', "sum"=> 1,"_id"=> 0]],
                    ['$sort' => ['date' =>1]]  
                ];
    
            case '2' :
                return [
                    ['$match' => ['video_id'=> intval($video_id)]],            
                    [ '$group'=> ['_id'=> ['$dateToString'=> ['format'=> '%Y-%m','date'=> '$created_at']],"sum"=> [ '$sum'=> 1 ]]],
                    [ '$project'=> ['date'=> '$_id', "sum"=> 1,"_id"=> 0]],
                    ['$sort' => ['date' =>1]]  
                ];
        
          
            case '1':
                return [
                    ['$match' => ['video_id'=> intval($video_id)]],            
                    [ '$group'=> ['_id'=> ['$dateToString'=> ['format'=> '%Y','date'=> '$created_at']],"sum"=> [ '$sum'=> 1 ]]],
                    [ '$project'=> ['date'=> '$_id', "sum"=> 1,"_id"=> 0]],
                    ['$sort' => ['date' =>1]]  
                ];
    
            default:
            break;

        }      
       
    }

     /**
     * Method to return the aggregation required to perform the region wise video counts analytics
     * @param int $totalRecord
     * 
     * @return array
     */
    public function geographicWiseVideoViewCountAggregateQuery($totalRecord,$video_id){
        
                return [
                    ['$match' => ['video_id'=> intval($video_id)]],            
                    [ '$group'=> ['_id'=> '$country',"sum"=> [ '$sum'=> 1 ]]],
                    [ '$project'=> ['country'=> '$_id', "sum"=> 1,"_id"=> 0]],
                    ['$sort' => ['country' =>1]]  
                ];
       
    }
}