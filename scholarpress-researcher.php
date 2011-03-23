<?php
/*
Plugin Name: ScholarPress Researcher
Plugin URI: http://scholarpress.net/researcher/
Description: Integrate your Zotero account to create a research blog with WordPress
Version: 1.0
Author: Jeremy Boggs
Author URI: http://scholarpress.net/
*/

/*
    Copyright (C) 2009-2011, Jeremy Boggs. All rights reserved.
    
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
	    require(dirname( __FILE__ ).'/phpZotero/phpZotero.php');
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
            'user_id' => false,
            'group_id' => false,
            'view'  => 'items',
            'api_key' => false,
            'collection_key' => false,
            'item_key' => false,
            'tag_name' => false,
            'content' => "bib",
            'style' => false,
            'order' => 'author',
            'sort' => 'asc',
            'limit' => "50",
            'format' => false
        ), $attributes));
        
        $params = array();
        
        if ($style)
            $params['style'] = $style;
        
        if ($sort)
            $params['sort'] = $sort;
        
        if ($content)
            $params['content'] = $content;
        
        if ($limit)
            $params['limit'] = $limit;

        if ($order)
            $params['order'] = $order;
        
        if ($format)
            $params['format'] = $format;
        
        // $apiKey = $this->getZoteroApiKey();
        
        $zotero = new phpZotero($api_key);
        
        if ($group_id) {
            if ($item_key) {
                $item = $zotero->getGroupItem($group_id, $item_key, $params);
                return $this->display_zotero_item($item);
            } else {
                if ($items = $zotero->getGroupItemsTop($group_id, $params)) {
                    return $this->display_zotero_items($items);
                }
            }
            return "ScholarPress Researcher Group #$group_id";
        } else {
            if ($user_id) {
                if ($item_key) {
                    $item = $zotero->getUserItem($user_id, $item_key, $params);
                    return $this->display_zotero_item($item);
                } else {
                    if ($items = $zotero->getUserItemsTop($user_id, $params)) {
                        return $this->display_zotero_items($items);
                    }
                }
            }
        }   
	}

	function display_zotero_items($entries) {
        if($items = $entries->getElementsByTagName("entry")){
            $html = '';
            foreach($items as $item) {
                $html .= $this->display_zotero_item($item);
            }
            return $html;
        }
	}
	
	function display_zotero_item($item) {
        if($item->getElementsByTagName("content")->length > 0){
            $html = $item->getElementsByTagName("content")->item(0)->nodeValue;
            return wpautop($html);
        }
	}
	
	function display_zotero_tags($tags) {
	    return 'Zotero tags!';
	}
	
	function display_zotero_tag($tag) {
	    return 'Zotero tag!';
	}
	
	function display_zotero_collections($collections) {
	    return 'Zotero collections!';
	}
	
	function display_zotero_collection($collection) {
	    return 'Zotero collection!';
	}
	
	function display_zotero_groups($groups) {
	    return 'Zotero groups!';
	}
}

$scholarpressResearcher = new ScholarPress_Researcher();