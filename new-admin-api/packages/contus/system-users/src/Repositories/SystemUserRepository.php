<?php

namespace Contus\SystemUser\Repositories;

use Auth;
use Contus\SystemUser\Model\SystemUser;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repository;
use Contus\User\Models\User;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SystemUserRepository extends Repository
{

    protected $_systemUser;

    public function __construct(User $systemUser)
    {
        parent::__construct();
        $this->_systemUser = $systemUser;
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_systemUser)->setEagerLoadingModels(['rules', 'rules.permissions']);
        return $this;
    }

    public function addSysUser()
    {
        $this->setRules([
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
            'permission_rule' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'company' => 'required',
            'location' => 'required',
            'max_failed_logins' => 'required',
            'status' => 'required'
        ]);

        $sysUser = new User();
        $user = Auth::user();

        $sysUser->first_name = $this->request->input('first_name');
        $sysUser->last_name = $this->request->input('last_name');
        $sysUser->name = trim($this->request->input('first_name') . ' ' . $this->request->input('last_name'));
        $sysUser->password = Hash::make($this->request->input('password'));
        $sysUser->permission_rule_id = $this->request->input('permission_rule');
        $sysUser->email = $this->request->input('email');
        $sysUser->phone = $this->request->input('phone_number');
        $sysUser->company = $this->request->input('company');
        $sysUser->location = $this->request->input('location');
        $sysUser->max_failed_logins = $this->request->input('max_failed_logins');
        $sysUser->is_active = $this->request->input('status') ? '1' : '0';
        $sysUser->is_super_admin = $this->request->input('is_super_admin') ? '1' : '0';
        $sysUser->can_change_password_for_next_login = $this->request->input('change_password') ? '1' : '0';
        $sysUser->ip_address = $this->request->ip();

        $sysUser->parent_id = $user->id;

        $sysUser->save();

        return response()->json([
            'success' => true,
            'message' => 'System User Created Successfully.',
        ]);
    }

    public function updateSysUser($id)
    {
        $this->setRules([
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'nullable',
            'permission_rule' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'company' => 'required',
            'location' => 'required',
            'max_failed_logins' => 'required',
            'status' => 'required',
            'is_super_admin' => 'required',
            'change_password' => 'nullable',
        ]);

        $this->_validate();

        // $sysUser = SystemUser::find($id);
        $sysUser = User::find($id);

        if (!$sysUser) {
            return response()->json([
                'success' => false,
                'message' => 'System User not found.',
            ], 404);
        }

        $sysUser->first_name = $this->request->input('first_name');
        $sysUser->last_name = $this->request->input('last_name');
        $sysUser->name = trim($this->request->input('first_name') . ' ' . $this->request->input('last_name'));
        if ($this->request->filled('password')) {
            $sysUser->password = Hash::make($this->request->input('password'));
        }
        $sysUser->permission_rule_id = $this->request->input('permission_rule');
        $sysUser->email = $this->request->input('email');
        $sysUser->phone = $this->request->input('phone_number');
        $sysUser->company = $this->request->input('company');
        $sysUser->location = $this->request->input('location');
        $sysUser->max_failed_logins = $this->request->input('max_failed_logins');
        $sysUser->is_active = $this->request->input('status') ? '1' : '0';
        $sysUser->is_super_admin = $this->request->input('is_super_admin') ? '1' : '0';
        $sysUser->can_change_password_for_next_login = $this->request->input('change_password') ? '1' : '0';
        $sysUser->ip_address = $this->request->ip();


        $sysUser->save();

        return response()->json([
            'success' => true,
            'message' => 'System User Updated Successfully.',
        ]);
    }

    public function statusUpdate()
    {
        $sysUser = SystemUser::where('id', $this->request->input('id'))->update(['status' => $this->request->input('status')]);

        return response()->json([
            'success' => true,
            'message' => 'Status Updated Successfully.',
        ]);
    }

    public function removeSysUser($id)
    {
        $sysUser = SystemUser::find($id);
        if ($sysUser) {
            $sysUser->delete();
            return response()->json([
                'success' => true,
                'message' => 'System User Deleted Successfully.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error Occurred.',
            ]);
        }
    }

    protected function searchFilter($builderCoupon)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) &&
            is_array($this->request->input(StringLiterals::SEARCHRECORD))
            ? $this->request->input(StringLiterals::SEARCHRECORD)
            : [];

        foreach ($searchRecordUsers as $key => $value) {
            if ($key == 'status' && $value == 'all') {
                continue;
            }

            if ($key == 'status' && $value == 'online') {
                // dd($key);
                $builderCoupon = $builderCoupon->where('status', '1');
                continue;
            }

            if ($key == 'status' && $value == 'offline') {
                $builderCoupon = $builderCoupon->where('status', '0');
                continue;
            }

            // dd($key);
            if ($key == 'name') {
                $builderCoupon = $builderCoupon->where('first_name', 'like', "%$value%")->orWhere('last_name', 'like', "%$value%");
                // dd($builderCoupon);
                continue;
            }

            if ($key == 'rule') {
                $builderCoupon = $builderCoupon->whereHas('rules', function ($query) use ($value) {
                    $query->where('rule_name', 'like', "%$value%");
                });
                continue;
            }

            if ($key == 'email') {
                $builderCoupon = $builderCoupon->where('email', 'like', "%$value%");
                continue;
            }

            if ($key == 'company') {
                $builderCoupon = $builderCoupon->where('company', 'like', "%$value%");
                continue;
            }

            $builderCoupon = $builderCoupon->where($key, 'like', "%$value%");
        }
        return $builderCoupon;
    }

    public function getGridHeadings()
    {
        return [
            'heading' => [
                ['name' => 'S.No', 'S.No' => 'SNo', 'sort' => false],
                ['name' => 'Full Name', 'value' => 'first_name', 'sort' => true, 'class' => false],
                ['name' => 'Rule', 'value' => 'permission_rule_id', 'sort' => true, 'class' => false],
                ['name' => 'Email', 'value' => 'email', 'sort' => true, 'class' => false],
                ['name' => 'Company', 'value' => 'company', 'sort' => true],
                ['name' => 'Last Login', 'value' => '', 'sort' => false],
                ['name' => 'Status', 'value' => 'status', 'sort' => false],
                ['name' => 'User Logs', 'value' => '', 'sort' => false],
                ['name' => 'Action', 'value' => '', 'sort' => false],
            ]
        ];
    }
}
