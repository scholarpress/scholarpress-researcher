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
    Copyright (C) 2009, Jeremy Boggs. All rights reserved.    
    
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

// Define plugin version number
define(SPRESEARCHER_VERSION_NUMBER, '1.0');

// Define path to the plugin
$spresearcher_path = ABSPATH . PLUGINDIR . DIRECTORY_SEPARATOR . 'scholarpress-researcher/';

include_once 'spresearcher-helpers.php';
include_once 'spresearcher-admin.php';