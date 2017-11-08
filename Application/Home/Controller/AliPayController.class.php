<?php
namespace Home\Controller;

use Think\Controller;

class AliPayController extends Controller
{

    protected function _initialize()
    {
        C('SHOW_PAGE_TRACE', false);
        $TMPL_PARSE_STRING = C('TMPL_PARSE_STRING');
        $TMPL_PARSE_STRING += [
            '__HTML__'      => __ROOT__ . '/html',
            '__HTML_CSS__'  => __ROOT__ . '/html/css',
            '__HTML_JS__'   => __ROOT__ . '/html/js',
            '__HTML_IMG__'  => __ROOT__ . '/html/images',
            '__HTML_WEUI__' => __ROOT__ . '/html/weui',
        ];
        C('TMPL_PARSE_STRING', $TMPL_PARSE_STRING);
    }
    public function pay() {
        $this->display('Public/pay');
    }
    /**
     * 支付宝支付异步通知回调函数
     */
    public function notify_url(){
        vendor('Alipay.lib.alipay_notify');
        //商户订单号
        $out_trade_no = I('post.out_trade_no');
        if ($out_trade_no) {
            $order = M("shoppingmall_goodsorder")->where(['ordernum' => $out_trade_no])->find();
            if(!empty($order) && $order['checkinfo'] == 1) {
                $alipay_config = C('ALIPAY_CONFIG');
                $alipayNotify = new \AlipayNotify($alipay_config);
                $verify_result = $alipayNotify->verifyNotify();
                if ($verify_result) {//验证成功
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    //请在这里加上商户的业务逻辑程序代


                    //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

                    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

                    //支付宝交易号

                    $trade_no = I('post.trade_no');

                    //交易状态
                    $trade_status = I('post.trade_status');

                    $tradeStatus = I('post.trade_status');
                    if ($tradeStatus == 'TRADE_SUCCESS' || $tradeStatus == 'TRADE_FINISHED') {
                        D('shop_order')->startTrans();
                        //更新订单的状态
                        $weixin_change = M('shop_order')->where(['id' => $order['id']])->save(['checkinfo' => 2, 'transaction_id' => $trade_no]);
                        //发送支付成功模板消息
                        switch($order['pay_type']){
                            case 1:
                                $pay_type_name ="微信";
                                break;
                            case 2:
                                $pay_type_name ="支付宝";
                                break;
                        }
                        $orderModel =new  \Common\Util\Order();
                        $orderModel->ChangeOrderStatus($order['id'],'payed');
                        if (false === $weixin_change) {
                            D('shop_order')->rollback();
                            return false;
                        } else {
                            D('shop_order')->commit();
                        }
                    }
                    //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

                    echo "success";        //请不要修改或删除

                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                } else {
                    //验证失败
                    echo "fail";

                    //调试用，写文本函数记录程序运行情况是否正常
                    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
                }
            }
        }
    }
    /**
     * 支付宝支付直接跳转函数
     */
    public function return_url(){
        vendor('Alipay.lib.alipay_notify');
        //商户订单号
        $out_trade_no = I('get.out_trade_no');
            $order = M("shop_order")->where(['ordernum' => $out_trade_no])->find();
            $alipay_config = C('ALIPAY_CONFIG');
            $alipayNotify = new \AlipayNotify($alipay_config);
            $verify_result = $alipayNotify->verifyReturn();
            if ($verify_result) {//验证成功
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //请在这里加上商户的业务逻辑程序代


                //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

                //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

                //支付宝交易号

                $trade_no = I('get.trade_no');

                //交易状态
                $trade_status = I('get.trade_status');

                $tradeStatus = I('get.trade_status');
                if ($tradeStatus == 'TRADE_SUCCESS' || $tradeStatus == 'TRADE_FINISHED') {
                    D('shop_order')->startTrans();
                    //更新订单的状态
                    $weixin_change = M('shop_order')->where(['id' => $order['id']])->save(['checkinfo' => 2, 'transaction_id' => $trade_no]);
                    if (false === $weixin_change) {
                        D('shop_order')->rollback();
                        return false;
                    } else {
                        D('shop_order')->commit();
                    }
                }
                //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

                echo "success";        //请不要修改或删除

                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            } else {
                //验证失败
                echo "fail";

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
    }
    //doalipay方法
    /*该方法其实就是将接口文件包下alipayapi.php的内容复制过来
      然后进行相关处理
    */
    public function doalipay($order){
        vendor('Alipay.lib.alipay_submit');
        $alipay_config = C('ALIPAY_CONFIG');
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $order['ordernum'];

        //订单名称，必填
        $subject = '养生商城';

        //付款金额，必填
        $total_fee = $order['money'];

        //收银台页面上，商品展示的超链接，必填
        $show_url = '';

        //商品描述，可空
        $body = '养生商城';
        //ptrace($this->alipay_config);

        /************************************************************/

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service"       => $alipay_config['service'],
            "partner"       => $alipay_config['partner'],
            "seller_id"  => $alipay_config['seller_id'],
            "payment_type"	=> $alipay_config['payment_type'],
            "notify_url"	=> $alipay_config['notify_url'],
            "return_url"	=> $alipay_config['return_url'],
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset'])),
            "out_trade_no"	=> $out_trade_no,
            "subject"	=> $subject,
            "total_fee"	=>$order['money'],
            "show_url"	=> '',
            //"app_pay"	=> "Y",//启用此参数能唤起钱包APP支付宝
            "body"	=> '',
            //其他业务参数根据在线开发文档，添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.2Z6TSk&treeId=60&articleId=103693&docType=1
            //如"参数名"	=> "参数值"   注：上一个参数末尾需要“,”逗号。

        );
        //建立请求

        $alipaySubmit = new \AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestParaToString($parameter);
        $payUrl = "https://mapi.alipay.com/gateway.do?" . $html_text;
        return($payUrl);
    }
    // 支付退款接口
    public  function  alipay_refund($order_no){
      /*  vendor("alipay-sdk.aop.AopClient");
        vendor("alipay-sdk.aop.request.AlipayTradeCreateRequest");
        $alipayconfig =C('ALIPAY_CONFIG');
        $orderModel = M('shoppingmall_goodsorder');
        $order      = $orderModel->where(['ordernum' => $order_no])->find();
        $aop = new \AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $alipayconfig['app_id'];
        $aop->rsaPrivateKeyFilePath =$alipayconfig['private_key_path'];
        $aop->alipayPublicKey=$alipayconfig['ali_public_key_path'];
        $aop->sign_type ='RSA';
        $aop->apiVersion = '1.0';
        $aop->postCharset='utf-8';
        $aop->format='json';
        $request = new \AlipayTradeCreateRequest();
        $post_array =array(
            'trade_no'=>$order['ordernum'],
            'refund_amount '=>$order['payment'],
        );
        $post_json =json_encode($post_array);
        $request->setBizContent($post_json);
        $result = $aop->execute ($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        return $resultCode;*/
        $orderModel = M('shop_order');
        $order      = $orderModel->where(['ordernum' => $order_no])->find();
        vendor("Alipay.lib.alipay_submit");
        $alipay_config =C('ALIPAY_CONFIG');
        $alipay_config['seller_user_id'] = $alipay_config['seller_id'];
        $alipay_config['notify_url'] = XILUDomain(). __ROOT__ . '/index.php/Home/AliPay/refund_notify_url';
        $alipay_config['refund_date']=date("Y-m-d H:i:s",time());
        $alipay_config['service']='refund_fastpay_by_platform_pwd';
        $parameter = array(
            "service"       => trim($alipay_config['service']),
            "partner"       => trim($alipay_config['partner']),
            "notify_url"	=> '',
            "seller_user_id"	=> trim($alipay_config['partner']),
            "refund_date"	=> trim($alipay_config['refund_date']),
            "batch_no"	=> date('Ymd').$order_no,
            "batch_num"	=> 1,
            "detail_data"	=>$order['transaction_id'].'^'.$order['payment'].'^退款',
            "_input_charset"	=> trim($alipay_config['input_charset'])
        );
        $alipaySubmit = new \AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestParaToString($parameter);
        $payUrl = "https://mapi.alipay.com/gateway.do?" . $html_text;
        return $payUrl;
    }
    //   支付宝退款接口回调地址
    public  function  refund_notify_url(){
        vendor("Alipay.lib.alipay_notify");
        $alipay_config =C('ALIPAY_CONFIG');
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        // ptrace($verify_result);
        if($verify_result){
            $batch_no = $_POST['batch_no'];
            $order =M('shop_order')->where(['ordernum'=>$batch_no])->find();
            $result =M('shop_order')->where(['ordernum'=>$batch_no])->save(['checkinfo'=>6]);
            if($result){
                if(!empty($order['coupon'])){
                    $memberClass =  new \Common\Util\Member();
                    $memberClass->set_user_coupons_status(explode(',',  $order['coupon']), 1);
                }
                if(!empty($order['coin'])){
                    $memberClass =  new \Common\Util\Member();
                    $memberClass->set_coin($order['uid'],$order['coin'],'add');
                    $memberClass->coin_log($order['uid'],'退款成功金币返回',$order['coin']);
                }
                $openid =get_wx_userinfo($order['uid'],'openid');
                $msg =array(
                    "first" =>"您申请的退款已通过",
                    "keyword1" =>$order['ordernum'],
                    "keyword2" =>$order['payment'],
                );
                $wechat = new \Home\Controller\WeixinController();
                $wechat->send_template($openid,'HZgSWF-3CeyXh1VcpCkB7_NVOhyxKW6em1sOwYSxBGI',$msg);
            }
            ptrace("退款回调结果".$result);
        }else{
            echo "fail";
        }
    }
}
