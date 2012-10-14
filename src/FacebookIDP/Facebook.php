<?php
namespace FacebookIDP;

class Facebook extends Oauth2\Client\IDP {

	public $scope = array('email', 'read_stream');

	function urlAuthorize()
	{
		return 'https://www.facebook.com/dialog/oauth';
	}

	function urlAccessToken()
	{
		return 'https://graph.facebook.com/oauth/access_token';
	}

	public function urlUserInfo(Oauth2\Client\Token\Access $token)
	{
		return 'https://graph.facebook.com/me?'.http_build_query(array(
			'access_token' => $token,
		));
	}

	public function userInfo($response, Oauth2\Client\Token\Access $token)
	{
		return array(
			'uid' => $response->id,
			'nickname' => isset($response->username) ? $response->username : null,
			'name' => $response->name,
			'first_name' => $response->first_name,
			'last_name' => $response->last_name,
			'email' => isset($response->email) ? $response->email : null,
			'location' => isset($response->hometown->name) ? $response->hometown->name : null,
			'description' => isset($response->bio) ? $response->bio : null,
			'image' => 'https://graph.facebook.com/me/picture?type=normal&access_token='.$token,
			'urls' => array(
			  'Facebook' => $response->link,
			),
		);
	}
}