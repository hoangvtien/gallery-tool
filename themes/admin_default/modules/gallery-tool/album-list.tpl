<!-- BEGIN: main -->
<div class="form-group">
    <form class="form-inline" id="filter-form" method="get" action="" onsubmit="return false;">
        <input class="form-control text" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.searchPost}"/>
        <input class="btn btn-primary" type="button" name="do" value="{LANG.filter_action}"/>
        <input class="btn btn-default" type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DATA_SEARCH.disabled}/>
        <input class="btn btn-default" type="button" name="clear" value="{LANG.filter_clear}"/>
    </form>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('input[name=clear]').click(function() {
        $('#filter-form .text').val('');
    });
    $('input[name=do]').click(function() {
        var f_q = $('input[name=q]').val();

        if (f_q != '') {
            $('#filter-form input, #filter-form select').attr('disabled', 'disabled');
            window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&q=' + f_q;
        } else {
            alert('{LANG.filter_err_submit}');
        }
    });
});
</script>
<form class="form-inline" action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <td class="center glt-col-id">
                    <input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
                </td>
                <td class="glt-col-id"><a href="{DATA_ORDER.id.data.url}" title="{DATA_ORDER.id.data.title}" class="{DATA_ORDER.id.data.class}">{LANG.albumID}</a></td>
                <td><a href="{DATA_ORDER.title.data.url}" title="{DATA_ORDER.title.data.title}" class="{DATA_ORDER.title.data.class}">{LANG.albumTitle}</a></td>
                <td>{LANG.albumNumPics}</td>
                <td>{LANG.albumPicSize}</td>
                <td class="glt-col-feature">{LANG.feature}</td>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: row -->
            <tr class="topalign">
                <td class="text-center">
                    <input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
                </td>
                <td class="text-center">{ROW.id}</td>
                <td>{ROW.title}</td>
                <td>{ROW.numpics}</td>
                <td>{ROW.bigw}x{ROW.bigh} - {ROW.smallw}x{ROW.smallh}</td>
                <td class="text-center">
                    <a class="btn btn-default btn-xs" href="{ROW.urlEdit}"><i class="fa fa-edit fa-fw"></i>{GLANG.edit}</a>
                    <a class="btn btn-danger btn-xs" href="javascript:void(0);" onclick="nv_delete_album({ROW.id});"><i class="fa fa-trash fa-fw"></i>{GLANG.delete}</a>
                </td>
            </tr>
            <!-- END: row -->
            <!-- BEGIN: generate_page -->
            <tr>
                <td colspan="8">
                    {GENERATE_PAGE}
                </td>
            </tr>
            <!-- END: generate_page -->
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8">
                    <!-- BEGIN: action -->
                    <span class="glt-{ACTION.class}-icon"><a onclick="nv_album_action(document.getElementById('levelnone'), '{LANG.alert_check}', {ACTION.key});" href="javascript:void(0);" class="nounderline">{ACTION.title}</a>&nbsp;</span>
                    <!-- END: action -->
                </td>
            </tr>
        </tfoot>
    </table>
</form>
<!-- END: main -->