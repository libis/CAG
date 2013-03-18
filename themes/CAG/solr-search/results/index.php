<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

  $pageTitle = __('Browse Items'); //TODO: Should this be browse items?
  head(array('title' => $pageTitle, 'id' => 'items', 'bodyclass' => 'browse'));
  
  $session = new Zend_Session_Namespace('style');

  $perPage = $session->perPage;
?>

<div id="primary" class="solr_results results">
    <h3></h3>
               
    <div class="topresults">
        <div class="resultCount"><?php echo __('%s resulaten', $results->response->numFound); ?></div>
        <?php echo pagination_links(array('partial_file' => 'common/pagination.php','per_page'=>$per_page)); ?> 
        <?php $alles = $results->response->numFound; ?>    
        <div class="resultsPerPage">
            <form action='<?php echo libis_curPageURL();?>' method="post">
                <select name="perPage" onchange="this.form.submit()">
                  <option <?php if($perPage==10){echo 'selected="selected"';}?> value="10">10 resultaten per pagina</option>
                  <option <?php if($perPage==20){echo 'selected="selected"';}?> value="20">20 resultaten per pagina</option>
                  <option <?php if($perPage==50){echo 'selected="selected"';}?> value="50">50 resultaten per pagina</option>
                  <option <?php if($perPage==$alles){echo 'selected="selected"';}?> value="<?php echo $alles;?>">Alle resultaten</option>
                
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
                <?php $item = get_item_by_id(preg_replace ( '/[^0-9]/', '', $doc->__get('id')));?>
            <div class="item" id="solr_<?php echo $doc->__get('id'); ?>">
                <div class="details">
                    <div class='resultbody'>                  
                        <div class='textfields'>

                            <?php if($item){ ?>
                                <?php set_current_item($item); ?>
                            <?php } ?>

                            <?php if(digitool_item_has_digitool_url($item)){ ?>
                                <div class="image">
                                    <?php echo digitool_get_thumb($item,true,false,'140');?>
                                    <?php //echo SolrSearch_ViewHelpers::createResultImgHtml($image, SolrSearch_ViewHelpers::getDocTitle($doc)); ?>
                                </div>
                            <?php } ?>

                            <!-- AFBEELDING -->
                            <?php if(item_has_type('Afbeelding')){?>

                                <div class="title">
                                    <?php $beelden="<table width='300'><th width='120'></th><th></th>";
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
                            <?php if(item_has_type('Werktuig')){?>
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
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php
    echo foot();
?>