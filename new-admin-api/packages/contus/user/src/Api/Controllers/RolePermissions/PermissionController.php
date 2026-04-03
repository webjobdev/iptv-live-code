<?php

namespace Contus\User\Api\Controllers\RolePermissions;

use Illuminate\Http\Request;
use Contus\Base\ApiController;
use Contus\PermissionRule\Model\Rule;
use Contus\PermissionRule\Model\RulePermissions;
use Illuminate\Support\Facades\Auth;

class PermissionController extends ApiController {

    public function assignPermissions() {
        // $rule = Rule::with('permissions')->find(132);
        $rule = Rule::with('permissions')->first();
        // $rulePermissions = RulePermissions::with('rules')->where('rule_id', 132)->get();

        return response()->json([
            'message' => 'List Retrieved Successfully!',
            'data' => $rule,
        ]);
    }
}
