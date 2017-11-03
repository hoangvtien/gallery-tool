<!-- BEGIN: main -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Language" content="vi" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{LANG.albumFindTitle}</title>
		<link rel="StyleSheet" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.admin_theme}/css/admin.css" type="text/css" />
		<link type="text/css" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.module_theme}/css/{MODULE_FILE}.css" rel="stylesheet" />
		<script type="text/javascript"> var nv_siteroot = "{NV_BASE_SITEURL}";</script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/{NV_LANG_INTERFACE}.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/global.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/admin.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
	</head>
	<body>
		<div id="getuidcontent">
			<form class="form-inline" id="formgetuid" method="get" action="{FORM_ACTION}">
			<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
			<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
			<input type="hidden" name="findOneAndReturn" value="1" />
			<input type="hidden" name="ids" value="{IDS}" />
			<input type="hidden" name="area" value="{RETURNAREA}" />
			<input type="hidden" name="input" value="{RETURNINPUT}" />
			<input type="hidden" name="multi" value="{MULTI}" />
			<table class="table table-striped table-bordered table-hover">
				<tbody>
					<tr><td colspan="8" class="center green"><strong>{LANG.albumFindTitle}</strong></td></tr>
					<tr>
						<td>{LANG.albumTitle}</td>
						<td><input class="form-control glt-input glt-txt-fh" type="text" name="q" value="{SEARCH.q}"/></td>
						<td class="glt-col-status"><input type="submit" name="submit" value="{LANG.search}" class="glt-button"/></td>
						<td class="glt-col-status"><input type="button" onclick="window.location='{URLCANCEL}';" value="{LANG.filter_cancel}" class="glt-button"/></td>
					</tr>
				</tbody>
			</table>
			</form>
		</div>
		<div id="resultdata">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<td class="glt-col-id center">ID</td>
						<td><a href="{DATA_ORDER.title.data.url}" title="{DATA_ORDER.title.data.title}" class="{DATA_ORDER.title.data.class}">{LANG.albumTitle}</a></td>
						<td>{LANG.albumPicSize}</td>
						<td>{LANG.albumNumPics}</td>
						<td class="center glt-col-feature">{LANG.select}</td>
					</tr>
				</thead>
				<tbody>
				<!-- BEGIN: row -->
					<tr>
						<td class="text-center"><strong>{ROW.id}</strong></td>
						<td>{ROW.title}</td>
						<td>{ROW.bigw}x{ROW.bigh} px - {ROW.smallw}x{ROW.smallh} px</td>
						<td>{ROW.numpics}</td>
						<td class="text-center"><a class="glt-select-icon nounderline" title="{LANG.select}" onclick="nv_close_pop('{ROW.id}', '{ROW.title}');" href="javascript:void(0);">{LANG.select}</a></td>
					</tr>
				<!-- END: row -->
				</tbody>
				<!-- BEGIN: generate_page -->
				<tbody>
					<tr>
						<td colspan="4" class="text-center">{GENERATE_PAGE}</td>
					</tr>
				<!-- END: generate_page -->
				</tbody>
			</table>
			<script type="text/javascript">
			function nv_close_pop( id, name ){
				var ids = "{IDS}";
				var multi = {MULTI};
				
				if( multi ){
					if( ids == "" ){
						ids = id;
					}else{
						ids = ids + "," + id;
					}	
					$("#{RETURNAREA}", opener.document).append('<li class="' + id + '">' + name + '<span onclick="nv_del_item_on_list(' + id + ', \'{RETURNAREA}\', nv_is_del_confirm[0], \'{RETURNINPUT}\');" class="glt-delete-icon">&nbsp;</span></li>');
				}else{
					ids = id;
					$("#{RETURNAREA}", opener.document).html('<li class="' + id + '">' + name + '<span onclick="nv_del_item_on_list(' + id + ', \'{RETURNAREA}\', nv_is_del_confirm[0], \'{RETURNINPUT}\');" class="glt-delete-icon">&nbsp;</span></li>');
				}

				$("input[name={RETURNINPUT}]", opener.document).val(ids);
				window.close()
			}
			</script>
		</div>
	</body>
</html>
<!--  END: main  -->