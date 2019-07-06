<?php
/**
 * Author:HeChao
 * Created by PhpStorm.
 * User: blant
 * Date: 2019-02-27
 * Time: 17:59
 */
namespace App\Http\Traits;

use GuzzleHttp\Client;

trait PassPortProxy
{
    /**
     * 使用密码获取 TOKEN
     *
     * @return mixed
     */
    public function getAuthTokenByPassword($guard = null)
    {
        $http_client = new Client();
        $domain      = request() -> root();
        $guard       = $guard ?? \Config ::get('auth.guards.api.provider');
        $params      = [
            'form_params' => [
                'grant_type'    => 'password',
                'client_id'     => config("passport.$guard.client_id"),
                'client_secret' => config("passport.$guard.client_secret"),
                'username'      => request('account'),
                'password'      => request('secret') ?? request('password'),
                'provider'      => $guard,
                'scope'         => '*',//作用域
            ],
        ];
        try{
            $response = $http_client -> post("$domain/oauth/token",$params);

            return json_decode($response -> getBody(),true);
        } catch (\Exception $error){
            abort($error -> getCode(),$error -> getMessage());
        }
    }

    /**
     * 用授权码获取 token
     * 暂未使用
     *
     * @param $code
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getAuthTokenByAuthcode($code)
    {
        $http_client = new Client();
        $domain      = request() -> root();
        try{
            $response = $http_client -> post("$domain/oauth/token",[
                'form_params' => [
                    'grant_type'    => 'authorization_code',
                    'client_id'     => '6',
                    'client_secret' => 'XNbZFAJt6P4Y010YYvvAt3eDx63dDsdsODQEwUjR',
                    'redirect_uri'  => 'http://cdygj.com/auth/callback',
                    'code'          => $code,
                ],
            ]);

            return json_decode($response -> getBody(),true);
        } catch (\Exception $error){

            abort($error -> getCode(),$error -> getMessage());
        }
        //授权码地址
        //http://cdygj.com/oauth/authorize?client_id=3&redirect_uri=http://cdygj.com/auth/callback&response_type=code
    }

    /**
     * 用刷新token获取新的 token
     *
     * @return mixed
     */
    public function getTokenByRefreshToken($guard = 'users')
    {
        $http_client   = new Client();
        $domain        = request() -> root();
        $refresh_token = request('token');
        try{
            $response = $http_client -> post("$domain/oauth/token",[
                'form_params' => [
                    'grant_type'    => 'refresh_token',
                    'refresh_token' => $refresh_token,
                    'client_id'     => config("passport.$guard.client_id"),
                    'client_secret' => config("passport.$guard.client_secret"),
                    'scope'         => '*',
                ],
            ]);

            return json_decode($response -> getBody(),true);
        } catch (\Exception $error){

            abort($error -> getCode(),$error -> getMessage());
        }
    }
}