<?php
class CCTable extends Omeka_Db_Table
{
    /**
     * Return a multidimensional array of location info
     * @param array|int $item_id
     * @return array
     **/
    public function findLicenseByItem($item, $findOnlyOne = false)
    {
        $db = get_db();
        
        if (($item instanceof Item) && !$item->exists()) {
            return array();
        } else if (is_array($item) && !count($item)) {
            return array();
        }
        
        $select = $db->select()->from(array('l' => $db->CC), 'l.*');
        
        $item = ($item instanceof Item) ? $item->id : $item;
        
        // Create a WHERE condition that will pull down all the location info
        if (count($item) > 1 || (is_array($item))) {
            $to_pass = array();
            foreach ($item as $it) {
                $to_pass[] = ($it instanceof Item) ? $it->id : $it;
            }
            $select->where('l.item_id IN (?)', $to_pass);
        } else {
            $select->where('l.item_id = ?', ($item instanceof Item) ? $item->id : $item);
        }
        
        $licenses = $this->fetchObjects($select);
        
        if ($findOnlyOne) {
            return current($licenses);
        }
        
        $indexed = array();
        
        //Now process into an array where the key is the item_id        
        foreach ($licenses as $k => $loc) {
            $indexed[$loc['item_id']] = $loc;
        }
        
        return $indexed;
    }
}