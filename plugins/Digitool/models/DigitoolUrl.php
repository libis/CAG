<?php
require_once 'DigitoolUrlTable.php';
/**
 * DigitoolUrl
 * @package: Omeka
 */
class DigitoolUrl extends Omeka_Record_AbstractRecord implements Zend_Acl_Resource_Interface
{
    public $item_id;
    public $pid;
    public $label;
          
    protected function _validate()
    {
        if (empty($this->item_id)) {
            $this->addError('item_id', 'DigitoolUrl requires an item id.');
        }
        
        //check if item/pid combo already exists
        $db = get_db();
        
        //echo $url->pid;
	$select = $db->query("SELECT id
		FROM omeka_digitool_urls
		WHERE pid = '".$this->pid."' AND item_id = '".$this->item_id."'		
	");

	$id = $select->fetchAll();
        if(!empty($id)):
            $this->addError('item_id', 'Item already has this pid.');
        endif;
       
    }
    
    public function get_thumb(){
        return get_option('digitool_thumb').$this->pid;        
    }
    
    public function get_view(){
        return get_option('digitool_view').$this->pid;        
    }
    
    /**
     * Required by Zend_Acl_Resource_Interface.
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'Digitool_DigitoolUrls';
    }
}
