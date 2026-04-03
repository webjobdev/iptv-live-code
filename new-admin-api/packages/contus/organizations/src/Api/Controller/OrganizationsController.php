<?php

namespace Contus\Organizations\Api\Controller;

use Carbon\Carbon;
use Contus\Base\Repositories\UploadRepository;
use Illuminate\Http\Request;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Organizations\Model\Organization;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Organizations\Model\OrganizationSubscription;
use Illuminate\Support\Facades\Auth;
use Contus\Organizations\Repositories\OrganizationRepository;

class OrganizationsController extends ApiController
{
    protected $_orgUpload;

    public function __construct(OrganizationRepository $OrganizationRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();

        $this->repository = $OrganizationRepository;
        $this->_orgUpload = $uploadRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo()
    {
        $user = Auth::user();
        return $this->getSuccessJsonResponse(['success']);
    }

    public function search(Request $request) {
        $counts = OrganizationSubscription::count();

        $expiredSubscribers = OrganizationSubscription::where('end_at', '<', Carbon::now())->count();
    }

    public function postAdd() {
        $isCreated = false;
        if ($this->repository->addorganization()) {
            $isCreated = true;
            // $this->request->session()->flash(StringLiterals::SUCCESS, trans('cms::subscription.add.success'));
            return ($isCreated) ? $this->getSuccessJsonResponse(['message' => trans('organizations::index.add.success')]) : $this->getErrorJsonResponse([], trans('organizations::index.add.error'));
        }
    }

    public function getRecordsCounts($id)
    {
        // $isFetched = false;
        // if ($this->repository->getCountOfRecords()) {
        //     $isFetched = true;
        //     return ($isFetched) ? $this->getSuccessJsonResponse(['message' => 'Records Fetched Successfully.']) : $this->getErrorJsonResponse([], 'Error Occurred!');
        // }

        $org = Organization::with(['organization', 'organization.subscribers', 'organization.subscribers.subscription_and_payments_detaile', 'organization.subscribers.devices', 'organization.subscribers.channels'])->where('id', $id)->get();

        $activeSubscribers = [];
        foreach ($org as $orgnztn) {
            // return $orgnztn->organization[0]->subscribers;
            foreach ($orgnztn->organization[0]->subscribers as $o) {
                $activeSubscribers[] = $o;
            }
        }

        return response()->json([
            'success' => true,
            'organizations' => $org,
            'subscribers' => $activeSubscribers,
            'message' => "Records Fetched Successfully."
        ]);
    }
}
