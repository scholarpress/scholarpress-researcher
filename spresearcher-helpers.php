<?php

/**
 * Sets the values for Zotero options.
 * 
 * @since 1.0
 * @uses get_option()
 * @uses update_option()
 * @uses add_option()
 * @param string $userId
 * @param string $apiKey
 **/
function spresearcher_set_options($zoteroUserId, $zoteroApiKey) 
{
	$spresearcherOptions = array(
	    'zotero_user_id' => $zoteroUserId,
	    'zotero_api_key' => $zoteroApiKey
	);
        
    $spresearcherOptionsName = 'SpResearcherOptions';
    
    if ( get_option($spresearcherOptionsName) ) {
	    update_option($spresearcherOptionsName, $spresearcherOptions);
    } else {
        $deprecated=' ';
        $autoload='no';
        add_option($spresearcherOptionsName, $spresearcherOptions, $deprecated, $autoload);
    }
}

/**
 * Returns all the fields for Zotero options as an array.
 * 
 * @since 1.0
 * @uses get_option()
 * @return array
 **/
function spresearcher_get_options()
{
    $spresearcherSavedOptions = get_option('SpResearcherOptions');
	if (!empty($spresearcherSavedOptions)) {
		foreach ($spresearcherSavedOptions as $key => $value)
			$spresearcherOptions[$key] = $value;
		}
	return $spresearcherOptions;
}