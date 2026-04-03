<?php

namespace Contus\Organizations\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Base\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Contus\Organizations\Model\Organization;
use Contus\Base\Helpers\StringLiterals;
use Contus\Organizations\Model\OrganizationDetail;

class OrganizationRepository extends Repository
{

    protected $_organization;

    public function __construct(Organization $organization)
    {
        parent::__construct();
        $this->_organization = $organization;
    }

    public function prepareGrid()
    {
        $this->setGridModel(
            $this->_organization
            // )->setEagerLoadingModels(['organization', 'organization.subscribers', 'organization.subscribers.subscription_and_payments_details', 'organization.subscribers.devices', 'organization.channels', 'organization.vods']);
        )->setEagerLoadingModels(['organization', 'organization.subscribers', 'organization.subscribers.subscription_and_payments_details', 'organization.subscribers.devices', 'organization.channels', 'organization.vods']);
        return $this;
    }

    public function addOrganization()
    {
        $user = Auth::user();
        $this->setRules([
            'name' => 'required|max:255',
        ]);

        $organization = Organization::create([
            'organization_name' => $this->request->input('name'),
            'owner_by' => $user->id,
        ]);

        OrganizationDetail::create([
            'organization_id' => $organization->id,
            'organization_name' => $organization->organization_name,
            'provider_id' => rand(0000, 9999),
        ]);

        return response()->json([
            'success' => true,
            'message' => trans('organizations::index.add.success'),
            // 'data' => $organization
        ]);
    }

    public function getGridHeadings()
    {
        if (config()->get('auth.providers.users.table') === 'customers') {
            return [
                StringLiterals::GRIDHEADING => [
                    ['name' => trans('organizations::index.name'), StringLiterals::VALUE => '', 'sort' => true],
                ]
            ];
        } else {
            return [
                StringLiterals::GRIDHEADING => [
                    ['name' => trans('organizations::index.name'), StringLiterals::VALUE => '', 'sort' => true, 'class' => false],
                ]
            ];
        }
    }

    // public function prepareGrid() {
    //     $this->setGridModel($this->_organization)
    //         ->setEagerLoadingModels(['organization']);
    //     return $this;
    // }
}
