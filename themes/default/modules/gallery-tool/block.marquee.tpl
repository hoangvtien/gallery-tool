<!-- BEGIN: main -->
<div id="glt-marquee-{CONFIG.bid}" class="glt-marquee">
	<ul>
		<!-- BEGIN: loop -->
		<li>
			<a href="{PIC.link}"{PIC.rel}><img class="glt-img-thumb" src="{PIC.thumbSmall}" width="{DATA.smallw}" height="{DATA.smallh}" alt="{PIC.title}"/></a>
		</li>
		<!-- END: loop -->
	</ul>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#glt-marquee-{CONFIG.bid}').marquee({
		delayBeforeStart: {CONFIG.delayBeforeStart},
		direction: '{CONFIG.direction}',
		duplicated: {CONFIG.duplicated},
		gap: {CONFIG.gap},
		duration: {CONFIG.duration},
		pauseOnHover: {CONFIG.pauseOnHover},
		pauseOnCycle: {CONFIG.pauseOnCycle},
	});
});
</script>
<!-- END: main -->