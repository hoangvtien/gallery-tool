<!-- BEGIN: main -->
<div class="panel-body">
    <div id="getuidcontent">
        <form id="formgetuid" method="get" action="{FORM_ACTION}">
            <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
            <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
            <input type="hidden" name="findOneAndReturn" value="1" />
            <input type="hidden" name="pictures" value="{PICTURES}" />
            <input type="hidden" name="area" value="{RETURNAREA}" />
            <input type="hidden" name="input" value="{RETURNINPUT}" />
            <div class="form-group">
                <div class="input-group">
                    <input class="form-control" type="text" name="q" value="{SEARCH.q}"/>
                    <div class="input-group-btn">
                        <input type="submit" name="submit" value="{LANG.search}" class="btn btn-primary"/>
                        <input type="button" onclick="window.location='{URLCANCEL}';" value="{LANG.filter_cancel}" class="btn btn-default"/>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="resultdata">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <td class="glt-col-id center">ID</td>
                    <td class="glt-col-image center">{LANG.picView}</td>
                    <td><a href="{DATA_ORDER.title.data.url}" title="{DATA_ORDER.title.data.title}" class="{DATA_ORDER.title.data.class}">{LANG.picTitle}</a></td>
                    <td>{LANG.albumPicSize}</td>
                    <td class="center glt-col-feature">{LANG.select}</td>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: row -->
                <tr>
                    <td class="text-center"><strong>{ROW.id}</strong></td>
                    <td class="text-center"><img src="{ROW.thumb}" alt="{ROW.title}" height="50"/></td>
                    <td>{ROW.title}</td>
                    <td>{ROW.width}x{ROW.height} px</td>
                    <td class="text-center"><a class="btn btn-default btn-xs" title="{LANG.select}" onclick="nv_close_pop('{ROW.id}', '{ROW.title}', '{ROW.thumb}');" href="javascript:void(0);"><i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>{LANG.select}</a></td>
                </tr>
                <!-- END: row -->
                <!-- BEGIN: generate_page -->
                <tr>
                    <td colspan="5" class="text-center">{GENERATE_PAGE}</td>
                </tr>
                <!-- END: generate_page -->
            </tbody>
        </table>
        <script type="text/javascript">
        function nv_close_pop(id, name, thumb) {
            var pictures = "{PICTURES}";

            if (pictures == "") pictures = id;
            else pictures = pictures + "," + id;

            $("#{RETURNAREA}", opener.document).append('<li class="list-group-item list-group-item-success" data-picid="' + id + '"><div><img src="' + thumb + '"/>' + name + '<span onclick="nv_del_item_on_list(' + id + ', \'{RETURNAREA}\', nv_is_del_confirm[0], \'{RETURNINPUT}\');"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></span></div></li>');
            $("input[name={RETURNINPUT}]", opener.document).val(pictures);
            window.close();
        }
        </script>
    </div>
</div>
<!--  END: main  -->