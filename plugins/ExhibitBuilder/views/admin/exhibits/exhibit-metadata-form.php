<form id="exhibit-metadata-form" method="post" class="exhibit-builder">
    <div class="seven columns alpha">
    <fieldset>
        <legend><?php echo __('Exhibit Metadata'); ?></legend>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('title', __('Title')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formText('title', $exhibit->title); ?>
            </div>
        </div>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('slug', __('Slug')); ?>
            </div>
            <div class="five columns omega inputs">
                <p class="explanation"><?php echo __('No spaces or special characters allowed'); ?></p>
                <?php echo $this->formText('slug', $exhibit->slug); ?>
            </div>
        </div>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('credits', __('Credits')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formText('credits', $exhibit->credits); ?>
            </div>
        </div>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('description', __('Description')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php echo $this->formTextarea('description', $exhibit->description, array('rows'=>'8','cols'=>'40')); ?>
            </div>
        </div>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('tags', __('Tags')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php $exhibitTagList = join(', ', pluck('name', $exhibit->Tags)); ?>
                <?php echo $this->formText('tags', $exhibitTagList); ?>
            </div>
        </div>
        <div class="field">
            <div class="two columns alpha">
                <?php echo $this->formLabel('theme', __('Theme')); ?>
            </div>
            <div class="five columns omega inputs">
                <?php $values = array('' => __('Current Public Theme')) + exhibit_builder_get_themes(); ?>
                <?php echo get_view()->formSelect('theme', $exhibit->theme, array(), $values); ?>
                <?php if ($theme && $theme->hasConfig): ?>
                    <a href="<?php echo html_escape(url("exhibits/theme-config/$exhibit->id")); ?>" class="configure-button button"><?php echo __('Configure'); ?></a>
                <?php endif;?>
            </div>
        </div>
        
        <div class="field">
            <label>Thumbnail</label>
            <?php
            	if($exhibit->thumbnail){
            		echo "<img width='150' src='".WEB_PUBLIC_THEME."/CAG/images/verhalen_thumbs/".$exhibit->thumbnail."'><br>Choose another thumbnail  ";
            	}
            ?>
            <style>
            	.exhibit-thumbs-list li{
                    display:inline;
                    padding: 0 0px;
            	}

            	.exhibit-thumbs-list img{
                    padding:5px 5px;
                    width:90px;
            	}
            </style>
            <br>
                <ul class="exhibit-thumbs-list">
                    <?php
                    $folder=dir("../themes/CAG/images/verhalen_thumbs");

                    while($folderEntry=$folder->read())
                    {
                            if($folderEntry == $exhibit->thumbnail)
                                    echo "<li><input selected='selected' type='radio' name='thumbnail' value='".$folderEntry."' /> <img width='150' src='".WEB_PUBLIC_THEME."/CAG/images/verhalen_thumbs/".$folderEntry."'></li>";

                            else
                                    if($folderEntry != '.' && $folderEntry != '..')
                                            echo "<li><input type='radio' name='thumbnail' value='".$folderEntry."' /><img width='150' src='".WEB_PUBLIC_THEME."/CAG/images/verhalen_thumbs/".$folderEntry."'></li>";
                    }

                    $folder->close();
                    ?>
                </ul>
            </div>
        
        
    </fieldset>
    <fieldset>
        <legend><?php echo __('Pages'); ?></legend>
        <div id="pages-list-container">
            <?php if (!$exhibit->TopPages): ?>
                <p><?php echo __('There are no pages.'); ?></p>
            <?php else: ?>
                <p id="reorder-instructions"><?php echo __('To reorder pages, click and drag the page up or down to the preferred location.'); ?></p>
                <?php echo common('page-list', array('exhibit' => $exhibit), 'exhibits'); ?>
            <?php endif; ?>
        </div>
        <div id="page-add">
            <input type="submit" name="add_page" id="add-page" value="<?php echo __('Add Page'); ?>" />
        </div>
    </fieldset>
    </div>
    <div id="save" class="three columns omega panel">
        <?php echo $this->formSubmit('save_exhibit', __('Save Changes'), array('class'=>'submit big green button')); ?>
        <?php if ($exhibit->exists()): ?>
            <?php echo exhibit_builder_link_to_exhibit($exhibit, __('View Public Page'), array('class' => 'big blue button', 'target' => '_blank')); ?>
            <?php echo link_to($exhibit, 'delete-confirm', __('Delete'), array('class' => 'big red button delete-confirm')); ?>
        <?php endif; ?>
        <div id="public-featured">
            <div class="public">
                <label for="public"><?php echo __('Public'); ?>:</label> 
                <?php echo $this->formCheckbox('public', $exhibit->public, array(), array('1', '0')); ?>
            </div>
            <div class="featured">
                <label for="featured"><?php echo __('Featured'); ?>:</label> 
                <?php echo $this->formCheckbox('featured', $exhibit->featured, array(), array('1', '0')); ?>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript" charset="utf-8">
//<![CDATA[
    jQuery(window).load(function() {
        Omeka.ExhibitBuilder.wysiwyg();
    });
//]]>
</script>
