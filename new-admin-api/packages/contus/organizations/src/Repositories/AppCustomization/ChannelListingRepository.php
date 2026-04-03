<?php

namespace Contus\Organizations\Repositories\AppCustomization;

use Contus\Base\Repository;
use Contus\Organizations\Model\ChannelListing;

class ChannelListingRepository extends Repository
{

    protected $_Channel_Listing;

    public function __construct(ChannelListing $channeListing)
    {
        parent::__construct();
        $this->_Channel_Listing = $channeListing;
    }

    public function postAdd()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requestData)
    {
        $this->setRules([
            'organization_id' => 'nullable|required',
            'monitization_plan_id' => 'nullable|required',
            'channel_listing' => 'nullable|required',
            'sequence_assigned_channels' => 'array|nullable',
            'group_channel_list' => 'array|nullable',
        ]);

        $this->_validate();

        $insert = new ChannelListing();

        $insert->organization_id = $requestData['organization_id'];
        $insert->monitization_plan_id = $requestData['monitization_plan_id'];
        $insert->channel_listing = $requestData['channel_listing'];
        $insert->sequence_assigned_channels = json_encode($requestData['sequence_assigned_channels']);
        $insert->group_channel_list = json_encode($requestData['group_channel_list']);

        $insert->save();

        return 'success';
    }


    public function prepareGrid()
    {
        $this->setGridModel($this->_Channel_Listing);
        // ->setEagerLoadingModels(['GetMonPlan.contentSets']);
        return $this;
    }
}