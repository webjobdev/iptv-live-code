<?php

namespace Contus\Subscribers\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Subscribers\Model\OrgSubscriberAndPayment;
use Contus\Subscribers\Model\OrgSubscriberPayment;
use Contus\Subscribers\Model\OrgSubscribers;
use Contus\Subscribers\Model\SubsciprionComment;
use Illuminate\Support\Facades\Log;

class PaymentHistoryRepository extends Repository {

    protected $orgSubscriberAndPayment;
    protected $SubscriberTransactionPayment;

    public function __construct(OrgSubscriberAndPayment $orgSubscriberAndPayment, OrgSubscriberPayment $orgSubscriberPayment) {
        parent::__construct();
        $this->orgSubscriberAndPayment = $orgSubscriberAndPayment;
        $this->SubscriberTransactionPayment = $orgSubscriberPayment;
    }


    public function addcomment() {
        $subscriber_id = $this->request->input('subscriber_id', $this->request->input('id'));
        $subscription_and_payments_id = $this->request->input('subscription_and_payments_id');

        // Log::info('addcomment request', [
        //     'subscriber_id' => $subscriber_id,
        //     'comment' => $this->request->input('comment'),
        // ]);

        $subscriber = OrgSubscribers::find($subscriber_id);
        if (!$subscriber) {
            // Log::warning('addcomment failed: subscriber not found', [
            //     'subscriber_id' => $subscriber_id,
            // ]);
            return response()->json([
                'success' => 'false',
                'message' => 'subscriber not found.',
            ], 422);
        }

        $subscription = OrgSubscriberAndPayment::find($subscription_and_payments_id);
        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription/payment not found.',
            ], 422);
        }

        $comment = SubsciprionComment::create([
            'subscriber_id' => $subscriber_id,
            'subscription_and_payments_id' => $subscription_and_payments_id,
            'comment' => $this->request->input('comment'),
        ]);

        // Log::info('addcomment success', [
        //     'comment_id' => $comment->id,
        //     'subscriber_id' => $subscriber_id,
        //     'subscription_and_payments_id' => $subscription_and_payments_id,
        // ]);

        return response()->json([
            'success' => true,
            'message' => trans('subscribers::index.payment_history.success')
        ]);
    }



    public function prepareGrid() {
        $this->setGridModel($this->orgSubscriberAndPayment)
            ->setEagerLoadingModels(['subscriber_detail', 'transaction_detail']);
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


    public function getGridHeadings() {
        return ['heading' => [
            ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
            ['name' => trans('subscribers::index.payment_number'), 'value' => '', 'sort' => true],
            ['name' => trans('subscribers::index.process_date'), 'value' => '', 'sort' => false],
            ['name' => trans('subscribers::index.by_user'), 'value' => '', 'sort' => false],
            ['name' => trans('subscribers::index.payment_service'), 'value' => '', 'sort' => false],
            ['name' => trans('subscribers::index.source_of_purchase'), 'value' => '', 'sort' => false],
            ['name' => trans('subscribers::index.payment_status'), 'value' => '', 'sort' => false],
            ['name' => trans('subscribers::index.internal_comment'), 'value' => '', 'sort' => false],
            ['name' => trans('subscribers::index.recepit'), 'value' => '', 'sort' => false],
            ['name' => trans('subscribers::index.total'), 'value' => '', 'sort' => false],
            ['name' => trans('subscribers::index.action'), 'value' => '', 'sort' => false],
        ]];
    }

    protected function searchFilter($builderCoupon) {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'is_active' && $value == 'all') {
                continue;
            }

            if ($key == 'valid_till') {
                $date = date_create($value);
                $value =  date_format($date, "Y-m-d");
            }
            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }
}
