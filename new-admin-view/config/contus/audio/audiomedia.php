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
    'audio' => [
        'supported_format'             => 'mp3 and wav',
        'fileSize'                     => 2,
        'temporary_storage_path'       => 'uploads'.DIRECTORY_SEPARATOR.'audios'.DIRECTORY_SEPARATOR.'files',
        'temporary_storage_path_chunks'       => 'uploads'.DIRECTORY_SEPARATOR.'audios'.DIRECTORY_SEPARATOR.'chunks',
        's3_location_audio_source'     => 'audios/source/'.date("Y").'/'.date("m").'/',
        's3_location_audio_destination'     => 'audios/destination/'.date("Y").'/'.date("m").'/',
    ],
    'audioAds' => [
        'supported_format'             => 'mp3 and wav',
        'fileSize'                     => 2,
        'temporary_storage_path'       => 'uploads'.DIRECTORY_SEPARATOR.'audioAds'.DIRECTORY_SEPARATOR.'files',
        'temporary_storage_path_chunks'       => 'uploads'.DIRECTORY_SEPARATOR.'audios'.DIRECTORY_SEPARATOR.'chunks',
        's3_location_audio_ad_source'     => 'audioAds/source/'.date("Y").'/'.date("m").'/',
        's3_location_audio_ad_destination'     => 'audioAds/destination/'.date("Y").'/'.date("m").'/',
    ],
];