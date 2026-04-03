<?php

namespace Contus\AppApi\Model;

use Contus\Base\Model;

class OtpVerification extends Model
{
    protected $table = "otp_verification";

    protected $fillable = [
        // "organization_id",
        // "user_id",
        "user_email",
        "otp",
    ];
}