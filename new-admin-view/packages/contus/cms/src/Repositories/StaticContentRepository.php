<?php

/**
 * Static Content Repository
 *
 * To manage the functionalities related to the Static Content Controller
 *
 * @vendor Contus
 *
 * @package Cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Cms\Models\StaticPages;
use Contus\Cms\Models\StaticPagesTranslation;

class StaticContentRepository extends BaseRepository {

    /**
     * Class property to hold the key which hold the static content object
     *
     * @var object
     */
    protected $_staticContent;
    /**
     * Construct method
     *
     * @param Contus\Cms\Models\StaticPages $staticContent
     */
    public function __construct(StaticPages $staticContent) {
        parent::__construct ();
        $this->_staticContent = $staticContent;
        $this->setRules ( [ 'title' => 'required','content' => 'required' ] );
    }
    /**
     * Store a newly created static content or update the static content.
     *
     * @param $id input
     * @return boolean
     */
    public function addOrUpdateStaticContents($id = null) {
        if (! empty ( $id )) {
            $contactUs = $this->_staticContent->find ( $id );
            if (! is_object ( $contactUs )) {
                return false;
            }
            $this->setRules ( [ 'title' => 'sometimes|required','is_active' => 'sometimes|required|boolean','content' => 'sometimes|required' ] );
            $contactUs->is_footer_menu = ($this->request->is_footer_menu) ? 1 : 0;
            $contactUs->updated_at=NOW();
            $contactUs->updator_id = $this->authUser->id;
        } else {
            $this->setRules ( [ 'title' => 'required|max:255','content' => 'required' ] );
            $contactUs = new StaticPages ();
            $contactUs->is_footer_menu = ($this->request->is_footer_menu) ? 1 : 0;
            $contactUs->is_active = 1;
            $contactUs->creator_id = $this->authUser->id;
        }
        $this->_validate ();
        $contactUs->fill ( $this->request->except ( '_token' ) );
        return ($contactUs->save ()) ? 1 : 0;
    }

    /**
     * Get one static content using id
     *
     * @param int $id
     * @return object
     */
    public function getStaticContent($id) {
        //return $this->_staticContent->with('StaticPagesTranslation')->find ( $id );
         return $this->_staticContent->select ( [ 'id','title','slug','content','is_active', 'is_footer_menu' ] )->with('StaticPagesTranslation')->find ( $id );

    }

    /**
     * fetches one Static content using slug
     *
     * @param int $subscriptionSlug
     * @return object
     */
    public function getStaticcontentSlug($subscriptionSlug) {
        return $this->_staticContent->where ( 'slug', $subscriptionSlug )->where ( 'is_active', 1 )->select ( 'id', 'title', 'slug', 'content', 'is_active' )->first ();
    }

    /**
     * Get all static content
     *
     * @return array
     */
    public function getAlltheStaticContents() {
        return $this->_staticContent->paginate ( 10 )->toArray ();
    }

    /**
     * Get all static content
     *
     * @return array
     */
    public function getAllStaticContents() {
        return $this->_staticContent->paginate ( 10 )->toArray ();
    }
    /**
     * Delete one static content using ID
     *
     * @param int $id
     * @return boolean
     */
    public function deleteStaticContent($id) {
        $data = $this->_staticContent->find ( $id );
        if ($data) {
            $data->delete ();
            return true;
        } else {
            return false;
        }
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel ( $this->_staticContent );
        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($staticContentBuilder) {
        /*
         * updated the all user record only an superadmin user.
         */
        return $staticContentBuilder->selectRaw('static_pages.*, static_pages.id as formatted_updated_date');
    }

    /**
     * Function to apply filter for search of latestnews grid
     *
     * @param mixed $builderUsers
     * @return \Illuminate\Database\Eloquent\Builder $builderUsers The builder object of users grid.
     */
    protected function searchFilter($builderStatics) {
        $searchstaticcontentRecordUsers = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        /**
         * Loop the search fields of users grid and use them to filter search results.
         */

        foreach ( $searchstaticcontentRecordUsers as $key => $value ) {
            if ($key == StringLiterals::ISACTIVE && $value == 'all') {
                continue;
            }
            if ($key == 'is_footer_menu' && $value == 'all') {
                continue;
            }
            $builderStatics = $builderStatics->where ( $key, 'like', "%$value%" );
        }

        return $builderStatics;
    }
    /**
     * Get headings for grid
     *
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [ 
        [ 'name' => trans ( 'cms::staticcontent.title' ),StringLiterals::VALUE => 'name','sort' => false ],
        [ 'name' => trans ( 'cms::staticcontent.is_footer_menu' ),StringLiterals::VALUE => 'is_footer_menu','sort' => false ],
        [ 'name' => trans ( 'cms::staticcontent.updated_at' ),StringLiterals::VALUE => '','sort' => false ],
        [ 'name' => trans ( 'cms::smstemplate.action' ),StringLiterals::VALUE => '','sort' => false ] 
        ] ];
    }
    /**
     * Method to update footer menu enable/disable from grid
     * 
     * @return boolen
    */
    public function addOrUpdateFooterMenu($static) {
        return StaticPages::where('id', $static)->update(['is_footer_menu' => $this->request->is_footer_menu]);
    }

    public function updateStaticContenTranslation ($id) {
        if(!empty($id)) {
            $this->setRules(['title' => StringLiterals::REQUIRED, 'content' => StringLiterals::REQUIRED,
            ]);
            $this->validate($this->request, $this->getRules());
            $staticContent_translation;
            if(StaticPagesTranslation::where('static_page_id', '=' ,$id)->where('language_id', '=' ,$this->request->languageCode)->count() > 0){
                $staticContent_translation = StaticPagesTranslation::where('static_page_id', '=', $id)->where('language_id', '=', $this->request->languageCode)->first();
            } else {
                $staticContent_translation = new StaticPagesTranslation();
                $staticContent_translation->static_page_id = $id;
                $staticContent_translation->language_id = $this->request->languageCode;
            }
            $staticContent_translation->title = $this->request->title;
            $staticContent_translation->content = $this->request->content;
            if($staticContent_translation->save()){
                $isStaticContent = true;
            } else {
                return false;
            }

        } else {
            return false;
        }


    }

    public function staticFooterStatus($ids, $isStatus){
        /**
         * Delete the video by the given id
         */
        $ids = is_array($ids) ? $ids : [$ids];
        /**
         * Check if the status is activate.
         * If yes, set is_active field to 1.
         * If no, then set is_active field to 0.
         */
        if ($isStatus == 'show') {
            $status = empty ($ids) ? StringLiterals::LITERALFALSE : $this->_staticContent->whereIn('id', $ids)->update(['is_footer_menu' => 1]);
           
            return $status;
        } else if ($isStatus == 'hide') {
            $status = empty ($ids) ? StringLiterals::LITERALFALSE : $this->_staticContent->whereIn('id', $ids)->update(['is_footer_menu' => 0]);
         
            return $status;
        }
    }
}