<?php
/*
	Plugin Name: On-Site-Notifications
	Plugin URI: http://www.q2apro.com/plugins/on-site-notifications
	Plugin Description: Facebook-like / Stackoverflow-like notifications on your question2answer forum that can replace all email-notifications.
	Plugin Version: → see qa-plugin.php
	Plugin Date: → see qa-plugin.php
	Plugin Author: q2apro.com
	Plugin Author URI: http://www.q2apro.com/
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: → see qa-plugin.php
	Plugin Update Check URI: https://raw.githubusercontent.com/q2apro/q2apro-on-site-notifications/master/qa-plugin.php
	
	This program is free software. You can redistribute and modify it 
	under the terms of the GNU General Public License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.gnu.org/licenses/gpl.html

*/

	return array(
		// default
		'enable_plugin' => 'Plugin aktivieren', // Enable Plugin (checkbox)
		'minimum_level' => 'Auf Seite zugreifen und Posts bearbeiten können:', // Level to access this page and edit posts:
		'plugin_disabled' => 'Dieses Plugin wurde deaktiviert.', // Plugin has been disabled.
		'access_forbidden' => 'Zugriff nicht erlaubt.', // Access forbidden.
		'plugin_page_url' => 'Seite im Forum öffnen:', // Open page in forum:
		'contact' => 'Bei Fragen bitte ^1q2apro.com^2 besuchen.', // For questions please visit ^1q2apro.com^2
		'no_notifications_label' => 'Wenn es keine Benachrichtigungen gibt, soll dieses Zeichen angezeigt werden:', // Label for notify bubble on top, next to user name
		'admin_maxeventsshow' => 'Maximal anzuzeigende Events im Benachrichtigungsfenster:', // extra
		'admin_newwindow' => 'Links im Benachrichtigungsfenster im neuen Browser-Tab öffnen.', // extra
		'admin_rtl' => 'Rechts-nach-Links (RTL) Sprache.', // extra (EN)
		
		// plugin
		'my_notifications' => 'Meine Benachrichtigungen',
		'show_notifications' => 'Benachrichtigungen anzeigen',
		'one_notification' => '1 neue Benachrichtigung',
		'x_notifications' => 'neue Benachrichtigungen',
		'close' => 'schließen',
		'in_answer' => 'Antwort bei',
		'in_comment' => 'Kommentar zu',
		'in_bestanswer' => 'Beste Antwort bei',
		'in_upvote' => 'Pluspunkt für',
		'in_downvote' => 'Minuspunkt für',
		'you_received' => 'Du hast eine',
		'message_from' => 'private Nachricht von',
		'wallpost_from' => 'Nachricht auf deiner Wall von',
	);


/*
	Omit PHP closing tag to help avoid accidental output
*/