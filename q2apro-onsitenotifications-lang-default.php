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
		'enable_plugin' => 'Enable Plugin',
		'minimum_level' => 'Level to access this page and edit posts:',
		'plugin_disabled' => 'Plugin has been disabled.',
		'access_forbidden' => 'Access forbidden.',
		'plugin_page_url' => 'Open page in forum:',
		'contact' => 'For questions please visit ^1q2apro.com^2',
		'no_notifications_label' => 'If there are no notifications, this will be displayed on the notify bubble:', // Label for notify bubble on top, next to user name
		'admin_maxeventsshow' => 'Maximum number of events to show in notification box:', // extra
		'admin_newwindow' => 'Open notification links in a new browser tab.', // extra
		'admin_rtl' => 'Right to Left (RTL) language. (For Q2A v1.7 and earlier, turn this on)', // extra (EN)

		// plugin
		'my_notifications' => 'My notifications',
		'show_notifications' => 'Show notifications',
		'one_notification' => '1 new notification',
		'x_notifications' => 'new notifications',
		'close' => 'close',
		'in_answer' => 'Answer',
		'in_comment' => 'Comment',
		'in_bestanswer' => 'Best Answer',
		'in_upvote' => 'Upvote',
		'in_downvote' => 'Downvote',
		'you_received' => 'on:',
		'you_received_on_question' => 'on your question:',
		'you_received_on_answer' => 'on your answer:',
		'you_received_on_comment' => 'on your comment:',
		'message_from' => 'sent you a private message:',
		'wallpost_from' => 'posted on your wall:',
	);


/*
	Omit PHP closing tag to help avoid accidental output
*/
