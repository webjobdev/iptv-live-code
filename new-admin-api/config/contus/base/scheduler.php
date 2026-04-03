<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Scheduler's
    |--------------------------------------------------------------------------
    |
    | this configuration will have array scheduler class should be executed
    |
     */
    Contus\Video\Schedulers\VideoToMp4ConvertScheduler::class,
    Contus\Video\Schedulers\UploadToS3Scheduler::class,
    Contus\Video\Schedulers\TranscoderJobStatusScheduler::class,
    Contus\Video\Schedulers\AWSTranscoderScheduler::class,
    Contus\Customer\Schedulers\SubscriptionScheduler::class,
    Contus\Video\Schedulers\NotificationClearScheduler::class,
    Contus\Video\Schedulers\ConvertLiveVideoToVodVideoScheduler::class,
    Contus\Video\Schedulers\GenerateImageScheduler::class,
    Contus\Audio\Schedulers\AudioUploadToS3Scheduler::class,
    // Contus\Audio\Schedulers\AudioTranscoderJobStatusScheduler::class,
    Contus\Video\Schedulers\VideosAudioTrackTranscodingScheduler::class,
    // Contus\Video\Schedulers\VideosTrailerTranscodingScheduler::class,
    Contus\Video\Schedulers\LiveStreamStatusScheduler::class,
    Contus\Video\Schedulers\PremimumStatusScheduler::class,
    Contus\Video\Schedulers\ExpireScheduledDateScheduler::class,
    // Contus\Video\Schedulers\LoginDeviceScheduler::class,
    Contus\Video\Schedulers\LandingBannerScheduler::class,
    Contus\Customer\Schedulers\SubscriptionExpireScheduler::class,
];
