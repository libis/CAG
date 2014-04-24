<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

  $pageTitle = __('Browse Items'); //TODO: Should this be browse items?
  echo head(array('title' => $pageTitle, 'id' => 'items', 'bodyclass' => 'browse'));
  $view='default';
  $session = new Zend_Session_Namespace('pagination_help');
  $per_page = $session->per_page;
  
  $itemids ='';
  //uitbreiding solr-geolocation
  $session->items = "";
  $session->locations= "";
  $session->from= ''; 
?>
<script>
jQuery(document).ready(function() {

  jQuery('.solr_facets .facet').addClass('clicker').click(function() {
    jQuery(this).toggleClass('active');
    jQuery(this).next().toggle();
    return false;
  }).next().hide();

});
</script>
<div id="primary" class="solr_results results">
                  
    <div class="topresults">
        <div class="resultCount"><?php echo __('%s resultaten', $results->response->numFound); ?></div>
        <?php echo pagination_links(array('partial_file' => 'common/pagination.php','per_page'=>$per_page)); ?> 
        
        <div class="resultsPerPage">
            <form action='<?php echo libis_curPageURL();?>' method="post">
                <select name="perPage" onchange="this.form.submit()">
                  <option <?php if($per_page==10){echo 'selected="selected"';}?> value="10">10 resultaten per pagina</option>
                  <option <?php if($per_page==20){echo 'selected="selected"';}?> value="20">20 resultaten per pagina</option>
                  <option <?php if($per_page==50){echo 'selected="selected"';}?> value="50">50 resultaten per pagina</option>                  
               
                </select>
            </form>
        </div>
        
           
    </div>        
    <div id="solr_results" class="item-list">

        <?php //$query = SolrSearch_QueryHelpers::getParams(); ?>

        <div class="solr_facets_container">
            <div id="solr_search" class="search solr_remove_facets"></div>
             <!-- Facets. -->
            <div class="solr_facets">

                <?php foreach ($results->facet_counts->facet_fields as $name => $facets): ?>

                <!-- Does the facet have any hits? -->
                <?php if (count(get_object_vars($facets))): ?>

                  <!-- Facet label. -->
                  <?php $label = SolrSearch_Helpers_Facet::nameTolabel($name); ?>
                  <h4 class="facet"><?php echo $label; ?></h4>

                  <ul style="display: none;">
                    <!-- Facets. -->
                    <?php foreach ($facets as $value => $count): ?>
                      <li class="<?php echo $value; ?>">

                        <!-- Facet link. -->
                        <?php $url = SolrSearch_Helpers_Facet::addFacet($name, $value); ?>
                        <a href="<?php echo $url; ?>" class="facet-value">
                          <?php echo $value; ?>
                        </a>

                        <!-- Facet count. -->
                        (<span class="facet-count"><?php echo $count; ?></span>)

                      </li>
                    <?php endforeach; ?>
                  </ul>

                <?php endif; ?>

              <?php endforeach; ?>
            </div>
              
          
        </div>
      
        <div id="results">              
            <div id="appliedParams">
                <!-- Get the applied facets. -->
                <?php foreach (SolrSearch_Helpers_Facet::parseFacets() as $f): ?>               
                  <span class="appliedFilter">

                    <!-- Facet label. -->
                    <?php $label = SolrSearch_Helpers_Facet::nameToLabel($f[0]); ?>
                    <span class="filterName"><?php echo $label; ?></span>
                    <span class="filterValue"><?php echo $f[1]; ?></span>

                    <!-- Remove link. -->
                    <?php $url = SolrSearch_Helpers_Facet::removeFacet($f[0], $f[1]); ?>
                    <a class="btnRemove imgReplace" href="<?php echo $url; ?>">remove</a>

                  </span>
                <?php endforeach; ?>

              
                <?php //echo SolrSearch_QueryHelpers::removeFacets(); ?>
            </div>
            
            
            <?php 
            //KIES VIEW
            if(isset($_GET['facet'])){
                $facet = $_GET['facet'];
                
                if(strpos($facet,'itemtype:("Nieuwsbericht" OR "Agendapunt")') !== false) {
                    $view = 'nieuws';
                }
                if(strpos($facet,'itemtype:"Publicatie"') !== false) {
                    $view = 'publicatie';
                }
                if(strpos($facet,'itemtype:"Project"') !== false) {
                    $view = 'project';
                }                    
            }            
            
            //VIEW = NIEUWS EN AGENDA
            if($view=='nieuws'){
                $featured="";$nieuws ="";$agenda="";
                foreach($results->response->docs as $doc):
                   $item = get_record_by_id('item',preg_replace ( '/[^0-9]/', '', $doc->__get('id'))); 

                   if($item->getItemType()->name == 'Nieuwsbericht' ||$item->getItemType()->name == 'Agendapunt'){
                       $html = "<div class='in_de_kijker' id='solr_".$doc->__get('id')."'>";                        
                       if($item->hasThumbnail()):
                           $html .= link_to_item(item_image('square_thumbnail', array('width'=>'80'), 0, $item), array('class' => 'item-thumbnail'), 'show', $item);
                       endif;
                       $html .= "<h4>".metadata($item,array('Dublin Core','Title'))."</h4>
                       <p>".metadata($item,array('Dublin Core','Description'),array('snippet'=>50))."</p> </div>";

                       if($item->featured==1){$featured .= $html;}
                       else{
                           if($item->getItemType()->name == 'Nieuwsbericht'){$nieuws .=$html;}
                           if($item->getItemType()->name == 'Agendapunt'){$agenda .=$html;}
                       }
                   }
                endforeach;
                
                if($featured){
                    echo "<div class='nieuws-kolom'><h2>In de kijker</h2>".$featured."</div>";
                }
                if($nieuws){
                    echo "<div class='nieuws-kolom'><h2>Nieuws</h2>".$nieuws."</div>";
                }
                if($agenda){
                    echo "<div class='nieuws-kolom'><h2>Agenda</h2>".$agenda."</div>";
                }
            }
            
            //VIEW = PUBLICATIE (PUBLICATIE, PROJECT)
            if($view=='publicatie' || $view=='project'){
                echo '<div id="info"></div>';
                if($view == 'publicatie'){?>                    
                    <script>jQuery("#info").load('<?php echo url('info');?> #publicatie');</script>
                <?php }
                if($view == 'project'){?>
                    <script>jQuery("#info").load('<?php echo url('info');?> #project');</script>
                <?php } ?>  
                <table id='publicatie-tabel'><tr>
                <?php        
                $side = 'left';
                foreach($results->response->docs as $doc):
                   $item = get_record_by_id('item',preg_replace ( '/[^0-9]/', '', $doc->__get('id'))); 

                   if($item->getItemType()->name == 'Publicatie' ||$item->getItemType()->name == 'Project'){
                       $html = "<td><div class='publicatie' id='solr_".$doc->__get('id')."'>";                        
                       if($item->hasThumbnail()):
                           $html .= link_to_item(item_image('square_thumbnail', array('width'=>'80'), 0, $item), array('class' => 'item-thumbnail'), 'show', $item);
                       endif;
                       $html .= "<div class='publicatie-text'><h4>".metadata($item,array('Dublin Core','Title'))."</h4>
                       <p>".metadata($item,array('Dublin Core','Description'),array('snippet'=>50))."</p> </div></div></td>";
                      
                       if($side == 'left'){
                           $side = 'right';
                       }else{
                           $side = 'left';
                           $html .= "</tr><tr>";
                       }
                       echo $html;
                   }
                endforeach;
                echo "</tr></table>";
            }
                
            //VIEW = DEFAULT (OBJECT, COLLECTIE, CONCEPT)
            if($view=='default'){
                foreach($results->response->docs as $doc):                   
                    $item = get_record_by_id('item',preg_replace ( '/[^0-9]/', '', $doc->__get('id')));                                         
                ?>
                <div class="item" id="solr_<?php echo $doc->__get('id'); ?>">
                    <div class="details">
                        <div class='resultbody'>                  
                            <div class='textfields'>

                            <?php
                            if($item){
                                    set_current_record('item',$item); 
                                    $itemids[] = $item->id;?>
                                <?php if(digitool_item_has_digitool_url($item)){ ?>
                                    <div class="image">
                                        <?php echo link_to_item(digitool_get_thumb_for_browse($item,'140'));?>
                                        <?php //echo SolrSearch_ViewHelpers::createResultImgHtml($image, SolrSearch_ViewHelpers::getDocTitle($doc)); ?>
                                    </div>
                                <?php } ?>

                                <!-- OBJECT -->
                                <?php if($item->getItemType()->name == 'Object'){?>

                                    <div class="title">
                                        <?php $beelden="<table width='300'><th width='120'></th><th></th>";
                                        if(metadata($item,array('Dublin Core','Title')))
                                          $beelden.="<tr><td><strong>Titel:</strong></td><td>".link_to_item(ucfirst(metadata($item,array('Dublin Core','Title'))))."</td></tr>";
                                        
                                        if(metadata($item,array('Dublin Core','Identifier'))){
                                            $identifier = metadata($item,array('Dublin Core','Identifier'));
                                            $beelden.="<tr><td><strong>Nummer:</strong></td><td>".$identifier."</td></tr>";
                                        }
                                        
                                        if(metadata($item,array('Item Type Metadata','Objectnaam')))
                                            $beelden.="<tr><td><strong>Objectnaam:</strong></td><td>".link_to_item(ucfirst(metadata($item,array('Item Type Metadata','Objectnaam'))))."</td></tr>";
                                        
                                        if(metadata($item,array('Dublin Core','Publisher')))
                                            $beelden.="<tr><td><strong>Naam Instelling:</strong></td><td>".metadata($item,array('Dublin Core','Publisher'))."</td></tr>";
                                       
                                        if(metadata($item,array('Dublin Core','Type')))
                                            $beelden.="<tr><td><strong>Objectcategorie:</strong></td><td>".metadata($item,array('Dublin Core','Type'))."</td></tr>";
                                        
                                        if(metadata($item,array('Dublin Core','Description')))
                                            $beelden.="<tr><td><strong>Beschrijving:</strong></td><td>".metadata($item,array('Dublin Core','Description'),array('snippet'=>200))."</td></tr>";
                                       
                                        
                                        $beelden.="</table></td></tr></table>";
                                        
                                        echo $beelden;
                                        ?>
                                    </div>
                                <?php } ?>

                                <!-- Collectie -->
                                <?php if($item->getItemType()->name == 'Collectie'){                                                   
                                    if(digitool_item_has_digitool_url($item)){ ?>
                                    <div class="image">
                                        <?php echo link_to_item(digitool_get_thumb_for_browse($item,'140'));?>
                                        <?php //echo SolrSearch_ViewHelpers::createResultImgHtml($image, SolrSearch_ViewHelpers::getDocTitle($doc)); ?>
                                    </div>
                                    <?php }
                                    $actoren= "<table><th width='120'></th><th></th>";

                                    if(metadata($item,array('Item Type Metadata','Naam instelling')))
                                        $actoren.= "<tr><td><strong>Naam Instelling:</strong></td><td>".metadata($item,array('Item Type Metadata','Naam instelling'))."</td></tr>";
                                    if(metadata($item,array('Item Type Metadata','Straat + Nr')))
                                        $actoren.= "<tr><td><strong>Straat + Nr:</strong></td><td>".metadata($item,array('Item Type Metadata','Straat + Nr'))."</td></tr>";
                                    if(metadata($item,array('Item Type Metadata','Postcode')))
                                        $actoren.= "<tr><td><strong>Postcode:</strong></td><td>".metadata($item,array('Item Type Metadata','Postcode'))."</td></tr>";
                                    if(metadata($item,array('Item Type Metadata','Stad')))
                                        $actoren.= "<tr><td><strong>Stad:</strong></td><td>".metadata($item,array('Item Type Metadata','Stad'))."</td></tr>";
                                    if(metadata($item,array('Item Type Metadata','Provincie')))
                                        $actoren.= "<tr><td><strong>Provincie:</strong></td><td>".metadata($item,array('Item Type Metadata','Provincie'))."</td></tr>";
                                    if(metadata($item,array('Item Type Metadata','Telefoon')))
                                        $actoren.= "<tr><td><strong>Telefoon:</strong></td><td>".metadata($item,array('Item Type Metadata','Telefoon'))."</td></tr>";
                                    if(metadata($item,array('Item Type Metadata','Fax')))
                                        $actoren.= "<tr><td><strong>Fax:</strong></td><td>".metadata($item,array('Item Type Metadata','Fax'))."</td></tr>";
                                    if(metadata($item,array('Item Type Metadata','Website')))
                                        $actoren.= "<tr><td><strong>Website:</strong></td><td>".metadata($item,array('Item Type Metadata','Website'))."</td></tr>";
                                    if(metadata($item,array('Item Type Metadata','E-mail')))
                                        $actoren.= "<tr><td><strong>E-mail:</strong></td><td>".metadata($item,array('Item Type Metadata','E-mail'))."</td></tr>";
                                    if(metadata($item,array('Dublin Core','Description')))
                                           $actoren.="<tr><td><strong>Beschrijving:</strong></td><td>".metadata($item,array('Dublin Core','Description'),array('snippet'=>200))."</td></tr>";
                                       
                                    $actoren .="</table>";//.plugin_append_to_items_browse_each();
                                ?>
                                    <div class="title"><?php echo $actoren; ?></div>
                                <?php }?>

                                <!-- concept -->
                                <?php if($item->getItemType()->name == 'Concept'){?>
                                    <div class="title">
                                    <?php $werktuigen ="<table><tr><td><strong>
                                                 ".link_to_item(metadata($item,array('Dublin Core','Title')))."
                                                 </strong></td></tr>
                                                 <tr><td>".metadata($item,array('Item Type Metadata','Scope'))."</td></tr></table>";

                                    $werktuigen .= plugin_append_to_items_browse_each();
                                    echo $werktuigen;?>
                                    </div>
                                <?php } ?>

                                <?php $tags = $doc->__get('tag');?>
                                <?php if($tags){ ?>
                                    <div class="tags">
                                        <strong>Trefwoorden:</strong>
                                        <?php 
                                        if(is_array($tags)){
                                            foreach($tags as $tag){
                                                echo "<a href='".libis_curPageURL()."AND tag:\"".$tag."\"'>".$tag."</a>";
                                                if ($tag !== end($tags))
                                                    echo ', ';
                                            }
                                        }else{
                                            echo "<a href='".libis_curPageURL()."AND tag:\"".$tags."\"'>".$tags."</a>";
                                        }?>    
                                    </div>
                                <?php } ?>    

                                <? }else{ ?>                      
                                    <div class="title">
                                        <h3><a href="<?php echo $doc->url; ?>" class="result-title">
                                        <?php echo is_array($doc->title) ? $doc->title[0] : $doc->title; ?>
                                      </a></h3>
                                    </div>
                                    <span class="result-type">(<?php echo $doc->resulttype; ?>)</span>
                                    <div class='resultbody'>
                                    <!-- Highlighting. -->
                                    <?php if (get_option('solr_search_hl') == 'true'): ?>
                                      <ul class="hl">
                                        <?php foreach($results->highlighting->{$doc->id} as $field): ?>
                                          <?php foreach($field as $hl): ?>
                                            <li class="snippet"><?php echo strip_tags($hl, '<em>'); ?></li>
                                          <?php endforeach; ?>
                                        <?php endforeach; ?>
                                      </ul>
                                    <?php endif; ?>

                                    <div class='textfields'>    
                                        <?php $tags = $doc->__get('tag');?> 
                                        <?php if($tags){ ?>
                                        <div class="tags">
                                        <strong>Trefwoorden:</strong>
                                            <?php 
                                            if(is_array($tags)){
                                                foreach($tags as $tag){
                                                    echo "<a href='".libis_curPageURL()."AND tag:\"".$tag."\"'>".$tag."</a>";
                                                    if ($tag !== end($tags))
                                                        echo ', ';
                                                }
                                            }else{
                                                echo "<a href='".libis_curPageURL()."AND tag:\"".$tags."\"'>".$tags."</a>";
                                            }?>    
                                        </div>
                                        <?php } ?>   
                                    </div>
                                    </div>
                                <? }?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php } ?>
        </div>
    </div>
    
    <!-- BEGIN SOLR GEOLOCATIION -->
    <?php 
        //uitbreiding solr-geolocation        
        $locationSolr = array();
        $itemsSolr = array();
        if($itemids){
            foreach($itemids as $id){
                $item = get_record_by_id('item',$id);                    
                $loc = $locs = get_db()->getTable('Location')->findLocationByItem($item, true);
                if(!empty($loc)){
                    $locationsSolr[] = $loc;
                    $center = $loc[$id];
                    $itemsSolr[] = $item;  
                }
            }          
        }  
    
        if(!empty($locationsSolr)){
            $locations = $locationsSolr;    
            $session->items= $itemsSolr;
            $session->from= 'solr';
            $session->locations= $locations;
            //var_dump($locationsSolr);?>
            <div id="map-block" style="clear:both;">        
                <?php 
                require(GEOLOCATION_PLUGIN_DIR . '/helpers/GoogleMap.php');
                $map = new Geolocation_View_Helper_GoogleMap();
                echo $map->googleMap('map-display', array('loadKml'=>true));?>
            </div><!-- end map_block -->
        <?php } ?>
        <div id="test" style="display:none;"></div>
        <!-- END SOLR-GEOLOCATION -->
    
        <?php //if(!empty($itemids)){//echo relatedTagCloud_get($itemids);} ?>
    
    <div class="topresults bottom">
        <div class="resultCount"><?php echo __('%s resulaten', $results->response->numFound); ?></div>
        <?php echo pagination_links(array('partial_file' => 'common/pagination.php','per_page'=>$per_page)); ?> 
        
        <div class="resultsPerPage">
            <form action='<?php echo libis_curPageURL();?>' method="post">
                <select name="perPage" onchange="this.form.submit()">
                  <option <?php if($per_page==10){echo 'selected="selected"';}?> value="10">10 resultaten per pagina</option>
                  <option <?php if($per_page==20){echo 'selected="selected"';}?> value="20">20 resultaten per pagina</option>
                  <option <?php if($per_page==50){echo 'selected="selected"';}?> value="50">50 resultaten per pagina</option>                  
                </select>
            </form>
        </div>           
    </div>  
</div>

<?php
    echo foot();    
?>
