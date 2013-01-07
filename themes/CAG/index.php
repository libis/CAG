<?php head(array('bodyid'=>'home', 'bodyclass' =>'two-col')); ?>

<div id="frontpage">
    <?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    <p><?php echo $homepageText; ?></p>

    <?php endif; ?>

  	<div id="carousel-block">



	<!-- Recente Verhalen -->

    <div id="item-block">
        <h2>Recente Verhalen</h2>
        <?php
        //get the 10 latest exhibits
        $exhibits = exhibit_builder_recent_exhibits(10); ?>

		<table>
        <tr class="block-tr"><td class="block-td">
		<div id="carousel-container">

			<div class="carouselclass" id="carousel2">
				<?php foreach($exhibits as $exhibit) {

					if (Libis_get_exhibit_thumb($exhibit)):?>
						<div class="carousel-feature">
							<?php echo exhibit_builder_link_to_exhibit($exhibit,LIBIS_get_exhibit_thumb($exhibit,array('class'=>'thumbnail carousel-image','height'=>'150','width'=>'150'))); ?>
						</div>
					<?php endif;?>
				<?php } ?>
		</div>

    </div>

		</td>
		<td>
			<p>Description of the slideshow</p>
		</td>
	</tr>
	</table>

	</div>

	<!-- Random Items -->
    <div id="item-block">
        <h2>Recente objecten</h2>

		</td>
		<td>
			<!-- TEST CAROUSEL -->
    <?php
    $items = Libis_get_random_featured_items('10');
    set_items_for_loop($items);
 	if (has_items_for_loop()): ?>
    <table>
        <tr class="block-tr"><td class="block-td">
    <div id="carousel-container">

		<div class="carouselclass" id="carousel3">
        <?php while (loop_items()):

			if (item_has_thumbnail()):?>
			<div class="carousel-feature">
				<?php echo link_to_item(item_square_thumbnail(array('alt'=>item('Dublin Core', 'Title'),'class'=>'thumbnail carousel-image','height'=>'150','width'=>'150'))); ?>
			</div>
	        <?php
	        endif;?>
        <?php endwhile; ?>
		</div>

    </div>
	 <?php endif;?>
    <!-- END TEST CAROUSEL -->
		</td>
		<td>
			<p>Description of the slideshow</p>
		</td>
	</tr></table>
	</div>


  </div>

<div id="wegwijs">
    <a href="wegwijs/"/><img alt="Wegwijs op deze website" src="<?php echo (img('wegwijs.gif'));?>" width="155" height="100"></a>
</div>




<?php foot(); ?>