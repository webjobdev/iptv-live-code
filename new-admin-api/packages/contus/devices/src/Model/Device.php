<?php

namespace Contus\Devices\Model;

use Carbon\Carbon;
use Contus\Customer\Models\Subscribers;
use Contus\Organizations\Model\Organization;
use Contus\Organizations\Model\OrgSubscribers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Device extends Model {

    use HasFactory;

    protected $table = 'devices';

    protected $fillable = ['mac_address', 'serial_no', 'device_redirect', 'identifier', 'timezone', 'organization_id', 'security_code_required', 'security_code', 'assigned_subscribers', 'device_model', 'firmware_version', 'ip_address', 'isp', 'location', 'status', 'create_subscribers', 'list', 'first_value', 'serial_mac_seperator', 'parse_file'];

    protected $casts = [
        'organization_id' => 'array',
        'assigned_subscribers' => 'array',
        // 'mac_address' => 'array',
        // 'serial_no' => 'array',
        // 'device_model' => 'array',
        // 'firmware_version' => 'array',
        // 'ip_address' => 'array',
        // 'identifier' => 'array'
    ];

    protected $appends = ['organizations', 'subscribers', 'parse_file_url', 'list_url'];

    public function getParseFileUrlAttribute() {
        return asset($this->parse_file);
    }

    public function getListUrlAttribute() {
        return asset($this->list);
    }

    public function getOrganizationsAttribute() {
        return Organization::whereIn('id', $this->organization_id ?? [])->get();
    }

    public function getSubscribersAttribute() {
        return OrgSubscribers::whereIn('id', $this->assigned_subscribers ?? [])->get();
    }
}
