<?php

namespace app\auth\controller;

use phpCAS;
use think\Controller;
use think\Request;

class CasLogin extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        init_php_cas();
    }

    public static function login()
    {
        phpCAS::forceAuthentication();
        return redirect(url('index/Index/index'));
    }

    public function logout()
    {
        phpCAS::logoutWithRedirectService(request()->domain() . '/');
    }
}
