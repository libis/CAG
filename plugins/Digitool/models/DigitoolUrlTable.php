<?php
class DigitoolUrlTable extends Omeka_Db_Table
{
    /**
     * Returns digitool urls for an item
     * @param Item $item An item or item id, or an array of items or item ids
     * @param boolean $findOnlyOne Whether or not to return only one location if it exists for the item
     * @return array of digitool url objects
     **/
    public function findDigitoolUrlByItem($item)
    {
        if(!$item){
            return false;
        }
        
        $db = get_db();

        if (($item instanceof Item) && !$item->exists()) {
            return false;
        } else if (is_array($item) && !count($item)) {
            return false;
        }

        // Create a SELECT statement for the table
        $select = $db->select()->from(array('d' => $db->DigitoolUrl), 'd.*');

        // Create a WHERE condition that will pull down all the digitool info
        if (is_array($item)) {
            $itemIds = array();
            foreach ($item as $it) {
                $itemIds[] = (int)(($it instanceof Item) ? $it->id : $it);
            }
            $select->where('d.item_id IN (?)', $itemIds);
        } else {
            $itemId = (int)(($item instanceof Item) ? $item->id : $item);
            $select->where('d.item_id = ?', $itemId);
        }

        // Get the DigitoolUrls
        $urls = $this->fetchObjects($select);

        //otherwise return the whole table
        return $urls;
    }
    
    /**
     * Returns digitool urls for an item
     * @param int $pid pid of the DigitoolUrl
     * @param Item $item An item
     * @param boolean $findOnlyOne Whether or not to return only one location if it exists for the item
     * @return array of digitool url objects
     **/
    public function findDigitoolUrlByPid($pid, $item)
    {
        $db = get_db();
        
        //echo $url->pid;
	$select = $db->query("SELECT id
		FROM omeka_digitool_urls
		WHERE pid = '".$pid."' AND item_id = '".$item->id."'		
	");

	$id = $select->fetchAll();

        return $id[0]['id'];
    }
    
    /**
    * Add permission check to location queries.
    *
    * Since all locations belong to an item we can override this method to join
    * the items table and add a permission check to the select object.
    *
    * @return Omeka_Db_Select
    */
    public function getSelect()
    {
        $select = parent::getSelect();
        $select->join(array('items' => $this->_db->Item), 'items.id = digitool_urls.item_id', array());
        $permissions = new Omeka_Db_Select_PublicPermissions('Items');
        $permissions->apply($select, 'items');
        return $select;
    }
}