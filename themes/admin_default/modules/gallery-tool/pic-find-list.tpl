<!-- BEGIN: main -->
<div style="padding:5px">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <td class="glt-col-id center">ID</td>
                <td class="glt-col-number">{LANG.picView}</td>
                <td>{LANG.picTitle}</td>
                <td class="glt-col-number">{LANG.albumPicSize}</td>
                <td class="center glt-col-feature">{LANG.select}</td>
            </tr>
        </thead>
    </table>
    <div id="data">
        <!-- BEGIN: data -->
        <table class="table table-striped table-bordered table-hover">
            <tbody>
                <!-- BEGIN: row -->
                <tr>
                    <td class="center glt-col-id"><strong>{ROW.id}</strong></td>
                    <td class="center glt-col-number"><img src="{ROW.thumb}" alt="{ROW.title}" height="50"/></td>
                    <td>{ROW.title}</td>
                    <td class="glt-col-number">{ROW.width}x{ROW.height} px</td>
                    <td class="center glt-col-feature"><input type="checkbox" name="pictureid" value="{ROW.id}"{ROW.checked} /></td>
                </tr>
                <!-- END: row -->
                <!-- BEGIN: generate_page -->
                <tr>
                    <td colspan="5" class="text-center">
                        <div id="loadpage">{GENERATE_PAGE}</div>
                    </td>
                </tr>
                <!-- END: generate_page -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <div class="pull-left">
                            <input type="button" value="{LANG.complete}" name="complete" id="complete" class="btn btn-primary"/>
                        </div>
                        <div class="pull-right">
                            <input class="btn btn-default" type="button" value="{LANG.checkall}" id="checkall" />
                            <input class="btn btn-default" type="button" value="{LANG.uncheckall}" id="uncheckall" />
                        </div>
                        <script type="text/javascript">
                        $(document).ready(function() {
                            $('#checkall').click(function() {
                                $('input:checkbox').each(function() {
                                    pictures = pictures.split(",");
                                    if (pictures[0] == "") pictures = new Array();

                                    var inlist = 0;
                                    var i = 0;
                                    for (i = 0; i < pictures.length; i++) {
                                        if ($(this).attr('value') == pictures[i]) {
                                            inlist = 1;
                                            break;
                                        }
                                    }
                                    if (inlist == 0) {
                                        pictures.push($(this).attr('value'));
                                    }
                                    pictures = pictures.toString();
                                    $(this).attr('checked', 'checked');
                                });
                            });

                            $('#uncheckall').click(function() {
                                $('input:checkbox').each(function() {
                                    pictures = pictures.split(",");
                                    if (pictures[0] == "") pictures = new Array();

                                    var listtemp = new Array();
                                    var i = 0;
                                    for (i = 0; i < pictures.length; i++) {
                                        if ($(this).attr('value') != pictures[i]) {
                                            listtemp.push(pictures[i]);
                                        }
                                    }
                                    pictures = listtemp;
                                    pictures = pictures.toString();
                                    $(this).removeAttr('checked');
                                });
                            });
                            $("input[name=pictureid]").click(function() {
                                pictures = pictures.split(",");
                                if (pictures[0] == "") pictures = new Array();
                                if ($(this).attr('checked')) {
                                    var inlist = 0;
                                    var i = 0;
                                    for (i = 0; i < pictures.length; i++) {
                                        if ($(this).attr('value') == pictures[i]) {
                                            inlist = 1;
                                            break;
                                        }
                                    }
                                    if (inlist == 0) {
                                        pictures.push($(this).attr('value'));
                                    }
                                } else {
                                    var listtemp = new Array();
                                    var i = 0;
                                    for (i = 0; i < pictures.length; i++) {
                                        if ($(this).attr('value') != pictures[i]) {
                                            listtemp.push(pictures[i]);
                                        }
                                    }
                                    pictures = listtemp;
                                }
                                pictures = pictures.toString();
                            });
                        });
                        </script>
                    </td>
                </tr>
            </tfoot>
        </table>
        <!-- END: data -->
    </div>
    <script type="text/javascript">
    var pictures = "{PICTURES}";

    function nv_load_page(url, tagsid) {
        url = rawurldecode(url) + "&getdata=1&area={RETURNAREA}&input={RETURNINPUT}&pictures=" + pictures;
        $('div#data').html('<div style="padding:5px;text-align:center"><img alt="Loading" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" /></div>');
        $('div#data').load(url);
        return;
    }
    $("input[name=complete]").click(function() {
        $('#tmp-data').html('');
        $('#tmp-data').load('{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&findListAndReturn=1&loadname=1&area={RETURNAREA}&input={RETURNINPUT}&pictures=' + pictures, function() {
            nv_return();
        });
    });

    function nv_return() {
        $("#{RETURNAREA}", opener.document).html($('#tmp-data').html());
        $("input[name={RETURNINPUT}]", opener.document).val(pictures);
        window.close();
    }
    </script>
    <div class="hidden" id="tmp-data"></div>
</div>
<!-- END: main -->