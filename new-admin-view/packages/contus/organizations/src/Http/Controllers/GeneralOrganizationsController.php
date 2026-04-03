<?php

namespace Contus\Organizations\Http\Controllers;

use Contus\Base\Controller;
use Illuminate\Http\Request;
use Contus\Organizations\Model\OrganizationDetail;
use Contus\Organizations\Model\Organization;
use Contus\Organizations\Model\OrganizationMonitizationPlan;
use Contus\Organizations\Model\OrganizationSubscription;
use Illuminate\Support\Facades\Log;

class GeneralOrganizationsController extends Controller {
    public function showdetails() {
        // dd(123);
        return view('organizations::organization-settings.addorganizationdetail');
    }

    public function getOrganizationName($orgId) {
        $organization = Organization::find($orgId);

        if ($organization) {
            return response()->json(['organization_name' => $organization->organization_name]);
        }

        return response()->json(['organization_name' => null]);
    }

    public function destroy(Request $request) {
        $organizationId = $request->input('id');

        if (!$organizationId) {
            return response()->json(['success' => false, 'message' => 'Organization ID is required'], 400);
        }

        $organizationDetail = OrganizationDetail::where('organization_id', $organizationId)->first();
        if (!$organizationDetail) {
            return response()->json(['success' => false, 'message' => 'Organization details not found'], 404);
        }

        $organization = Organization::find($organizationId);
        if (!$organization) {
            return response()->json(['success' => false, 'message' => 'Organization not found'], 404);
        }

        try {
            $organizationDetail->delete();
            $organization->delete();

            return response()->json(['success' => true, 'message' => 'Organization deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete organization.'], 500);
        }
    }   

    public function clone($id) {
        $organization = Organization::find($id);

        if (!$organization) {
            // Log::warning("Clone attempt failed: organization_id {$id} not found.");
            return response()->json(['success' => false, 'message' => 'Original not found']);
        }

        // Clone Organization
        $organizationCloned = $organization->replicate();
        $organizationCloned->organization_name = $organization->organization_name . ' (Clone)';
        $organizationCloned->created_at = now();
        $organizationCloned->updated_at = now();
        $organizationCloned->save();

        // Clone all related OrganizationDetails
        $details = OrganizationDetail::where('organization_id', $id)->get();
        foreach ($details as $original) {
            $cloned = $original->replicate();
            $cloned->organization_name = $original->organization_name . ' (Clone)';
            $cloned->organization_id = $organizationCloned->id;
            $cloned->created_at = now();
            $cloned->updated_at = now();
            $cloned->save();

            // Log::info("OrganizationDetails cloned: original_id={$original->id}, new_id={$cloned->id}");
        }

        // Clone all related Monitization Plans
        $plans = OrganizationMonitizationPlan::where('organization_id', $id)->get();
        foreach ($plans as $plan) {
            $clonedPlan = $plan->replicate();
            $clonedPlan->organization_id = $organizationCloned->id;
            $clonedPlan->created_at = now();
            $clonedPlan->updated_at = now();
            $clonedPlan->save();

            // Log::info("Plan cloned: original_id={$plan->id}, new_id={$clonedPlan->id}");
        }

        // Clone all related Subscriptions
        $subscriptions = OrganizationSubscription::where('organization_id', $id)->get();
        foreach ($subscriptions as $subscriber) {
            $clonedSub = $subscriber->replicate();
            $clonedSub->organization_id = $organizationCloned->id;
            $clonedSub->created_at = now();
            $clonedSub->updated_at = now();
            $clonedSub->save();

            // Log::info("Subscription cloned: original_id={$subscriber->id}, new_id={$clonedSub->id}");
        }

        // Log::info("Organization cloned: original_id={$organization->id}, new_id={$organizationCloned->id}");

        return response()->json(['success' => true, 'new_id' => $organizationCloned->id]);
    }


    // public function searchOrganizations(Request $request) {
    //     $keyword = $request->input('keyword');

    //     $organizations = Organization::when($keyword, function ($query) use ($keyword) {
    //         return $query->where('organization_name', 'LIKE', "%{$keyword}%");
    //     })->get();

    //     return response()->json(['data' => $organizations]);
    // }
}
