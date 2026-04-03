<?php

namespace Contus\Subscribers\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Subscribers\Model\OrgCreditCard;
use Illuminate\Support\Facades\Log;

class CreditCardRepository extends Repository
{
    protected $_creditcard;

    public function __construct(OrgCreditCard $orgCreditCard)
    {
        parent::__construct();
        $this->_creditcard = $orgCreditCard;
    }


    public function addorupdatecreditcard($id = null)
    {
        // Log::info('fetch id.', ['id' => $id]);
        try {
            if (!empty($id)) {
                $creditcard = $this->_creditcard->find($id);
                // if (!is_object($creditcard)) {
                //     Log::warning('[Credit Card] no credit carf found for given Id.', ['id' => $id]);
                //     return false;
                // }

                // Log::info('[Credit Card] updating exiting credit card.', ['credit_card_id' => $id]);

                $this->setRules([
                    'profile_name' => 'nullable|max:255',
                    'security_type' => 'nullable|max:255',
                    'card_type' => 'nullable|max:255',
                    'card_number' => 'nullable|max:255',
                    'expiration_month' => 'nullable|max:255',
                    'expiration_year' => 'nullable|max:255',
                    'cvv' => 'nullable|max:255',
                    'billing_address' => 'nullable|max:255',
                    'first_name' => 'nullable|max:255',
                    'last_name' => 'nullable|max:255',
                    'email' => 'nullable|max:255',
                    'phone_number' => 'nullable|max:255',
                    'address' => 'nullable|max:255',
                    'city' => 'nullable|max:255',
                    'zip_code' => 'nullable|max:255',
                    'country' => 'nullable|max:255',
                    'state' => 'nullable|max:255',
                    'is_active' => 'nullable|max:255',
                ]);
            } else {
                // Log::info('[Credit Card] Creating new credit card record.');

                $this->setRules([
                    'profile_name' => 'nullable|max:255',
                    'security_type' => 'nullable|max:255',
                    'card_type' => 'nullable|max:255',
                    'card_number' => 'nullable|max:255',
                    'expiration_month' => 'nullable|max:255',
                    'expiration_year' => 'nullable|max:255',
                    'cvv' => 'nullable|max:255',
                    'billing_address' => 'nullable|max:255',
                    'first_name' => 'nullable|max:255',
                    'last_name' => 'nullable|max:255',
                    'email' => 'nullable|max:255',
                    'phone_number' => 'nullable|max:255',
                    'address' => 'nullable|max:255',
                    'city' => 'nullable|max:255',
                    'zip_code' => 'nullable|max:255',
                    'country' => 'nullable|max:255',
                    'state' => 'nullable|max:255',
                    'is_active' => 'nullable|max:255',
                ]);

                $creditcard = new OrgCreditCard();
                $creditcard->is_active = 1;
            }

            $this->_validate();

            foreach ($this->request->all() as $key => $value) {
                if ($creditcard->isFillable($key)) {
                    $creditcard->$key = $value;
                }
            }

            if ($creditcard->save()) {
                // Log::info('[Credit Card] Credit Card saved successfully.', ['credit_card_id' => $creditcard->id]);
                return response()->json([
                    'success' => true,
                    'message' => trans('subscribers::index.credit_card_update.success'),
                ]);
            } else {
                // Log::warning('[Credit Card] Failed to save Credit Card.', ['credit_card_id' => $id ?? 'new']);
                return response()->json([
                    'success' => true,
                    'message' => trans('subscribers::index.credit_card.error'),
                ]);
            }
        } catch (\Exception $e) {
            // Log::error('[[Credit Card]] Exception during save operation.', [
            //     'id' => $id,
            //     'error' => $e->getMessage(),
            // ]);
            return 0;
        }
    }


    public function prepareGrid()
    {
        $this->setGridModel($this->_creditcard);
        // ->setEagerLoadingModels('subscriber');
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

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => trans('subscribers::index.profile_name'), 'value' => '', 'sort' => true],
                ['name' => trans('subscribers::index.status'), 'value' => '', 'sort' => true],
                ['name' => trans('subscribers::index.type'), 'value' => '', 'sort' => true],
                ['name' => trans('subscribers::index.card_number'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.expiration'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.security_type'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.transactions'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.billing_address'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.user'), 'value' => '', 'sort' => false],
                ['name' => trans('subscribers::index.action'), 'value' => '', 'sort' => false],
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
