<?php 

$bodyclass = 'page simple-page';
if (simple_pages_is_home_page(get_current_simple_page())) {
    $bodyclass .= ' simple-page-home';
} ?>

<?php head(array('title' => html_escape(simple_page('title')), 'bodyclass' => $bodyclass, 'bodyid' => html_escape(simple_page('slug')))); ?>

<div id="primary">
	<?php if(simple_page('title') != "Home" && simple_page('title') != "Beeldbank" && simple_page('title') != "Werktuigen" && simple_page('title') != "Alle verhalen"){ ?>
	<div id="nav-left">
		
		<?php 
			if(Libis_get_simple_pages_nav())
				echo Libis_get_simple_pages_nav();
		?>
	</div>	
	<?php } ?>
	<div id="page">
	    <?php if (!simple_pages_is_home_page(get_current_simple_page())): ?>
	    <p id="simple-pages-breadcrumbs"><?php echo simple_pages_display_breadcrumbs(); ?></p>
	    <?php endif; ?>
	    <h1><?php echo html_escape(simple_page('title')); ?></h1>
	    <?php echo eval('?>' . simple_page('text')); ?>
    </div>
</div>
<?php if (!simple_pages_is_home_page(get_current_simple_page())): ?>

<?php endif; ?>

<?php echo foot(); ?>