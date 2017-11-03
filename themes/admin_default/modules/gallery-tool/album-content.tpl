<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<form class="form-horizontal" method="post" action="{FORM_ACTION}" id="post-form">
    <input type="hidden" name="id" id="post-id" value="{ID}"/>
    <div class="panel panel-default">
        <div class="panel-heading">{TABLE_CAPTION}</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label col-md-8"><strong>{LANG.albumTitle}</strong></label>
                <div class="col-md-10 col-lg-7">
                    <input type="text" name="title" value="{DATA.title}" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8"><strong>{LANG.albumDes}</strong></label>
                <div class="col-md-16">
                    <input type="text" name="description" value="{DATA.description}" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8"><strong>{LANG.albumBigSize}</strong></label>
                <div class="col-md-16">
                    <div class="row">
                        <div class="col-xs-11">
                            <input type="text" name="bigw" value="{DATA.bigw}" class="form-control"/>
                        </div>
                        <div class="col-xs-2 text-center">
                            <span class="glt-label-middle">x</span>
                        </div>
                        <div class="col-xs-11">
                            <input type="text" name="bigh" value="{DATA.bigh}" class="form-control"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-8"><strong>{LANG.albumSmallSize}</strong></label>
                <div class="col-md-16">
                    <div class="row">
                        <div class="col-xs-11">
                            <input type="text" name="smallw" value="{DATA.smallw}" class="form-control"/>
                        </div>
                        <div class="col-xs-2 text-center">
                            <span class="glt-label-middle">x</span>
                        </div>
                        <div class="col-xs-11">
                            <input type="text" name="smallh" value="{DATA.smallh}" class="form-control"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            {LANG.albumPics}
            <div class="pull-right">
                <i class="fa fa-plus fa-fw" aria-hidden="true"></i><a href="#" id="pictures-add-one" class="nounderline">{LANG.albumPicAddOne}</a>
                <i class="fa fa-align-justify fa-fw" aria-hidden="true"></i><a href="#" id="pictures-add-list" class="nounderline">{LANG.albumPicAddList}</a>
            </div>
            <input type="hidden" name="pictures" value="{PICTURES}"/>
        </div>
        <ul id="pictures-area" class="list-group">
            <!-- BEGIN: picture -->
            <li class="list-group-item list-group-item-success" data-picid="{PICTURE.id}">
                <div>
                    <img src="{PICTURE.thumb}"/>
                    {PICTURE.title}<span onclick="nv_del_item_on_list({PICTURE.id}, 'pictures-area', nv_is_del_confirm[0], 'pictures')"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></span>
                </div>
            </li>
            <!-- END: picture -->
        </ul>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <input type="submit" name="submit" value="{LANG.save}" class="btn btn-primary"/>
        </div>
    </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $("#pictures-area").sortable({
        cursor: "crosshair",
        update: function(event, ui) {
            nv_sort_item('pictures-area', 'pictures');
        }
    });
    $("#pictures-area").disableSelection();
    $("a#pictures-add-one").click(function() {
        var pictures = $("input[name=pictures]").attr("value");
        nv_open_browse("{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&findOneAndReturn=1&area=pictures-area&input=pictures&pictures=" + pictures, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    });
    $("a#pictures-add-list").click(function() {
        var pictures = $("input[name=pictures]").attr("value");
        nv_open_browse("{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&findListAndReturn=1&area=pictures-area&input=pictures&pictures=" + pictures, "NVImg", "850", "610", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    });
});
</script>
<!-- END: main -->

<!-- BEGIN: complete -->
<div class="alert alert-success center">
    <p>{MESSAGE}</p>
    <p><img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading..." height="8"/></p>
</div>
<!-- END: complete -->