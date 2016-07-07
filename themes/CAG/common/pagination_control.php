<?php if ($this->pageCount > 1): ?>
<ul class="pagination_list">
    
    <?php if ($this->first != $this->current): ?>
    <!-- First page link --> 
    <li class="pagination_first">
    <a href="<?php echo html_escape($this->url(array('page' => $this->first), null, $_GET)); ?>"><<</a>
    </li>
    <?php endif; ?>
    
    <?php if (isset($this->previous)): ?>
    <!-- Previous page link --> 
    <li class="pagination_previous">
    <a href="<?php echo html_escape($this->url(array('page' => $this->previous), null, $_GET)); ?>"><</a>
    </li>
    <?php endif;?>    
    
    <!-- Numbered page links -->
    <li class="pagination_range"><?php echo "Pagina ".$this->current." van ".$this->lastPageInRange; ?></li>
    
    <?php if (isset($this->next)): ?> 
    <!-- Next page link -->
    <li class="pagination_next">
    <a href="<?php echo html_escape($this->url(array('page' => $this->next), null, $_GET)); ?>">></a>
    </li>
    <?php endif; ?>
    
    <?php if ($this->last != $this->current): ?>
    <!-- Last page link --> 
    <li class="pagination_last">
    <a href="<?php echo html_escape($this->url(array('page' => $this->last), null, $_GET)); ?>">>></a>
    </li>
    <?php endif; ?>
</ul>
<?php endif; ?>

