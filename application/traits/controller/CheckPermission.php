<?php

namespace app\traits\controller;

use think\Session;

trait CheckPermission
{
    protected function checkPermission()
    {
        if (!cidr_match(request()->ip(), config('ip.allow'))) {
            if (!Session::has('user') || !Session::get('user')['username']) {
                $this->error('您不在校园网内，请使用CAS登录验证身份', url('auth/OAuthLogin/login'), null, 0);
            }
        }
    }
}
