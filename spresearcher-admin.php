<?php 

include_once 'spresearcher-helpers.php';

// Insert links into the plugin hook list for 'admin_menu'
add_action('admin_menu', 'spresearcher_admin_menu');

function spresearcher_admin_menu() {
    add_menu_page('SP Researcher','SP Researcher', 8,'scholarpress-researcher','spresearcher_manage');
}

function spresearcher_manage() {
    if ($_POST['save_options']) {
        $zoteroUserId = !empty($_REQUEST['zotero_user_id']) ? $_REQUEST['zotero_user_id'] : '';
        $zoteroApiKey = !empty($_REQUEST['zotero_api_key']) ? $_REQUEST['zotero_api_key'] : '';
        
        spresearcher_set_options($zoteroUserId, $zoteroApiKey);
    }
    
    $spresearcherOptions = spresearcher_get_options();
    
    ?>
    <div class="wrap">
    <h2>ScholarPress Researcher</h2>
    
    <br class="clear" />
    <form method="post">
        		<input type="hidden" name="updateinfo" value="<?php echo $mode?>" />
        <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <label for="zotero_user_id">Zotero User ID</label>
            </th>
            <td>
                <input name="zotero_user_id" type="text" id="user_id" value="<?php echo $spresearcherOptions['zotero_user_id']; ?>" class="regular-text" />
                <p class="description">The ID for your user. If you do not know your ID, you can find it under <a href="https://www.zotero.org/settings/keys">Settings > Feeds API</a> in your Zotero.org profile.</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="zotero_api_key">API Key</label>
            </th>
            <td>
                <input name="zotero_api_key" type="text" id="course_number" value="<?php echo $spresearcherOptions['zotero_api_key']; ?>" class="regular-text" />
                <p class="description">Your API Key. You can create one under <a href="https://www.zotero.org/settings/keys">Settings > Feeds API</a> in your Zotero.org profile.</p>
            </td>
        </tr>
        
        </table>
        <p class="submit">
        <input type="submit" name="save_options" class="button-primary" value="Save Changes" />
        </p>
        
    </form>
    <br class="clear" />
    </div>
<?php }
