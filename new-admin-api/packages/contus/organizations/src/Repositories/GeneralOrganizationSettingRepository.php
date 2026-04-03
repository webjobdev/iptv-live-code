<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Repository;
use Contus\Organizations\Model\Organization;
use Contus\Organizations\Model\OrganizationDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralOrganizationSettingRepository extends Repository
{
    protected $_generalsetting;

    public function __construct(OrganizationDetail $organization_detail)
    {
        parent::__construct();
        $this->_generalsetting = $organization_detail;
    }

    public function addgeneralorganizationsetting()
    {

        $organizationId = $this->request->input('organization_id', $this->request->input('id'));
        // Log::info('Starting general organization settings update.', ['organization_id' => $organizationId]);

        $organization = Organization::find($organizationId);
        // if (!$organization) {
        //     Log::warning('Organization not found.', ['organization_id' => $organizationId]);
        // }

        $organizationDetail = OrganizationDetail::firstOrNew(['organization_id' => $organizationId]);

        $this->setRules([
            'organization_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'organization_name' => 'nullable|string|max:255',
            'prefix' => 'nullable|string|max:255',
            'api_access' => 'nullable|in:0,1,true,false',
            'login_token' => 'nullable|string|max:255',
            'api_token' => 'nullable|string|max:255',
            'select_platform' => 'nullable|array',
        ]);

        // if ($this->request->hasFile('organization_logo')) {
        //     $file = $this->request->file('organization_logo');
        //     $filename = time() . '_' . $file->getClientOriginalName();
        //     $file->move(public_path('uploads/logos'), $filename);
        //     $organizationDetail->organization_logo = 'uploads/logos/' . $filename;
        //     // Log::info('Organization logo uploaded.', ['filename' => $filename]);
        // }

        if (isset($this->request->organization_logo)) {
            $fileName = basename($this->request->organization_logo);
            $localStoragePath = config("app.url") . config("contus.base.image.thumbnail.temporary_image_storage_path");
            $localIamgePath = $localStoragePath . DIRECTORY_SEPARATOR . $fileName;
            $organizationDetail->organization_logo = $localIamgePath;
        }


        if ($this->request->filled('organization_name') && $this->request->input('organization_name') !== 'undefined') {
            $organization->organization_name = $this->request->input('organization_name');
            $organization->save();
            // Log::info('Organization name updated.', ['name' => $organization->organization_name]);
        }

        $organizationDetail->organization_name = $this->request->input('organization_name') !== 'undefined'
            ? $this->request->input('organization_name')
            : $organizationDetail->organization_name;

        $organizationDetail->prefix = $this->request->input('prefix') !== 'undefined'
            ? $this->request->input('prefix')
            : $organizationDetail->prefix;

        $organizationDetail->api_access = $this->request->has('api_access') ? 1 : 0;

        $organizationDetail->login_token = $this->request->input('login_token') !== 'undefined'
            ? $this->request->input('login_token')
            : $organizationDetail->login_token;

        $organizationDetail->api_token = $this->request->input('api_token') !== 'undefined'
            ? $this->request->input('api_token')
            : $organizationDetail->api_token;

        $organizationDetail->select_platform = $this->request->input('select_platform');

        $organizationDetail->save();

        return response()->json([
            'success' => true,
            'message' => trans('organizations::index.update.success'),
        ]);
    }


    public function addorganizationsetting()
    {
        $organizationId = $this->request->input('organization_id', $this->request->input('id'));
        $organizationDetail = OrganizationDetail::firstOrNew(['organization_id' => $organizationId]);

        $organizationDetail->max_activation_length = $this->request->input('max_activation_length');
        $organizationDetail->device_activation_limit = $this->request->input('device_activation_limit');
        $organizationDetail->void_payment_in = $this->request->input('void_payment_in');
        $organizationDetail->custom_charges = $this->request->boolean('custom_charges');
        $organizationDetail->custom_subscription = $this->request->boolean('custom_subscription');
        $organizationDetail->device_slots = $this->request->boolean('device_slots');
        $organizationDetail->device_linking = $this->request->boolean('device_linking');
        $organizationDetail->link_code_expiration = $this->request->input('link_code_expiration');
        $organizationDetail->active_toa = $this->request->boolean('active_toa');
        $organizationDetail->subscription_activation = $this->request->boolean('subscription_activation');
        $organizationDetail->subscription_prorating = $this->request->boolean('subscription_prorating');
        $organizationDetail->content_add_on_prorating = $this->request->boolean('content_add_on_prorating');
        $organizationDetail->voucher_slots = $this->request->input('voucher_slots');
        $organizationDetail->expired_voucher_removal = $this->request->input('expired_voucher_removal');
        $organizationDetail->voucher_subscribers = $this->request->boolean('voucher_subscribers');

        $organizationDetail->unlimited = $this->request->boolean('unlimited') ? 1 : 0;
        $organizationDetail->use_system_default = $this->request->boolean('max_activation_length_system_default') ? 1 : 0;
        $organizationDetail->disallow_void = $this->request->boolean('disallow_void') ? 1 : 0;
        $organizationDetail->max_activation_length_system_default = $this->request->boolean('max_activation_length_system_default') ? 1 : 0;
        $organizationDetail->device_activation_limit_system_default = $this->request->boolean('device_activation_limit_system_default') ? 1 : 0;
        $organizationDetail->void_payment_in_system_default = $this->request->boolean('void_payment_in_system_default') ? 1 : 0;

        $organizationDetail->custom_charges_system_default = $this->request->boolean('custom_charges_system_default') ? 1 : 0;
        $organizationDetail->custom_subscription_system_default = $this->request->boolean('custom_subscription_system_default') ? 1 : 0;
        $organizationDetail->device_slots_system_default = $this->request->boolean('device_slots_system_default') ? 1 : 0;
        $organizationDetail->device_linking_system_default = $this->request->boolean('device_linking_system_default') ? 1 : 0;
        $organizationDetail->link_code_expiration_system_default = $this->request->boolean('link_code_expiration_system_default') ? 1 : 0;
        $organizationDetail->active_toa_system_default = $this->request->boolean('active_toa_system_default') ? 1 : 0;
        $organizationDetail->subscription_activation_system_default = $this->request->boolean('subscription_activation_system_default') ? 1 : 0;
        $organizationDetail->subscription_prorating_system_default = $this->request->boolean('subscription_prorating_system_default') ? 1 : 0;
        $organizationDetail->content_add_on_prorating_system_default = $this->request->boolean('content_add_on_prorating_system_default') ? 1 : 0;
        $organizationDetail->voucher_subscribers_system_default = $this->request->boolean('voucher_subscribers_system_default') ? 1 : 0;
        $organizationDetail->expired_voucher_removal_system_default = $this->request->boolean('expired_voucher_removal_system_default') ? 1 : 0;
        $organizationDetail->voucher_slots_system_default = $this->request->boolean('voucher_slots_system_default') ? 1 : 0;

        $organizationDetail->save();

        return response()->json([
            'success' => true,
            'message' => trans('organizations::index.setting_update.success'),
        ]);
    }



    public function prepareGrid()
    {
        $this->setGridModel($this->_generalsetting)->setEagerLoadingModels(['organization']);
        return $this;
    }

    protected function updateGridQuery($builder)
    {
        $organizationId = $this->request->input('organization_id', $this->request->input('id'));
        if ($organizationId) {
            $builder = $builder->where('organization_id', $organizationId);
        }
        return $builder;
    }
}
