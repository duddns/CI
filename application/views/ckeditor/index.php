<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ckeditor test</title>
    <link href="css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-responsive.css" rel="stylesheet" media="screen">
    <link href="css/jquery.fileupload-ui.css" rel="stylesheet" media="screen">
    <link href="css/editor.css" rel="stylesheet" media="screen">
</head>
    
<body>

<div class="container">
    <h2>ckeditor test</h2>
    
    <form class="form-horizontal" action="/ckeditor/save" method="post" enctype="multipart/form-data">
        <fieldset>
            <div class="control-group">
                <label class="control-label">제목</label>
                <div class="controls">
                    <input class="input-xxlarge" type="text" name="name" value="" placeholder="제목" />
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">내용</label>
                <div class="controls">
                    <textarea id="WYSIWYG" name="content" placeholder="내용을 입력해 주세요."></textarea>
                </div>
            </div>
            <div class="control-group">
                <div id="FILEUPLOAD" class="controls">
                    <div class="uploader">
                        <span class="btn btn-success fileinput-button">
                            <i class="icon-plus icon-white"></i>
                            <span>Add files...</span>
                            <input type="file" name="image" data-url="/ckeditor/fileupload" multiple="multiple" />
                        </span>
                        <div class="fileupload-progress fade">
                            <div class="progress active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                <div class="bar" style="width: 0%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="files">
                        <table class="table table-striped">
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="control-group">
                <div class="controls">
                    <input class="btn btn-large" type="reset" data-dismiss="modal" value="취소" />
                    <input class="btn btn-large btn-primary" type="submit" value="저장" />
                </div>
            </div>
        </fieldset>
    </form>
</div>


<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/jquery.fileupload.js"></script>
<script type="text/javascript" src="js/editor.js"></script>

</body>
</html>