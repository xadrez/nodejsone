<?php
/**
 * Views: Template manager 
 * Page content and structure is managed with a seperate page object.
 *
 * @version 1.0
 * @author Michael Peacock
 */
class template {

	private $page;
	
	/**
	 * Include our page class, and build a page object to manage the content and structure of the page
	 */
    public function __construct() 
    {
	    include( FRAMEWORK_PATH . '/registry/objects/page.class.php');
	    $this->page = new Page();

    }
    
    /**
     * Add a template bit from a view to our page
     * @param String $tag the tag where we insert the template e.g. {hello}
     * @param String $bit the template bit (path to file, or just the filename)
     * @return void
     */
    public function addTemplateBit( $tag, $bit )
    {	
		if( strpos( $bit, 'views/' ) === false )
		{
			$bit = 'views/' . PHPAppRegistry::getSetting('view') . '/templates/' . $bit;
		}
		$this->page->addTemplateBit( $tag, $bit );
    }
    
    /**
     * Take the template bits from the view and insert them into our page content
     * Updates the pages content
     * @return void
     */
    private function replaceBits()
    {
	    $bits = $this->page->getBits();
		
	    // loop through template bits e.g.
	    foreach( $bits as $tag => $template )
	    {
		    $templateContent = file_get_contents( $template );
		    $newContent = str_replace( '{' . $tag . '}', $templateContent, $this->page->getContent() );
		    $this->page->setContent( $newContent );
	    }
    }
    
    /**
     * Replace tags in our page with content
     * @return void
     */
    private function replaceTags( $pp = false )
    {
	    // get the tags in the page
	    if( $pp == false )
	    {
		     $tags = $this->page->getTags();
	    }
	    else
	    {
		     $tags = $this->page->getPPTags();
	    }
	   
	    // go through them all
	    foreach( $tags as $tag => $data )
	    {
		    // if the tag is an array, then we need to do more than a simple find and replace!
			
		    if( is_array( $data ) )
		    {
			    if( $data[0] == 'SQL' )
			    {
				    // it is a cached query...replace tags from the database
				    $this->replaceDBTags( $tag, $data[1] );
			    }
			    elseif( $data[0] == 'DATA' )
			    {
				     // it is some cached data...replace tags from cached data
				    $this->replaceDataTags( $tag, $data[1] );
			    }
	    	}
	    	else
	    	{	
		    	// replace the content
				   	
		    	$newContent = str_replace( '{' . $tag . '}', $data, $this->page->getContent() );
		    	// update the pages content
				/*print_r('<pre> newContent...');
				print_r($tag);	
				print_r('</pre>');*/
		    	$this->page->setContent( $newContent );
	    	}
	    }

    }
    
    /**
     * Replace content on the page with data from the database
     * @param String $tag the tag defining the area of content
     * @param int $cacheId the queries ID in the query cache
     * @return void
     */
    private function replaceDBTags( $tag, $cacheId )
    {
	    $block = '';
		$blockOld = $this->page->getBlock( $tag );
		
		// foreach record relating to the query...
		while ($tags = PHPAppRegistry::getObject('db')->resultsFromCache( $cacheId ) )
		{
			$blockNew = $blockOld;
			// create a new block of content with the results replaced into it
			foreach ($tags as $ntag => $data) 
	       	{
	        	$blockNew = str_replace("{" . $ntag . "}", $data, $blockNew); 
	        }
	        $block .= $blockNew;
		}
		$pageContent = $this->page->getContent();
		// remove the seperator in the template, cleaner HTML
		$newContent = str_replace( '<!-- START ' . $tag . ' -->' . $blockOld . '<!-- END ' . $tag . ' -->', $block, $pageContent );
		// update the page content
		$this->page->setContent( $newContent );
	}
    
	/**
     * Replace content on the page with data from the cache
     * @param String $tag the tag defining the area of content
     * @param int $cacheId the datas ID in the data cache
     * @return void
     */
    private function replaceDataTags( $tag, $cacheId )
    {

	    $blockOld = $this->page->getBlock( $tag );
		$block = '';
		$tags = PHPAppRegistry::getObject('db')->dataFromCache( $cacheId );
		foreach( $tags as $key => $tagsdata )
		{
			$blockNew = $blockOld;
			foreach ($tagsdata as $taga => $data) 
	       	{

	        	$blockNew = str_replace("{" . $taga . "}", $data, $blockNew); 
	        }
	        $block .= $blockNew;
		}


		$pageContent = $this->page->getContent();
		$newContent = str_replace( '<!-- START '.$tag.' -->'.$blockOld.'<!-- END '.$tag.' -->', $block, $pageContent );
		$this->page->setContent( $newContent );
    }
    
    /**
     * Get the page object
     * @return Object 
     */
    public function getPage()
    {
	    return $this->page;
    }
    
    /**
     * Set the content of the page based on a number of templates
     * pass template file locations as individual arguments
     * @return void
     */
    public function buildFromTemplates()
    {
	    $bits = func_get_args();
	    $content = "";
	    foreach( $bits as $bit )
	    {
		    
		    if( strpos( $bit, 'views/' ) === false )
		    {
			    //Comes from the theme settings
			    //print_r($bit);
			    $bit = 'views/' . PHPAppRegistry::getSetting('view') . '/templates/' . $bit;
		    }
		    if( file_exists( $bit ) == true )
		    {
			    $content .= file_get_contents( $bit );
		    }
		    
	    }
	    $this->page->setContent( $content );
    }
    
    /**
     * Convert an array of data into some tags
     * @param array the data 
     * @param string a prefix which is added to field name to create the tag name
     * @return void
     */
    public function dataToTags( $data, $prefix )
    {
	    foreach( $data as $key => $content )
	    {
		    $this->page->addTag( $prefix.$key, $content);
	    }
    }
    
    /**
     * Take the title we set in the page object, and insert them into the view
     */
    public function parseTitle()
    {
	    //print_r('parseTitle triggered...');
	    $newContent = str_replace('<title>', '<title>'. $this->page->getTitle(), $this->page->getContent() );
	    $this->page->setContent( $newContent );
    }
    
    /**
     * Parse the page object into some output
     * @return void
     */
    public function parseOutput()
    {
	    $this->replaceBits();
	    $this->replaceTags(false);
	    $this->replaceTags(true);
	    $this->parseTitle();
    }
    
}
?>