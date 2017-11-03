<!-- BEGIN: main -->
<div class="clearfix" id="picedit-wrap">
    <!-- BEGIN: error -->
    <div class="alert alert-danger">{ERROR}</div>
    <!-- END: error -->
    <form method="post" action="{FORM_ACTION}" id="post-form">
    	<input type="hidden" name="id" id="post-id" value="{ID}"/>
    	<div class="form-group">
            <label><strong>{LANG.picTitle}</strong></label>
            <input type="text" name="title" value="{DATA.title}" class="form-control"/>
        </div>
    	<div class="form-group">
            <label><strong>{LANG.picDescription}</strong></label>
            <input type="text" name="description" value="{DATA.description}" class="form-control"/>
        </div>
    	<div class="form-group">
            <label><strong>{LANG.picOther} 1</strong></label>
            <input type="text" name="info1" value="{DATA.info1}" class="form-control"/>
        </div>
    	<div class="form-group">
            <label><strong>{LANG.picOther} 2</strong></label>
            <input type="text" name="info2" value="{DATA.info2}" class="form-control"/>
        </div>
    	<div class="form-group">
            <label><strong>{LANG.picOther} 3</strong></label>
            <input type="text" name="info3" value="{DATA.info3}" class="form-control"/>
        </div>
    	<div class="form-group">
            <label><strong>{LANG.picOther} 4</strong></label>
            <input type="text" name="info4" value="{DATA.info4}" class="form-control"/>
        </div>
    	<div class="form-group">
            <label><strong>{LANG.picOther} 5</strong></label>
            <input type="text" name="info5" value="{DATA.info5}" class="form-control"/>
        </div>
    	<div class="form-group">
            <label><strong>{LANG.picLink}</strong></label>
            <input type="text" name="link" value="{DATA.link}" class="form-control"/>
        </div>
        <input type="submit" name="submit" value="{LANG.save}" class="btn btn-primary"/>
    </form>
</div>
<script type="text/javascript">
$(window).on('load', function() {
    parent.UpdateFrameHeight($('#picedit-wrap').height());
});
</script>
<!-- END: main -->

<!-- BEGIN: complete -->
<div class="clearfix" id="picedit-wrap">
    <div class="alert alert-info text-center glt-mb0">
    	<p>{MESSAGE}</p>
    	<p><img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading..." height="8"/></p>
    </div>
</div>
<script type="text/javascript">
$(window).on('load', function() {
    parent.UpdateFrameHeight($('#picedit-wrap').height());
});
setTimeout(function() {
    parent.location.href = parent.location.href;
}, 3000);
</script>
<!-- END: complete -->