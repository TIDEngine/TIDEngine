<div class="container-fluid" id="content_holder">
<div id="elf_breadcrumbs">{breadcrumbs}</div>
    <div id="elfinder"></div>
</div>
<script type="text/javascript" charset="utf-8">
			$().ready(function() {
				var elf = $('#elfinder').elfinder({
					url : '?u=admin/media/request',  // connector URL (REQUIRED)
				       height: '550'            // language (OPTIONAL)
				}).elfinder('instance');
			});
		</script>

