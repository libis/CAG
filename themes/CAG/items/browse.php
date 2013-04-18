<?php head(array('title'=>'Browse Items','bodyid'=>'items','bodyclass' => 'browse')); ?>
<div id="primary">
<h1>Resultaten</h1>

<?php if($_GET['type'] == 'Actor'){?>
<ul class="items-nav navigation" id="secondary-nav">
	<?php
		echo nav(
			array(
				'Zoeken naar actoren' => uri('actoren/')
			)
		);
	?>
</ul>
<?php }else{ ?>

<!--  navigation -->
<ul class="items-nav navigation" id="secondary-nav">
	<?php
		echo nav(
			array(
				'Zoeken' => uri('beeldbank/')
				/*'Kaart' => uri('items/map/')*/

			)
		);
	?>
</ul>
<?php } ?>
<div id="browse-left">
<div id="pagination-top" class="pagination"><?php echo pagination_links(); ?></div>
<br>

<?php
	$actoren = "";
	$beelden = "";
	$werktuigen = "";

	$actor_count=0;
	$beeld_count=0;
	$werk_count=0;

	while (loop_items()):
		if(item_has_type('Object')){

			$beeld_count++;
			$beelden.="<table><tr><th width='150'></th><th='250' ></th></tr>
						<tr><td>";

			if (digitool_thumbnail(get_current_item())):
			$beelden.= link_to_item(digitool_thumbnail(get_current_item()));
			endif;
			$beelden.="</td><td><table><th width='120'></th><th></th>";
			if(item('Dublin Core','Title'))
			$beelden.="<tr><td><strong>Titel:</strong></td><td>".link_to_item(item('Dublin Core','Title'))."</td></tr>";
			if(item('Dublin Core','Publisher'))
			$beelden.="<tr><td><strong>Naam Instelling:</strong></td><td>".item('Dublin Core','Publisher')."</td></tr>";
			if(item('Dublin Core','Identifier')){
				$identifier = item('Dublin Core','Identifier');
				$beelden.="<tr><td><strong>Nummer:</strong></td><td>".$identifier."</td></tr>";
			}
			if(item('Dublin Core','Type'))
			$beelden.="<tr><td><strong>Objectcategorie:</strong></td><td>".item('Dublin Core','Type')."</td></tr>";
			$beelden.="</table></td>
						</tr></table>";

			if (item_has_tags()):
			$beelden .="<div class='tags'><p><strong>Trefwoorden:</strong>
								".item_tags_as_string()."</p></div>";
			endif;

			echo plugin_append_to_items_browse_each();

		};//endif


		//check 'Actor'
		if(item_has_type('Actor')){
			$actor_count++;
			$actoren.="
						<table><tr><td>";
			//if (item_has_thumbnail()):
				//	 $actoren.= link_to_item(item_square_thumbnail());
			//endif;
			if (digitool_thumbnail(get_current_item())):
				 $actoren.= link_to_item(digitool_thumbnail(get_current_item()));
			endif;
			$actoren.= "</td><td><table>";
			if(item('Item Type Metadata','Naam instelling'))
				$actoren.= "<tr><td><strong>Naam Instelling:</strong></td><td>".item('Item Type Metadata','Naam instelling')."</td></tr>";
			if(item('Item Type Metadata','Straat + Nr'))
				$actoren.= "<tr><td><strong>Straat + Nr:</strong></td><td>".item('Item Type Metadata','Straat + Nr')."</td></tr>";
			if(item('Item Type Metadata','Postcode'))
				$actoren.= "<tr><td><strong>Postcode:</strong></td><td>".item('Item Type Metadata','Postcode')."</td></tr>";
			if(item('Item Type Metadata','Stad'))
				$actoren.= "<tr><td><strong>Stad:</strong></td><td>".item('Item Type Metadata','Stad')."</td></tr>";
			if(item('Item Type Metadata','Provincie'))
				$actoren.= "<tr><td><strong>Provincie:</strong></td><td>".item('Item Type Metadata','Provincie')."</td></tr>";
			if(item('Item Type Metadata','Telefoon'))
				$actoren.= "<tr><td><strong>Telefoon:</strong></td><td>".item('Item Type Metadata','Telefoon')."</td></tr>";
			if(item('Item Type Metadata','Fax'))
				$actoren.= "<tr><td><strong>Fax:</strong></td><td>".item('Item Type Metadata','Fax')."</td></tr>";
			if(item('Item Type Metadata','Website'))
				$actoren.= "<tr><td><strong>Website:</strong></td><td>".item('Item Type Metadata','Website')."</td></tr>";
			if(item('Item Type Metadata','E-mail'))
				$actoren.= "<tr><td><strong>E-mail:</strong></td><td>".item('Item Type Metadata','E-mail')."</td></tr>";

			if (item_has_tags()):
				$actoren .="<div class='tags'><p><strong>Trefwoorden:</strong>
							".item_tags_as_string()."</p></div>";
		endif;
		$actoren .="</table>"
					.plugin_append_to_items_browse_each().
					"</td></tr></table>";

	};//endif instelling/actoren

	//if item-type is 'uitgelicht'
	if(item_has_type('Concept')){
		$werk_count++;
		$werktuigen.="
					<table><tr><td>";
		if (digitool_thumbnail(get_current_item())):
			$werktuigen.= link_to_item(digitool_thumbnail(get_current_item()));
		endif;
		$werktuigen .="</td><td><table>
					 <tr><td><strong>
					 ".link_to_item(item('Dublin Core','Title'))."
					 </strong></td></tr>
					 <tr><td>".item('Item Type Metadata','Scope')."</td></tr>
				     ";

		$werktuigen .="</table>"
					.plugin_append_to_items_browse_each().
					"</td></tr></table>";


	};//endif werktuigen/uitgelicht


	endwhile;
	$display = '';

	?>
	<?php if($beeld_count != 0){
		?>
		<h2 class="toggle beeld">Beelden (<?php /*echo $beeld_count;*/echo total_results();?>)</h2>
		<div class='item hentry beeld-list' <?php echo $display;?>>
		<?php $display='style="display:none;"'; ?>
		<div class='item-meta'>
		<div class='item-description'> <?php echo $beelden;?> </div></div></div>
	<?php } if($actor_count != 0){?>
		<h2 class="toggle actor">Actoren (<?php echo $actor_count;?>)</h2>

		<div class='item hentry actor-list'  <?php echo $display;?>>
		<?php $display='style="display:none;"'; ?>
		<div class='item-meta'>
		<div class='item-description'><?php echo $actoren;?></div></div></div>

	<?php } if($werk_count != 0){?>
		<h2 class="toggle werktuig">Werktuigen (<?php echo $werk_count;?>)</h2>
		<div class='item hentry werktuig-list'  <?php echo $display;?>>
		<div class='item-meta'>
		<div class='item-description'><?php echo $werktuigen;?></div></div></div>
	<?php }
	if($_GET['type']=='Actor'){
		// Sam: {} toegevoegd bij $actor_count==0 om niet altijd Er werden geen actoren gevonden te tonen
		if($actor_count==0) {
		?>	<p>Er werden geen actoren gevonden.</p> <?php }
	}else{
		if($actor_count==0 && $beeld_count==0 && $werk_count==0){
			?>	<p>Er werden geen resultaten gevonden.</p> <?php }
	}
	?>



	<div id="pagination-bottom" class="pagination"><?php echo pagination_links(); ?></div>
	</div>
	<div id="browse-right">
		<?php

		echo plugin_append_to_items_browse(); ?>
	</div>

	<script>

	jQuery(".beeld").click(function () {
		jQuery(".beeld-list").toggle();
	});

	jQuery(".actor").click(function () {
		jQuery(".actor-list").toggle();
	});

	jQuery(".werktuig").click(function () {
		jQuery(".werktuig-list").toggle();
	});
	</script>


</div>
<?php foot(); ?>