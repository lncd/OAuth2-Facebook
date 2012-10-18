<?php
/**
 * Facebook OAuth2 Provider
 *
 * This IDP is based on code originally by Phil Sturgeon which can be found here:
 *
 *     https://github.com/philsturgeon/codeigniter-oauth2/blob/master/libraries/Provider/Facebook.php
 *
 * @package    lncd/oauth2-facebook
 * @category   Provider
 * @author     Alex Bilbie
 * @copyright  (c) 2012 University of Lincoln
 * @license    http://opensource.org/licenses/mit-license.php
 */

class FacebookIDP extends \Oauth2\Client\IDP {

    public $scope = array('email', 'read_stream');
    public $responseType = 'string';

    public function urlAuthorize()
    {
        return 'https://www.facebook.com/dialog/oauth';
    }

    public function urlAccessToken()
    {
        return 'https://graph.facebook.com/oauth/access_token';
    }

    public function urlUserDetails(\Oauth2\Client\Token\Access $token)
    {
        return 'https://graph.facebook.com/me?'.http_build_query(array(
            'access_token' => (string) $token,
        ));
    }

    public function userDetails($response, \Oauth2\Client\Token\Access $token)
    {
        $details = array(
            'uid' => $response->id,
            'nickname' => isset($response->username) ? $response->username : null,
            'name' => $response->name,
            'first_name' => $response->first_name,
            'last_name' => $response->last_name,
            'email' => isset($response->email) ? $response->email : null,
            'location' => isset($response->hometown->name) ? $response->hometown->name : null,
            'description' => isset($response->bio) ? $response->bio : null,
            'urls' => array(
              'Facebook' => $response->link,
            ),
            'image' =>  null
        );

        if ($headers = get_headers('https://graph.facebook.com/me/picture?type=normal&access_token='.$token, 1))
        {
            $details['image'] = $headers['Location'];
        }

        return $details;
    }
}