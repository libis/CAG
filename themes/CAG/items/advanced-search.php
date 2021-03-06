<?php
if (!$isPartial): // If we are using the partial view of this search form.

head(array('title'=>'Beeldbank Geavanceerd Zoeken', 'bodyclass' => 'advanced-search', 'bodyid' => 'advanced-search-page'));
?>
<div id="primary">

	<h1>Beeldbank - Geavanceerd Zoeken</h1>

	<ul class="items-nav navigation" id="secondary-nav">
            <?php //echo custom_nav_items(); ?>
            <?php
            	echo nav(
             		array(
               			'Zoeken' => uri('beeldbank/'),
               			'Kaart' => uri('items/map/')

             		)
           		);
            ?>
	</ul>
<br>
<br>
<script>
jQuery(document).ready(function () {
	var blackListGroups = [
        "Contribution Form"
	];
	var blackListElements = [
        "section_id",
        "exhibit_title",
			"exhibit_description",
			"credits",
			"featured",
			"theme",
			"theme_options",
			"exhibit_slug",
			"exhibit_thumbnail",
			"section_title",
			"sections_description",
			"exhibit_id",
			"section_order",
			"section_slug",
			"section_thumbnail",
			"page_title",
			"section_id",
			"page_slug",
			"layout",
			"page_order",
			"item_id",
			"page_id",
			"item_text",
			"caption",
			"item_order",
			"tags",
			"auteur",
			"To",
			"Zoekterm",
			"Digitool View",
			"Digitool thumbnail",
			"Birth Date",
			"Birthplace",
			"Death Date",
			"Occupation",
			"Biographical Text",
			"Bibliography",
			"Interviewer",
   		"Interviewee",
    		"Location",
   		"Transcription",
    		"Original Format",
    		"Duration",
    		"Bit Rate/Frequency",
    		"Time Summary",
    		"Compression",
    		"CC",
    		"BCC",
    		"Producer",
    		"Director",
    		"Event Type",
			"Participants",
			"Standards",
			"Objectives",
			"Materials",
		   "Lesson Plan Text",
		   "From",
		   "digitool_id",
			"digitool-update1",
			"digitool-update10",
			"digitool-update2",
			"digitool-update3",
			"digitool-update4",
			"digitool-update5",
			"digitool-update6",
			"digitool-update7",
			"digitool-update8",
			"digitool-update9",
			"digitool1",
			"digitool10",
			"digitool2",
			"digitool3",
			"digitool4",
			"digitool5",
			"digitool6",
			"digitool7",
			"digitool8",
			"digitool9",
			"pid",
			"Document",
			"Moving Image",
			"Oral History",
			"Sound",
			"Website",
			"Event",
			"Email",
			"Lesson Plan",
			"Hyperlink",
			"Person",
			"Interactive Resource",
			"Timeline",
			"Exhibit _temp",
			"pids_temp"
	];
	jQuery.each(blackListGroups, function (index, value) {
		jQuery("#advanced-0-element_id optgroup[label='" + value + "']").remove();
	});
	jQuery.each(blackListElements, function (index, value) {
		jQuery("#advanced-0-element_id option[label='" + value + "']").remove();
	});
});
</script>

<?php endif; ?>
<?php if (!$formActionUri): ?>
    <?php $formActionUri = uri(array('controller'=>'items', 'action'=>'browse')); ?>
<?php endif; ?>

<form <?php echo _tag_attributes($formAttributes); ?> action="<?php echo html_escape($formActionUri); ?>" method="get">
	<div id="search-keywords" class="field">
		<?php echo label('keyword-search','Zoek op sleutelwoorden'); ?>
		<div class="inputs">
		<?php echo text(array('name'=>'search','size' => '40','id'=>'keyword-search','class'=>'textinput'),$_REQUEST['search']); ?>
		</div>
	</div>
	<div id="search-narrow-by-fields" class="field">

		<div class="label">Zoek op specifieke velden</div>

			<div class="inputs">
				<?php
				//If the form has been submitted, retain the number of search fields used and rebuild the form
				if(!empty($_GET['advanced'])) {
					$search = $_GET['advanced'];
				}else {
					$search = array(array('field'=>'','type'=>'','value'=>''));
				}

				//Here is where we actually build the search form
				foreach ($search as $i => $rows): ?>
					<div class="search-entry">
					<?php
					//The POST looks like =>
					// advanced[0] =>
						//[field] = 'description'
						//[type] = 'contains'
						//[terms] = 'foobar'
					//etc
					echo select_element(
						array('name'=>"advanced[$i][element_id]"),
						@$rows['element_id'],
						null,
						array('record_types'=>array('Item', 'All'), 'sort'=>'alpha')); ?>

					<?php
						echo Libis_select(
							array('name'=>"advanced[$i][type]"),
							array('contains'=>'bevat', 'does not contain'=>'bevat niet', 'is empty'=>'is leeg', 'is not empty'=>'is niet leeg'),
							@$rows['type']
						);
					?>

					<?php
						echo text(
							array('name'=>"advanced[$i][terms]", 'size'=>20),
							@$rows['terms']);
					?>
					<button type="button" class="remove_search" disabled="disabled" style="display: none;">-</button>
					</div>
				<?php endforeach; ?>
			</div>
			<button type="button" class="add_search">Voeg een veld toe</button>
		</div>

	<!--	<div id="search-by-range" class="field">
		    <label for="range">Zoek op nummer (voorbeeld: 1-4, 156, 79)</label>
			<div class="inputs">
			<?php echo text(
				array('name'=>'range', 'size'=>'40','class'=>'textinput'),
				@$_GET['range']); ?>
				</div>
		</div>  -->

		<div id="search-selects">
	<!--<div class="field"> -->
	<?php //echo label('collection-search', 'Zoek op collectie');
	?>
	<!--<div class="inputs">--><?php //echo select_collection(array('name'=>'collection', 'id'=>'collection-search'), $_REQUEST['collection']);
	?><!--</div>
	</div>-->
	<div class="field">
	<?php echo label('item-type-search', 'Zoek op objecttype'); ?>
	<div class="inputs"><?php echo select_item_type(array('name'=>'type', 'id'=>'item-type-search'), $_REQUEST['type']); ?></div>
	</div>

	<?php //if(has_permission('Users', 'browse')):
	?>
	<!--<div class="field"> -->
	<?php
	    //echo label('user-search', 'Zoek op gebruiker');
	    ?>
	<!--<div class="inputs"><?php //echo select_user(array('name'=>'user', 'id'=>'user-search'), $_REQUEST['user']);
	?></div>
	</div>-->
	<?php //endif;
	?>
	<div class="field">
	<?php echo label('tag-search', 'Zoek op trefwoord'); ?>
	<div class="inputs"><?php echo text(array('name'=>'tags','size' => '40','id'=>'tag-search','class'=>'textinput'),$_REQUEST['tags']); ?></div>
	</div>
	</div>
	<?php //if (has_permission('Items','showNotPublic')):
	?>
	<!--<div class="field"> -->
		<?php //echo label('public','Publiek/Niet-publiek');
		?>
		<!--<div class="inputs">--->
		    <?php //echo select(array('name' => 'public', 'id' => 'public'), array('1' => 'Enkel publiek', '0' => 'Enkel niet-publiek'));
		    ?>
	    <!--</div>
	</div>

	<div class="field">-->
		<?php //echo label('featured','Featured/Non-Featured');
		?>
		<!--<div class="inputs"> -->
		    <?php //echo select(array('name' => 'featured', 'id' => 'featured'), array('1' => 'Enkel "Featured" ', '0' => 'Enkel "Non-Featured"'));
		    ?>
	<!--</div>
	</div> -->
	<?php //endif;
	?>

	<?php is_admin_theme() ? fire_plugin_hook('admin_append_to_advanced_search') : fire_plugin_hook('public_append_to_advanced_search'); ?>
	<div>
	    <input type="submit" class="submit submit-medium" name="submit_search" id="submit_search_advanced" value="Zoek" />
    </div>
</form>

<?php echo js('search'); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        Omeka.Search.activateSearchButtons();
    });
</script>
<?php if (!$isPartial): ?>
    </div> <!-- Close 'primary' div. -->
    <?php foot(); ?>
<?php endif; ?>
