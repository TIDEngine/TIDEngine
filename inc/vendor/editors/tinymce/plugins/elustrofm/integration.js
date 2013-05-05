tinymce.PluginManager.add("elustrofm",function(editor,url){

    editor.settings.file_browser_callback=function(id,value,type,win){

     cmsURL = "inc/vendor/editors/tinymce/plugins/elustrofm/index.html?integration=fm&lang="+editor.settings.language+"&filetype="+type;
        editor.windowManager.open({
            file : cmsURL,
            title : 'elustroFM',
            width : 700,
            height : 550,
            resizable : "yes",
            scrollbars : "no",
            inline : "yes",
            close_previous : "no",
            popup_css : false
        }, {
            window : win,
            input : id
        });
        return false;



    }





})
