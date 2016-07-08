var Phone = (function () {
return{
    param: true,
    checkPhone: function () { //验证电话号码
        var mobileNumber = $("#mobile").val();
        var reg = /^1[3|4|5|6|7|8|9][0-9]{9}$/;
        if (!reg.test(mobileNumber)) {
            $.tips("请输入有效的手机号")
            Phone.param = false;
        }
    },
    checkPassword: function () {
        var password = $("#pwd").val();
        var reg = /^[0-9A-Za-z]{6,20}$/;
        if (!reg.test(password)) {
            $.tips("请输入6—20位的密码");
            Phone.param = false;
            return;
        }
        //不能为连续数字或重复的字符
        // if(Phone.checkIsEqualPassword(password)||Phone.checkIsPlusNumeric(password)||Phone.checkIsMinusNumeric(password)){
        //      $.tips("您的密码过于简单！");
        //      Phone.param = false;
        // }
    },
    checkIsEqualPassword: function (password) {
        //不能重复的字符
        var flag = true;
        var str = password.charAt(0);
          for (var i = 0; i < password.length; i++) {
           if (str != password.charAt(i)) {
            flag = false;
            break;
           }
          }
          return flag;            
    },
    checkIsPlusNumeric: function (password) {
        //不能为连续递增数字
        var flag = true;// 如果全是连续数字返回true
        var isNumeric = true;// 如果全是数字返回true
          for (var i = 0; i < password.length; i++) {
           if (isNaN(password.charAt(i))) {
            isNumeric = false;
            break;
           }
          }
          if (isNumeric) {// 如果全是数字则执行是否连续数字判断
           for (var i = 0; i < password.length; i++) {
            if (i > 0) {// 判断如123456
                var num = parseInt(password.charAt(i) + "");
                var num_ = parseInt(password.charAt(i - 1) + "") + 1;
             if (num != num_) {
              flag = false;
              break;
             }
            }
           }
          } else {
           flag = false;
          }
          return flag;          
    },
    checkIsMinusNumeric: function (password) {
        //不能为连续递减数字
         var flag = true;// 如果全是连续数字返回true
         var isNumeric = true;// 如果全是数字返回true
          for (var i = 0; i < password.length; i++) {
           if (isNaN(password.charAt(i))) {
            isNumeric = false;
            break;
           }
          }
          if (isNumeric) {// 如果全是数字则执行是否连续数字判断
           for (var i = 0; i < password.length; i++) {
            if (i > 0) {// 判断如654321
                var num = parseInt(password.charAt(i) + "");
                var num_ = parseInt(password.charAt(i - 1) + "") - 1;
             if (num != num_) {
              flag = false;
              break;
             }
            }
           }
          } else {
           flag = false;
          }
          return flag;
    },
 }})()