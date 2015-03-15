<?php
/*
Plugin Name: Beyond The Whiteboard RSS Fixer
Version: 1.1.1
Plugin URI: http://karma.net
Description: Combines multiple workouts in a single day to a single event
Author: Jay Colson
Author URI: http://karma.net/
*/

/**
 * Add menu item to Options menu.
 */
function btwb_core_options()
{
	if (function_exists('add_options_page'))
	{
		add_options_page('Beyond The Whiteboard RSS Fixer', 'Beyond The Whiteboard RSS Fixer', 8, 'btwb', 'btwb_core_options_page');
	}
}

/**
 * Trigger the adding of the menu option.
 */
add_action('admin_menu', 'btwb_core_options');

/**
 * Draw normal options page.
 */
function btwb_core_options_page()
{

?>

<div style="margin:50px auto; text-align:center;">
	<h3>Beyond The Whiteboard RSS Fixer</h3>
	<p>Plugin is loaded - use cron to activate the
	http://yourwebsite.com/wp-content/plugins/btwb/reparse.php?url=http://beyondthewhiteboard.com/gyms/4860-threefold-crossfit/wods.atom
	(or something similar) script.  This plugin requires simplepie-core plugin as well.</p>
</div>

<?php
}
?>