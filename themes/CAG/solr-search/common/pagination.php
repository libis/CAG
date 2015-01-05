<?php if($this->pageCount > 1): ?>

    
    <div class="resultNav">
	<ul class="listNav">
	<?php $array = Zend_Registry::get('pagination');?>
          <li><a href="<?php echo html_escape($this->url("solr-search/results/index/page/0", $_GET)); ?>"><<</a></li>
          <li><a href="<?php echo html_escape($this->url("solr-search/results/index/page/".$this->previous, $_GET)); ?>"><</a></li>
          <li>Pagina <?php echo $array['page'];?>  van <?php echo $this->pageCount;?></li>
          <li><a href="<?php echo html_escape($this->url("solr-search/results/index/page/".$this->next, $_GET)); ?>">></a></li>
                           
          <li><a href="<?php echo html_escape($this->url("solr-search/results/index/page/".$this->pageCount,$_GET)); ?>">>></a></li>
	</ul>
    </div>    

<?php endif; ?>

