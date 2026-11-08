<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Portal base URLs (same server, different hosts)
    |--------------------------------------------------------------------------
    |
    | Used for absolute links in admin notifications and emails when the
    | request Host is not the admin/agent domain.
    |
    */

    'admin_url' => rtrim(env('ADMIN_PORTAL_URL', 'https://tonkow.com'), '/'),

    'agent_url' => rtrim(env('AGENT_PORTAL_URL', 'https://agto-app.com'), '/'),

    'admin_path' => trim(env('ADMIN_PORTAL_PATH', 'adto-app'), '/'),

    'support_email' => env('TOAPP_SUPPORT_EMAIL', 'tonkowtek@to-app.com'),

    'social' => [
        'facebook' => 'https://www.facebook.com/profile.php?id=61573036076867',
        'instagram' => 'https://www.instagram.com/toapp_official',
        'tiktok' => 'https://www.tiktok.com/@toapp_official',
        'threads' => 'https://www.threads.com/@toapp_official',
        'x' => 'https://x.com/toapp_official',
    ],

];
