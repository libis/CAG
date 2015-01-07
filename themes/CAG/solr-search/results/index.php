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
                
                if(strpos($facet,'Agendapunt') !== false || 
                    strpos($facet,'Nieuwsbericht') !== false) {
                    $view = 'nieuws';
                }
               
                if(strpos($facet,'Publicatie') !== false) {
                    $view = 'publicatie';
                }
                if(strpos($facet,'Project') !== false) {
                    $view = 'project';
                }
                 if(strpos($facet,'Publicatie') !== false && 
                    strpos($facet,'Project') !== false) {
                    $view = 'pubpro';
                }
            }            
            
            //VIEW = NIEUWS EN AGENDA
            if($view=='nieuws'){
                $featured="";$nieuws ="";$agenda="";
                foreach($results->response->docs as $doc):
                   $item = get_record_by_id('item',preg_replace ( '/[^0-9]/', '', $doc->__get('id'))); 

                   if(!is_null($item)) {
			   if($item->getItemType()->name == 'Nieuwsbericht' ||$item->getItemType()->name == 'Agendapunt'){
			       $html = "<div class='in_de_kijker' id='solr_".$doc->__get('id')."'>";                        
			       if($item->hasThumbnail()):
				   $html .= link_to_item(item_image('thumbnail', array('width'=>'80'), 0, $item), array('class' => 'item-thumbnail'), 'show', $item);
			       endif;
			       $html .=link_to_item("<h4>".metadata($item,array('Dublin Core','Title'))."</h4>",array(),'show',$item).
			       "<p>".metadata($item,array('Dublin Core','Description'),array('snippet'=>50))."</p> </div>";
	
			       if($item->featured==1){$featured .= $html;}                       
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
                    echo "<div class='nieuws-kolom kolom-agenda'><h2>Agenda</h2>".$agenda."</div>";
                }
            }
            
            //VIEW = PUBLICATIE (PUBLICATIE && PROJECT)
            if($view=='pubpro'){
                $featured="";$pub ="";$project="";
                foreach($results->response->docs as $doc):
                   $item = get_record_by_id('item',preg_replace ( '/[^0-9]/', '', $doc->__get('id'))); 

                   if($item->getItemType()->name == 'Publicatie' ||$item->getItemType()->name == 'Project'){
                       $html = "<div class='in_de_kijker' id='solr_".$doc->__get('id')."'>";                        
                       if($item->hasThumbnail()):
                           $html .= link_to_item(item_image('thumbnail', array('width'=>'80'), 0, $item), array('class' => 'item-thumbnail'), 'show', $item);
                       endif;
                       $html .=link_to_item("<h4>".metadata($item,array('Dublin Core','Title'))."</h4>",array(),'show',$item).
                       "<p>".metadata($item,array('Dublin Core','Description'),array('snippet'=>50))."</p> </div>";

                       if($item->featured==1){$featured .= $html;}                       
                       if($item->getItemType()->name == 'Publicatie'){$pub .=$html;}
                       if($item->getItemType()->name == 'Project'){$project .=$html;}
                      
                   }
                endforeach;
                
                if($featured){
                    echo "<div class='nieuws-kolom'><h2>In de kijker</h2>".$featured."</div>";
                }
                if($pub){
                    echo "<div class='nieuws-kolom'><h2>Publicaties</h2>".$pub."</div>";
                }
                if($project){
                    echo "<div class='nieuws-kolom'><h2>Projecten</h2>".$project."</div>";
                }
            }
            
            //VIEW = PUBLICATIE (PUBLICATIE)
            if($view=='publicatie'){
                $featured="";$pub ="";
                $i='odd';
                
                foreach($results->response->docs as $doc):
                   $class='in_de_kijker'; 
                   $item = get_record_by_id('item',preg_replace ( '/[^0-9]/', '', $doc->__get('id'))); 
                    
                   if($item->getItemType()->name == 'Publicatie'){
                       if($item->featured==0){
                           if($i == 'odd'){
                               $class = 'in_de_kijker odd';
                               $i='even';
                           }else{
                               $class = 'in_de_kijker even';
                               $i = 'odd';
                           }
                       }
                       $html = "<div class='".$class."' id='solr_".$doc->__get('id')."'>";                        
                       if($item->hasThumbnail()):
                           $html .= link_to_item(item_image('thumbnail', array('width'=>'80'), 0, $item), array('class' => 'item-thumbnail'), 'show', $item);
                       endif;
                       $html .=link_to_item("<h4>".metadata($item,array('Dublin Core','Title'))."</h4>",array(),'show',$item).
                       "<p>".metadata($item,array('Dublin Core','Description'),array('snippet'=>50))."</p> </div>";

                       if($item->featured==1){$featured .= $html;}
                       else{
                        $pub .=$html;                        
                       }
                   }
                endforeach;
                
                if($featured){
                    echo "<div class='nieuws-kolom'><h2>Recente publicaties</h2>".$featured."</div>";
                }
                if($pub){
                    echo "<div class='nieuws-kolom two-col-nieuws'><h2>Alle publicaties</h2>".$pub."</div>";
                }
            }
            
            //VIEW = Project(PROJECT)
            if($view=='project'){
                $featured="";$project="";
                $i='odd';
                 
                foreach($results->response->docs as $doc):
                   $class='in_de_kijker';  
                   $item = get_record_by_id('item',preg_replace ( '/[^0-9]/', '', $doc->__get('id'))); 

                   if($item->getItemType()->name == 'Project'){
                       if($item->featured==0){
                           if($i == 'odd'){
                               $class = 'in_de_kijker odd';
                               $i='even';
                           }else{
                               $class = 'in_de_kijker even';
                               $i = 'odd';
                           }
                       }
                       $html = "<div class='".$class."' id='solr_".$doc->__get('id')."'>";                        
                       if($item->hasThumbnail()):
                           $html .= link_to_item(item_image('thumbnail', array('width'=>'80'), 0, $item), array('class' => 'item-thumbnail'), 'show', $item);
                       endif;
                       $html .=link_to_item("<h4>".metadata($item,array('Dublin Core','Title'))."</h4>",array(),'show',$item).
                       "<p>".metadata($item,array('Dublin Core','Description'),array('snippet'=>50))."</p> </div>";

                       if($item->featured==1):$featured .= $html;
                       else: $project .=$html;
                       endif;
                      
                   }
                endforeach;
                
                if($featured){
                    echo "<div class='nieuws-kolom'><h2>Lopende projecten</h2>".$featured."</div>";
                }
                if($project){
                    echo "<div class='nieuws-kolom two-col-nieuws'><h2>Afgelopen projecten</h2>".$project."</div>";
                }
            }
                
            //VIEW = DEFAULT (OBJECT, COLLECTIE, AlGEMENE INFO)
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
                                         if(metadata($item,array('Item Type Metadata','Objectnaam')))
                                            $beelden.="<tr><td><strong>Objectnaam:</strong></td><td>".link_to_item(ucfirst(metadata($item,array('Item Type Metadata','Objectnaam'))))."</td></tr>";
                                        
                                        if(metadata($item,array('Dublin Core','Title')))
                                          $beelden.="<tr><td><strong>Titel:</strong></td><td>".link_to_item(ucfirst(metadata($item,array('Dublin Core','Title'))))."</td></tr>";
                                        
                                        if(metadata($item,array('Dublin Core','Identifier'))){
                                            $identifier = metadata($item,array('Dublin Core','Identifier'));
                                            $beelden.="<tr><td><strong>Objectnummer:</strong></td><td>".$identifier."</td></tr>";
                                        }
                                        if(metadata($item,array('Dublin Core','Date')))
                                            $beelden.="<tr><td><strong>Datering:</strong></td><td>".metadata($item,array('Dublin Core','Date'))."</td></tr>";
                                       
                                        
                                        if(metadata($item,array('Dublin Core','Description')))
                                            $beelden.="<tr><td><strong>Beschrijving:</strong></td><td>".metadata($item,array('Dublin Core','Description'),array('snippet'=>200))."</td></tr>";
                                       
                                        
                                        $beelden.="</table>";
                                        
                                        echo $beelden;
                                        ?>
                                    </div>
                                <?php } ?>

                                <!-- Collectie -->
                                <?php if($item->getItemType()->name == 'Collectie'){                                                   
                                    
                                    $actoren= "<table><th width='120'></th><th></th>";

                                    if(metadata($item,array('Item Type Metadata','Naam instelling')))
                                        $actoren.= "<tr><td><strong>Naam Instelling:</strong></td><td>".link_to_item(metadata($item,array('Item Type Metadata','Naam instelling')),array(),'show',$item)."</td></tr>";
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
                                    if(metadata($item,array('Item Type Metadata','Beschrijving')))
                                           $actoren.="<tr><td><strong>Beschrijving:</strong></td><td>".metadata($item,array('Item Type Metadata','Beschrijving'),array('snippet'=>200))."</td></tr>";
                                       
                                    $actoren .="</table>";//.plugin_append_to_items_browse_each();
                                ?>
                                    <div class="title"><?php echo $actoren; ?></div>
                                <?php }?>

                                <!-- concept -->
                                <?php if($item->getItemType()->name == 'Algemene-info'){?>
                                    <div class="title">
                                    <?php 
                                        $werktuigen="<table width='300'><th width='120'></th><th></th>";
                                        
                                        if(metadata($item,array('Dublin Core','Title')))
                                            $werktuigen.="<tr><td><strong>Naam:</strong></td><td>".link_to_item(ucfirst(metadata($item,array('Dublin Core','Title'))))."</td></tr>";
                                        if(metadata($item,array('Item Type Metadata','Scope')))
                                            $werktuigen.= "<tr><td><strong>Definitie:</strong></td><td>".metadata($item,array('Item Type Metadata','Scope'))."</td></tr>";
                                        if(metadata($item,array('Dublin Core','Description')))
                                            $werktuigen.="<tr><td><strong>Beschrijving:</strong></td><td>".metadata($item,array('Dublin Core','Description'),array('snippet'=>200))."</td></tr>";
                                        $werktuigen.="</table>";
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
        <div id="handle" style="display:none;"></div>
        <!-- END SOLR-GEOLOCATION -->
    
        <?php //if(!empty($itemids)){//echo relatedTagCloud_get($itemids);} ?>
    
    <div class="topresults bottom">
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
</div>

<?php
    echo foot();    
?>
