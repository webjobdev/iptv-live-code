<?php

namespace Contus\Subscribers\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Subscribers\Model\SubscriberAssignedDevice;

class PartnerProductRepository extends Repository
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
                // 'subscriber_detaile',
                'subscription_and_payments_detaile.transaction_detail',
            ]);
        return $this;
    }

    protected function updateGridQuery($builder)
    {
        $subscriberId = $this->request->input('subscriber-id') ?? $this->request->input('subscriber_id');
        sleep(2);
        if ($subscriberId) {
            return $builder->where('subscriber_id', $subscriberId);
        }
        return $builder;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => trans('subscribers::index.name'), 'value' => '', 'sort' => true],
                ['name' => trans('subscribers::index.active_from'), 'value' => '', 'sort' => true],
                ['name' => trans('subscribers::index.active_untill'), 'value' => '', 'sort' => true],
                ['name' => trans('subscribers::index.active_error'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.product_status'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.activation'), 'value' => '', 'sort' => false],
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
}
