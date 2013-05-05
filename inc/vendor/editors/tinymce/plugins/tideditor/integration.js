
tinymce.PluginManager.add("tideditor",function(editor,url){

    editor.settings.file_browser_callback=function(id,value,type,win){
        var langCode= tinymce.PluginManager.requireLangPack('tideditor');
        var langCode=langCode?langCode:"en";

        editor.windowManager.open({
            file : "inc/vendor/editors/tinymce/plugins/tideditor/elfinder.html?lang="+langCode+"&filetype="+type,
            title : 'TIDEditor',
            width : 800,
            height : 600,
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
