<?php

namespace Contus\ChannelServices\Repositories;

use App\Console\Commands\SetupEpgService;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\ChannelServices\Model\EpgService;
use Contus\ChannelServices\Services\EpgParserService;

class EpgServiceRepository extends Repository
{
    protected $_epgServiceModel;

    public function __construct(EpgService $epgServiceModel)
    {
        parent::__construct();
        $this->_epgServiceModel = $epgServiceModel;
    }

    public function create()
    {
        return $this->postCreate($this->request->all());
    }

    public function postCreate($requestData)
    {
        $this->setRules([
            'task_name' => 'required',
            'schedule_base' => 'required',
            'start_time' => 'required',
            'time_zone' => 'required',
            'shift_postfix' => 'required',
            'source_url' => 'required',
            'is_active' => 'nullable',
        ]);

        $this->_validate();

        $insert = new EpgService();

        $insert->task_name = $requestData['task_name'];
        $insert->schedule_base = $requestData['schedule_base'];
        $insert->start_time = json_encode($requestData['start_time']);
        $insert->time_zone = $requestData['time_zone'];
        $insert->shift_postfix = $requestData['shift_postfix'];
        $insert->source_url = $requestData['source_url'];
        $insert->is_active = isset($requestData['is_active']) ? 1 : 0;

        $insert->save();

        return 'success';
    }

    public function postEdit($id)
    {
        if (!empty($id)) {
            $data = $this->_epgServiceModel->findOrFail($id);

            $this->setRules([
                'task_name' => 'required',
            ]);

            $this->validate($this->request, $this->getRules());

            $data->task_name = $this->request->task_name;
            $data->schedule_base = $this->request->schedule_base;
            $data->start_time = json_encode($this->request->start_time);
            $data->time_zone = $this->request->time_zone;
            $data->shift_postfix = $this->request->shift_postfix;
            $data->source_url = $this->request->source_url;
            $data->is_active = $this->request->is_active ? 1 : 0;

            $data->save();

            return 'success';
        } else {
            return false;
        }

    }

    public function postToggleEdit($id)
    {
        if (!empty($id)) {
            $channel = $this->_epgServiceModel->findOrFail($id);

            $channel->is_active = $this->request->is_active ? 1 : 0;
            $channel->save();

            return response()->json([
                'success' => true,
                'message' => 'Epg Data Update Successfully.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid ID'], 400);
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_epgServiceModel)
            ->setEagerLoadingModels([
                'executions' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ]);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Task Name', 'value' => 'task_name', 'sort' => true],
                ['name' => 'Source', 'value' => 'source_url', 'sort' => false],
                ['name' => 'Execution Results', 'value' => 'status', 'sort' => false],
                ['name' => 'Schedule', 'value' => 'schedule_base', 'sort' => false],
                ['name' => 'Last Run', 'value' => 'last_run', 'sort' => false],
                ['name' => 'Next Run', 'value' => 'next_run', 'sort' => false],
                ['name' => 'Status', 'value' => 'is_active', 'sort' => false],
                ['name' => 'Actions', 'value' => '', 'sort' => false],
            ]
        ];
    }

    public function postRun($id)
    {
        $epgService = $this->_epgServiceModel->findOrFail($id);

        $parser = app(SetupEpgService::class);
        $executedBy = auth()->user() ? auth()->user()->email : 'Admin';

        if ($parser->handle($epgService, $executedBy)) {
            return response()->json(['success' => true, 'message' => 'EPG Task started successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to run EPG Task.']);
        }
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'is_active' && $value == 'all') {
                continue;
            }

            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }
}