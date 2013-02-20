function reloadFiles() {
    $.ajax({
        url: '/ckeditor/filebrowse',
        dataType: 'json',
        success: function(data) {
            var html = [];
            for (var i = 0, len = data.files.length; i < len; i++) {
                var file = data.files[i];
                html.push('<tr>\
                        <td class="preview"><img src="' + file.url + '" /></td>\
                        <td class="name">' + file.name + '</td>\
                        <td class="size">' + (file.size / 1024 / 1024).toFixed(2) + 'MB</td>\
                        <td class="action">\
                            <a class="insert btn btn-small" href="#" data-url="' + file.url + '">입력</a>\
                            <a class="delete btn btn-danger btn-small" href="#" data-url="' + file.delete + '" data-file="' + file.name + '">삭제</a>\
                        </td>\
                    </tr>');
            };
            $('#FILEUPLOAD .files .table').html(html.join());
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert('서버에 문제가 생겼습니다.\n잠시후에 다시 시도해 주세요.');
        }
    });
};


$(function() {
    CKEDITOR.replace('WYSIWYG', {
        width: 542,
        height: 400,
        toolbar:  [
                { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                { name: 'insert', items: [ 'Table', 'HorizontalRule', 'SpecialChar' ] },
                { name: 'tools', items: [ 'Maximize' ] },
                { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
                { name: 'others', items: [ '-' ] },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
                { name: 'styles', items: [ 'Styles', 'Format' ] }
            ]
    });
});

$(function() {
    $('#FILEUPLOAD').css('width', '542px');
    
    $('#FILEUPLOAD input[type=file]').fileupload({
        dataType: 'json',
        start: function(e, data) {
            $('#FILEUPLOAD .fileupload-progress').toggleClass('in');
        },
        stop: function(e, data) {
            $('#FILEUPLOAD .fileupload-progress').toggleClass('in')
                .find('.progress').attr('aria-valuenow', '0')
                .find('.bar').css('width', '0%');
        },
        done: function(e, data) {
            reloadFiles();
        },
        progress: function(e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#FILEUPLOAD .progress .bar').css('width', progress + '%');
        }
    });
    
    $('#FILEUPLOAD').delegate('.files .insert', 'click', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('data-url');
        var html = '<img src="' + url + '" width="600" />';
        
        CKEDITOR.instances.WYSIWYG.insertHtml(html);
        
        return false;
    });
    
    $('#FILEUPLOAD').delegate('.files .delete', 'click', function(e) {
        e.preventDefault();
        
        var el = $(this);
        var url = el.attr('data-url');
        var file = el.attr('data-file');
        $.ajax({
            type: 'POST',
            url: url,
            contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'json',
            data: {'file': file},
            success: function(data) {
                el.parent().parent().remove();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert('서버에 문제가 생겼습니다.\n잠시후에 다시 시도해 주세요.');
            }
        });
        
        return false;
    });
    
    reloadFiles();
});