<?php

namespace Contus\PermissionRule\Repositories;

use App\Models\User;
use Contus\PermissionRule\Model\RulePermissions;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\Organizations\Model\Organization;
use Contus\PermissionRule\Model\Permissions;
use Contus\PermissionRule\Model\Rule;
use Contus\PermissionRule\Model\UserOrganization;
use Contus\PermissionRule\Model\PermissionRulesOrg;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Str;

class PermissionRuleRepository extends Repository
{

    protected $_rule;

    public function __construct(Rule $rule)
    {
        parent::__construct();
        $this->_rule = $rule;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_rule)->setEagerLoadingModels(['organization', 'permissions']);
        return $this;
    }

    public function addRulePermissions()
    {
        $this->setRules([
            'rule_name' => 'required',
            'organization_id' => 'required',
            'modules' => 'required',
        ]);

        $rule = new Rule();
        $rule->rule_name = $this->request->input('rule_name');
        $rule->save();

        foreach ($this->request->input('organization_id') as $orgId) {
            PermissionRulesOrg::updateOrCreate([
                'permission_rule_id' => $rule->id,
                'organization_id' => $orgId,
            ], [
                'created_by' => Auth::user()->id,
            ]);
        }

        $modules = $this->request->input('modules');
        DB::transaction(function () use ($modules, $rule) {
            foreach ($modules as $mod) {
                $currentPermissions = [];

                if (isset($mod['permissions'])) {
                    $permissions = $mod['permissions'];
                    $currentPermissions['view'] = (isset($permissions['View']) && $permissions['View'] === true) ? 1 : 0;
                    $currentPermissions['create'] = (isset($permissions['Create']) && $permissions['Create'] === true) ? 1 : 0;
                    $currentPermissions['edit'] = (isset($permissions['Edit']) && $permissions['Edit'] === true) ? 1 : 0;
                    $currentPermissions['delete'] = (isset($permissions['Delete']) && $permissions['Delete'] === true) ? 1 : 0;
                    $currentPermissions['hide'] = (isset($permissions['Hide']) && $permissions['Hide'] === true) ? 1 : 0;

                    if ($mod['name'] == 'Activation') {
                        $currentPermissions['view'] = (isset($permissions['View']) && $permissions['View'] === true) ? 1 : 0;
                        $currentPermissions['create'] = (isset($permissions['Create Payments']) && $permissions['Create Payments'] === true) ? 1 : 0;
                        $currentPermissions['cash_payment'] = (isset($permissions['Cash Payments']) && $permissions['Cash Payments'] === true) ? 1 : 0;
                        $currentPermissions['refund_payment'] = (isset($permissions['Refund Payments']) && $permissions['Refund Payments'] === true) ? 1 : 0;
                        $currentPermissions['length_adjustment'] = (isset($permissions['Length Adjustments']) && $permissions['Length Adjustments'] === true) ? 1 : 0;
                        $currentPermissions['hide'] = (isset($permissions['Hide']) && $permissions['Hide'] === true) ? 1 : 0;
                    }

                    if ($mod['name'] == 'Subscribers') {
                        $currentPermissions['security_search'] = (isset($permissions['Security Search']) && $permissions['Security Search'] === true) ? 1 : 0;
                    }
                }

                RulePermissions::updateOrCreate(
                    [
                        'rule_id' => $rule->id,
                        'permission_module_name' => $mod['name']
                    ],
                    $currentPermissions
                );
            }
        });

        return response()->json([
            'success' => true,
            'message' => trans('permission-rules::index.add.success'),
        ]);
    }

    public function updateRulePermissions($id)
    {
        $this->setRules([
            'rule_name' => 'required',
            'organization_id' => 'required',
            'modules' => 'required',
        ]);

        $rule = Rule::find($id);
        $rule->rule_name = $this->request->input('rule_name');
        $rule->save();

        foreach ($this->request->input('organization_id') as $orgId) {
            PermissionRulesOrg::updateOrCreate([
                'permission_rule_id' => $rule->id,
                'organization_id' => $orgId,
            ], [
                'created_by' => Auth::user()->id,
            ]);
        }

        $modules = $this->request->input('modules');

        foreach ($modules as $mod) {
            $currentPermissions = [];

            if (isset($mod['permissions'])) {
                $permissions = $mod['permissions'];
                $currentPermissions['view'] = (isset($permissions['View']) && $permissions['View'] === true) ? 1 : 0;
                $currentPermissions['create'] = (isset($permissions['Create']) && $permissions['Create'] === true) ? 1 : 0;
                $currentPermissions['edit'] = (isset($permissions['Edit']) && $permissions['Edit'] === true) ? 1 : 0;
                $currentPermissions['delete'] = (isset($permissions['Delete']) && $permissions['Delete'] === true) ? 1 : 0;
                $currentPermissions['hide'] = (isset($permissions['Hide']) && $permissions['Hide'] === true) ? 1 : 0;

                if ($mod['name'] == 'Activation') {
                    $currentPermissions['view'] = (isset($permissions['View']) && $permissions['View'] === true) ? 1 : 0;
                    $currentPermissions['create'] = (isset($permissions['Create Payments']) && $permissions['Create Payments'] === true) ? 1 : 0;
                    $currentPermissions['cash_payment'] = (isset($permissions['Cash Payments']) && $permissions['Cash Payments'] === true) ? 1 : 0;
                    $currentPermissions['refund_payment'] = (isset($permissions['Refund Payments']) && $permissions['Refund Payments'] === true) ? 1 : 0;
                    $currentPermissions['length_adjustment'] = (isset($permissions['Length Adjustments']) && $permissions['Length Adjustments'] === true) ? 1 : 0;
                    $currentPermissions['hide'] = (isset($permissions['Hide']) && $permissions['Hide'] === true) ? 1 : 0;
                }

                if ($mod['name'] == 'Subscribers') {
                    $currentPermissions['security_search'] = (isset($permissions['Security Search']) && $permissions['Security Search'] === true) ? 1 : 0;
                }
            }

            RulePermissions::updateOrCreate(
                [
                    'rule_id' => $id,
                    'permission_module_name' => $mod['name']
                ],
                $currentPermissions
            );
        }

        return response()->json([
            'success' => true,
            'message' => trans('permission-rules::index.update.success'),
        ]);
    }

    public function deleteRuleData($id)
    {
        $rule = Rule::find($id);
        if ($rule) {
            $rule->delete();
            $permissionRuleIds = RulePermissions::where('rule_id', $id)->pluck('id');
            RulePermissions::whereIn('id', $permissionRuleIds)->delete();
        }

        return response()->json([
            'success' => true,
            'message' => trans('permission-rules::index.delete.success'),
        ]);
    }

    public function searchByName()
    {
        $userIds = User::where('name', 'like', '%' . $this->request->input('name') . '%')->pluck('id');
        $partnerProvider = RulePermissions::with('user')->whereIn('created_by', $userIds)->get();

        return response()->json([
            'success' => true,
            'data' => $partnerProvider,
            'message' => trans('permission-rules::index.fetch-data.success'),
        ]);
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecords = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        foreach ($searchRecords as $key => $value) {
            // if ($key == 'status' && $value == 'all') {
            //     continue;
            // }

            if ($key == 'rule_name') {
                $builderCoupon = $builderCoupon->where('rule_name', 'like', "%$value%");
                continue;
            }

            // if ($key == 'valid_till') {
            //     $date = date_create($value);
            //     $value =  date_format($date, "Y-m-d");
            // }
            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => trans('permission-rules::index.rule'), 'value' => 'rule_name', 'sort' => true, 'class' => false],
                ['name' => trans('permission-rules::index.org'), 'value' => 'organization_id', 'sort' => true, 'class' => false],
                ['name' => trans('permission-rules::index.action'), 'value' => '', 'sort' => false],
            ]
        ];
    }


    public function getOrgList()
    {
        $user = Auth::user();
        $userOrgIds = UserOrganization::where('user_id', $user->id)->pluck('organization_id');
        $orgs = Organization::whereIn('id', $userOrgIds)->get();

        return response()->json([
            'success' => true,
            'data' => $orgs,
            'message' => trans('permission-rules::index.fetch-data.success'),
        ]);
    }
}
