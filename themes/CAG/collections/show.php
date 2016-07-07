<?php
$collectionTitle = strip_formatting(metadata('collection', array('Dublin Core', 'Title')));
if ($collectionTitle == '') {
    $collectionTitle = __('[Untitled]');
}
?>

<?php echo head(array('title'=> $collectionTitle, 'bodyclass' => 'collections show')); ?>

<p id="simple-pages-breadcrumbs">
<a href="/">Home</a> >      
        <a href="/beeldbank">Beeldbank</a> > <a href='<?php echo url('collections');?>'>Collectie-inventarissen</a>
        <?php if($text = metadata('collection', array('Dublin Core','Title'))): ?>
        > <?php echo $text; ?>
        <?php endif;?>        
</p>   

<h1><?php echo $collectionTitle; ?></h1>

<?php if (metadata('collection', array('Dublin Core', 'Title'))): ?>
    <div class="element">
        <h3><?php echo __('Title'); ?></h3>
        <div class="element-text"><?php echo metadata('collection', array('Dublin Core', 'Title')); ?></div>
    </div>
<?php endif; ?>

<?php if (metadata('collection', array('Dublin Core', 'Description'))): ?>
    <div class="element">
        <h3><?php echo __('Description'); ?></h3>
        <div class="element-text"><?php echo text_to_paragraphs(metadata('collection', array('Dublin Core', 'Description'))); ?></div>
    </div>
<?php endif; ?>

<?php if (metadata('collection', array('Dublin Core', 'Creator'))): ?>
    <div class="element">
        <h3><?php echo __('Bewaarinstelling'); ?></h3>
        <div class="element-text"><?php echo text_to_paragraphs(metadata('collection', array('Dublin Core', 'Creator'))); ?></div>
    </div>
<?php endif; ?>

<?php if (metadata('collection', array('Dublin Core', 'Date'))): ?>
    <div class="element">
        <h3><?php echo __('Date'); ?></h3>
        <div class="element-text"><?php echo text_to_paragraphs(metadata('collection', array('Dublin Core', 'Date'))); ?></div>
    </div>
<?php endif; ?>

<div id="collection-items">
    <h2><?php echo __('Selectie objecten in de collectie'); ?></h2>
    <?php if (metadata('collection', 'total_items') > 0): ?>
        <?php foreach (loop('items') as $item): ?>
        <?php $itemTitle = strip_formatting(metadata('item', array('Dublin Core', 'Title'))); ?>
        <div class="item hentry">
            <h3><?php echo link_to_item($itemTitle, array('class'=>'permalink')); ?></h3>
            
            <?php if(digitool_item_has_digitool_url($item)){ ?>
                <div class="image">
                    <?php echo link_to_item(digitool_get_thumb_for_browse($item,'140'));?>                                        
                </div>
            <?php } ?>

            <?php if ($text = metadata('item', array('Item Type Metadata', 'Text'), array('snippet'=>250))): ?>
            <div class="item-description">
                <p><?php echo $text; ?></p>
            </div>
            <?php elseif ($description = metadata('item', array('Dublin Core', 'Description'), array('snippet'=>250))): ?>
            <div class="item-description">
                <?php echo $description; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    
    <a class="link-to-browse" href="<?php echo url('/solr-search/results/?q=&facet=collection:&quot;'.$collectionTitle.'&quot;');?>">Bekijk alle objecten in deze collectie</a>
    <?php else: ?>
        <p><?php echo __("There are currently no items within this collection."); ?></p>
    <?php endif; ?>
        
</div><!-- end collection-items -->

<?php fire_plugin_hook('public_collections_show', array('view' => $this, 'collection' => $collection)); ?>

<?php echo foot(); ?>
