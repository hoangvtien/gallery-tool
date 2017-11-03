<!-- BEGIN: main -->
<form class="form-horizontal" method="post" action="{FORM_ACTION}">
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.cfgUpload}</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label col-md-10"><strong>{LANG.cfgchunk_size}</strong></label>
                <div class="col-md-14">
                    <div class="row">
                        <div class="col-sm-14">
                            <input type="text" name="chunk_size" value="{DATA.chunk_size}" class="form-control glt-sm-mb10"/>
                        </div>
                        <div class="col-sm-10">
                            <select name="chunk_size_unit" class="form-control">
                                <!-- BEGIN: size_unit_1 --><option value="{SIZEUNIT.key}"{SIZEUNIT.chunk_size}>{SIZEUNIT.title}</option><!-- END: size_unit_1 -->
                            </select>
                        </div>
                    </div>
                    <div class="help-block glt-mb0">{LANG.cfgchunk_size_note}</div>
                </div>
            </div>
            <div class="form-group glt-mb0">
                <label class="control-label col-md-10"><strong>{LANG.cfgmax_file_size}</strong></label>
                <div class="col-md-14">
                    <div class="row">
                        <div class="col-sm-14">
                            <input type="text" name="max_file_size" value="{DATA.max_file_size}" class="form-control glt-sm-mb10"/>
                        </div>
                        <div class="col-sm-10">
                            <select name="max_file_size_unit" class="form-control">
                                <!-- BEGIN: size_unit_2 --><option value="{SIZEUNIT.key}"{SIZEUNIT.max_file_size}>{SIZEUNIT.title}</option><!-- END: size_unit_2 -->
                            </select>
                        </div>
                    </div>
                    <div class="help-block glt-mb0">{LANG.cfgmaxNote} {MAX_SIZE_NOTE}</div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <input class="btn btn-primary" type="submit" name="submit" value="{LANG.save}"/>
        </div>
    </div>
</form>
<!-- END: main -->