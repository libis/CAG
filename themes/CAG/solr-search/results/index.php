<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

  $pageTitle = __('Browse Items'); //TODO: Should this be browse items?
  head(array('title' => $pageTitle, 'id' => 'items', 'bodyclass' => 'browse'));
  
  $session = new Zend_Session_Namespace('style');

  $perPage = get_option('solr_search_rows');//$session->perPage;
  
  //uitbreiding solr-geolocation
  $session->items = "";
  $session->locations= "";
?>

<div id="primary" class="solr_results results">
                  
    <div class="topresults">
        <div class="resultCount"><?php echo __('%s resultaten', $results->response->numFound); ?></div>
        <?php echo pagination_links(array('partial_file' => 'common/pagination.php','per_page'=>$per_page)); ?> 
        
        <div class="resultsPerPage">
            <form action='<?php echo libis_curPageURL();?>' method="post">
                <select name="perPage" onchange="this.form.submit()">
                  <option <?php if($perPage==10){echo 'selected="selected"';}?> value="10">10 resultaten per pagina</option>
                  <option <?php if($perPage==20){echo 'selected="selected"';}?> value="20">20 resultaten per pagina</option>
                  <option <?php if($perPage==50){echo 'selected="selected"';}?> value="50">50 resultaten per pagina</option>                  
                </select>
            </form>
        </div>
        
           
    </div>        
    <div id="solr_results" class="item-list">

        <?php $query = SolrSearch_QueryHelpers::getParams(); ?>

        <div class="solr_facets_container">
            <div id="solr_search" class="search solr_remove_facets"></div>    

                <?php if(!empty($facets)): ?>

                <div class="solr_facets">
                    <?php foreach ((array)$results->facet_counts->facet_fields as $facet => $values): ?>
                        <?php $props = get_object_vars($values); ?>
                        <?php if (!empty($props)): ?>
                        <h4 class="facet"><?php echo SolrSearch_QueryHelpers::parseFacet($facet); ?></h4>
                        <ul>
                            <?php foreach($values as $label => $count): ?>
                            <li><?php echo SolrSearch_QueryHelpers::createFacetHtml($query, $facet, $label, $count); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
       
        <div id="results">              
            <div id="appliedParams">
                <?php echo SolrSearch_QueryHelpers::removeFacets(); ?>
            </div>

            <?php foreach($results->response->docs as $doc): ?>
                <?php
                    $item = get_item_by_id(preg_replace ( '/[^0-9]/', '', $doc->__get('id')));
                    
                                          
                ?>
                <div class="item" id="solr_<?php echo $doc->__get('id'); ?>">
                    <div class="details">
                        <div class='resultbody'>                  
                            <div class='textfields'>

                            <?php
                            if($item){
                                    set_current_item($item); 
                                    $itemids[] = $item->id;?>
                                <?php if(digitool_item_has_digitool_url($item)){ ?>
                                    <div class="image">
                                        <?php echo link_to_item(digitool_get_thumb_for_browse($item,'140'));?>
                                        <?php //echo SolrSearch_ViewHelpers::createResultImgHtml($image, SolrSearch_ViewHelpers::getDocTitle($doc)); ?>
                                    </div>
                                <?php } ?>

                                <!-- OBJECT -->
                                <?php if(item_has_type('Object')){?>

                                    <div class="title">
                                        <?php $beelden="<table width='300'><th width='120'></th><th></th>";
                                        if(item('Item Type Metadata','Objectnaam'))
                                            $beelden.="<tr><td><strong>Objectnaam:</strong></td><td>".link_to_item(item('Item Type Metadata','Objectnaam'))."</td></tr>";
                                         if(item('Dublin Core','Title'))
                                            $beelden.="<tr><td><strong>Titel:</strong></td><td>".link_to_item(item('Dublin Core','Title'))."</td></tr>";
                                        if(item('Dublin Core','Publisher'))
                                            $beelden.="<tr><td><strong>Naam Instelling:</strong></td><td>".item('Dublin Core','Publisher')."</td></tr>";
                                        if(item('Dublin Core','Identifier')){
                                            $identifier = item('Dublin Core','Identifier');
                                            $beelden.="<tr><td><strong>Nummer:</strong></td><td>".$identifier."</td></tr>";
                                        }
                                        if(item('Dublin Core','Type'))
                                            $beelden.="<tr><td><strong>Objectcategorie:</strong></td><td>".item('Dublin Core','Type')."</td></tr>";
                                        $beelden.="</table></td></tr></table>";
                                        echo $beelden;
                                        ?>
                                    </div>
                                <?php } ?>

                                <!-- Actor -->
                                <?php if(item_has_type('Actor')){                                                   
                                    /*if (digitool_thumbnail(get_current_item())):
                                             $actoren.= link_to_item(digitool_thumbnail(get_current_item()));
                                    endif;*/
                                    $actoren= "<table><th width='120'></th><th></th>";
                                    if(item('Item Type Metadata','Naam instelling'))
                                        $actoren.= "<tr><td><strong>Naam Instelling:</strong></td><td>".item('Item Type Metadata','Naam instelling')."</td></tr>";
                                    if(item('Item Type Metadata','Straat + Nr'))
                                        $actoren.= "<tr><td><strong>Straat + Nr:</strong></td><td>".item('Item Type Metadata','Straat + Nr')."</td></tr>";
                                    if(item('Item Type Metadata','Postcode'))
                                        $actoren.= "<tr><td><strong>Postcode:</strong></td><td>".item('Item Type Metadata','Postcode')."</td></tr>";
                                    if(item('Item Type Metadata','Stad'))
                                        $actoren.= "<tr><td><strong>Stad:</strong></td><td>".item('Item Type Metadata','Stad')."</td></tr>";
                                    if(item('Item Type Metadata','Provincie'))
                                        $actoren.= "<tr><td><strong>Provincie:</strong></td><td>".item('Item Type Metadata','Provincie')."</td></tr>";
                                    if(item('Item Type Metadata','Telefoon'))
                                        $actoren.= "<tr><td><strong>Telefoon:</strong></td><td>".item('Item Type Metadata','Telefoon')."</td></tr>";
                                    if(item('Item Type Metadata','Fax'))
                                        $actoren.= "<tr><td><strong>Fax:</strong></td><td>".item('Item Type Metadata','Fax')."</td></tr>";
                                    if(item('Item Type Metadata','Website'))
                                        $actoren.= "<tr><td><strong>Website:</strong></td><td>".item('Item Type Metadata','Website')."</td></tr>";
                                    if(item('Item Type Metadata','E-mail'))
                                        $actoren.= "<tr><td><strong>E-mail:</strong></td><td>".item('Item Type Metadata','E-mail')."</td></tr>";

                                    $actoren .="</table>".plugin_append_to_items_browse_each();
                                ?>
                                    <div class="title"><?php echo $actoren; ?></div>
                                <?php }?>

                                <!-- WERKTUIG -->
                                <?php if(item_has_type('Concept')){?>
                                    <div class="title">
                                    <?php $werktuigen ="<table><tr><td><strong>
                                                 ".link_to_item(item('Dublin Core','Title'))."
                                                 </strong></td></tr>
                                                 <tr><td>".item('Item Type Metadata','Scope')."</td></tr></table>";

                                    $werktuigen .= plugin_append_to_items_browse_each();
                                    echo $werktuigen;?>
                                    </div>
                                <?php } ?>



                                <?php $tags = $doc->__get('tag'); ?>
                                <?php if($tags){ ?>
                                    <div class="tags">
                                        <strong>Trefwoorden:</strong>
                                        <?php echo SolrSearch_ViewHelpers::tagsToStrings($tags); ?>
                                    </div>

                                <?php } ?>
                                <? }else{ //enditem?>                      
                                    <div class="title">
                                        <h3><?php echo SolrSearch_ViewHelpers::createResultLink($doc); ?></h3>
                                    </div>

                                    <div class='resultbody'>
                                      <?php $image = $doc->__get('image');?>
                                      <?php if($image): ?>
                                      <div class="image">
                                        <?php echo SolrSearch_ViewHelpers::createResultImgHtml($image, SolrSearch_ViewHelpers::getDocTitle($doc)); ?>
                                      </div>
                                      <?php endif; ?>

                                      <div class='textfields'>
                                        <?php if($results->responseHeader->params->hl == true): ?>
                                        <div class="solr_highlight">
                                          <?php echo SolrSearch_ViewHelpers::displaySnippets($doc->id, $results->highlighting); ?>
                                        </div>
                                        <?php endif; ?>

                                        <?php $tags = $doc->__get('tag'); ?>
                                        <?php if($tags): ?>
                                          <div class="tags">
                                            <strong>Tags:</strong>
                                            <?php echo SolrSearch_ViewHelpers::tagsToStrings($tags); ?>
                                          </div>
                                        <?php endif; ?>
                                      </div>
                                    </div>
                                <? }?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- BEGIN SOLR GEOLOCATIION -->
    <?php 
        //uitbreiding solr-geolocation        
        $locationSolr = array();
        
        if($itemids){
                foreach($itemids as $id){
                    $item = get_item_by_id($id);                    
                    $loc = geolocation_get_location_for_item($item);
                    if(!empty($loc)){
                        $locationsSolr = $locationSolr + $loc;
                        $center = $loc[$id];
                    }
                }
                $locations = $locationsSolr;                
         }         
         $session->items= $itemids;
         $session->locations= $locations;
    ?>
    
    <?php if(!empty($locationsSolr)){
        //var_dump($locationsSolr);?>
        <div id="map-block" style="clear:both;">        
            <?php echo geolocation_google_map('map-display', array('loadKml'=>true),$center);?>
        </div><!-- end map_block -->
    <?php } ?>
    <div id="test" style="display:none;"></div>
    <!-- END SOLR-GEOLOCATION -->
    
    <?php if(!empty($itemids)){ echo relatedTagCloud_get($itemids);} ?>
    
    <div class="topresults bottom">
        <div class="resultCount"><?php echo __('%s resulaten', $results->response->numFound); ?></div>
        <?php echo pagination_links(array('partial_file' => 'common/pagination.php','per_page'=>$per_page)); ?> 
        
        <div class="resultsPerPage">
            <form action='<?php echo libis_curPageURL();?>' method="post">
                <select name="perPage" onchange="this.form.submit()">
                  <option <?php if($perPage==10){echo 'selected="selected"';}?> value="10">10 resultaten per pagina</option>
                  <option <?php if($perPage==20){echo 'selected="selected"';}?> value="20">20 resultaten per pagina</option>
                  <option <?php if($perPage==50){echo 'selected="selected"';}?> value="50">50 resultaten per pagina</option>                  
                </select>
            </form>
        </div>           
    </div>  
</div>
<?php
    echo foot();
?>