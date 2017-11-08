<?php
/**
 * Function
 * Created by PhpStorm.
 * User: john
 * Date: 2017/2/23
 * Time: 15:14
 */
namespace Shop\Admin;

use Admin\Controller\AdminController;
use Common\Builder\FormBuilder;
use Common\Builder\ListBuilder;

class OrderAdmin extends AdminController{
    // 订单列表
    public  function  index(){
        $map = [];
        // $map['type'] ='normal';
        $this->extendDates($map, 'create_time');

        $keyword = I('keyword', '', 'string');
        if ($keyword) {
            $map['id|ordernum'] = [
                $keyword,
                $keyword,
                '_multi' => true,
            ];
        }

        // 下单人搜索
        if ($username = I('username', '')) {
            $uid = M('admin_user')->getFieldByUsername($username, 'id');
            if ($uid) {
                $map['uid'] = $uid;
            }
        }

        if ($checkinfo = I('checkinfo', '')) {
            $map['checkinfo'] = $checkinfo;
        }

        list($data_list, $page, $model_object) = $this->lists('goodsorder', $map, 'id DESC');

        foreach ($data_list as $key => &$record) {
            $record['goodsinfo'] = get_goods_info($record['id'],1);
            $record['checkname_color'] =$model_object->get_color($record['checkinfo']);
        }

        if ($data_list) {
            $usernames = array_unique(array_column($data_list, 'username', 'username'));
            $usernames = array_merge(['' => '请选择'], $usernames);
        } else {
            $usernames = ['' => '请选择'];
        }

        $js = <<<JS
<script>
$(function(){
    var form = $('form');
    var o_action = form.attr('action');
    $('.export').on('click', function(){
        form.attr('action', $(this).attr('href'));
        $('.search-btn').click();
        form.attr('action', o_action);
        return false;
    });
})
</script>
JS;
        $exportBtn['title'] = '导出全部订单';
        $exportBtn['class'] = 'btn btn-primary export';
        $exportBtn['href']  = U('Order/export', I('get.'), false);

        // 使用Builder快速建立列表页面。
        $builder = new ListBuilder();
        $builder->setMetaTitle('订单列表') // 设置页面标题
        ->addTopButton('self', $exportBtn)
            ->addSearchItem('keyword', 'text', '订单号', '')
            ->addSearchItem('dates', 'dateranger', '', '下单时间', 365)
            ->addSearchItem('checkinfo', 'select', '', '', ['' => '订单状态'] + $model_object->checkinfos)
            ->addTableColumn('id', 'id')
            ->addTableColumn('ordernum', "订单号")
            ->addTableColumn('create_time', "下单日期")
            ->addTableColumn('nickname', "下单人")
            ->addTableColumn('goodsinfo', '商品信息')
            ->addTableColumn('price_text', '订单金额')
            ->addTableColumn('checkname_color', "状态")
            ->addTableColumn("right_button", "操作管理", "btn")

            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit', ['href' => U('edit', ['id' => '__data_id__'])]) // 添加编辑按钮
            // ->addRightButton('delete', ['model' => 'goodsorder'])
            ->setExtraHtml($js)
            ->display();
    }

    public  function  edit($id){
        $model_object = D('goodsorder');
        if (IS_POST) {
            $order_info =D('goodsorder')->where(['id'=>$id])->find();
            if($order_info['checkinfo']==5 ||$order_info['checkinfo']==6 ||$order_info['checkinfo']==7 ){
                $this->error('退款的订单不能修改状态');
            }
            if (!$data = $model_object->create()) {
                $this->error($model_object->getError());
            } else {
                $data['unread'] =1;
                if ($data['type'] != 'conference' && $data['checkinfo'] == 3) {
                    $data['auto_complete_time'] =datetime();
                    if (empty($data['postmode'])) {
                        $this->error('请选择快递公司');
                    }
                    if (empty($data['postid'])) {
                        $this->error('请填写快递单号');
                    }
                }
                if (!isset($data['posttime'])) {
                    $data['posttime'] = datetime();
                }
                if (false !== $model_object->where(['id' => $id])->save($data)) {
                    $this->success('更新成功', U('index'));
                } else {
                    trace($model_object->getError());
                    $this->error('更新失败');
                }
            }
        } else {
            $info               = $model_object->find($id);
            $info['price_text'] = "总价：{$info['total']}元/支付价：{$info['payment']}元";
            $info['goodsinfo']  = nl2br(get_goods_info($id));
            if (!$info['receipt']) {
                $info['receipt_title'] = '';
            }else{
                $receipt_tit_array = ['1'=>'个人','2'=>'公司'];
                $receipt_tit =$receipt_tit_array[$info['receipt_tit']];
                $info['receipt_title'] = '【'.$receipt_tit.'】'.$info['receipt_title'];
            }

            $postmode = M('shop_express')->order('order ASC')->getField('name,name AS alias');
            $builder  = new FormBuilder();
            $builder->setMetaTitle('更新') // 设置页面标题
            ->setPostUrl(U('', ['id' => $id]))
                ->addFormItem('ordernum', 'static', '订单号','')
                ->addFormItem('goodsinfo', 'static', '商品信息','')
                ->addFormItem('price_text', 'static', '价格','')
                ->addFormItem('integral', 'static', '使用积分','')
                ->addFormItem('pay_type_name', 'static', '支付方式','');
            if(!empty($info['receipt_title'])){
                $builder->addFormItem('receipt_title', 'static', '发票抬头','');
                // ->addFormItem('receipt_address', 'static', '发票邮递地址','');
            }
            if(!empty($info['remark'])){
                $builder->addFormItem('remark', 'static', '订单备注','');
                // ->addFormItem('receipt_address', 'static', '发票邮递地址','');
            }
            $builder->addFormItem('address_realname', 'text', '收货人','')
                ->addFormItem('address_phone', 'text', '联系方式','')
                ->addFormItem('address_detail', 'text', '详细地址','')
                ->addFormItem('type', 'hidden', '状态','');
            $builder
                ->addFormItem('checkinfo', 'select', '订单状态', '', $model_object->checkinfos)
                ->addFormItem('postmode', 'select', '快递公司', '', $postmode)
                ->addFormItem('postid', 'text', '快递单号','')
                ->addFormItem('posttime', 'datetime', '送货时间','');
            $builder
                ->addFormItem('id', 'hidden', 'ID', '')
                ->addFormItem('uid','hidden','uid','')
                ->addFormItem('ordernum','hidden','ordernum','')
                ->setFormData($info)
                ->display();
        }
    }
    // 退款列表
    public  function  refund_order(){
        $map =array(
            'checkinfo'=>5
        );
        list($data_list,$page,$model_obeject) =$this->lists('goodsorder',$map,'id ASC');
        foreach ($data_list as $key => &$record) {
            $record['goodsinfo'] = get_goods_info($record['id'],1);
        }
        $builder = new ListBuilder();
        $builder->setMetaTitle('退货列表') // 设置页面标题
        ->addTableColumn('id', 'id')
            ->addTableColumn('ordernum', "订单号")
            ->addTableColumn('create_time', "下单日期")
            ->addTableColumn('nickname', "申请人")
            ->addTableColumn('goodsinfo', '商品信息')
            ->addTableColumn('price_text', '订单金额')
            ->addTableColumn("right_button", "操作管理", "btn")
            ->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('self', array('title'=>'审核','class'=>'label label-primary-outline label-pill','href'=>U('refund_order_edit',['id' => '__data_id__'])))// 添加审核按钮
            ->display();
    }

    // 退款审核
    public  function refund_order_edit($id){
        $model_object = D('goodsorder');
        if (IS_POST) {
            if (!$data = $model_object->create()) {
                $this->error($model_object->getError());
            } else {
                $wechat = new \Home\Controller\WeixinController();
                if($data['checkinfo']==6){
                    switch($data['pay_type']){
                        case 1;
                            $refund = $wechat->applyRefund($data['ordernum'],$data['payment']);
                            break;
                        case 2:
                            $alipay =new \Home\Controller\AliPayController();
                            $refund =$alipay->alipay_refund($data['ordernum']);
                            $this->success('',$refund);
                            break;
                    }

                    if($refund['status']){
                        $order =M('shop_order')->where(['ordernum'=>$data['ordernum']])->find();
                        /*if(!empty($order['coupon'])){
                            $memberClass =  new \Common\Util\Member();
                            $memberClass->set_user_coupons_status(explode(',',  $order['coupon']), 1);
                        }*/
                      /*  if(!empty($order['coin'])){
                            $memberClass =  new \Common\Util\Member();
                            $memberClass->set_coin($order['uid'],$order['coin'],'add');
                            $memberClass->coin_log($order['uid'],'退款成功金币返回',$order['coin']);
                        }*/
                      /*  $msg =array(
                            "first" =>"您申请的退款已通过",
                            "keyword1" =>$data['ordernum'],
                            "keyword2" =>$data['payment'],
                        );
                        $wechat->send_template($openid,'HZgSWF-3CeyXh1VcpCkB7_NVOhyxKW6em1sOwYSxBGI',$msg);*/
                    }else{
                        $this->error($refund['msg']);
                    }
                }else{

                }
                if (false !== $model_object->where(['id' => $id])->save($data)) {
                    switch($data['checkinfo']){
                        case 6:
                            $this->success('退款成功', U('refund_order'));
                            break;
                        case 7:
                            $this->success('拒绝退货成功', U('refund_order'));
                    }
                } else {
                    trace($model_object->getError());
                    $this->error('退款失败');
                }
            }
        } else {
            $info           = $model_object->find($id);
            if(empty($info)){
                $this->error('该订单不存在');
            }
            if($info['checkinfo']!=5){
                $this->error('该订单不存在');
            }
            $info['price_text'] = "总价：{$info['total']}元/支付价：{$info['payment']}元";
            $info['goodsinfo']  = nl2br(get_goods_info($id));
            if (!$info['receipt']) {
                $info['receipt_title'] = '无';
            }
            $builder  = new FormBuilder();
            $builder->setMetaTitle('更新') // 设置页面标题
            ->setPostUrl(U('', ['id' => $id]))
                ->addFormItem('ordernum', 'static', '订单号','')
                ->addFormItem('goodsinfo', 'static', '商品信息','')
                ->addFormItem('price_text', 'static', '价格','')
                ->addFormItem('pay_type_name', 'static', '支付方式','')
                ->addFormItem('checkinfo', 'radio', '审核状态', '', ['6'=>'通过','7'=>'拒绝退货']);
            $builder
                ->addFormItem('id', 'hidden', 'ID', '')
                ->addFormItem('uid','hidden','uid','')
                ->addFormItem('transaction_id','hidden','transaction_id','')
                ->addFormItem('payment','hidden','payment','')
                ->addFormItem('ordernum','hidden','ordernum','')
                ->addFormItem('pay_type','hidden','pay_type','')
                ->setFormData($info)
                ->display();
        }
    }

// 导出EXCEL
    public function export()
    {
        $get = I('get.');
        $get['type'] ='normal';
        // 下单人搜索
        if ($username = I('username', '')) {
            $uid = M('admin_user')->getFieldByUsername($username, 'id');
            if ($uid) {
                $get['uid'] = $uid;
            }
            unset($get['username']);
        }

        unset($get['keyword']);
        if (isset($get['dates'])) {
            $start_date = substr($get['dates'], 0, 10);
            $end_date   = substr($get['dates'], 11, 10);
        } else {
            $start_date = date('Y-m-d', strtotime('-1 year'));
            $end_date   = date('Y-m-d');
        }
        $get['create_time'] = [
            ['egt', $start_date . ' 00:00:00'],
            ['lt', $end_date . ' 23:59:59'],
        ];
        if(isset($get['id'])){
            $get['shop_id'] =$get['id'];
            unset($get['id']);
        }
        if(isset($get['user_id'])){
            $get['recUid'] =$get['user_id'];
            $get['checkinfo'] =array('in','2,3,4');
            unset($get['user_id']);
        }
        $get['status'] = 1;
        unset($get['dates']);
        if (empty($get)) {
            $get = array('status' => 1);
        }
        unset($get['p']);
        $xlsName = "订单EXCEL";
        $xlsCell = array(
            array('ordernum', '订单号'),
            array('nickname', '下单人'),
            array('payment', '金额'),
            array('goods', '商品信息'),
            array('create_time', '下单日期'),
            array('checkinfo', '订单状态'),
            array('address_realname', '收货人'),
            array('address_phone', '订单联系方式'),
            array('address_detail', '收货地址'),
        );
        $xlsModel = D('Goodsorder');
        $xlsData  = $xlsModel->field('id,ordernum,uid,payment,create_time,checkinfo,address_realname,address_phone,address_detail,total,privilege')->where($get)->select();
        $user_ids = array_column($xlsData, 'uid');
        if ($user_ids) {
            $users = M('admin_user')->where(['id' => ['in', $user_ids]])->getField('id,username');
        } else {
            $users = [];
        }
        foreach ($xlsData as $k => $v) {
            $xlsData[$k]['username'] =filterEmoji($v['username']);
            $xlsData[$k]['checkinfo'] = isset($xlsModel->checkinfos[$v['checkinfo']]) ? $xlsModel->checkinfos[$v['checkinfo']] : '未知状态';
            $xlsData[$k]['uid']       = isset($users[$v['uid']]) ? $users[$v['uid']] : '未知';
            $xlsData[$k]['goods']     = get_goods_info($v['id']);
        }

        $this->exportExcel($xlsName, $xlsCell, $xlsData);
    }

    public function exportExcel($expTitle, $expCellName, $expTableData)
    {
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle); //文件名称
        $fileName = date('_YmdHis'); //or $xlsTitle 文件名称可根据自己情况设定
        $cellNum  = count($expCellName);
        $dataNum  = count($expTableData);
        vendor("phpexcel.Classes.PHPExcel");
        $objPHPExcel = new \PHPExcel();
        $cellName    = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1'); //合并单元格
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 3), ' ' . $expTableData[$i][$expCellName[$j][0]]);
            }
        }
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls"); //attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    // 提现
    public  function withdraw(){
        $map=[];
        $group = I('status',1);
        $map['status'] =$group;
        $tab_list    = [
            '1'=>['title' => '待审核', 'href' => U('withdraw', ['status' => '1'])],
            '2'=>['title' => '审核通过', 'href' => U('withdraw', ['status' => '2'])],
            '3'=>['title' => '审核未通过', 'href' => U('withdraw', ['status' => '3'])],
        ];
        list($data_list, $page, $model_object) = $this->lists('UserWithdraw', $map, '`id` DESC');
        $builder = new ListBuilder();
        $builder->setMetaTitle("提现审核管理") // 设置页面标题
        ->setTabNav($tab_list,$group)
        ->addTableColumn("nickname", "姓名")
            ->addTableColumn("money", "提现金额")
            ->addTableColumn("create_time", "申请时间");
            if($group==1){
                $builder->addTableColumn("right_button", "操作管理", "btn");
            }
            $builder->setTableDataList($data_list) // 数据列表
            ->setTableDataPage($page->show()); // 数据列表分页;
              if($group==1){
                  $builder->addRightButton('self', array('title'=>'审核','class'=>'label label-primary-outline label-pill','href'=>U('withdraw_check',['id' => '__data_id__'])));// 添加删除按钮
              }
            $builder->display();
    }

    public  function  withdraw_check($id){
        $model_object =D('UserWithdraw');
        if(IS_POST){
            $data = $model_object->create(format_data());
            $withdraw =  $model_object->find($id);
            if ($data) {
                $wechat = new \Home\Controller\WeixinController();
                // 同意提现
                if($data['status'] ==2){
                    $openid =get_wx_userinfo($withdraw['uid'],'openid');
                    $apply_result = $wechat->applyWithDraw($withdraw,$openid);
                    if($apply_result['status']){
                   /*     $msg =array(
                            "first" =>"您申请的提现通过审核，现金会发到您的微信红包中",
                            "keyword1" =>$withdraw['money'],
                            "keyword2" =>date('Y-m-d',time()),
                        );
                        $wechat->send_template($openid,'ewtF6fuNaRIL57fV5zwQO4u4n4ACoPrvgKN15VIJ1aQ',$msg);*/
                    }else{
                        $this->error($apply_result['info']);
                    }
                }
                // 拒绝提现
                if($data['status'] ==3){
                    // TODO 提现失败发送失败模板消息 没有模板
                    /* $msg =array(
                         "first" =>"您申请的提现审核失败",
                         "keyword1" =>$withdraw['money'],
                         "keyword2" =>date('Y-m-d',time()),
                     );*/
                }
                $id = $model_object->where(['id' => $id])->save($data);
                if (false !== $id) {
                    $this->success("审核成功", U("withdraw"));
                } else {
                    $this->error("审核失败" . $model_object->getError());
                }
            } else {
                $this->error($model_object->getError());
            }
        }else{
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle("审核") // 设置页面标题
            ->setPostUrl(U('')) // 设置表单提交地址
            ->addFormItem('id', 'hidden', 'id', '')
                ->addFormItem('username', 'static', '姓名', '')
                ->addFormItem('money', 'static', '提现金额', '')
                ->addFormItem('status', 'radio', '状态', '', ['2' => '同意提现', '3' => '拒绝提现'])
                ->setFormData($model_object->find($id))
                ->display();
        }
    }
}