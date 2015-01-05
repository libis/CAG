<?php head(array('title'=>'Beeldbank','bodyid'=>'items','bodyclass'=>'tags')); ?>



	<h1>Beeldbank</h1>

	<ul class="items-nav navigation" id="secondary-nav">
            <?php //echo custom_nav_items(); ?>
            <?php
            	echo nav(
             		array(
               			'Zoeken' => uri('beeldbank/'),
               			'Kaart' => uri('items/map/'),

             		)
           		);
            ?>
	</ul>

	<div id="search-beeldbank">	<p>
					<?php echo simple_search(); ?>
					<?php echo link_to_advanced_search(); ?>
					</p>
	</div>

	<?php //echo tag_cloud($tags,uri('items/browse')); ?>



<?php foot(); ?>