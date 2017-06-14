<?php

namespace app\traits\controller;

use phpCAS;

trait CheckPermission
{
    protected function checkPermission()
    {
        if (!cidr_match(request()->ip(), config('ip.allow'))) {
            init_php_cas();
            if (!phpCAS::isAuthenticated()) {
                $this->error('您不在校园网内，请使用CAS登录验证身份', url('auth/CasLogin/login'), null, 0);
            }
        }
    }
}
