$(function() {
    'use strict';

    var exRedpack = {
        init: function() {
            $('.btn_exchange_redpack').click(function () {
                var url = $(this).attr('data-url');
                var token = $(this).attr('data-token');
                var redpack_amount = $(this).attr('data-redpack-amount');
                var buttons1 = [
                    {
                        text: '请确认',
                        label: true
                    },
                    {
                        text: '兑换 '+redpack_amount+'元 红包',
                        onClick: function() {
                            $.showIndicator();
                            $.ajax({
                                type: 'post',
                                url: url,
                                data: 'token='+token,
                                dataType: 'json',
                                success: function(resp) {
                                    var url = '';
                                    try {
                                        url = resp.data.url;
                                    } catch(e) {}
                                    if (resp.errno == 0) {
                                        $.router.loadPage(url);
                                    } else if (resp.errno == 4) {
                                        if (resp.errmsg) {
                                            $.toast(resp.errmsg);
                                        }
                                        setTimeout(function(){
                                            $.showIndicator();
                                            window.location.href = window.sysinfo.loginurl;
                                        }, 2000);
                                    } else {
                                        $.hideIndicator();
                                        $.toast(resp.errmsg);
                                        if (url != '') {
                                            setTimeout(function(){
                                                $.router.loadPage(url);
                                            }, 2000);
                                        }
                                    }
                                }
                            });
                        }
                    }
                ];
                var buttons2 = [
                    {
                        text: '取消',
                        bg: 'danger'
                    }
                ];
                var groups = [buttons1, buttons2];
                $.actions(groups);
            });
        }
    };

    var wxMenu = {
        show: function() {
            wx.ready(function(){
                if (window.sysinfo.weixin_menu == '1') {
                    wx.showOptionMenu();
                    if (window.sysinfo._local_development == '1') {
                        $.toast('show menu');
                    }
                } else {
                    wx.hideOptionMenu();
                    if (window.sysinfo._local_development == '1') {
                        $.toast('hide menu');
                    }
                }
            });
        },
        hide: function() {
            wx.ready(function(){
                wx.hideOptionMenu();
                if (window.sysinfo._local_development == '1') {
                    $.toast('hide menu');
                }
            });
        }
    };

    var Timer = {
        timerId: null,
        run: function(endtime) {
            if (!endtime) return;
            var ts = endtime - (new Date());//毫秒
            var dd = parseInt(ts / 1000 / 60 / 60 / 24, 10);//天
            var hh = parseInt(ts / 1000 / 60 / 60 % 24, 10);//小时
            var mm = parseInt(ts / 1000 / 60 % 60, 10);//分钟
            var ss = parseInt(ts / 1000 % 60, 10);//秒
            dd = this.format(dd);
            hh = this.format(hh);
            mm = this.format(mm);
            ss = this.format(ss);
            var txt = dd + '天' + hh + '时' + mm + '分' + ss + '秒';
            if (arguments[1] != '') {   //id
                $('#'+id).html(txt);
            } else if (arguments[2] == 'object') {  //返回对象类型
                return {
                    day: dd,
                    hour: dd,
                    minute: dd,
                    second: dd
                };
            } else {    //默认返回字符串
                return txt;
            }
            this.timerId = setInterval(function(){
                this.run(endtime);
            }, 1000);
        },
        format: function(t) {
            return t<10?'0'+t:t;
        },
        clear: function() {
            if (this.timerId) {
                clearInterval(this.timerId);
            }
        }
    };

    //home
    $(document).on('pageInit', ".superpage_home", function(e, id, page) {
        wxMenu.show();
        exRedpack.init();
    });

    //list
    $(document).on('pageInit', ".superpage_list", function(e, id, page) {
        wxMenu.show();
        $('.btn_share').click(function () {
            $.showIndicator();
        });
        function addItems(data, params) {
            var html = '', item, item_url;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                item_url = params.item_url+'&id='+item['id'];
                html += '<div class="col-50 item_wrap">'+
                '<a href="'+item_url+'">'+
                '<div class="item_img">'+
                '    <img src="'+item['cover']+'" onerror="this.src=\''+params.img_placeholder+'\'"/>'+
                '</div>'+
                '<div class="text-overflow font7">'+item['title']+'</div>'+
                '<div class="item_info clearfix font6">';
                if (item['type'] == 1 || item['type'] == 7) {
                    html += '<span class="pull-left">'+item['sales']+'人已购买</span>';
                } else if (item['type'] == 2) {
                    html += '<span class="pull-left">'+item['joined_total']+'人已参与</span>';
                }
                html += '<span class="pull-right">';
                if (item['price'] > 0) {
                    html += '<span class="credit_color">'+item['credit']+'</span>'+item['credit_name']+'+<span class="credit_color">'+item['price']+'</span>元';
                } else {
                    html += '<span class="credit_color">'+item['credit']+'</span>'+item['credit_name'];
                }
                html += '</span></div></a></div>';
            }
            $('.product_wrap .row').append(html);
        }
        var loading = false;
        var maxPage = 100;
        $(page).on('infinite', '.infinite-scroll',function() {
			if (loading) return;
            loading = true;
            var t = this;
            var url = $(t).attr('data-url');
            var item_url = $(t).attr('data-item-url');
            var img_placeholder = $(t).attr('data-img-placeholder');
            var pageno = $(t).attr('data-page');
            pageno = parseInt(pageno) + 1;
            url += '&page='+pageno;

            $.ajax({
                //http://veking_wx.localhost/app/index.php?i=6&c=entry&type=1&do=list&m=superman_creditmall&page=2
                url: url,
                dataType: 'json',
                success: function(response) {
                    loading = false;
                    if (response.length > 0) {
                        var params = {
                            item_url: item_url,
                            img_placeholder: img_placeholder
                        };
                        addItems(response, params);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll'));
                        $('.infinite-scroll-preloader').remove();
                    }
                }
            });
        });

        $('.type_link').click(function(){
            $('.type_link').each(function(){
                $(this).removeClass('button-fill');
            });
            var product_type = $(this).attr('data-type');
            $('.get-type').attr('data-type',product_type);
            $(this).addClass('button-fill');
            //$.router.loadPage($(t).attr('data-url'));
        });

        $('.sort-link').click(function(){
            $('.sort-link').each(function(){
                $(this).removeClass('button-fill');
            });
            $(this).addClass('button-fill');
            var type = $('.get-type').attr('data-type');
            var url = $(this).attr('data-url');
            url += '&type='+type;
            $.router.loadPage(url);
        });
    });

    //list-share
    $(document).on('pageInit', ".superpage_list_share", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        function addItems(data, params) {
            var html = '', item;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                var item_url = params.item_url+'&id='+item['id'];
                html += '<li>';
                html += '<div class="item-content">';
                html += '<div class="item-media">';
                html += '<img src="'+item['cover']+'"  onerror="this.src=\''+params.img_placeholder+'\'" style=\'width: 2.2rem;\'>';
                html += '</div>';
                html += '<div class="item-inner">';
                html += '<div class="item-text font7">'+item['title']+'</div>';
                html += '<div class="item-title-row">';
                html += '<div class="item-title font6">分享 <span class="text-strong credit_color">+'+item['share_credit']+'</span> '+item['share_credit_title']+'</div>';
                html += '<div class="item-title">';
                html += '<a href="'+item_url+'" class="button button-dark font6">点击分享</a>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</li>';
            }
            $('.share_list ul').append(html);
        }
        var loading = false;
        var maxPage = 100;
        $(page).on('infinite', '.infinite-scroll',function() {
            if (loading) return;
            loading = true;
            var t = this;
            var url = $(t).attr('data-url');
            var item_url = $(t).attr('data-item-url');
            var img_placeholder = $(t).attr('data-img-placeholder');
            var pageno = $(t).attr('data-page');
            pageno = parseInt(pageno) + 1;
            url += '&page=' + pageno;

            $.ajax({
                url: url,
                dataType: 'json',
                success: function (response) {
                    loading = false;
                    if (response.length > 0) {
                        var params = {
                            item_url: item_url,
                            img_placeholder: img_placeholder
                        };
                        addItems(response, params);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll'));
                        $('.infinite-scroll-preloader').remove();
                    }
                }
            });
        });
    });

    //list-redpack
    $(document).on('pageInit', ".superpage_list_redpack", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        exRedpack.init();
        //常见问题
        $('.open_help').click(function () {
            $.popup('.popup_help');
        });
    });

    //redpack-detail
    $(document).on('pageInit', ".superpage_detail_redpack", function(e, id, page) {
        wxMenu.show();
    });

    //detail
    $(document).on('pageInit', ".superpage_detail", function(e, id, page) {
        var thispage = this;
        wxMenu.show();
        //浏览数
        $.ajax({
            url: $(thispage).attr('data-view-url'),
            success: function(response){}
        });

        //分享数
        sharedata.success = function(){
            $.ajax({
                url: $(thispage).attr('data-share-url'),
                success: function(response){}
            });
        };

        $('.btn_exchange').click(function(){
            var t = this;
            if ($(t).attr('data-flag') == '1') {
                return;
            }
            $(t).addClass('disabled').attr('data-flag', '1');
            $.showIndicator();
            var url = $(t).attr('data-url');
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(resp) {
                    $.hideIndicator();
                    $(t).removeClass('disabled').attr('data-flag', '0');
                    if (resp.errno == 0) {
                        url = url.replace('&check=yes', '');
                        $.router.loadPage(url);
                    } else if (resp.errno == 4) {
                        if (resp.errmsg) {
                            $.toast(resp.errmsg);
                        }
                        setTimeout(function(){
                            $.showIndicator();
                            window.location.href = window.sysinfo.loginurl;
                        }, 2000);
                    } else {
                        $.toast(resp.errmsg);
                        if (resp.data.url) {
                            setTimeout(function(){
                                $.router.loadPage(resp.data.url);
                            }, 2000);
                        }
                    }
                }
            });
            return true;
        });

        $('.tab-link').click(function(){
            var tab = $(this).attr('data-tabs');
            tab = 'tab'+tab;
            $('.content').attr('data-tabs',tab);
        });
        function addItems(data, params) {
            var html = '', item;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                html += '<li><a href="#" class="item-link list-button">'+
                        '<div class="row no-gutter">'+
                        '<div class="col-40 clearfix text-overflow">'+
                        '<div class="row no-gutter">'+
                        '<div class="pull-left avatar_wrap">'+
                        '<img class="avatar" src="'+item['avatar']+
                        '" onerror="this.src=\'../app/resource/images/heading.jpg\'" alt=""/>'+ '</div>'+
                        '<div class="pull-left font7 nickname text-overflow col-60">'+item['nickname']+'</div>' +
                        '</div></div>'+
                        '<div class="col-20 font6">'+item['credit']+item['credit_title']+'</div>'+
                        '<div class="col-40 font6">'+item['dateline']+'</div>' +
                        '</div></a></li>';
            }
            $('.exchange_wrap ul').append(html);
        }
        var loading = false;
        var maxPage = 100;
        $(page).on('infinite', '.infinite-scroll',function() {
            if (loading) return;
            loading = true;
            var t = this;
            var url = $(t).attr('data-url');
            var pageno = $(t).attr('data-page');
            var tabs = $(t).attr('data-tabs');
            if (tabs=='tab1') {
                return;
            }
            pageno = parseInt(pageno) + 1;
            url += '&page='+pageno;
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    loading = false;
                    if (response.length > 0) {
                        addItems(response);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll'));
                        $('.infinite-scroll-preloader').remove();
                    }
                }
            });
        });
    });

    //confirm order
    $(document).on('pageInit', ".superpage_confirm", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        //提交订单按钮
        $('#btn_submit_order').click(function(){
            $.showIndicator();
            var t = this;
            $(t).addClass('disabled').attr('disabled', true);
            var url = window.location.href;
            var id = $('input[name=id]', page).val();
            var token = $('input[name=token]', page).val();
            var total = $('input[name=total]', page).val();
            var dispatch_id = $('input[name=dispatch_id]', page).val();
            var address_id = $('input[name=address_id]', page).val();
            var remark = $('textarea[name=remark]', page).val();
            var need_address = $('#need_address').val();
            var prodcut_credit = $('#prodcut_credit').val();
            var mycredit = $('#mycredit').val();
            var credit = total * prodcut_credit;
            var data = '';
            if (dispatch_id == '') {
                $.hideIndicator();
                $.toast('请选择配送方式');
                $(t).removeClass('disabled').removeAttr('disabled');
                return false;
            }
            if (need_address != '0' && address_id == '') {
                $.hideIndicator();
                $.toast('请添加收货地址');
                $(t).removeClass('disabled').removeAttr('disabled');
                return false;
            }
            if (credit <= 0) {
                $.hideIndicator();
                $.toast('非法参数');
                $(t).removeClass('disabled').removeAttr('disabled');
                return false;
            }
            if (mycredit < credit) {
                $.hideIndicator();
                $.toast('您的积分不足');
                $(t).removeClass('disabled').removeAttr('disabled');
                return false;
            }
            data += 'id='+id;
            data += '&token='+token;
            data += '&total='+total;
            data += '&dispatch_id='+dispatch_id;
            data += '&address_id='+address_id;
            data += '&remark='+remark;
            data += '&submit=yes';
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                dataType: 'json',
                success: function(resp){
                    $.hideIndicator();
                    $(t).removeClass('disabled').removeAttr('disabled');
                    if (resp.errno == 0) {
                        if (resp.data.url) {
                            $.router.loadPage(resp.data.url);
                        }
                    } else if (resp.errno == 4) {
                        if (resp.errmsg) {
                            $.toast(resp.errmsg);
                        }
                        setTimeout(function(){
                            $.showIndicator();
                            window.location.href = window.sysinfo.loginurl;
                        }, 2000);
                    } else {
                        $.toast(resp.errmsg);
                        if (resp.data.url) {
                            setTimeout(function(){
                                $.router.loadPage(resp.data.url);
                            }, 2000);
                        }
                    }
                }
            });
        });

        //配送方式
        var dispatch_btn = $('.dispatch_wrap a.list-button');
        dispatch_btn.click(function(){
            dispatch_btn.each(function(){
                $(this).removeClass('dispatch_active').next().hide();
                $('.icon', this).remove();
            });
            var txt = $(this).html();
            var dispatch_id = $(this).attr('data-dispatch-id');
            var need_address = $(this).attr('data-need-address');
            $(this).addClass('dispatch_active').html(txt+'<span class="icon icon-check pull-right"></span>').next().show();
            $('#dispatch_id').val(dispatch_id);
            $('#need_address').val(need_address);
            if (need_address != '0') {
                var address_id = $(this).attr('data-address-id');
                $('#address_id').val(address_id);
            }
        });

        $('#product_total').click(function(){
            return false;
        });

        //加数量
        var product_total = $('#product_total');
        $('#btn_plus').click(function(){
            var t = this;
            var total = parseInt(product_total.html());
            var max_total = parseInt($(this).attr('data-max-total'));
            if (total > 0) {
                total += 1;
                if (total > max_total) {
                    $.toast($(t).attr('data-total-title'));
                    return;
                }
                var order_buy_num = $(this).attr('data-order-buy-num');
                if (order_buy_num > 0 && total > order_buy_num) {
                    $.toast($(t).attr('data-over-title'));
                    return;
                }
                product_total.html(total);
                $('#total').val(total);
            }
        });

        //减数量
        $('#btn_minus').click(function(){
            var t = this;
            var total = parseInt(product_total.html());
            var min_total = $(this).attr('data-min-total');
            if (total > 0) {
                total -= 1;
                if (total < min_total) {
                    $.toast($(t).attr('data-title'));
                    return;
                } else {
                    product_total.html(total);
                }
                $('#total').val(total);
            }
        });
    });

    //pay
    $(document).on('pageInit', ".superpage_pay", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        //选择支付方式
        $('.pay_type').click(function(){
            if (!$(this).hasClass('disabled')) {
                $('.pay_type').each(function(){
                    $(this).addClass('button-dark');
                    $('.icon', this).remove();
                });
                var txt = $(this).html();
                $(this).html('<span class="icon icon-check"></span> '+txt).removeClass('button-dark');
                var pay_type = $(this).attr('data-pay-type');
                $('#pay_type').val(pay_type);
            }
        });

        //微信支付按钮初始化
        wx.ready(function(){
            wx.checkJsApi({
                jsApiList: ['chooseWXPay'],
                success: function(res) {
                    if (res.checkResult.chooseWXPay) {
                        var price = $('.btn_wechat_pay').attr('data-price');
                        $('.btn_wechat_pay').removeClass('disabled').html('微信支付 '+price+'元');
                    }
                }
            });
        });

        //确认支付按钮
        $('#btn_submit_pay').click(function(){
            $.showIndicator();
            var t = this;
            $(t).addClass('disabled').attr('disabled', true);
            var url = window.location.href;
            var id = $('input[name=id]').val();
            var token = $('input[name=token]').val();
            var pay_type = $('input[name=pay_type]').val();
            var choose_paytype = $('form').attr('data-choose-paytype');
            var data = '';
            if (choose_paytype == '1' && pay_type == '') {
                $.hideIndicator();
                $.toast('请选择支付方式');
                $(t).removeClass('disabled').removeAttr('disabled');
                return false;
            }
            data += 'id='+id;
            data += '&token='+token;
            data += '&pay_type='+pay_type;
            data += '&submit=yes';
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                dataType: 'json',
                success: function(resp){
                    $.hideIndicator();
                    if (resp.errno == 0) {
                        $.toast(resp.errmsg);
                        setTimeout(function(){
                            if (resp.data.url) {
                                $.router.loadPage(resp.data.url);
                            } else if (resp.data.redirect_url) {
                                window.location.href = resp.data.redirect_url;
                            }
                        }, 2000);
                    } else {
                        $(t).removeClass('disabled').removeAttr('disabled');
                        $.toast(resp.errmsg);
                        if (resp.data.url) {
                            setTimeout(function(){
                                $.router.loadPage(resp.data.url);
                            }, 2000);
                        }
                    }
                }
            });
        });
    });

    //address
    $(document).on('pageInit', ".superpage_address", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        //删除地址
        $('.delete_address').click(function () {
            var t = this;
            var buttons1 = [
                {
                    text: '确认删除该地址？',
                    label: true
                },
                {
                    text: '确认',
                    bold: true,
                    color: 'danger',
                    onClick: function() {
                        $.router.loadPage($(t).attr('data-url'));
                    }
                }
            ];
            var buttons2 = [
                {
                    text: '取消',
                    bg: 'danger'
                }
            ];
            var groups = [buttons1, buttons2];
            $.actions(groups);
        });

        //加载地区
        $("#city-picker").cityPicker({
            toolbarTemplate: '<header class="bar bar-nav"><button class="button button-link pull-right close-picker">确定</button><h1 class="title">选择地区</h1></header>'
        });

        //表单页
        if ($('form', thispage).length > 0) {
            $('form', thispage).submit(function(){
                var username = $('#username').val();
                if (username == '') {
                    $.toast('请输入您的姓名');
                    return false;
                }

                var mobile = $('#mobile').val();
                if (!mobile) {
                    $.toast('请输入您的手机号');
                    return false;
                }
                if (mobile.search(/^([0-9]{11})?$/) == -1) {
                    $.toast('请输入正确的手机号码');
                    return false;
                }

                var city = $('#city-picker').val();
                if (!city) {
                    $.toast('请选择地区');
                    return false;
                }

                var address = $('#address').val();
                if (address == '') {
                    $.toast('请输入详细地址');
                    return false;
                }
                return true;
            });

            //共享微信收货地址
            var wxVersion = 5.0;
            if (wxVersion >= 5.0) {
                try {
                    WeixinJSBridge.invoke(
                        'editAddress',
                        {
                            'appId': '',
                            'timeStamp': '',
                            'nonceStr': '',
                            'signType': 'SHA1',
                            'addrSign': '',
                            'scope': 'jsapi_address'
                        },
                        function (res) {
                            if (res.err_msg == 'edit_address:ok') {
                                $.alert(res);
                                //document.getElementById("showAddress").innerHTML="收件人："+res.userName+"  联系电话："+res.telNumber+"  收货地址："+res.proviceFirstStageName+res.addressCitySecondStageName+res.addressCountiesThirdStageName+res.addressDetailInfo+"  邮编："+res.addressPostalCode;
                            } else {
                                $.alert("获取地址失败，请刷新重试");
                            }
                        }
                    );
                } catch(e) {
                    console.log('not in wechat or version');
                }
            } else {
                if (window.sysinfo._debug) {
                    $.toast('wechat version('+wxVersion+') not supported');
                }
            }
        }
    });

    //order
    $(document).on('pageInit', ".superpage_order", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        function check_order_pay(t) {
            if ($(t).attr('data-flag') == 1) {
                return;
            }
            $.showIndicator();
            $(t).addClass('disabled');
            var url = $(t).attr('data-url');
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(resp){
                    $.hideIndicator();
                    if (resp.errno == 0) {
                        url = url.replace('&check=yes', '');
                        $.router.loadPage(url);
                    } else {
                        $(t).removeClass('disabled');
                        $.toast(resp.errmsg);
                        if (resp.data.url) {
                            setTimeout(function(){
                                $.router.loadPage(resp.data.url);
                            }, 2000);
                        }
                    }
                }
            });
        }
        //订单支付
        $('.btn_order_pay').click(function(){
            check_order_pay(this);
        });
        //订单操作
        $('.btn_order_operate').click(function () {
            var t = this;
            if ($(t).attr('data-flag') == 1) {
                return;
            }
            $(t).attr('data-flag', 1).addClass('disabled');
            var buttons1 = [
                {
                    text: $(t).attr('data-title'),
                    label: true
                },
                {
                    text: '确认',
                    bold: true,
                    color: 'danger',
                    onClick: function() {
                        $.ajax({
                            url: $(t).attr('data-url'),
                            success: function(resp) {
                                if (resp.errno == 0) {
                                    $.toast(resp.errmsg);
                                    if (resp.data.url) {
                                        setTimeout(function(){
                                            $.router.loadPage(resp.data.url);
                                        }, 2000);
                                    }
                                } else {
                                    $(t).attr('data-flag', 0).addClass('disabled');
                                    $.toast(resp.errmsg);
                                }
                            }
                        });
                    }
                }
            ];
            var buttons2 = [
                {
                    text: '取消',
                    bg: 'danger',
                    onClick: function() {
                        $(t).attr('data-flag', 0);
                        $(t).removeClass('disabled');
                    }
                }
            ];
            var groups = [buttons1, buttons2];
            $.actions(groups);
            return false;
        });

        //评价按钮
        $('.btn_comment').click(function(){
            $.toast('评价功能暂未开放！');
            return false;
        });

        //我的订单页无限滚动
        function addItems(data,params) {
            var html = '', item;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                params.item_url += '&orderid='+item['id'];
                params.detail_url += '&id='+item['product']['id'];
                params.receive_url += '&orderid='+item['id'];
                params.pay_url += '&orderid='+item['id'];
                params.comment_url += '&orderid='+item['id'];
                params.list_url += '&type='+item['product']['type'];
                html += '<div class="card">';
                html += '<a href="'+params.item_url+'">';
                html += '<div class="card-header">';
                html += '<span class="font7">订单号: '+item['ordersn']+'</span>';
                html += '<span class="credit_color font7">'+item['status_title']+'</span>';
                html += '</div></a>';
                html += '<div class="card-content">';
                html += '<div class="list-block media-list">';
                html += '<ul>';
                if (item['isredpack']) {
                    html += '<a href="'+params.list_url+'">';
                } else {
                    html += '<a href="'+params.detail_url+'">';
                }
                html += '<li class="item-content">';
                html += '<div class="item-media">';
                html += '<img class="cover" src="'+item['product']['cover']+'" onerror="this.src=\''+params.img_placeholder+'\'"/>';
                var red_arr = [5, 6];
                if (item['product']['cover'].indexOf('/addons/') && red_arr.indexOf(item['product']['type']) != '-1') {
                    html += '<span>'+item['extend']['redpack_amount']+'元</span>';
                }
                html += '</div>';
                html += '<div class="item-inner text-overflow">';
                html += '<div class="item-title-row">';
                html += '<div class="item-title">'+item['product']['title']+'</div>';
                html += '</div>';
                html += '<div class="item-subtitle clearfix">';
                html += '<div class="pull-left font6 total_wrap">';
                html += 'X'+item['total']+'</div>';
                html += '<div class="pull-left">';
                html += '<button class="button disabled font5 product_type">'+item['product']['type_name']+'</button>';
                html += '</div></div></div></li></a></ul></div></div>';
                html += '<div class="card-footer clearfix">';
                html += '<div class="row no-gutter order_footer_wrap">';
                html += '<div class="col-50 font6 order_footer_left">实付:';
                if (item['price']>0) {
                    html += item['credit']+item['credit_title']+'+'+item['price']+'元';
                } else {
                    html += item['credit']+item['credit_title'];
                }
                html += '</div><div class="col-50 btn_wrap text-right">';
                html += '<a href="'+params.item_url+'" class="button button-dark button-fill">查看</a>&nbsp;';
                if (item['status'] == 0) {
                    html += '<a href="'+params.pay_url+'" class="button button-fill button-success btn_order_pay">立即支付</a>&nbsp;';
                } else if (item['status'] == 2) {
                    html += '<a href="javascript:;" data-url="'+params.receive_url+'" data-flag="0" data-title="确认已收到商品？" class="button button-fill create-actions btn_order_operate">确认收货</a>&nbsp;';
                } else if (item['status'] == 3) {
                    html += '<a href="'+params.comment_url+'" class="button button-fill btn_comment">评价赚积分</a>';
                }
                html += '</div></div></div></div>';
            }
            $('.add-order').append(html);
            $('.btn_order_pay').click(function(){
                check_order_pay(this);
            });
        }
        var loading = false;
        var maxPage = 100;
        $(page).on('infinite', '.infinite-scroll',function() {
            if (loading) return;
            loading = true;
            var t = this;
            var url = $(t).attr('data-url');
            var pageno = $(t).attr('data-page');
            var item_url = $(t).attr('data-item-url');
            var detail_url = $(t).attr('data-detail-url');
            var img_placeholder = $(t).attr('data-img-placeholder');
            var receive_url = $(t).attr('data-receive-url');
            var pay_url = $(t).attr('data-pay-url');
            var comment_url = $(t).attr('data-comment-url');
            var list_url = $(t).attr('data-list-url');
            pageno = parseInt(pageno) + 1;
            url += '&page='+pageno;
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    loading = false;
                    if (response.length > 0) {
                        var params = {
                            item_url: item_url,
                            detail_url: detail_url,
                            img_placeholder: img_placeholder,
                            receive_url: receive_url,
                            pay_url: pay_url,
                            comment_url: comment_url,
                            list_url: list_url
                        };
                        addItems(response,params);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll'));
                        $('.infinite-scroll-preloader').remove();
                    }
                }
            });
        });
    });

    //profile
    $(document).on('pageInit', ".superpage_profile", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        $('.recommend_wrap img').click(function(){
            $.alert($('.swiper-slide').width());
        });
		var localIds;
        $('.myavatar_wrap').click(function () {
            var t = this;
			if (window.sysinfo.container == 'wechat') {
				wx.chooseImage({
					count: 1, // 默认9
					sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
					sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
					success: function (res) {
						localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
						$('img', t).attr('src', localIds);
					}
				});
			} else {
				$.toast('请在微信中上传头像');
			}
        });
		$('.myavatar_wrap img').click(function(event){
			$.popup('.popup_big_avatar');
			event.stopPropagation();
		});
		$('.popup_big_avatar').click(function(){
			$.closeModal('.popup_big_avatar');		
		});
		var saveMemberInfo = function() {
            var serverId = $('#serverId').val();
            var mobile = $('#mobile').val();
            var nickname = $('#nickname').val();
            var email = $('#email').val();
            var token = $('input[name=token]').val();
            var url = window.location.href;
			$.ajax({
				type: 'post',
				data: 'serverId='+serverId+'&mobile='+mobile+'&email='+email+'&nickname='+nickname+'&token='+token+'&submit=yes',
				dataType: 'json',
				url: url,
				success: function(resp) {
					$.hideIndicator();
					$('input[name=submit]').removeClass('disabled');
					if (resp.errno == 0) {
                        $.toast('保存成功，跳转中...');
                        if (resp.data.url) {
                            setTimeout(function(){
                                $.router.loadPage(resp.data.url);
                            }, 2000);
                        }
					} else {
						$.toast(resp.errmsg);
					}
				}
			});
		};
        $('input[name=submit]', thispage).click(function(){
            $.showIndicator();
            var t = this;
            $(t).addClass('disabled');
            var mobile = $('#mobile').val();
            var email = $('#email').val();
            if (mobile != '') {
                if (!/^1\d{10}$/.test(mobile)) {
                    $.hideIndicator();
                    $.toast('请输入合法的手机号');
                    $(t).removeClass('disabled');
                    return false;
                }
            }
            if (email != '') {
                if (!/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/.test(email)) {
                    $.hideIndicator();
                    $.toast('请输入合法邮箱');
                    $(t).removeClass('disabled');
                    return false;
                }
            }
			try {	
				if (localIds.length > 0) {
					wx.uploadImage({
						localId: localIds[0], // 需要上传的图片的本地ID，由chooseImage接口获得
						isShowProgressTips: 0, // 默认为1，显示进度提示
						success: function (res) {
							var serverId = res.serverId; // 返回图片的服务器端ID
							$('#serverId').val(serverId);
							saveMemberInfo();
						},
						fail: function (res) {
							$.alert(JSON.stringify(res));
						}
					});
				} else {
					saveMemberInfo();
				}
			} catch (e){
				saveMemberInfo();
			}
        });

        //积分明细页无限滚动
        function addItems(data,credit_title) {
            var html = '', item;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                html += '<li>'+
                '<div class="item-content"><div class="item-inner">'+
                '<div class="item-title-row">'+
                '<div class="font7">'+item['remark']+'</div>'+
                '<div class="credit_time font6">'+item['createtime']+'</div></div>'+
                '<div class="credit_num credit_color text-strong">'+item['num']+'<span class="font6">'+credit_title+'</span></div>'+
                '</div></div></li>';
            }
            $('.log-list ul').append(html);
        }
        var loading = false;
        var maxPage = 100;
        $(page).on('infinite', '.infinite-scroll',function() {
			if (loading) return;
            loading = true;            
			var t = this;
            var url = $(t).attr('data-url');
            var pageno = $(t).attr('data-page');

            pageno = parseInt(pageno) + 1;
            url += '&page='+pageno;

            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    loading = false;
                    if (response.length > 0) {
                        var credit_title = $(t).attr('data-credit-type');
                        addItems(response,credit_title);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll'));
                        $('.infinite-scroll-preloader').remove();
                    }
                }
            });
        });
    });

    //mycredit
    $(document).on('pageInit', ".superpage_mycredit", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
        //积分明细页无限滚动
        function addItems(data,credit_title) {
            var html = '', item;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                html += '<li>'+
                '<div class="item-content">'+
                '<div class="item-inner">'+
                '<div class="row no-gutter creditlog_wrap">'+
                '<div class="col-75">'+
                '<div class="item-title-row">'+
                '<div class="font7 text-overflow">'+item['remark']+'</div>'+
                '<div class="credit_time font6">'+item['createtime']+'</div>'+
                '</div></div>'+
                '<div class="col-25 text-overflow text-right">'+
                '<div class="credit_num credit_color text-strong">'+
                item['num']+'<span class="font6">'+credit_title+'</span>'+
                '</div></div></div></div></div></li>'
            }
            $('.log-list ul').append(html);
        }
        var loading = false;
        var maxPage = 100;
        $(page).on('infinite', '.infinite-scroll',function() {
            if (loading) return;
            loading = true;
            var t = this;
            var url = $(t).attr('data-url');
            var pageno = $(t).attr('data-page');

            pageno = parseInt(pageno) + 1;
            url += '&page='+pageno;

            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    loading = false;
                    if (response.length > 0) {
                        var credit_title = $(t).attr('data-credit-type');
                        addItems(response,credit_title);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll'));
                        $('.infinite-scroll-preloader').remove();
                    }
                }
            });
        });
    });

    //creditrank
    $(document).on('pageInit', ".superpage_creditrank", function(e, id, page) {
        var thispage = this;
        wxMenu.show();
        if (typeof _creditrank_type_button != undefined) {
            $('.creditrank_type').click(function () {
                $.actions(_creditrank_type_button);
                return false;
            });
        }
    });

    //cart
    $(document).on('pageInit', ".superpage_cart", function(e, id, page) {
        var thispage = this;
        wxMenu.hide();
    });

    //exchangerank
    $(document).on('pageInit', ".superpage_exchangerank", function(e, id, page) {
        var thispage = this;
        wxMenu.show();
    });

    //exchangelog
    $(document).on('pageInit', ".superpage_exchangelog", function(e, id, page) {
        var thispage = this;
        wxMenu.show();
        function addItems(data) {
            var html = '', item;
            for (var i=0; i<data.length; i++) {
                item = data[i];
                html += '<li>';
                html += '<div class="item-content">';
                html += '<div class="item-media">';
                html += '<img src="'+item['avatar']+'" onerror="this.src=\'../app/resource/images/heading.jpg\'" style=\'width: 1.8rem;\'>';
                html += '</div>';
                html += '<div class="item-inner">';
                html += '<div class="row">';
                html +=     '<div class="col-70">';
                html +=         '<div class="item-title-row">';
                html +=             '<div class="item-title font7">'+item['nickname']+'</div>';
                html +=         '</div>';
                html +=         '<div class="item-subtitle font5">'+item['dateline']+'</div>';
                html +=     '</div>';
                html +=     '<div class="col-30 pull-right">';
                html +=         '<a href="#">'+item['credit']+item['credit_title']+'</a>';
                html +=     '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</li>';
            }
            $('.exchangelog_wrap ul').append(html);
        }
        var loading = false;
        var maxPage = 100;
        $(page).on('infinite', '.infinite-scroll',function() {
            if (loading) return;
            loading = true;
            var t = this;
            var url = $(t).attr('data-url');
            var pageno = $(t).attr('data-page');

            pageno = parseInt(pageno) + 1;
            url += '&page=' + pageno;
            $.ajax({
                url: url,
                dataType: 'json',
                success: function (response) {
                    loading = false;
                    if (response.length > 0) {
                        addItems(response);
                        $(t).attr('data-page', pageno);
                        $.refreshScroller();
                    } else {
                        $.detachInfiniteScroll($('.infinite-scroll'));
                        $('.infinite-scroll-preloader').remove();
                    }
                }
            });
        })
    });

    //service
    $(document).on('pageInit', ".superpage_service", function(e, id, page) {
        var thispage = this;
        wxMenu.show();
    });

    //task
    $(document).on('pageInit', ".superpage_task", function(e, id, page) {
        var thispage = this;
        wxMenu.show();
        //切换任务类型
        $('.buttons-tab a').click(function(){
            $.showIndicator();
        });
        //领取、完成任务
        $('.btn_task').click(function(){
            var t = this;
            if ($(t).attr('data-flag') == '1') {
                return;
            }
            $.showIndicator();
            $(t).attr('data-flag', '1');
            var url = $(t).attr('data-url');
            var status = $(t).attr('data-status');
            var type = $(t).attr('data-type');
            var builtin = $(t).attr('data-builtin');
            var name = $(t).attr('data-name');
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(resp) {
                    $.hideIndicator();
                    if (resp.errno == '0') {    //成功
                        if (type == 2) {//日常任务
                            $(t).unbind('click').attr('href', resp.data.url).html('做任务');
                            $.toast('领取成功，跳转中...');
                            if (resp.data.url) {
                                setTimeout(function(){
                                    if (builtin == '0') {
                                        window.location.href = resp.data.url;
                                    } else {
                                        $.router.loadPage(resp.data.url);
                                    }
                                }, 2000);
                            }
                        } else if (type == 1) {//新手任务
                            if (status == '') {  //领取成功
                                var data_url = url.replace('act=get','act=complete');
                                $(t).attr('data-status', '0').attr('data-url', data_url).attr('data-flag', '0').removeClass('disabled button-warning').html('完成任务');
                                $.toast(resp.errmsg);
                                if (resp.data.url) {
                                    setTimeout(function(){
                                        $.router.loadPage(resp.data.url);
                                    }, 2000);
                                }
                            }
                            if (status == '0') {        //完成成功
                                $(t).attr('data-flag', '1').addClass('disabled').html('已完成');
                                $.toast(resp.data.award);
                            }
                        }
                    } else {
                        $.toast(resp.errmsg);
                        if (resp.errno == '4') {    //未登录
                            window.location.href = window.sysinfo.loginurl;
                        } else if (resp.errno == '1007') {     //未完成
                            $(t).attr('data-flag', '0');
                            if (resp.data.url) {
                                setTimeout(function(){
                                    if (name =='superman_creditmall_task6') {
                                        window.location.href = resp.data.url;
                                    } else {
                                        $.router.loadPage(resp.data.url);
                                    }
                                }, 2000);
                            }
                        }
                    }
                }
            });
        });
    });

    //help
    $(document).on('pageInit', ".superpage_help", function(e, id, page) {
        var thispage = this;
        wxMenu.show();
    });

    $.init();
});
