<?php

use think\Env;

return [
	'app_id' => Env::get('oauth.app_id'),
	'app_secret' => Env::get('oauth.app_secret'),
	'redirect_uri' => Env::get('oauth.redirect_uri'),
];