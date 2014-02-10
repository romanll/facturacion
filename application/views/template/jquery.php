<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
	var jq_source='<?php echo base_url("libs/jquery/jquery-1.10.2.min.js"); ?>';
	window.jQuery || document.write('<script src="'+jq_source+'"><\/script>')
</script>
<script>
//Fix to up and down key
/*
$(document).keydown(function(event) {
	var derscroll=$("#der").scrollTop();
    if(event.which==40){$('#der').animate({scrollTop: derscroll+100}, 15);}
    if(event.which==38){$('#der').animate({scrollTop: derscroll-100}, 15);}
});
*/
</script>