<?php
/**
 * Function
 * Created by PhpStorm.
 * User: john
 * Date: 2017/2/27
 * Time: 16:25
 */

namespace Common\Util;
class Message{
    private $BASE_URL;
    private $ACCOUNT_SID;
    private $CONTENT_TYPE;
    private $AUTH_TOKEN;
    private $ACCEPT;

    public  function  __construct(){
        $this->BASE_URL ='https://api.miaodiyun.com/20150822/';
        $this->ACCOUNT_SID ='472ee50ac44143168f9538186bffed85';
        $this->AUTH_TOKEN ='ea4dd804152d4325ab9be1c1a58cf12b';
        $this->CONTENT_TYPE ='application/x-www-form-urlencoded';
        $this->ACCEPT ='application/json';
    }

    public function createUrl($funAndOperate)
    {
        // 时间戳
        date_default_timezone_set("Asia/Shanghai");
        $timestamp = date("YmdHis");
        return $this->BASE_URL . $funAndOperate;
    }

    public  function createSig()
    {
        $timestamp = date("YmdHis");
        // 签名
        $sig = md5($this->ACCOUNT_SID  . $this->AUTH_TOKEN . $timestamp);
        return $sig;
    }

    public  function createBasicAuthData()
    {
        $timestamp = date("YmdHis");
        // 签名
        $sig = md5($this->ACCOUNT_SID.$this->AUTH_TOKEN.$timestamp);
        return array("accountSid" =>$this->ACCOUNT_SID, "timestamp" => $timestamp, "sig" => $sig, "respDataType"=> "JSON");
    }

    /**
     * 创建请求头
     * @param body
     * @return
     */
    public function createHeaders()
    {
        $headers = array('Content-type: ' . $this->CONTENT_TYPE, 'Accept: ' . $this->ACCEPT);

        return $headers;
    }
    /**
     * post请求
     *
     * @param funAndOperate
     *            功能和操作
     * @param body
     *            要post的数据
     * @return
     * @throws IOException
     */
    public  function post($funAndOperate, $body)
    {
        // 构造请求数据
        $url = $this->createUrl($funAndOperate);
        $headers = $this->createHeaders();

        /* echo("url:<br/>" . $url . "\n");
         echo("<br/><br/>body:<br/>" . json_encode($body));
         echo("<br/><br/>headers:<br/>");
         var_dump($headers);*/

        // 要求post请求的消息体为&拼接的字符串，所以做下面转换
        $fields_string = "";
        foreach ($body as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        // 提交请求
        $con = curl_init();
        curl_setopt($con, CURLOPT_URL, $url);
        curl_setopt($con, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($con, CURLOPT_HEADER, 0);
        curl_setopt($con, CURLOPT_POST, 1);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($con, CURLOPT_POSTFIELDS, $fields_string);
        $result = curl_exec($con);
        curl_close($con);
        return "" . $result;
    }

}