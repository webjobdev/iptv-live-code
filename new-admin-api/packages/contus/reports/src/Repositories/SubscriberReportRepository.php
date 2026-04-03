<?php

namespace Contus\Reports\Repositories;

use Barryvdh\DomPDF\Facade\Pdf;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Reports\Model\SubscriberReports;
use Contus\Subscribers\Model\OrgSubscribers;
use Contus\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriberReportRepository extends Repository
{
    protected $_subscriberReports;
    protected $_orgSubscribers;

    public function __construct(SubscriberReports $subscriberReports, OrgSubscribers $orgSubscribers)
    {
        parent::__construct();
        $this->_subscriberReports = $subscriberReports;
        $this->_orgSubscribers = $orgSubscribers;
    }

    public function postCreate()
    {
        $createData = $this->create($this->request->all());
        return $createData;
    }

    public function create($requestData)
    {
       $user = Auth::user();

        $this->setRules([
            'report_name' => 'required',
            'report_type' => 'required',
            'organization' => 'required',
            // 'report_fields' => 'required',
            // 'report_filter' => 'required',
            // 'generate' => 'required',
        ]);

        $this->_validate();

        $insert = new SubscriberReports();

        $insert->report_name = $requestData['report_name'];
        $insert->report_type = $requestData['report_type'];
        $insert->organization = $requestData['organization'];
        $insert->report_fields = $requestData['report_fields'];
        $insert->report_filter = $requestData['report_filter'];
        $insert->generate = $requestData['generate'];
        $insert->created_by = $user->id;

        $insert->save();

        return 'success';
    }

    public function postGenerate()
    {
        $GenerateData = $this->reportenerate($this->request->all());
        return $GenerateData;
    }

    public function reportenerate($requestData)
    {
        $user = Auth::user();
        $this->setRules([
            'report_name' => 'required',
            'report_type' => 'required',
            'organization' => 'required',
            // 'report_fields' => 'required',
            // 'report_filter' => 'required',
            // 'generate' => 'required',
        ]);

        $this->_validate();

        $insert = new SubscriberReports();

        $insert->report_name = $requestData['report_name'];
        $insert->report_type = $requestData['report_type'];
        $insert->organization = $requestData['organization'];
        $insert->report_fields = $requestData['report_fields'];
        $insert->report_filter = $requestData['report_filter'];
        $insert->generate = $requestData['generate'];
        $insert->created_by = $user->id;

        $insert->save();

        return 'success';
    }

    public function report($id)
    {
        if (!empty($id)) {
            $data = $this->_subscriberReports->findOrFail($id);

            $data->generate = $this->request->generate ? 1 : 0;
            $data->save();

            return 'success';
        } else {
            return
                'false';
        }
    }

    public function savepdf($id)
    {

        // dd($orgSubscribers);
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_subscriberReports)
            ->setEagerLoadingModels(['organization']);
        return $this;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'value' => 'SNo', 'sort' => false],
                ['name' => 'Number', 'value' => '', 'sort' => true],
                ['name' => 'Name', 'value' => '', 'sort' => false],
                ['name' => 'Created', 'value' => '', 'sort' => false],
                ['name' => 'Organization', 'value' => '', 'sort' => false],
                ['name' => 'Actions', 'value' => '', 'sort' => false],
            ]
        ];
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD)
            && is_array($this->request->input(StringLiterals::SEARCHRECORD))
            ? $this->request->input(StringLiterals::SEARCHRECORD)
            : [];

        foreach ($searchRecordUsers as $key => $value) {
            if (in_array($key, ['is_active', 'is_parental']) && $value === 'all') {
                continue;
            }

            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }

        return $builderCoupon;
    }
}