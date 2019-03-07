<?php
/*
	Plugin Name: On-Site-Notifications
	Plugin URI: 
	Plugin Description: Facebook-like / Stackoverflow-like notifications on your question2answer forum that can replace all email-notifications.
	Plugin Version: 1.3.0
	Plugin Date: 2018-08-23
	Plugin Author: q2apro.com
	Plugin Author URI: q2apro
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: 1.6
	Plugin Update Check URI: 

	This program is free software. You can redistribute and modify it
	under the terms of the GNU General Public License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.gnu.org/licenses/gpl.html

*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

// language file
qa_register_plugin_phrases('q2apro-onsitenotifications-lang-*.php', 'q2apro_onsitenotifications_lang');

// page for ajax
qa_register_plugin_module('page', 'q2apro-onsitenotifications-page.php', 'q2apro_onsitenotifications_page', 'On-Site-Notifications Page');

// layer
qa_register_plugin_layer('q2apro-onsitenotifications-layer.php', 'q2apro On-Site-Notifications Layer');

// admin
qa_register_plugin_module('module', 'q2apro-onsitenotifications-admin.php', 'q2apro_onsitenotifications_admin', 'q2apro On-Site-Notifications Admin');

// track events
qa_register_plugin_module('event', 'q2apro-onsitenotifications-event.php', 'q2apro_onsitenotifications_event', 'q2apro History Check Mod');


// cache function for notification count +1
function q2apro_notifycount_increase($userid)
{
	if(!empty($userid))
	{
		// central qa_notifycount table
		qa_db_query_sub('
			INSERT INTO ^notifycount (userid, notifycount) VALUES(#, 1) 
			ON DUPLICATE KEY UPDATE userid = #, notifycount = (notifycount+1)
			',
			$userid, $userid
		);
	}
}

// cache function to nill the notification count
function q2apro_notifycount_nill($userid)
{
	if(!empty($userid))
	{
		// central qa_notifycount table
		qa_db_query_sub('
			INSERT INTO ^notifycount (userid, notifycount) VALUES(#, 0) 
			ON DUPLICATE KEY UPDATE userid = #, notifycount = 0
			',
			$userid, $userid
		);
	}
}
