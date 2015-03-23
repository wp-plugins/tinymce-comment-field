jQuery(function ($) {

    $('.comment-reply-link').click(function (e) {
        e.preventDefault();
        var args = $(this).data('onclick');
        args = args.replace(/.*\(|\)/gi, '').replace(/\"|\s+/g, '');
        args = args.split(',');
        tinymce.EditorManager.execCommand('mceRemoveEditor', true, 'comment');
        addComment.moveForm.apply(addComment, args);
        tinymce.EditorManager.execCommand('mceAddEditor', true, 'comment');
    });

    $("#cancel-comment-reply-link").click(function () {
        setTimeout(function () {
            tinymce.EditorManager.execCommand('mceRemoveEditor', true, 'comment');
            tinymce.EditorManager.execCommand('mceAddEditor', true, 'comment');
        }, 500);
    });

});