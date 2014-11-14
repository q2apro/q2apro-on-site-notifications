<?php
/*
	Plugin Name: On-Site-Notifications
	Plugin URI: http://www.q2apro.com/plugins/on-site-notifications
	Plugin Description: Facebook-like / Stackoverflow-like notifications on your question2answer forum that can replace all email-notifications.
	Plugin Version: 1.2
	Plugin Date: 2014-11-14
	Plugin Author: q2apro.com
	Plugin Author URI: http://www.q2apro.com/
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: 1.6
	Plugin Update Check URI: https://raw.githubusercontent.com/q2apro/q2apro-on-site-notifications/master/qa-plugin.php
	
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
	qa_register_plugin_module('event', 'q2apro-history-check.php','q2apro_history_check','Q2APRO History Check Mod');


/*
	Omit PHP closing tag to help avoid accidental output
*/