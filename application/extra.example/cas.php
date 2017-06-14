<?php

use think\Env;

return [
    'server_version' => Env::get('cas.server_version', '2.0'),
    'server_hostname' => Env::get('cas.server_hostname', 'cas.eeyes.net'),
    'server_port' => (int)Env::get('cas.server_port', '443'),
    'server_uri' => (string)Env::get('cas.server_uri', ''),
];
