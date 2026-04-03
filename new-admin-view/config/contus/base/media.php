<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Various Media Configuration by model
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many model based configuration such as supported_format
    | maximum_file_size in MB,temporary_image_storage_path
    |
    */
    'video' => [
        'supported_format'             => 'mp4, mov, 3gp, avi, mkv and wmv',
        'fileSize'                     => 2,
        'temporary_storage_path'       => 'uploads'.DIRECTORY_SEPARATOR.'videos'.DIRECTORY_SEPARATOR.'files',
        's3_location_video_source'     => 'videos/source/'.date("Y").'/'.date("m").'/',
        's3_location_video_destination'=> 'videos/output/'.date("Y").'/'.date("m").'/',
    ],
    'live_video_recordings' => [
        'supported_format'             => 'mp4',
        'fileSize'                     => 2,
        'temporary_storage_path'       => 'uploads'.DIRECTORY_SEPARATOR.'videos'.DIRECTORY_SEPARATOR.'live_recordings',
        's3_location_video_source'     => 'videos/source/live_recordings/'.date("Y").'/'.date("m").'/',
        's3_location_video_destination'=> 'videos/output/live_recordings/'.date("Y").'/'.date("m").'/',
    ],
    'video_lingual_audio_tracks' => [
        'supported_format'             => 'mp3 and wav',
        's3_location_audio_source'     => 'videos/source/audio_tracks/'.date("Y").'/'.date("m").'/',
    ]
];