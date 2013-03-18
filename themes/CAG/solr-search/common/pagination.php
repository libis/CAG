<?php if($this->pageCount > 1): ?>

    
    <div class="resultNav">
	<ul class="listNav">
	<?php $array = Zend_Registry::get('pagination');?>
		  <li><a href="<?php echo html_escape($this->url(array('page' => 0), null, $_GET)); ?>"><<</a></li>
          <li><a href="<?php echo html_escape($this->url(array('page' => $this->previous), null, $_GET)); ?>"><</a></li>
          <li>Pagina <?php echo $array['page'];?>  van <?php echo $this->pageCount;?></li>
          <li><a href="<?php echo html_escape($this->url(array('page' => $this->next), null, $_GET)); ?>">></a></li>
          <li><a href="<?php echo html_escape($this->url(array('page' => $this->pageCount), null, $_GET)); ?>">>></a></li>
	</ul>
    </div>    

<?php endif; ?>

