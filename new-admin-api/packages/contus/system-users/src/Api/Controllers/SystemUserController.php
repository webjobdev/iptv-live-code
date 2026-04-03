<?php

namespace Contus\SystemUser\Api\Controllers;

use Contus\SystemUser\Model\SystemUser;
use Contus\SystemUser\Repositories\SystemUserRepository;
use Contus\Base\ApiController;
use Google\Service\Adsense\Alert;
use Barryvdh\DomPDF\Facade\Pdf;

class SystemUserController extends ApiController {

    public function __construct(SystemUserRepository $apiAccessRespository) {
        parent::__construct();
        $this->repository = $apiAccessRespository;
        $this->repository->setRequestType(static::REQUEST_TYPE);
    }

    public function getInfo() {
        return $this->getSuccessJsonResponse([
            'success'
        ]);
    }

    public function postAdd() {
        $isCreated = false;

        if ($this->repository->addSysUser()) {
            $isCreated = true;

            return ($isCreated)
                ? $this->getSuccessJsonResponse(['message' => 'System User Added Successfully.'])
                : $this->getErrorJsonResponse([], 'Error occurred while adding System User.');
        }
    }

    public function postEdit($id) {
        $isUpdated = false;

        if ($this->repository->updateSysUser($id)) {
            $isUpdated = true;

            return ($isUpdated)
                ? $this->getSuccessJsonResponse(['message' => 'System User Updated Successfully.'])
                : $this->getErrorJsonResponse([], 'Error occurred while updating System User.');
        }
    }

    public function postStatusEdit() {
        $isEdited = false;

        if ($this->repository->statusUpdate()) {
            $isEdited = true;

            return ($isEdited)
                ? $this->getSuccessJsonResponse(['message' => 'Status Updated Successfully.'])
                : $this->getErrorJsonResponse([], 'Error Occurred!');
        }
    }

    public function postRemove($id) {
        $isRemoved = false;

        if ($this->repository->removeSysUser($id)) {
            $isRemoved = true;

            return ($isRemoved)
                ? $this->getSuccessJsonResponse(['message' => 'Record Deleted Successfully'])
                : $this->getErrorJsonResponse([], 'Error Occurred!');
        }
    }

    public function searchRecord() {
        $isEdited = false;

        $data = $this->repository->searchByName()->getData('data');

        if ($this->repository->searchByName()) {
            $isEdited = true;

            return ($isEdited)
                ? $this->getSuccessJsonResponse([
                    'message' => trans('api-access::index.fetch-data.success'),
                    'data' => $data['data'][0] ?? null,
                ])
                : $this->getErrorJsonResponse([], trans('api-access::index.fetch-data.error'));
        }
    }
    
    public function downloadUserLog($id)
    {
        $user = $this->repository->getUserLogData($id);
        // dd($user->phone);
        if (!$user) {
            abort(404, 'User not found');
        }

        // Format dates safely
        $loginAt = $user->is_log_in_at ?? '-';
        $logoutAt = $user->is_log_out_at ?? '-';
        $createdAt = $user->created_at ?? '-';
        $updatedAt = $user->updated_at ?? '-';

        // Full name
        $fullName = ($user->first_name ?? '') . ' ' . ($user->last_name ?? '');

        // HTML Design
        $html = "
            <h2 style='text-align:center;'>User Log Details</h2>
            <hr>

            <table width='100%' border='1' cellspacing='0' cellpadding='8'>
                <tr style='background-color:#f2f2f2;'>
                    <th align='left'>Field</th>
                    <th align='left'>Value</th>
                </tr>

                <tr>
                    <td><strong>Name</strong></td>
                    <td>{$fullName}</td>
                </tr>

                <tr>
                    <td><strong>Email</strong></td>
                    <td>{$user->email}</td>
                </tr>

                <tr>
                    <td><strong>Phone Number</strong></td>
                    <td>{$user->phone}</td>
                </tr>

                <tr>
                    <td><strong>Company</strong></td>
                    <td>{$user->company}</td>
                </tr>

                <tr>
                    <td><strong>Location</strong></td>
                    <td>{$user->location}</td>
                </tr>

                <tr>
                    <td><strong>Login Time</strong></td>
                    <td>{$loginAt}</td>
                </tr>

                <tr>
                    <td><strong>Logout Time</strong></td>
                    <td>{$logoutAt}</td>
                </tr>

                <tr>
                    <td><strong>Created At</strong></td>
                    <td>{$createdAt}</td>
                </tr>

                <tr>
                    <td><strong>Updated At</strong></td>
                    <td>{$updatedAt}</td>
                </tr>
            </table>

            <br><br>
            <p style='text-align:center; font-size:12px; color:#777;'>
                Generated on: " . date('Y-m-d H:i:s') . "
            </p>";

        $pdf = \Pdf::loadHTML($html);

        return $pdf->download('user_log_' . $id . '.pdf');
    }

}