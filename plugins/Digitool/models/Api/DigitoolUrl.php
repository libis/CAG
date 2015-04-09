<?php

class Api_DigitoolUrl extends Omeka_Record_Api_AbstractRecordAdapter
{
    public function getRepresentation(Omeka_Record_AbstractRecord $record)
    {
        $representation = array();
        $representation['id'] = $record->id;
        $representation['item_id'] = $record->item_id;
        $representation['pid'] = $record->pid;      
        $representation['label'] = $record->label;    
        return $representation;
    }
    
    public function getResourceId()
    {
        return "Digitool_DigitoolUrls";
    }
    
    // Set data to a record during a POST request.
    public function setPostData(Omeka_Record_AbstractRecord $record, $data)
    {
        if (isset($data->item_id)) {
            $record->item_id = $data->item_id;
        }
        if (isset($data->pid)) {
            $record->pid = $data->pid;
        }
        if (isset($data->label)) {
            $record->label = $data->label;
        }        
    }
    
    // Set data to a record during a PUT request.
    public function setPutData(Omeka_Record_AbstractRecord $record, $data)
    {
        $this->setPostData($record, $data);
    }
    
    // Set data to a record during a DELETE request.
    public function setDeleteData(Omeka_Record_AbstractRecord $record, $data)
    {
        $this->setPostData($record, $data);
    } 
    
}