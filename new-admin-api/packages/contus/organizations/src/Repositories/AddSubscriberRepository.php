<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Repository;
use Contus\Organizations\Model\OrgSubscribers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AddSubscriberRepository extends Repository
{
    protected $_orgSubscribers;
    public function __construct(OrgSubscribers $orgSubscribers)
    {
        parent::__construct();
        $this->_orgSubscribers = $orgSubscribers;
    }

    public function addSub()
    {
        $user = Auth::user();

        $this->setRules([
            'organization_id' => 'required|integer|exists:organizations,id',
            'organization_name' => 'nullable|string|max:255',
            'account_number' => 'required|string|max:255',
            'pin_code' => 'required|string|max:255',
            'user_name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone_number_code' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'timezone' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string',
        ]);

        $this->_validate();

        $data = $this->request->only([
            'organization_id',
            'organization_name',
            'account_number',
            'pin_code',
            'user_name',
            'password',
            'first_name',
            'last_name',
            'email',
            'phone_number_code',
            'phone_number',
            'address',
            'city',
            'zip_code',
            'country',
            'state',
            'language',
            'date_of_birth',
            'timezone',
        ]);


        $data['ip_address'] = $user->ip_address;

        if (empty($data['date_of_birth'])) {
            unset($data['date_of_birth']);
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        OrgSubscribers::create($data);

        return response()->json([
            'status' => true,
            'message' => trans('organizations::index.anc_send.success'),
        ]);
    }

    public function prepareGrid()
    {
        $this->setGridModel($this->_orgSubscribers);
        return $this;
    }
}
