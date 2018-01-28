<?php

use think\Env;

return [
    'account' => [
        'url' => Env::get('oauth.url','https://account.eeyes.net/'),
        'app' => [
        'id' => Env::get('oauth.app_id'),
        'secret' => Env::get('oauth.app_secret'),
        'redirect_uri' => Env::get('oauth.redirect_uri'),
        ],
    ],
];