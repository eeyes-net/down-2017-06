<?php

namespace app\auth\controller;

use think\Controller;
use think\Request;
use think\Session;
use think\Exception;
use think\Log;
use app\common\model\User;
use sxxuz\OAuth2\Client\Provider\Eeyes;
use sxxuz\OAuth2\Client\Provider\EeyesResourceOwner;

class OAuthLogin extends Controller
{
    public function login(Request $request)
    {
		$scopes = [
			'info-username.read',
			'info-user_id.read',
			'info-name.read',
		];
    	$eeyesClient = new Eeyes([
    		'clientId'       => config('oauth.app_id'),
		    'clientSecret'   => config('oauth.app_secret'),
			'redirectUri'    => config('oauth.redirect_uri'),
			'scope'	         => $scopes,
		]);

		$user = $eeyesClient->getUser();

		$username = $user['username'];
		$name = $user['name'];

		$u = User::where('username', $username)->find();

		if (!$u) {
			$u = new User();
			$u->username = $username;
			$u->name = $name;
			$u->save();
		}

		Session::set('user', $user);

		return redirect('/');
    }

    public function logout()
    {
        Session::delete('user');
        return redirect('/');
    }

    public function getUser()
    {
        if (Session::has('user'))
        {
            $username = Session::get('user')['username'];
        } else {
            $username = '游客';
        }

        return json([
            'code' => '200',
            'username' => $username,
            'msg' => 'OK',
        ]);
    }
}
