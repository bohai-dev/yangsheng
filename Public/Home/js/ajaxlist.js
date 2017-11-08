/**
 * Created by Administrator on 2016/12/1.
 */
//增加无数据提示
function addnothing(){
    $(".loadlist").after(
    '<div class="tc nothing">'+
        '<i><img src="html/images/default/icon_none-order.png" alt="" class="imgm"></i>'+
        '<span class="mt10 fs14 col9">抱歉，没有找到<em class="keyword"></em></span>'+
    '</div>'+
    '<div class="mtb10 tc loading" style="display: none"><img src="html/images/loading2.gif" class="imgm" alt="" /></div>'
    );
}

//判断页面是否到达底部
function checkload(ajaxdata) {
    if ($(window).scrollTop() + $(window).height() + 50 >= $(document).height()) {
        LoadList(ajaxurl, ajaxdata);
    }
}

//重新查询(切换Tab、筛选等)
function ajaxagain(ajaxdata){
    ispost = true;
    page = 1;
    $(".loadlist").html('');
    $(".loading").html('<img src="html/images/loading2.gif" class="imgm" alt="" /></div>');
    $(".nothing").hide();
    LoadList(ajaxurl, ajaxdata);
}

ispost = true;  //是否还有数据
temp = true;    //ajax是否执行完毕
page = 1;       //页数
keyword = '';
//AJAX加载
function LoadList(ajaxurl, ajaxdata) {
    var url  = ajaxurl  || "{:U('Home/Index/index')}";
    ajaxdata['page'] = page;
    if(isEmptyObject(ajaxdata)){
        $.alert('传入的数据为空');
        return false;
    }
    if (ispost && temp) {
        //$.showLoading();          //加载标志1
        $(".loading").show();   //加载标志2
        $(".loadclick").hide(); //点击加载按钮
        temp = false;
        $.ajax({
            url: url,
            type: 'POST',
            data: ajaxdata,
            dataType: 'json',
            timeout: 9999,
            success: function(result) {
                if (result.msg == '') {
                    ispost = false;
                    if(page == 1){
                        $(".nothing").show();
                        $(".loading").html("").css({"background":"none"});
                    }else{
                        $(".loading").html("<div class='mtb10 col9 fs12'>已显示所有<em class='keyword'></em>！</div>").css({"background":"none"});
                    }
                    var nothingtext=(result.nothingtext!=undefined && result.nothingtext!='')?result.nothingtext:'相关信息';
                    goodsnone(nothingtext);
                }else{
                    $(".loading").hide();
                    $(".loadlist").append(result.msg);
                    $(".loadclick").show();
                    imglazyload();
                    imgHeight();
                    coupon_height();
                }
                page++;
            },
            error: function() {
                $(".loading").hide();
            }
        }).always(function(){
            //$.hideLoading();
            temp = true;
        });
    }
}

//判断对象是否为空
function isEmptyObject(obj){
    var res;
    for(res in obj){
        return false;
    }
    return true;
}

//修改无商品提示
function goodsnone(nothingtext){
    var keywordtext = keyword == '' ? '' : '与“<em class="colred">'+keyword+'</em>”相关的';
    $(".keyword").html(keywordtext+nothingtext);
}