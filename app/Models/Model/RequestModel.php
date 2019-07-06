<?php
/**
 * Author:HeChao
 * Created by PhpStorm.
 * User: blant
 * Date: 2019-02-25
 * Time: 13:28
 */
namespace App\Models\Model;

use GuzzleHttp\Client;

/**
 * 业务处理,用于请求外部接口
 *
 * @property Client httpClient
 */
class RequestModel
{

    /**
     * RequestModel constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this -> httpClient = $client;
    }

    /**
     * 发送短信
     *
     * @param $code
     *
     * @return string
     */
    public function sendSms($code)
    {
        $result = $this -> httpClient -> get('www.baidu.com');

        return $result && $code?"success":"fail";
    }

}