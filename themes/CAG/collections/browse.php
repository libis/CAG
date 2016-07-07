<?php
$pageTitle = __('Collectie-inventarissen');
echo head(array('title'=>$pageTitle,'bodyclass' => 'Collectie-inventarissen'));
?>

<p id="simple-pages-breadcrumbs">
<a href="/">Home</a> > 

     
        <a href="/beeldbank">Beeldbank</a> > <a href='<?php echo url('collections');?>'>Collectie-inventarissen</a>
        
        
</p>   

<h1><?php echo $pageTitle; ?></h1>

<?php echo pagination_links(); ?>

<?php foreach (loop('collections') as $collection): ?>

<div class="collection">

    <h2><?php echo link_to_collection(); ?></h2>

    <?php if (metadata('collection', array('Dublin Core', 'Description'))): ?>
    <div class="element">
        <h3><?php echo __('Description'); ?></h3>
        <div class="element-text"><?php echo text_to_paragraphs(metadata('collection', array('Dublin Core', 'Description'))); ?></div>
    </div>
    <?php endif; ?>

    <?php if ($collection->hasContributor()): ?>
    <div class="element">
        <h3><?php echo __('Contributors'); ?></h3>
        <div class="element-text">
            <p><?php echo metadata('collection', array('Dublin Core', 'Contributor'), array('all'=>true, 'delimiter'=>', ')); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <p class="view-items-link"><a class="link-to-browse" href="<?php echo url('/solr-search/results/?q=&facet=collection:&quot;'.metadata('collection', array('Dublin Core', 'Title')).'&quot;');?>">Bekijk de collectie</a></p>

    <?php fire_plugin_hook('public_collections_browse_each', array('view' => $this, 'collection' => $collection)); ?>

</div><!-- end class="collection" -->

<?php endforeach; ?>

<?php echo pagination_links(); ?>

<?php fire_plugin_hook('public_collections_browse', array('collections'=>$collections, 'view' => $this)); ?>

<?php echo foot(); ?>
