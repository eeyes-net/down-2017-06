<?php

use think\Env;

return [
    'admin' => Env::get('password.admin', 'admin'),
];
