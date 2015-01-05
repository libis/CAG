<!-- Text Area (where you adjust the AddThis button) -->
<div class="field">
	<label for="addThis_script">Paste the 'AddThis Button'code in this box</label>
	<div class="inputs">
	<textarea name="addThis_script" rows="20" cols="60">
	<?php if(get_option('addThis_script')=='default'){?>
	 	<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style ">
		<a class="addthis_button_preferred_1"></a>
		<a class="addthis_button_preferred_2"></a>
		<a class="addthis_button_preferred_3"></a>
		<a class="addthis_button_preferred_4"></a>
		<a class="addthis_button_compact"></a>
		<a class="addthis_counter addthis_bubble_style"></a>
		</div>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4de3a8704c741536"></script>
		<!-- AddThis Button END -->
	<?php }
	else{
		echo (get_option('addThis_script')); 
	}
	?>	
	</textarea>
 <p class="explanation">Simply follow <a href='https://www.addthis.com/get-addthis?where=website&type=bm&clickbacks=1&frm=home&analytics=0&bm=tb14#.TezBX1uRMvY'>this link</a> to get your custom 'AddThis Button' code.</p> 
</div></div>
