<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DrmProfileDetail;
use Contus\Drm\Model\DrmProfileDetails;
use Illuminate\Support\Facades\DB;

class DrmProfileDetailsSeeder extends Seeder {
    public function run() {
        DB::table('drm_profile_details')->insert([
            'drm_provider' => 'Widevine',
            'drm_type' => 'Streaming',
            'authorization_url' => 'https://example.com/auth',
            'license_persistent' => 'yes',
            'license_limitation' => 'none',
            'license_duration' => '3600',
            'hdcp_type' => 'type1',
            'robustness' => 'SW_SECURE_CRYPTO',
            'is_active' => '1',
            'fps_certificate' => 'cert123',
            'output_protection_level' => 'HDCP14',
            'integration_type' => 'standard',
            'playready_security_level' => '150',
            'hardware_drm_required' => 'no',
            'rooted_devices_allowed' => 'yes',
            'drm_details_id' => 1, // Ensure this ID exists in the `drm_details` table
        ]);
    }
}
