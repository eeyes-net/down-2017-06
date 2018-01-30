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
    	$provider = new Eeyes([
    		'clientId'       => config('oauth.app_id'),
		    'clientSecret'   => config('oauth.app_secret'),
		    'redirectUri'    => config('oauth.redirect_uri'),
	    ]);

    	if($request->get('code') == null)
	    {
	    	$authorizationUrl = $provider->getAuthorizationUrl().'?'.http_build_query([
	    		'client_id'     => config('oauth.app_id'),
				'redirect_uri'  => config('oauth.redirect_uri'),
				'response_type' => 'code',
				'scope'         => implode(' ',[
					'info-username.read',
					'info-user_id.read',
					'info-name.read',
				]),
			    ]);
		    Session::set('oauth2state',$provider->getState());
		    return redirect($authorizationUrl);
	    } elseif ($provider->getState() == !null || $provider->getState() !== Session::get('oauth2state')) {
    		if(isset($_SESSION['oauth2state']))
            {
                Session::delete('oauth2state');
            }
    		exit('Invalid State');
	    } else {
    		try{
			    $response = $provider->getAccessToken('authorization_code',[
				    'code' => $request->get('code'),
			    ]);

			    Session::set('authorization',$response->getValues()['token_type']);
			    Session::set('access_token',$response->getToken());
			    Session::set('refresh_token',$response->getRefreshToken());

			    $user = $provider->getResourceOwner($response);
			    $userID = $user->getId();
			    $result = User::where('user_id',$userID)->select();

		    } catch (Exception $exception) {
    			exit($exception->getMessage());
		    }

    		if(isset($result))
		    {
		    	$newUser = new User();
		    	$newUser->user_id = $userID;
		    	$newUser->username = $user->getUsername();
		    	$newUser->name = $user->getName();
		    	$newUser->save();
		    }

            Session::set('name',$user->getName());

    		return redirect('/');
	    }
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
