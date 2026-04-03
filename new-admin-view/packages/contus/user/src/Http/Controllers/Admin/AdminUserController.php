<?php

namespace Contus\User\Http\Controllers\Admin;

use Carbon\Carbon;
use Contus\Base\Controller as BaseController;
use Contus\Base\Helpers\StringLiterals;
use Contus\User\Repositories\AdminUserRepository;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends BaseController
{
    protected $adminUserRepository;

    public function __construct(AdminUserRepository $adminUserRepository)
    {
        parent::__construct();
        $this->adminUserRepository = $adminUserRepository;
        $this->adminUserRepository->setRequestType(static::REQUEST_TYPE);
    }

    public function getIndex($status = 'all')
    {
        return view('user::admin.users.index', [
            'users' => $this->adminUserRepository->getUsers($status),
            'status' => $status,
        ]);
    }

    public function getAdd()
    {
        return view('user::admin.users.add', [getRules(),
        ]);
    }

    public function postAdd()
    {
        try {
            $this->adminUserRepository->addOrUpdateUsers();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return redirect(StringLiterals::ADMIN_USERS)
            ->withSuccess(trans('user::adminuser.success'));
    }

    public function getEdit($id)
    {
        return view('user::admin.users.edit', [
            'user' => $this->adminUserRepository->getUser($id),
            'groups' => $this->adminUserRepository->getGroupsList(),
            StringLiterals::RULES => $this->adminUserRepository
                ->setRule('email', 'required|email|unique')
                ->getRules(),
        ]);
    }

    public function postUpdate($id)
    {
        $this->adminUserRepository->addOrUpdateUsers($id);
        return redirect(StringLiterals::ADMIN_USERS)
            ->withSuccess(trans('user::adminuser.updated'));
    }

    public function getDestroy($id)
    {
        $this->adminUserRepository->getUsersDelete($id);
        return redirect(StringLiterals::ADMIN_USERS)
            ->withSuccess(trans('user::adminuser.deleted'));
    }

    public function postAction()
    {
        $this->adminUserRepository->getUsersDeleteAll();
        return redirect(StringLiterals::ADMIN_USERS)
            ->withSuccess(trans('adminuser.selected_deleted'));
    }

    public function getChangepassword()
    {
        return view('user::admin.changepassword', [
            StringLiterals::RULES => $this->adminUserRepository
                ->setRules([
                    'old_password' => 'required',
                    'password' => 'required|confirmed',
                    'password_confirmation' => 'required|same:password',
                ])
                ->getRules(),
        ]);
    }

    public function postChangepassword()
    {
        if ($this->adminUserRepository->updatePassword()) {
            return redirect('users/changepassword')
                ->withSuccess(trans('user::adminuser.changepassword.success'));
        }

        return redirect('users/changepassword')
            ->withErrors(trans('user::adminuser.changepassword.incorrect'));
    }

    public function getProfile()
    {
        return view('user::admin.profile');
    }

    public function postProfile()
    {
        $this->adminUserRepository->updateProfile(Auth::user()->id);
        return redirect('users/profile')
            ->withSuccess(trans('user::adminuser.profile.success'));
    }

    public function getUnique($id = null)
    {
        $status = $this->adminUserRepository->isUniqueUserEmail($id) ? 200 : 404;
        return response()->json([], $status);
    }

    public function getGrid()
    {
        return view('user::admin.users.grid');
    }

    public function getGridlist()
    {
        return view('user::admin.users.gridView');
    }

    public function getLogout()
    {
        Auth::user()->update(['last_logged_out_at' => Carbon::now()]);
        Auth::logout();

        return redirect('/');
    }

    public function getFeedback()
    {
        return view('video::admin.feedback.feedback');
    }

    public function demoGetProfile()
    {
        return view('user::admin.demo_profile');
    }
}
