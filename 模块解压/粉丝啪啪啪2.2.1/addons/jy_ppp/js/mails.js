//聊天页 私信过滤弹窗 
var Mail=(function(){
    return {
        fType:$("#filterType").val(),
        msgShow:function(){
            //友情提示显示
            $("#mask").removeClass("hidden");
            $(".simple_info_guolv").removeClass("hidden");
        },
        msgHide:function(){
            //友情提示隐藏
            $("#mask").addClass("hidden");
            $(".simple_info_guolv").addClass("hidden");
        },
        sure:function(){
            //点击记录
            $(".simple_info_guolv").addClass("hidden");
            $(".simple_info_unaffect").hide();
            $("#mask").addClass("hidden");
        },
        noRecord:function(){
            //取消记录
            $(".simple_info_unaffect").hide();
            $("#mask").addClass("hidden");
        },
        content:function(a,b,c){
            //改变参数
            var aSpan=$(".simple_info_guolv .abolish_yellow").find('span');
            aSpan.eq(0).html(a);
            aSpan.eq(1).html(b);
            aSpan.eq(2).html(c);
        }
    }
})()

$(function(){
    //是否显示免扰设置页面提示
    $.ajax({url:'/v20/msg/filter_msg_unread_count.html',data:{},dataType:'json',type:'post',success:function(data){
           if(data>20)
           {
               $(".onekey_set").show()
           }
    }});
    //是否显示友情提示
    $.ajax({url:'/v20/msg/filter_msg_popup.html',data:{},dataType:'json',type:'post',success:function(map){
      if(map)
      {
          $("#filterType").val(map.filterType);
          Mail.fType=map.filterType;
          if(map.filterType==1)
          {
              Mail.content(map.unreadMsgCount,map.filterMsgCount,'封普通招呼' );
          }else if(map.filterType==2)
          {
              Mail.content(map.unreadMsgCount,map.filterMsgCount,'封是无头像的异性发来的。')
          }else if(map.filterType==3){
              Mail.content(map.unreadMsgCount,map.filterMsgCount,'封来自与你不同省市异性发来的。')
          }else if(map.filterType==4){
              Mail.content(map.unreadMsgCount,map.filterMsgCount,'封来信的异性年龄不符合你的征友要求。')
          }else if(map.filterType==5){
              Mail.content(map.unreadMsgCount,map.filterMsgCount,'封来信的异性未验证身份。')
          }
          Mail.msgShow();
      }
    }});
    $("body").on("tap",".btn1",function(){
        //暂不使用
        Mail.msgHide();
    }).on("tap",".btn2",function(){
        //确定使用
        $.ajax({url:'/v20/msg/filter_msg_once.html',data:{"filterType":Mail.fType},dataType:'json',type:'post',success:function(data){
            if(data==-1)
            {
                $.tips("网络问题请稍后重试");
            }else{
                $(".simple_info_unaffect .pink").html(data);
                $(".simple_info_guolv").addClass("hidden");
                $(".simple_info_unaffect").show();
            }
        }});
    }).on("tap",".btn3",function(){
        //暂不记录
       Mail.noRecord()
    }).on("tap",".btn4",function(){
        //记录当前选项
        $.ajax({url:'/v20/msg/filter_msg_save.html',data:{"filterType":Mail.fType,fromMailBox:1},dataType:'json',type:'post',success:function(data){
            if(data==1)
            {
                Mail.sure();
                $.tips("记录成功");
            }else
            {
                $.tips("网络问题请稍后重试");
            }
        }});
    });
})