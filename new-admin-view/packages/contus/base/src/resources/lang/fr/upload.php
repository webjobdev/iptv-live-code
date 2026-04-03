<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Upload Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain messages used by
    | upload html template and error messsage
    |
    */
    'stat'       => [
        'waiting'    => 'attendre..',
        'processing' => 'En traitement..',
        'uploading'  => 'mise en ligne (: pc) ..',
        'done'       => 'Terminé',
        'failed'     => 'Échoué'
    ], 
    'error'       => [
        'minimumResolution' => ': attribut résolution minimale doit être au moins: résolution',
        'mime'              => 'Image devrait dans: mime',
        'fileSize'          => "La taille de l'image doit être inférieure à: maxSize",
        'invalid'           => "L'image sélectionnée est Résolution minimale non valide 400 * 300"
    ],
    'url'  => "VideoUrl doit être une URL HTTP valide.",
];
