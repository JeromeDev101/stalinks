<?php

return [
    'EXT_STATUS_NEW' => 0,
    'EXT_STATUS_CRAWL_FAILED' => 10,
    'EXT_STATUS_CONTACTS_NULL' => 20,
    'EXT_STATUS_GOT_CONTACTS' => 30,
    'EXT_STATUS_AHREAFED' => 40,
    'EXT_STATUS_CONTACTED' => 50,
    'EXT_STATUS_REFUSED' => 60,
    'EXT_STATUS_IN_TOUCHED' => 70,
    'EXT_STATUS_UNDEFINED' => 80,
    'UNQUALIFIED' => 90,
    'EXT_STATUS_QUALIFIED' => 100,

    //user type
    'USER_TYPE_NORMAL' => 0,
    'USER_TYPE_ADMIN' => 10,

    //action log
    'ACTION_UPDATE' => 'update',
    'ACTION_CREATE' => 'create',
    'ACTION_DELETE' => 'delete',
    'ACTION_ALEXA' => 'get_alexa',

    'backlink_status' => [
        'BACKLINK_STATUS_PROCESSING' => 'Processing',
        'BACKLINK_STATUS_LIVE' => 'Live',
        'BACKLINK_STATUS_CONTENT_WRITING' => 'Content Writing',
        'BACKLINK_STATUS_CONTENT_SENT' => 'Content sent',
        'BACKLINK_STATUS_UNDEFINED' => 'Undefined',
    ],
    'status_mails' => [
        'FAILED' => 0,
        'SUCCESS' => 1,
    ]
];
