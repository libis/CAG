<?php

require_once('index.php');    

    $solr = SolrSearch_Helpers_Index::connect();    
    
    $solr->deleteByQuery('*:*');
    $solr->commit();
    $solr->optimize();

    $db     = get_db();
    $table  = $db->getTable('Item');
    $select = $table->getSelect();

    $table->filterByPublic($select, true);
    $table->applySorting($select, 'id', 'ASC');

    // First get the items.
    $pager = new SolrSearch_DbPager($db, $table, $select);
    while ($items = $pager->next()) {
        foreach ($items as $item) {
            $docs = array();
            $doc = SolrSearch_Helpers_Index::itemToDocument($db, $item);
            $docs[] = $doc;
            $solr->addDocuments($docs);
        }
        $solr->commit();
    }

    // Now the other addon stuff.
    $mgr  = new SolrSearch_Addon_Manager($db);
    $docs = $mgr->reindexAddons();
    $solr->addDocuments($docs);
    $solr->commit();

    $solr->optimize();

?>
