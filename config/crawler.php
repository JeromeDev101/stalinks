<?php

return [
    'selectorDomCrawl' => ['footer', '*[id*="footer"]', '*[class*="footer"]', 'header', '*[id*="header"]', '*[class*="header"]', '*[class*="feedback"]',
     '*[class*="region"]', '*[id*="region"]', '*[class*="social"]', '*[id*="social"]', '*[class*="widget"]'],
    'regex_email' => '([a-zA-Z0-9+._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9_-]+)',
    'regex_fb' => '/(?:https?:\/\/)?(?:www\.)?(mbasic.facebook|m\.facebook|facebook|fb)\.(com|me)\/(?:(?:\w\.)*#!\/)?(?:pages\/.*?\/)?(?:[\w\-\.]*\/)*([\w\-\.]*)/',
    'max_process' => 50,
    'max_process_ahrefs' => 100
];