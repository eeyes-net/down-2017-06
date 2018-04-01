<?php

namespace app\auth\controller;

use app\common\model\User;
use sxxuz\OAuth2\Client\Provider\Eeyes;
use think\Controller;
use think\Request;
use think\Session;

class OAuthLogin extends Controller
{
    public function login(Request $request)
    {
        $scopes = [
            'info-username.read',
            'info-name.read',
        ];
        $eeyesClient = new Eeyes([
            'clientId' => config('oauth.app_id'),
            'clientSecret' => config('oauth.app_secret'),
            'redirectUri' => config('oauth.redirect_uri'),
            'scope' => $scopes,
        ]);

        $user = $eeyesClient->getUser();

        $u = User::where('username', $user['username'])->find();

        if (!$u) {
            $u = new User();
            $u->username = $user['username'];
            $u->name = $user['name'];
            $u->save();
        }

        $user['id'] = $u->id;

        Session::set('user', $user);

        return redirect('/');
    }

    public function logout()
    {
        Session::delete('user');
        return redirect('https://cas.xjtu.edu.cn/logout');
    }

    public function getUser()
    {
        if (Session::has('user')) {
            $code = 200;
            $username = Session::get('user')['name'];
        } else {
            $code = 403;
            $username = '游客';
        }

        return json([
            'code' => $code,
            'username' => $username,
            'msg' => 'OK',
        ]);
    }
}
