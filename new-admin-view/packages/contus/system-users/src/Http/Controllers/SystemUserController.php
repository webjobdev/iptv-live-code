<?php

namespace Contus\SystemUser\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Contus\Base\Controller;
use Contus\User\Models\User;

class SystemUserController extends Controller
{

    public function index()
    {
        return view('system-users::index');
    }

    public function getGridlist()
    {
        return view('system-users::gridView');
    }

    public function addApiUser()
    {
        return view('system-users::api-user.create');
    }

    public function editApiUser()
    {
        return view('system-users::api-user.edit');
    }

    public function downloadUserLog($id)
    {
        // $user = $this->repository->getUserLogData($id);

        $user = User::find($id);

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

        $pdf = Pdf::loadHTML($html);

        return $pdf->download('user_log_' . $id . '.pdf');
    }
}
