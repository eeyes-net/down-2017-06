<?php

namespace app\auth\controller;

use think\Controller;
use think\Session;
use GuzzleHttp\Client;
use app\common\model\User;

class OAuthLogin extends Controller
{
    public function login()
    {
        if(!Session::has('authorization'))
        {
            return redirect(config('oauth.account.url') . 'oauth/authorize?' . http_build_query([
                    'client_id' => config('oauth.account.app.ia'),
                    'redirect_uri' => config('oauth.account.app.redirect_uri'),
                    'response_type' => 'code',
                    'scope' => implode(' ', [
                        'info-username.read',
                        'info-user_id.read',
                        'info-name.read',
                        'info-email.read',
                        'info-email.write',
                        'info-mobile.read',
                        'info-mobile.write',
                        'info-school.read',
                    ]),
                ]));
        }

        $client = new Client;
        $response = $client->get(config('oauth.account.url') . '/api/user', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => Session::get('authorization'),
            ],
        ]);
        $data = json_decode((string)$response->getBody(), true);
        $username = $data['username'];

        $user = User::where('username',$username)->select();
        if(!$user)
        {
            $user = new User();
            $user -> username = $username;
            $user -> stu_id = $data['stu_id'];
            $user -> name = $data['name'];
            $user -> save();
        }
        Session::set('name',$data['name']);
        return redirect('/');
    }

    public function logout()
    {
        Session::destroy();
        return redirect('/');
    }

    public function getUser()
    {
        if (Session::has('name'))
        {
            $username = Session::get('name');
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
