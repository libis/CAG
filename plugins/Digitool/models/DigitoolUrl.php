<?php
require_once 'DigitoolUrlTable.php';
/**
 * DigitoolUrl
 * @package: Omeka
 */
class DigitoolUrl extends Omeka_Record_AbstractRecord
{
    public $item_id;
    public $pid;
          
    protected function _validate()
    {
        if (empty($this->item_id)) {
            $this->addError('item_id', 'DigitoolUrl requires an item id.');
        }
    }
    
    public function get_thumb(){
        return get_option('digitool_thumb').$this->pid;        
    }
    
    public function get_view(){
        return get_option('digitool_view').$this->pid;        
    }
}
