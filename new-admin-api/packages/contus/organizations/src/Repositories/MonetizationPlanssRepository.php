<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\ChannelListing;
use Contus\Organizations\Model\OrgMonetizationPlanss;
use Contus\Organizations\Model\OrgMonetznSubsriptionContentSets;
use Illuminate\Support\Facades\Auth;

class MonetizationPlanssRepository extends Repository
{

    protected $plan;

    public function __construct(OrgMonetizationPlanss $orgMonitizationPlanss)
    {
        parent::__construct();
        $this->plan = $orgMonitizationPlanss;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->plan)
            ->setEagerLoadingModels(['organization', 'contentSets', 'user']);
        // // $this->setGridModel($this->plan)->setEagerLoadingModels(['organization', 'contentSets', 'accessories', 'partnerProduct', 'extraPartnerProduct']);
        // dd($this);
        return $this;
    }

    protected function updateGridQuery($builder)
    {
        $organizationId = $this->request->input('id') ?? $this->request->input('organization_id');
        if ($organizationId) {
            return $builder->where('organization_id', $organizationId);
        }
        return $builder;
    }

    // public function postCreate() {
    //     $this->setRules([
    //         'organization_id' => 'required',
    //         'subscriptionData' => 'required',
    //     ]);

    //     $user = Auth::user();
    //     $subscriptionData = $this->request->input('subscriptionData');
    //     $subscriptionPlan = new OrgMonetizationPlanss();
    //     $subscriptionPlan->organization_id = $this->request->input('organization_id');
    //     $subscriptionPlan->subscription_name = $subscriptionData['subs_name'];
    //     $subscriptionPlan->subscription_identifier = $subscriptionData['identifier'];
    //     $subscriptionPlan->platforms = $subscriptionData['select_platform'];
    //     $subscriptionPlan->subscription_length = $subscriptionData['subs_length'];
    //     $subscriptionPlan->subs_length_time_type = $subscriptionData['subs_time_type'];
    //     $subscriptionPlan->subscription_type = $subscriptionData['payment_method'];
    //     $subscriptionPlan->advertising = $subscriptionData['is_advertise']  == 'true' ? '1' : '0';
    //     $subscriptionPlan->currency = $subscriptionData['currency'];
    //     $subscriptionPlan->price = $subscriptionData['subs_price'];
    //     $subscriptionPlan->autopay = $subscriptionData['is_autopay'] == 'true' ? '1' : '0';
    //     $subscriptionPlan->subscription_devices = $subscriptionData['subs_devices'];
    //     if ($this->request->input('accessories')) {
    //         $accessory = [];
    //         foreach ($this->request->input('accessories') as $acc) {
    //             $accessory[] = $acc['id'];
    //         }
    //         $subscriptionPlan->org_monetzn_accessories_id = $accessory;
    //     }

    //     if ($this->request->input('partnerProduct')) {
    //         $pProduct = [];
    //         foreach ($this->request->input('partnerProduct') as $pp) {
    //             $pProduct[] = $pp['id'];
    //         }
    //         $subscriptionPlan->org_monetzn_partner_product_id = $pProduct;
    //     }

    //     if ($this->request->input('extraPartnerProduct')) {
    //         $extraProduct = [];
    //         foreach ($this->request->input('extraPartnerProduct') as $extraPp) {
    //             $extraProduct[] = $extraPp['id'];
    //         }
    //         $subscriptionPlan->org_monetzn_extra_partner_product_id = $extraProduct;
    //     }

    //     if ($this->request->input('conditionRules')) {

    //         $subsRules = [];
    //         foreach ($this->request->input('conditionRules')['subsRule'] as $subRule) {
    //             $subsRules[] = $subRule;
    //         }
    //         $subscriptionPlan->conditional_subscriptions = $subsRules;

    //         $accRules = [];
    //         foreach ($this->request->input('conditionRules')['accessoriesRule'] as $accRule) {
    //             $accRules[] = $accRule;
    //         }
    //         $subscriptionPlan->conditional_content_addons = $accRules;

    //         $contentRules = [];
    //         foreach ($this->request->input('conditionRules')['contentRule'] as $cntntRule) {
    //             $contentRules[] = $cntntRule;
    //         }
    //         $subscriptionPlan->conditional_accessories = $contentRules;
    //     }

    //     $subscriptionPlan->org_monetzn_content_set_id = '';
    //     $subscriptionPlan->created_by = $user->id;
    //     $subscriptionPlan->save();

    //     $susContentSet = new OrgMonetznSubsriptionContentSets();
    //     $susContentSet->organization_id = $this->request->input('organization_id');
    //     $susContentSet->montzn_plan_id = $subscriptionPlan->id;

    //     $chnleSet = $this->request->input('channelDataSet');
    //     if ($chnleSet) {
    //         $chnls = [];
    //         foreach ($chnleSet as $chnl) {
    //             $chnls[] = $chnl['id'];
    //         }
    //         $susContentSet->channel_id = $chnls;
    //     }

    //     $chnleAddOnsSet = $this->request->input('channelAddOnsData');
    //     if ($chnleAddOnsSet) {
    //         $chnlAddOns = [];
    //         foreach ($chnleAddOnsSet as $chnl) {
    //             $chnlAddOns[] = $chnl['id'];
    //         }
    //         $susContentSet->channel_add_ons_id = $chnlAddOns;
    //     }
    //     $lEvntSet = $this->request->input('liveEventData');
    //     $lEvnts = [];
    //     if ($lEvntSet) {
    //         foreach ($lEvntSet as $lEvnt) {
    //             $lEvnts[] = $lEvnt['id'];
    //         }
    //         // dd(99);
    //     }
    //     $susContentSet->live_event_id = $lEvnts ? $lEvnts : ["2", "1"];
    //     // dd($susContentSet);

    //     $lEvntAddOnsSet = $this->request->input('liveEventData');
    //     $lEvntAddOns = [];
    //     if ($lEvntAddOnsSet) {
    //         foreach ($lEvntAddOnsSet as $lEvnt) {
    //             $lEvntAddOns[] = $lEvnt['id'];
    //         }
    //     }
    //     $susContentSet->live_event_add_ons_id = $lEvntAddOns ? $lEvntAddOns : ["2", "1"];

    //     $vodSet = $this->request->input('vodData');
    //     $vods = [];
    //     if ($vodSet) {
    //         foreach ($vodSet as $vod) {
    //             $vods[] = $vod['id'];
    //         }
    //     }
    //     $susContentSet->vod_id = $vods;

    //     $vodAddOnsSet = $this->request->input('vodAddOnsData');
    //     $vodAddOns = [];
    //     if ($vodAddOnsSet) {
    //         foreach ($vodAddOnsSet as $lEvnt) {
    //             $vodAddOns[] = $lEvnt['id'];
    //         }
    //         // $susContentSet->vod_add_ons_id = $vodAddOns;
    //     }
    //     $susContentSet->vod_add_ons_id = ['1', '2'];

    //     $tvShow = $this->request->input('tvShowContentSet');
    //     $tvShows = [];
    //     if ($tvShow) {
    //         foreach ($tvShow as $show) {
    //             $tvShows[] = $show['id'];
    //         }
    //     }
    //     $susContentSet->tv_show_id = $tvShows ? $tvShows : ["2", "1"];

    //     $tvShowAddOnsSet = $this->request->input('tvShowAddOnsContentSet');
    //     $tvShowAddOns = [];
    //     if ($tvShowAddOnsSet) {
    //         foreach ($tvShowAddOnsSet as $show) {
    //             $tvShowAddOns[] = $show['id'];
    //         }
    //     }
    //     $susContentSet->tv_show_add_ons_id = $tvShowAddOns ? $tvShowAddOns : ["2", "1"];
    //     $susContentSet->save();

    //     if ($susContentSet) {
    //         $subscriptionPlan->update(['org_monetzn_content_set_id' => $susContentSet->id]);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => trans('organizations::index.create.success'),
    //     ]);
    // }

    public function postCreate()
    {
        $this->setRules([
            'organization_id' => 'required',
            'subscriptionData' => 'required',
        ]);

        // dd($this->request->all()); 
        $user = Auth::user();
        $subscriptionData = $this->request->input('subscriptionData');
        $subscriptionPlan = new OrgMonetizationPlanss();
        $subscriptionPlan->organization_id = $this->request->input('organization_id');
        $subscriptionPlan->subscription_name = $subscriptionData['subs_name'];
        $subscriptionPlan->subscription_identifier = $subscriptionData['identifier'];
        if (isset($subscriptionData['select_platform'])) {
            $subscriptionPlan->platforms = $subscriptionData['select_platform'];
        }
        $subscriptionPlan->use_org_settings = $subscriptionData['org_settings'] == true ? '1' : '0';
        if (isset($subscriptionData['subs_unlimited_time']) && $subscriptionData['subs_unlimited_time'] && $subscriptionData['subs_unlimited_time'] == true) {
            $subscriptionPlan->subscription_length = '';
            $subscriptionPlan->subs_length_time_type = 'unlimited';
        } else {
            $subscriptionPlan->subscription_length = $subscriptionData['subs_length'];
            $subscriptionPlan->subs_length_time_type = $subscriptionData['subs_time_type'];
        }
        $subscriptionPlan->subscription_type = $subscriptionData['payment_method'];
        $subscriptionPlan->advertising = isset($subscriptionData['is_advertise']) == true ? '1' : '0';
        $subscriptionPlan->currency = $subscriptionData['currency'] ?? '';

        if ($subscriptionData['payment_method'] == 'paid') {
            $subscriptionPlan->subscription_price = $subscriptionData['subs_price'];
        } else {
            $subscriptionPlan->subscription_price = 'free';
        }

        $subscriptionPlan->total_price = $subscriptionData['totalDevicesPrice'] ?? '';

        if ($this->request->input('devicePrices')) {

            $prices = [];

            foreach (($this->request->input('devicePrices')['devices'] ?? []) as $item) {
                $prices[] = [
                    'id' => $item['id'] ?? null,
                    'inputedVal' => $item['inputedVal'] ?? null,
                ];
            }

            $subscriptionPlan->additional_device_price = $prices;
        }

        $subscriptionPlan->autopay = isset($subscriptionData['is_autopay']) == true ? '1' : '0';
        $subscriptionPlan->subscription_devices = $subscriptionData['subs_devices'] ?? '';
        if ($this->request->input('accessories')) {
            $accessory = [];
            foreach ($this->request->input('accessories') as $acc) {
                $accessory[] = $acc['id'];
            }
            $subscriptionPlan->org_monetzn_accessories_id = $accessory;
        }

        if ($this->request->input('partnerProduct')) {
            $pProduct = [];
            foreach ($this->request->input('partnerProduct') as $pp) {
                $pProduct[] = $pp['id'];
            }
            $subscriptionPlan->org_monetzn_partner_product_id = $pProduct;
        }

        if ($this->request->input('extraPartnerProduct')) {
            $extraProduct = [];
            foreach ($this->request->input('extraPartnerProduct') as $extraPp) {
                $extraProduct[] = $extraPp['id'];
            }
            $subscriptionPlan->org_monetzn_extra_partner_product_id = $extraProduct;
        }

        if ($this->request->input('conditionRules')) {

            $subsRules = [];
            foreach ($this->request->input('conditionRules')['subsRule'] as $subRule) {
                $subsRules[] = $subRule;
            }
            $subscriptionPlan->conditional_subscriptions = $subsRules;

            $accRules = [];
            foreach ($this->request->input('conditionRules')['accessoriesRule'] as $accRule) {
                $accRules[] = $accRule;
            }
            $subscriptionPlan->conditional_content_addons = $accRules;

            $contentRules = [];
            foreach ($this->request->input('conditionRules')['contentRule'] as $cntntRule) {
                $contentRules[] = $cntntRule;
            }
            $subscriptionPlan->conditional_accessories = $contentRules;
        }

        $subscriptionPlan->org_monetzn_content_set_id = '';
        // return $subscriptionPlan;
        $subscriptionPlan->created_by = $user->id;
        $subscriptionPlan->save();

        $susContentSet = new OrgMonetznSubsriptionContentSets();
        $susContentSet->organization_id = $this->request->input('organization_id');
        $susContentSet->montzn_plan_id = $subscriptionPlan->id;
        // $susContentSet->featured_title = $this->request->input('featured_title');

        $chnleSet = $this->request->input('channelDataSet');
        if ($chnleSet) {
            $chnls = [];
            foreach ($chnleSet as $chnl) {
                $chnls[] = $chnl['id'];
            }
            $susContentSet->channel_id = $chnls;
        }

        $chnleAddOnsSet = $this->request->input('channelAddOnsData');
        if ($chnleAddOnsSet) {
            $chnlAddOns = [];
            foreach ($chnleAddOnsSet as $chnl) {
                $chnlAddOns[] = $chnl['id'];
            }
            $susContentSet->channel_add_ons_id = $chnlAddOns;
        }
        $lEvntSet = $this->request->input('liveEventData');
        $lEvnts = [];
        if ($lEvntSet) {
            foreach ($lEvntSet as $lEvnt) {
                $lEvnts[] = $lEvnt['id'];
            }
            // dd(99);
        }
        $susContentSet->live_event_id = $lEvnts ? $lEvnts : ["2", "1"];
        // dd($susContentSet);

        $lEvntAddOnsSet = $this->request->input('liveEventData');
        $lEvntAddOns = [];
        if ($lEvntAddOnsSet) {
            foreach ($lEvntAddOnsSet as $lEvnt) {
                $lEvntAddOns[] = $lEvnt['id'];
            }
        }
        $susContentSet->live_event_add_ons_id = $lEvntAddOns ? $lEvntAddOns : ["2", "1"];

        $vodSet = $this->request->input('vodData');
        $vods = [];
        if ($vodSet) {
            foreach ($vodSet as $vod) {
                $vods[] = $vod['id'];
            }
        }
        $susContentSet->vod_id = $vods;

        $vodAddOnsSet = $this->request->input('vodAddOnsData');
        $vodAddOns = [];
        if ($vodAddOnsSet) {
            foreach ($vodAddOnsSet as $lEvnt) {
                $vodAddOns[] = $lEvnt['id'];
            }
            // $susContentSet->vod_add_ons_id = $vodAddOns;
        }
        $susContentSet->vod_add_ons_id = ['1', '2'];

        $tvShow = $this->request->input('tvShowContentSet');
        $tvShows = [];
        if ($tvShow) {
            foreach ($tvShow as $show) {
                $tvShows[] = $show['id'];
            }
        }
        $susContentSet->tv_show_id = $tvShows ? $tvShows : ["2", "1"];

        $tvShowAddOnsSet = $this->request->input('tvShowAddOnsContentSet');
        $tvShowAddOns = [];
        if ($tvShowAddOnsSet) {
            foreach ($tvShowAddOnsSet as $show) {
                $tvShowAddOns[] = $show['id'];
            }
        }
        $susContentSet->tv_show_add_ons_id = $tvShowAddOns ? $tvShowAddOns : ["2", "1"];
        $susContentSet->save();

        if ($susContentSet) {
            $channelListing = new ChannelListing();
            $channelListing->organization_id = $subscriptionPlan->organization_id;
            $channelListing->monitization_plan_id = $subscriptionPlan->id;
            $channelListing->save();
        }

        if ($susContentSet) {
            $subscriptionPlan->update(['org_monetzn_content_set_id' => $susContentSet->id]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('organizations::index.create.success'),
        ]);
    }


    public function postEdit($id)
    {
        $this->setRules([
            'organization_id' => 'required',
            'subscriptionData' => 'required',
        ]);

        $user = Auth::user();
        $subscriptionData = $this->request->input('subscriptionData');
        $subscriptionPlan = OrgMonetizationPlanss::find($id);
        $subscriptionPlan->organization_id = $this->request->input('organization_id');
        $subscriptionPlan->subscription_name = $subscriptionData['subs_name'];
        $subscriptionPlan->subscription_identifier = $subscriptionData['identifier'];
        if (isset($subscriptionData['select_platform'])) {
            $subscriptionPlan->platforms = $subscriptionData['select_platform'];
        }
        $subscriptionPlan->use_org_settings = $subscriptionData['org_settings'] == true ? '1' : '0';
        if ($subscriptionData['subs_unlimited_time'] && $subscriptionData['subs_unlimited_time'] == true) {
            $subscriptionPlan->subscription_length = '';
            $subscriptionPlan->subs_length_time_type = 'unlimited';
        } else {
            $subscriptionPlan->subscription_length = $subscriptionData['subs_length'];
            $subscriptionPlan->subs_length_time_type = $subscriptionData['subs_time_type'];
        }
        $subscriptionPlan->subscription_type = $subscriptionData['payment_method'];
        $subscriptionPlan->advertising = isset($subscriptionData['is_advertise']) == true ? '1' : '0';
        $subscriptionPlan->currency = $subscriptionData['currency'];

        if ($subscriptionData['payment_method'] == 'paid') {
            $subscriptionPlan->subscription_price = $subscriptionData['subs_price'];
        } else {
            $subscriptionPlan->subscription_price = 'free';
        }

        $subscriptionPlan->total_price = $subscriptionData['totalDevicesPrice'];

        if ($this->request->input('devicePrices')) {
            $prices = [];
            foreach ($this->request->input('devicePrices')['devices'] as $item) {
                $prices[] = $item;
            }
            $subscriptionPlan->additional_device_price = $prices;
        }

        $subscriptionPlan->autopay = isset($subscriptionData['is_autopay']) == true ? '1' : '0';
        $subscriptionPlan->subscription_devices = $subscriptionData['subs_devices'];
        if ($this->request->input('accessories')) {
            $accessory = [];
            foreach ($this->request->input('accessories') as $acc) {
                $accessory[] = $acc['id'];
            }
            $subscriptionPlan->org_monetzn_accessories_id = $accessory;
        }

        if ($this->request->input('partnerProduct')) {
            $pProduct = [];
            foreach ($this->request->input('partnerProduct') as $pp) {
                $pProduct[] = $pp['id'];
            }
            $subscriptionPlan->org_monetzn_partner_product_id = $pProduct;
        }

        if ($this->request->input('extraPartnerProduct')) {
            $extraProduct = [];
            foreach ($this->request->input('extraPartnerProduct') as $extraPp) {
                $extraProduct[] = $extraPp['id'];
            }
            $subscriptionPlan->org_monetzn_extra_partner_product_id = $extraProduct;
        }

        if ($this->request->input('conditionRules')) {

            $subsRules = [];
            foreach ($this->request->input('conditionRules')['subsRule'] as $subRule) {
                $subsRules[] = $subRule;
            }
            $subscriptionPlan->conditional_subscriptions = $subsRules;

            $accRules = [];
            foreach ($this->request->input('conditionRules')['accessoriesRule'] as $accRule) {
                $accRules[] = $accRule;
            }
            $subscriptionPlan->conditional_content_addons = $accRules;

            $contentRules = [];
            foreach ($this->request->input('conditionRules')['contentRule'] as $cntntRule) {
                $contentRules[] = $cntntRule;
            }
            $subscriptionPlan->conditional_accessories = $contentRules;
        }

        // $subscriptionPlan->org_monetzn_content_set_id = '';
        $subscriptionPlan->created_by = $user->id;
        $subscriptionPlan->save();

        $susContentSet = OrgMonetznSubsriptionContentSets::find($subscriptionPlan->org_monetzn_content_set_id);
        // $susContentSet->montzn_plan_id = $subscriptionPlan->id;
        $chnleSet = $this->request->input('channelDataSet');
        if ($chnleSet) {
            $chnls = [];
            foreach ($chnleSet as $chnl) {
                $chnls[] = $chnl['id'];
            }
            $existingChannels = is_array($susContentSet->channel_id)
                ? $susContentSet->channel_id
                : json_decode($susContentSet->channel_id, true);

            if (!is_array($existingChannels)) {
                $existingChannels = [];
            }
            $susContentSet->channel_id = array_values(array_unique(array_merge($existingChannels, $chnls)));
        }

        $chnleAddOnsSet = $this->request->input('channelAddOnsData');
        if ($chnleAddOnsSet) {
            $chnlAddOns = [];
            foreach ($chnleAddOnsSet as $chnl) {
                $chnlAddOns[] = $chnl['id'];
            }
            $susContentSet->channel_add_ons_id = $chnlAddOns;
        }

        $lEvntSet = $this->request->input('liveEventData');
        if ($lEvntSet) {
            $lEvnts = [];
            foreach ($lEvntSet as $lEvnt) {
                $lEvnts[] = $lEvnt['id'];
            }
            $susContentSet->live_event_id = $lEvnts;
        }

        $lEvntAddOnsSet = $this->request->input('liveEventData');
        if ($lEvntAddOnsSet) {
            $lEvntAddOns = [];
            foreach ($lEvntAddOnsSet as $lEvnt) {
                $lEvntAddOns[] = $lEvnt['id'];
            }
            $susContentSet->live_event_add_ons_id = $lEvntAddOns;
        }

        $vodSet = $this->request->input('vodData');
        if ($vodSet) {
            $vods = [];
            foreach ($vodSet as $vod) {
                $vods[] = $vod['id'];
            }
            $susContentSet->vod_id = $vods;
        }

        $vodAddOnsSet = $this->request->input('vodAddOnsData');
        if ($vodAddOnsSet) {
            $vodAddOns = [];
            foreach ($vodAddOnsSet as $lEvnt) {
                $vodAddOns[] = $lEvnt['id'];
            }
            // $susContentSet->vod_add_ons_id = $vodAddOns;
            $susContentSet->vod_add_ons_id = ['1', '2'];
        }

        $tvShow = $this->request->input('tvShowContentSet');
        if ($tvShow) {
            $tvShows = [];
            foreach ($tvShow as $show) {
                $tvShows[] = $show['id'];
            }
            $susContentSet->tv_show_id = $tvShows;
        }

        $tvShowAddOnsSet = $this->request->input('tvShowAddOnsContentSet');
        if ($tvShowAddOnsSet) {
            $tvShowAddOns = [];
            foreach ($tvShowAddOnsSet as $show) {
                $tvShowAddOns[] = $show['id'];
            }
            $susContentSet->tv_show_add_ons_id = $tvShowAddOns;
        }
        $susContentSet->save();

        if ($susContentSet) {
            $subscriptionPlan->update(['org_monetzn_content_set_id' => $susContentSet->id]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('organizations::index.create.success'),
        ]);
    }

    public function updateStatus()
    {
        $monetznPlan = OrgMonetizationPlanss::find($this->request->input('id'));
        if ($monetznPlan) {
            $reqStatus = $this->request->input('status');
            $monetznPlan->status = $reqStatus;
            $monetznPlan->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Status Updated Successfully.'
        ]);
    }

    public function deletePlan($id)
    {
        $monetznPlan = OrgMonetizationPlanss::find($id);
        if ($monetznPlan) {
            $monetznPlan->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Plan Deleted Successfully.'
        ]);
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

            if ($key == 'platforms') {
                $builderCoupon->whereJsonContains('platforms->' . $value, true);
                continue;
            }

            if ($key == 'autopay') {
                $builderCoupon->where('autopay', $value);
                continue;
            }

            if ($key == 'user') {
                $builderCoupon->whereHas('user', function ($query) use ($value) {
                    $query->where('email', 'like', "%$value%");
                });
                continue;
            }

            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }

        return $builderCoupon;
    }

    public function togglePublishNow($id)
    {
        $monetznPlan = OrgMonetizationPlanss::find($id);

        if ($monetznPlan) {
            $monetznPlan->is_active = $monetznPlan->is_active ? '0' : '1';
            $monetznPlan->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Plan Published Successfully.'
        ]);
    }


    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => 'Name', 'value' => 'subscription_name', 'sort' => true, 'class' => false],
                ['name' => 'Length', 'value' => 'subscription_length', 'sort' => true, 'class' => false],
                ['name' => 'Platforms', 'value' => 'platforms', 'sort' => true, 'class' => false],
                ['name' => 'Currency', 'value' => 'currency', 'sort' => true],
                ['name' => 'Autopay', 'value' => 'autopay', 'sort' => true],
                ['name' => 'Device Support', 'value' => 'subscription_devices', 'sort' => true],
                ['name' => 'Price', 'value' => 'price', 'sort' => true],
                ['name' => 'User', 'value' => 'created_by', 'sort' => true],
                ['name' => trans('api-access::index.action'), 'value' => '', 'sort' => false],
            ]
        ];
    }
}
