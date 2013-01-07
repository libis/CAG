<?php 
/**
 * @copyright Roy Rosenzweig Center for History and New Media, 2007-2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Omeka
 * @access private
 */
 
/**
 * @internal This implements Omeka internals and is not part of the public API.
 * @access private
 * @package Omeka
 * @author CHNM
 * @copyright Roy Rosenzweig Center for History and New Media, 2007-2010
 */
class ItemRss2
{
    public function render(array $records)
    {
        $entries = array();
        //aanpassing joris
    	$items = recent_items(3);
		
		foreach ($items as $item) {
			set_current_item($item);
           	if(item_has_type("Still Image")){
	            $entries[] = $this->itemToRss($item);
			}
            release_object($item);
	    }

		$exhibits = exhibit_builder_recent_exhibits($num = 3);

		foreach($exhibits as $exhibit){
			$entries[] = $this->exhibitToRss($exhibit);    
		}
        //einde aanpassing        
        $headers = $this->buildRSSHeaders();
        $headers['entries'] = $entries;
        $feed = Zend_Feed::importArray($headers, 'rss');
        return $feed->saveXML();        
    }
    
    protected function buildRSSHeaders()
    {
        $headers = array();
        
        // How do we determine what title to give the RSS feed?        
        $headers['title'] = settings('site_title');
        
        $headers['link'] = xml_escape(__v()->serverUrl(isset($_SERVER['REQUEST_URI'])));
        $headers['lastUpdate'] = time();
        $headers['charset'] = "UTF-8";    
        
        // Feed could have a description, where would it be stored ?
        // $headers['description'] = ""
        
        $headers['author'] = settings('site_title');
        $headers['email'] = settings('administrator_email');
        $headers['copyright'] = settings('copyright');
        
        //How do we determine how long a feed can be cached?
        //$headers['ttl'] = 
        
        return $headers;
    }
    
    protected function buildDescription($item)
    {
        $description = '';

        $description .= show_item_metadata();
        
        //Output HTML that would display all the files in whatever way is possible
        $description .= display_files($item->Files);
        
        return $description;
    }
    
    protected function itemToRSS($item)
    {        
        $entry = array();
        set_current_item($item);
        
        // Title is a CDATA section, so no need for extra escaping.
        $entry['title'] = strip_formatting(item('Dublin Core', 'Title', array('no_escape'=>true)));
        $entry['description'] = $this->buildDescription($item);
        
        $entry['link'] = xml_escape(abs_item_uri($item));
                
        $entry['lastUpdate'] = strtotime($item->added);
                
        //List the first file as an enclosure (only one per RSS feed)
        if(($files = $item->Files) && ($file = current($files))) {
            $entry['enclosure']   = array();
            $fileDownloadUrl = file_display_uri($file);
            $enc['url']           = $fileDownloadUrl;
            $enc['type']          = $file->mime_browser;
            $enc['length']        = (int) $file->size;
            $entry['enclosure'][] = $enc;
        }

        return $entry;        
    }
    
    //aanpassing joris
    protected function exhibitToRSS($exhibit){
    
    	$entry = array();
    	// Title is a CDATA section, so no need for extra escaping.    
    	$entry['title'] = strip_formatting($exhibit->title);    
    	$entry['description'] = $exhibit->description;    	 
    
    	$entry['link'] = uri("exhibits/show/").$exhibit->slug;//xml_escape(link_to_exhibit($exhibit));
        	     
    	//$entry['lastUpdate'] = "";
    
    	return $entry;
    }
}
