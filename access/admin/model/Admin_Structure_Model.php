<?php

class Admin_Structure_Model extends Model {

    public function __construct() {
        parent::__construct();
    }



    public function create_page() {

        $r = $this->Module_Forms;

        $pages = array(
            'option1' => array(
                'data' => array('value'=>'home'),
                'text'=>'Home Page'
            ),
             'option2' => array(
                'data' => array('value'=>'hossme'),
                'text'=>'Hossssme Page'
            ),
            'option3' => array(
                'data' => array('value'=>'asdasd'),
                'text'=>'Hossme Page'
            ),
        );



        $html = '<div class="create_page_form span12">';
        $html = '<form id="form" method="post" action="'.  format_urls('admin-structure-pages_router-create_page_process').'">';
        $a = array(
            'input' => array(
                'data' => array('type' => 'text', 'class' => 'input-block-level', 'id'=>'page_title', 'placeholder' => 'Page Title')
            ),
            'legend' => array(
                'data'=>array('data-toggle'=>'collapse', 'data-target'=>'#form_page_collapse'),
                'text' => 'Page Meta Data  <b class="ic" id="meta_collapse">@</b>'
            )
        );
        $html .= $r->creates($a);
        $html .= '<div class="row">';
        $html .= '<div id="form_page_collapse" class="collapse">';
        $html .= '<div class="span6">';
        $html .= '<div class="form-horizontal control-group">';
        $a = array('label' => array(
                'data' => array('class' => 'control-label', 'for' => 'slug'),
                'text' => 'Page Slug'
            )
        );
        $html .= $r->creates($a);
        $html .= '<div class="controls">';
        $a = array('input' => array(
                'data' => array('type' => 'text', 'id' => 'slug',  'name' => 'slug', 'class' => 'span12', 'placeholder' => 'Page Slug')
            )
        );
        $html .= $r->creates($a);
        $html .= '</div>';

        $html .= '</div>';

        $html .= '<div class="form-horizontal control-group">';
        $a = array('label' => array(
                'data' => array('class' => 'control-label', 'for' => 'keywords'),
                'text' => 'Meta Keywords'
            )
        );
        $html .= $r->creates($a);
        $html .= '<div class="controls">';
        $a = array('input' => array(
                'data' => array('type' => 'text', 'id' => 'keywords', 'name' => 'keywords', 'class' => 'span12', 'id' => 'keywords', 'placeholder' => 'Meta Keywords')
            )
        );
        $html .= $r->creates($a);
        $html .= '</div>';

        $html .= '</div>';

        $html .= '<div class="form-horizontal control-group">';
        $a = array('label' => array(
                'data' => array('class' => 'control-label', 'for' => 'description'),
                'text' => 'Meta Description'
            )
        );
        $html .= $r->creates($a);
        $html .= '<div class="controls">';
        $a = array('textarea' => array(
                'data' => array('type' => 'text', 'id' => 'description', 'name' => 'description', 'class' => 'span12', 'rows'=>'3')
            )
        );
        $html .= $r->creates($a);
        $html .= '</div>';

        $html .= '</div>';

        $html .= '</div>';

        $html .= '<div class="row">';
        $html .= '<div class="span6">';

        $html .= '<div class="form-horizontal control-group">';
        $a = array('label' => array(
                'data' => array('class' => 'control-label', 'for' => 'add_meta'),
                'text' => 'Additional Meta Tags'
            )
        );
        $html .= $r->creates($a);
        $html .= '<div class="controls">';
        $a = array('textarea' => array(
                'data' => array('type' => 'text', 'class' => 'span12', 'id' => 'add_meta',  'name' => 'add_meta', 'rows'=>'3')

        ));
        $html .= $r->creates($a);
        $html .= '</div>';

        $html .= '</div>';


        $html .= '<div class="form-horizontal control-group">';
        $a = array('label' => array(
                'data' => array('class' => 'control-label', 'for' => 'pages'),
                'text' => 'Parent Page'
            )
        );
        $html .= $r->creates($a);
        $html .= '<div class="controls">';
        $a = array('select' => array(
                'data' => array('id' => 'pages', 'name' => 'pages', 'class' => 'span12'),
                'child'=>$pages
            )
        );
        $html .= $r->creates($a);
        $html .= '</div>';

        $html .= '</div>';

            $html .= '<div class="form-horizontal control-group checkbox_pos">';

        $html .= '';
        $a = array('label' => array(
                'data' => array('class'=>'checkbox'),
                'child'=>array(
                    'input' => array(
                        'data' => array('type'=>'checkbox',  'name' => 'menu', 'id' => 'menu'),
                        'text'=>'<span class="checkbox_span">Include Page in main Menu navigation</span>'
                    )
                    )
            )
        );
        $html .= $r->creates($a);


        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';


        $html .= '</div>';
        // PAGE CONTENTS
        $html .= '<div class="page_content">';
        $a = array('legend' => array(
             'text' => 'Page Content'

            )
        );
        $html .= $r->creates($a);

        $a = array('textarea' => array(
                'data' => array('class' => 'span12', 'id' => 'page_content',   'name' => 'page_content', 'rows'=>'20')
            )

        );

        $html .= $r->creates($a);
        if($this->main_conf['editor'] == 'ckeditor'){
            $da = 'inc/vendor/editors/ckeditor/plugins/elfinder/';
            $link = SITE_URL.$da;
            $html .='<script>CKEDITOR.replace( "page_content",
                {
                filebrowserBrowseUrl : "'.$link.'elfinder.html?mode=file",
                filebrowserImageBrowseUrl : "'.$link.'elfinder.html?mode=image",
                filebrowserFlashBrowseUrl : "'.$link.'elfinder.html?mode=flash",
                filebrowserImageUploadUrl : "'.$link.'elfinder.html?mode=image",
                filebrowserFlashUploadUrl : "'.$link.'elfinder.html?mode=flash",
                filebrowserImageWindowWidth : "950",
                filebrowserImageWindowHeight : "490",
                filebrowserWindowWidth : "950",
                filebrowserWindowHeight : "490",
                height: 500});
                </script>';

        }else  if($this->main_conf['editor'] == 'tinymce'){
            $html .='<script type="text/javascript">

tinymce.PluginManager.load("tideditor", "http://localhost/TIDEngine/inc/vendor/editors/tinymce/plugins/tideditor/integration.js");
//tinymce.PluginManager.load("elustrofm", "http://localhost/TIDEngine/inc/vendor/editors/tinymce/plugins/elustrofm/integration.js");

tinymce.init({
    mode : "exact",
    elements : "page_content",
    height: 500,
    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste media imanager tideditor elustrofm"
    ],

    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor emoticons elustrofm media imanager tideditor elustrofm",

});

</script>
';
        }

        $html .= '</div>';

        $html .= '</div>';
        $html .= '<div class="submit_line">';
        $a = array('button' => array(
             'data' => array('class' => 'btn btn-large btn-primary', 'type' => 'submit', 'id'=>'submit'),
             'text'=>'Create Page'

            )
        );
        $html .= $r->creates($a);
        $html .= '</div>';
        $html .="<script>
   var options = {
        beforeSubmit:  showRequest,
        success:       showResponse
    };

$('form').ajaxForm(options);
</script>";
        $html .= '</form>';
        $html .= '</div>';
        echo $html;


    }

     public function create_page_process(){
        pr($_POST);
    }
}