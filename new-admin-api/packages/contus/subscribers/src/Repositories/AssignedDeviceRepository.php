<?php

namespace Contus\Subscribers\Repositories;

use Carbon\Carbon;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Subscribers\Model\OrgSubscribers;
use Contus\Subscribers\Model\OrgDevices;
use Contus\Subscribers\Model\SubscriberAssignedDevice;
use DB;
use Illuminate\Support\Facades\Log;

class AssignedDeviceRepository extends Repository
{
    protected $subscriberAssignedDevice;

    public function __construct(SubscriberAssignedDevice $subscriberAssignedDevice)
    {
        parent::__construct();
        $this->subscriberAssignedDevice = $subscriberAssignedDevice;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->subscriberAssignedDevice)
            ->setEagerLoadingModels([
                'device_detaile',
                'subscriber_detaile',
                'subscription_and_payments_detaile',
            ]);
        return $this;
    }

    protected function updateGridQuery($builder)
    {
        $subscriberId = $this->request->input('subscriber-id') ?? $this->request->input('subscriber_id');
        if ($subscriberId) {
            return $builder->where('subscriber_id', $subscriberId);
        }
        return $builder;
    }


    public function assigned_device_info($subscriber_id)
    {
        return SubscriberAssignedDevice::where('subscriber_id', $subscriber_id)->get();
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => 'Identifier', 'value' => '', 'sort' => true],
                ['name' => 'Active Since', 'value' => '', 'sort' => false],
                ['name' => 'MAC', 'value' => '', 'sort' => false],
                ['name' => 'Model', 'value' => '', 'sort' => false],
                ['name' => 'Role', 'value' => '', 'sort' => false],
                ['name' => 'Actions', 'value' => '', 'sort' => false],
            ]
        ];
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'is_active' && $value == 'all') {
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

    /**
     * Get devices that are not assigned to any subscriber slot,
     * respecting the organization's device activation limit.
     *
     * @return \Illuminate\Support\Collection
     */
    public function notAssignedDevices()
    {
        $subscriberId = $this->request->get('subscriber_id');
        if (!$subscriberId) {
            return collect();
        }

        // Fetch subscriber to get organization_id
        $subscriber = OrgSubscribers::find($subscriberId);
        Log::info('subscriber', [$subscriber]);
        if (!$subscriber) {
            return collect();
        }

        // Fetch devices from org_subscriber_devices that aren't in subscriber_assigned_device and are active
        $query = OrgDevices::whereDoesntHave('subscriber_assigned_device')
            ->where('is_active', 1)
            ->where('subscriber_id', $subscriberId);

        return $query->get();
    }
}
