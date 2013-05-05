    $("#demo1").ajaxupload({
        url:"inc/vendor/FileUpload/upload.php",
        remotePath:"/Applications/XAMPP/xamppfiles/htdocs/TIDEngine/inc/uploads",
        thumbHeight:"50",
        thumbWidth:"50",
        thumbPostfix:"_thumbs",
        thumbPath:"/Applications/XAMPP/xamppfiles/htdocs/TIDEngine/inc/uploads/thumbs",
        finish:function(files)
        {
            conole.log(files);
        },
        success:function(fileName)
        {
            $("#files").append("<img src='http://localhost/TIDEngine//inc/uploads/"+fileName["thumb"]+"' />");
            $("#demo1").ajaxupload("clear")
        }
    });