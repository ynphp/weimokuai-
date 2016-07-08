//征友条件
var Match = (function () {
    return {
        param: {
            con_age: $('#con_age').val(),
            con_height: $('#con_height').val(),
            con_income: $('#con_income').val(),
            con_province: $('#con_province').val(),
            con_edu: $('#con_edu').val(),
            con_girlCareId:$("#con_girlCareId").val()
        },
        changeEvent: function ($this) {
            var $this_box = $this.closest(".option_box"),
                $top = $this_box.find(".top"),
                $title = $this_box.find(".title"),
                $option = $this.closest("ul");
            $option.find("li span").removeClass("pink");
            $this.addClass("pink");
            $title.addClass("pink").html($this.html());
            Match.toggle($top);
        },
        changeAge: function (age) {
            Match.param.con_age = age;
        },
        changeHeight: function (height) {
            Match.param.con_height = height;
        },
        changeSalary: function (salary) {
            Match.param.con_income = salary;
        },
        changeEducation: function (education) {
            Match.param.con_edu = education;
        },
        changeProvince: function (provinceId) {
            Match.param.con_province = provinceId;
        },
        changeInterest: function (girlCare) {
            Match.param.con_girlCareId = girlCare;
        },
        toggle: function ($this) {
            var $ul = $this.next("ul");
            if($ul.hasClass("hidden")){//显示
                $(".top i").removeClass("top_trg").addClass('bot_trg');
                $(".option_box ul").addClass("hidden");
                $this.find("i").addClass("top_trg").removeClass("bot_trg");
                $ul.removeClass("hidden");
            }else{//隐藏
                $this.find("i").removeClass("top_trg").addClass('bot_trg');
                $ul.addClass("hidden");
            }
        }
    }
})
();