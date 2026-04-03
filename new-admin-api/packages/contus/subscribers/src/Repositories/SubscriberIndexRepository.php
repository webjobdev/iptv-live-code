<?php

namespace Contus\Subscribers\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
// use Contus\Organizations\Model\OrgSubscribers;
use Contus\Subscribers\Model\OrgSubscriberAndPayment;
use Contus\Subscribers\Model\OrgSubscribers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SubscriberIndexRepository extends Repository
{

    protected $_orgSubscribers;
    protected $_orgSubscriberAndPayment;

    public function __construct(OrgSubscribers $orgSubscribers, OrgSubscriberAndPayment $orgSubscriberAndPayment)
    {
        parent::__construct();
        $this->_orgSubscribers = $orgSubscribers;
        $this->_orgSubscriberAndPayment = $orgSubscriberAndPayment;
    }

    public function addSub()
    {
        $subId = $this->request->input('sub_id', $this->request->input('id'));
        // Log::info('Starting general organization subscriber operation.', ['sub_id' => $subId]);

        $user = Auth::user();

        // Validation rules
        $this->setRules([
            'organization_id' => 'nullable',
            'organization_name' => 'required',
            'account_number' => 'required',
            'pin_code' => 'nullable',
            'user_name' => 'required',
            'password' => 'nullable',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone_number_code' => 'nullable',
            'phone_number' => 'nullable',
            'address' => 'nullable',
            'city' => 'nullable',
            'zip_code' => 'nullable',
            'country' => 'nullable',
            'state' => 'nullable',
            'language' => 'nullable',
            'date_of_birth' => 'nullable',
            'timezone' => 'nullable',
            'ip_address' => 'nullable|string',
        ]);

        $this->_validate();

        $data = $this->request->only([
            'organization_id',
            'organization_name',
            'account_number',
            'pin_code',
            'user_name',
            'password',
            'first_name',
            'last_name',
            'email',
            'phone_number_code',
            'phone_number',
            'address',
            'city',
            'zip_code',
            'country',
            'state',
            'language',
            'date_of_birth',
            'timezone',
        ]);

        $data['ip_address'] = $user->ip_address;

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if ($subId) {
            $subscriber = OrgSubscribers::find($subId);

            if ($subscriber) {
                $subscriber->update($data);

                return response()->json([
                    'status' => true,
                    'message' => trans('organizations::index.anc_send.updated'),
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Subscriber not found.',
                ], 404);
            }
        } else {
            OrgSubscribers::create($data);

            return response()->json([
                'status' => true,
                'message' => trans('subscribers::index.vsubscriber_add.success'),
            ]);
        }
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_orgSubscribers)
            ->setEagerLoadingModels([
                'subscription_and_payments_details',
                // 'subscription_and_payments_details.PlanDetail',
                'FetchOrganization',
                'FetchOrganization.OrgMonPlan'
            ]);
        return $this;
    }

    protected function updateGridQuery($builder)
    {
        $subscriberId = $this->request->input('subscriberId');
        if ($subscriberId) {
            return $builder->where('org_subscribers.id', $subscriberId);
        }
        return $builder;
    }

    public function fetchdata()
    {
        $rowsPerPage = $this->request->get('rowsPerPage');
        // Ensure rowsPerPage is a valid positive integer to avoid MariaDB syntax errors (OFFSET without LIMIT)
        $perPage = (is_numeric($rowsPerPage) && (int) $rowsPerPage > 0) ? (int) $rowsPerPage : 15;

        return DB::table('org_subscribers')
            ->leftJoin('org_subscription_and_payments', 'org_subscription_and_payments.subscriber_id', '=', 'org_subscribers.id')
            ->select('org_subscription_and_payments.*', 'org_subscribers.*')
            ->orderBy('org_subscribers.id', 'desc')
            ->paginate($perPage);
    }

    // public function fetchdata() {
    //     $leftJoin = DB::table('org_subscription_and_payments')
    //         ->leftJoin('org_subscribers', 'org_subscribers.id', '=', 'org_subscription_and_payments.subscriber_id')
    //         ->select('org_subscription_and_payments.*', 'org_subscribers.*')
    //         ->get();

    //     $rightJoin = DB::table('org_subscribers')
    //         ->leftJoin('org_subscription_and_payments', 'org_subscription_and_payments.subscriber_id', '=', 'org_subscribers.id')
    //         ->select('org_subscription_and_payments.*', 'org_subscribers.*')
    //         ->whereNull('org_subscription_and_payments.subscriber_id')
    //         ->get();

    //     return $leftJoin->merge($rightJoin);
    // }


    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => trans('subscribers::index.acc_number'), 'value' => '', 'sort' => true],
                ['name' => trans('subscribers::index.username'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.fullname'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.email'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.phone'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.created'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.subscription'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.subscription_status'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.subscription_length'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.expries'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.autopay'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.active_device'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.last_activity'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.opration'), 'value' => '', 'sort' => false],
            ]
        ];
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        // dd($searchRecordUsers);
        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'is_active' && $value == 'all') {
                continue;
            }

            if ($key == 'user_name') {
                $builderCoupon->where('user_name', 'like', "%$value%");
                continue;
            }

            if ($key == 'account_number') {
                $builderCoupon->where('account_number', 'like', "%$value%");
                continue;
            }

            if ($key == 'valid_till') {
                $date = date_create($value);
                $value = date_format($date, "Y-m-d");
            }
            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }
}
