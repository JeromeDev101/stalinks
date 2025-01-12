<?php

return [
    'EXT_STATUS_NEW' => 0,
    'EXT_STATUS_CRAWL_FAILED' => 10,
    'EXT_STATUS_CONTACTS_NULL' => 20,
    'EXT_STATUS_GOT_CONTACTS' => 30,
    'EXT_STATUS_CONTACTED' => 50,
    'EXT_STATUS_CONTACTED_VIA_FORM' => 120,
    'EXT_STATUS_NO_ANSWER' => 55,
    'EXT_STATUS_REFUSED' => 60,
    'EXT_STATUS_IN_TOUCHED' => 70,
    'EXT_STATUS_UNDEFINED' => 80,
    'UNQUALIFIED' => 90,
    'EXT_STATUS_QUALIFIED' => 100,
    'EXT_STATUS_GOT_EMAIL' => 110,

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
    ],

//    Languages that are being used in a single country.
//    language_id => country_id
    'language_country' => [
        '10' => 12,
        '13' => 4,
        '21' => 188,
        '23' => 1,
        '24' => 2,
        '26' => 190,
        '29' => 83,
        '32' => 179
    ],

    'FORMULA' => [
        'EXT_BUYER_ADDITIONAL' => 10,
        'EXT_PERCENTAGE' => 17,
        'AFFILIATE_PERCENTAGE' => 15,
    ]
];
