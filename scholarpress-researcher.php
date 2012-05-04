<?php
/*
Plugin Name: ScholarPress Researcher
Plugin URI: http://scholarpress.net/researcher/
Description: Integrate your Zotero account to create a research blog with WordPress
Version: 1.1
Author: Jeremy Boggs, Sean Takats
Author URI: http://scholarpress.net/
*/

/*
    Copyright (C) 2009-2012, Jeremy Boggs and Sean Takats. All rights reserved.
    
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class ScholarPress_Researcher {
        
    /**
     * ScholarPress Researcher constructor
     *
     * @since 1.0
     * @uses add_action()
     * @uses register_activation_hook()
     * @uses register_deactivation_hook()
     */
	function scholarpress_researcher() {
		add_action( 'init', array ( $this, 'init' ) );
		add_action( 'admin_init', array ( $this, 'admin_init' ) );
		add_action( 'plugins_loaded', array( $this, 'loaded' ) );

		// When Researcher is loaded, get includes.
		add_action( 'scholarpress_researcher_loaded', array( $this, 'includes' ) );

		// When Researcher is initialized, add localization files.
		add_action( 'scholarpress_researcher_init', array( $this, 'textdomain' ) );
		
		// Add shortcode
		add_shortcode('zotero', array($this,'shortcode'));
	}

    /**
     * Adds a plugin initialization action.
     */
	function init() {
		do_action( 'scholarpress_researcher_init' );
	}

	/**
     * Adds a plugin admin initialization action.
     */
	function admin_init() {
	    do_action( 'scholarpress_researcher_admin_init');
	}

	/**
     * Adds a plugin loaded action.
     */
	function loaded() {
		do_action( 'scholarpress_researcher_loaded' );
	}

    /**
     * Includes other necessary plugin files.
     */
	function includes() {
	    require(dirname( __FILE__ ).'/libZotero/build/libZoteroSingle.php');
	}

    /**
     * Handles localization files. Added on scholarpress_researcher_init. 
     * Plugin core localization files are in the 'languages' directory. Users
     * can also add custom localization files in 
     * 'wp-content/scholarpress-courseware-files/languages' if desired.
     *
     * @uses load_textdomain()
     * @uses get_locale()
     */
	function textdomain() {
		$locale = get_locale();
		$mofile_custom = WP_CONTENT_DIR . "/scholarpress-researcher-files/languages/spresearcher-$locale.mo";
		$mofile_packaged = WP_PLUGIN_DIR . "/scholarpress-researcher/languages/spresearcher-$locale.mo";

    	if ( file_exists( $mofile_custom ) ) {
      		load_textdomain( 'spresearcher', $mofile_custom );
      		return;
      	} else if ( file_exists( $mofile_packaged ) ) {
      		load_textdomain( 'spresearcher', $mofile_packaged );
      		return;
      	}
	}
	
	function shortcode($attributes) {
	    // extract the shortcode attributes
        extract(shortcode_atts(array(
            'library_type' => false,
            'library_id' => false,
            'library_slug' => "",
            'api_key' => false,
            'item_key' => false,
            'collection_key' => false,
            'content' => 'bib,coins',
            'style' => false,
            'order' => 'creator',
            'sort' => 'asc',
            'limit' => "100", //restrict the total items we'll fetch to 100 unless overridden
            'format' => false,
            'tag_name' => false,
            'cache_time' => "3600" // cache defaults to one hour, uses APC
        ), $attributes));
        
        $params = array();
        
        if ($collection_key)
            $params['collectionKey'] = $collection_key;

        if ($content)
            $params['content'] = $content;
        
        if ($style)
            $params['style'] = $style;
        
        if ($order)
            $params['order'] = $order;
        
        if ($sort)
            $params['sort'] = $sort;
        
        if ($limit)
            $totalItemLimit = $limit;

        if ($format)
            $params['format'] = $format;
        
        if ($item_key)
            $params['itemKey'] = $item_key;
        
        $base_url = "http://www.zotero.org";
        $library = new Zotero_Library($library_type, $library_id, $library_slug, $api_key, $base_url, $cache_time);

        // code to step through multiple requests for bibliographies longer than 100 items
        
        //start at the beginning of our list by setting an offset of 0
        $offset = 0;
        //limit to 100 items per http request
        //this is the maximum number of items the API will return in a single request
        $perRequestLimit = 100;
        //keep count of the items we've gotten
        $fetchedItemsCount = 0;
        //keep track of whether there are more items to fetch
        $moreItems = true;
        //where we'll keep the list of items we retrieve
        $items = array();
        
        //while there are more items and we haven't gotten to our limit yet
        while(($fetchedItemsCount < $totalItemLimit) && $moreItems){
            //fetching items starting at $offset with $perRequestLimit items per request
            $fetchedItems = $library->fetchItemsTop(array_merge($params, array('limit'=>$perRequestLimit, 'start'=>$offset)));
            //put the items from this last request into our array of items
            $items = array_merge($items, $fetchedItems);
            //update the count of items we've got and offset by that amount
            $fetchedItemsCount += count($fetchedItems);
            $offset = $fetchedItemsCount;
            
            //Zotero_Library keeps track of the last feed it got so we can check if there is a 'next' link
            //indicating more results to be fetched
            if(!isset($library->getLastFeed()->links['next'])){
                $moreItems = false;
            }
        }
        
        return $this->display_zotero_items($items);
	}

	function display_zotero_items($items) {
        $html = '';
        foreach($items as $item) {
            $html .= Zotero_Lib_Utils::wrapLinks($item->bibContent);
            $html .= htmlspecialchars_decode($item->subContents['coins']);
        }
        return wpautop($html);
	}
}

$scholarpressResearcher = new ScholarPress_Researcher();