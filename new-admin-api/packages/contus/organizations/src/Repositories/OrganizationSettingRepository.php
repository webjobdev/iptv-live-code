<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\OrganizationACSetting;

class OrganizationSettingRepository extends Repository
{
    protected $_orgSetting;

    public function __construct(OrganizationACSetting $orgACSetting)
    {
        parent::__construct();
        $this->_orgSetting = $orgACSetting;
    }

    public function postAdd()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requestData)
    {
        $this->setRules([
            'organization_id' => 'nullable|required',
            'time_zone' => 'nullable|required',
            'system_default' => 'nullable|required',
            'pin_code' => 'nullable|required',
            'random' => 'nullable|required',
            'screen_server' => 'nullable|required',
            'minutes' => 'nullable|required',
            'ss_system_default' => 'nullable|required',
            'stb_start_channel' => 'nullable|required',
            'channel_id' => 'nullable|required',
            'sorting_number' => 'nullable|required',
        ]);

        $this->_validate();

        $data = new OrganizationACSetting();

        $data->organization_id = $requestData['organization_id'];
        $data->time_zone = $requestData['time_zone'];
        $data->system_default = isset($requestData['system_default']) ? 1 : 0;
        $data->pin_code = $requestData['pin_code'];
        $data->random = isset($requestData['random']) ? 1 : 0;
        $data->screen_server = $requestData['screen_server'];
        $data->minutes = isset($requestData['minutes']) ? 1 : 0;
        $data->ss_system_default = isset($requestData['ss_system_default']) ? 1 : 0;
        $data->stb_start_channel = isset($requestData['stb_start_channel']) ? 1 : 0;
        $data->channel_id = $requestData['channel_id'];
        $data->sorting_number = $requestData['sorting_number'];

        $data->save();

        return 'success';
    }

    public function postEdit($id)
    {
        if (!empty($id)) {
            $data = $this->_orgSetting->find($id);
            // dd($data);

            $this->setRules([
                'time_zone' => 'nullable|required',
            ]);
            $this->validate($this->request, $this->getRules());

            // $data->live = $this->request->live ? 1 : 0;

            $data->time_zone = $this->request->time_zone;
            $data->pin_code = $this->request->pin_code;
            $data->screen_server = $this->request->screen_server;
            $data->channel_id = $this->request->channel_id;
            $data->sorting_number = $this->request->sorting_number;
            $data->random = $this->request->random ? 1 : 0;
            $data->minutes = $this->request->minutes ? 1 : 0;
            $data->ss_system_default = $this->request->ss_system_default ? 1 : 0;
            $data->stb_start_channel = $this->request->stb_start_channel ? 1 : 0;


            $data->save();

            return true;

        } else {
            return
                false;
        }
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_orgSetting)
            ->setEagerLoadingModels(['GetChannel']);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Time Zone', 'value' => '', 'sort' => true],
                ['name' => 'Pin Code', 'value' => '', 'sort' => false],
                ['name' => 'Screen Server', 'value' => '', 'sort' => false],
                ['name' => 'Channel', 'value' => '', 'sort' => false],
                ['name' => 'Channel Number', 'value' => '', 'sort' => false],
                ['name' => 'Action', 'value' => '', 'sort' => false],
            ]
        ];
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) &&
            is_array($this->request->input(StringLiterals::SEARCHRECORD)) ?
            $this->request->input(StringLiterals::SEARCHRECORD) : [];

        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'is_active' && $value == 'all') {
                continue;
            }

            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;

    }
}