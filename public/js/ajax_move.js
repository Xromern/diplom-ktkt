

function showAjaxComment(article_id,path,preloader){
    $.ajax({
        type: "POST",
        url: path,//showComments
        data: ({
            'id': article_id,
    "page": getUrlVars()['page'],
}),
    beforeSend: function() {
        $('#comments-block').html('');
        preloader.showPeloader();

    },
    success: function (html) {
        setTimeout(function () {
            preloader.hidePrloaer();
            $('#comments-block').html(html);
        },300)
    }
});
}

