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

class Scholarpress_Researcher {

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
		add_shortcode('spresearcher', array($this,'shortcode'));
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
	
	function shortcode($attrs) {
	    return 'ScholarPress Researcher';
	}
}

$scholarpressResearcher = new ScholarPress_Researcher();