var Expression = (function () {
    return {
        images: [{className:'face_1',text: '微笑'},
            {className:'face_2',text: '害羞'},
            {className:'face_3',text: '喜欢'},
            {className:'face_4',text: '快哭了'},
            {className:'face_5',text: '爱心'},
            {className:'face_6',text: '擦汗'},{className:'face_7',text: '愤怒'},
            {className:'face_8',text: '可爱'},{className:'face_9',text: '小可怜'},{className:'face_10',text: '尴尬'},{className:'face_11',text: '呲牙'},
            {className:'face_12',text: '红唇'},{className:'face_13',text: '难过'},{className:'face_14',text: '亲亲'},{className:'face_15',text: '委屈'},
            {className:'face_16',text: '疑惑'},{className:'face_17',text: '拥抱'},{className:'face_18',text: '再见'},{className:'face_19',text: '咖啡'},
            {className:'face_20',text: '礼物'},{className:'face_21',text: '玫瑰'}],
        initBox: function ($selector) {
            $.each(Expression.images, function () {
                if(this.className=="face_1" || this.className=="face_11"){
                    return true;
                }
                var li = $('<li></li>').data('express', this.text).append('<i class="icon '+this.className +'"></i>');
                $selector.append(li);
            });
        },
        replaceHtml: function ($selector) {
            var html = $selector.html();
            $.each(Expression.images, function () {
                var re = new RegExp("\\["+this.text+"\\]","g");
                html = html.replace(re, '<i class="icon ' + this.className + '"></i>');
            });
            $selector.html(html);
        }
    }

})();
