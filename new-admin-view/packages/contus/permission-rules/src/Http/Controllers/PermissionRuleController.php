<?php

namespace Contus\PermissionRule\Http\Controllers;

use App\Http\Controllers\Controller;
use Contus\PermissionRule\Model\Rule;
use Contus\PermissionRule\Model\PermissionRule;
use Illuminate\Http\Request;

class PermissionRuleController extends Controller {

    public function index() {
        return view('permission-rules::index');
    }

    public function getGridlist() {
        return view('permission-rules::gridView');
    }

    public function addPermissionRule() {
        return view('permission-rules::create');
    }

    public function editPermissionRule() {
        return view('permission-rules::edit');
    }

    public function deleteRule(Request $request) {
        $id = $request->input('id');
        $rule = Rule::find($id);
        $permissionRules = PermissionRule::where('rule_id', $id)->get();

        return response()->json([
            'success' => $rule,
            'message' => $rule ? 'Rule and permissions of rule deleted.' : 'Rule or permissions of rule not found',
        ]);
    }
}
