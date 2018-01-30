<?php

use think\Env;

return [
	'url' => Env::get('oauth.url','https://account.eeyes.net/'),
	'app_id' => Env::get('oauth.app_id'),
	'app_secret' => Env::get('oauth.app_secret'),
	'redirect_uri' => Env::get('oauth.redirect_uri'),
];