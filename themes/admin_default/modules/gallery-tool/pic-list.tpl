<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}themes/default/images/{MODULE_FILE}/frameworks/lightbox2/css/lightbox.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/default/images/{MODULE_FILE}/frameworks/lightbox2/js/lightbox.min.js"></script>
<script type="text/javascript">
lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true,
    'albumLabel': "{LANG.image} %1/%2"
});
</script>
<div class="form-group">
    <form class="form-inline" id="filter-form" method="get" action="" onsubmit="return false;">
        <input class="form-control text" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.searchPost}"/>
        <input class="btn btn-primary" type="button" name="do" value="{LANG.filter_action}"/>
        <input class="btn btn-default" type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DATA_SEARCH.disabled}/>
        <input class="btn btn-default" type="button" name="clear" value="{LANG.filter_clear}"/>
    </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
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
<form>
    <div class="row">
        <!-- BEGIN: row -->
        <div class="col-xs-12 col-sm-8 col-md-4">
            <div class="panel panel-default">
                <div class="panel-body glt-panel-body-sm">
                    <div class="text-center"><h3 class="tl">{ROW.title}</h3></div>
                    <div class="center glt-m-bottom">
                        <a href="{ROW.file}" data-lightbox="imagelist" data-title="{ROW.title}">
                            <img data-toggle="imgtip" src="{ROW.thumb}" class="img" title="<ul class='glt-ul'><li>{LANG.picULPicSize}: {ROW.width}x{ROW.height} px</li><li>{LANG.picSize}: {ROW.size}</li><li>{LANG.picFormat}: {ROW.format}</li></ul>"/>
                        </a>
                    </div>
                    <div class="text-center">
                        <input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
                        <a href="#modalEditPic" data-toggle="modal" title="{LANG.picEdit}" data-picid="{ROW.id}" data-picname="{ROW.title}"><i class="fa fa-pencil fa-lg"></i></a>
                        <a href="javascript:void(0);" title="{LANG.picDel}" onclick="nv_delete_pic({ROW.id});"><i class="fa fa-trash fa-lg"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: row -->
    </div>
</form>
<!-- BEGIN: generate_page -->
<div class="generate_page">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalEditPic">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{LANG.filter_cancel}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">&nbsp;</h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
function UpdateFrameHeight(h) {
    $('#modalEditPicIframe').height(h);
}
$(document).ready(function(){
    $('[data-toggle="imgtip"]').tooltip({
        container: 'body',
        html: true,
    });
    $('#modalEditPic').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        modal.find('.modal-title').text('{LANG.picEdit}: ' + button.data('picname'));
    });
    $('#modalEditPic').on('shown.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        modal.find('.modal-body').html('<iframe id="modalEditPicIframe" class="picedit-iframe" src="{URL_EDIT}' + button.data('picid') + '"></iframe>');
    });
    $('#modalEditPic').on('hide.bs.modal', function(event) {
        var modal = $(this);
        modal.find('.modal-body').html('');
    });
});
</script>
<!-- END: main -->