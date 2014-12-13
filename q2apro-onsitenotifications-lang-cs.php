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
		'enable_plugin' => 'Povolit Plugin',
		'minimum_level' => 'Úroveň k přístupu na tuto stránku upravovat své příspěvky:',
		'plugin_disabled' => 'Plugin byl zakázán.',
		'access_forbidden' => 'Přístup zakázán.',
		'plugin_page_url' => 'Otevřít strana na fóru:',
		'contact' => 'Máte-li dotazy, prosím navštivte ^1q2apro.com^2',
		'no_notifications_label' => 'Pokud nejsou k dispozici žádné upozornění, zobrazí se na oznamovací bublině:', // Label for notify bubble on top, next to user name
		'admin_maxeventsshow' => 'Maximální počet akcí poli oznámení:', // extra
		'admin_newwindow' => 'Otevřít odkazy z oznamovacím poli v nové záložce prohlížeče.', // extra
		'admin_rtl' => 'Zprava doleva (RTL) jazyce ( pro Araby ).', // extra (EN)
		
		// plugin
		'my_notifications' => 'Moje oznámení',
		'show_notifications' => 'Zobrazit oznámení',
		'one_notification' => '1 Nové oznámení',
		'x_notifications' => 'Nové oznámení',
		'close' => 'Zavřít',
		'in_answer' => 'Odpověď na',
		'in_comment' => ' Nový komentář k',
		'in_bestanswer' => 'Nejlepší odpověď na',
		'in_upvote' => '+1 Kladné hlasování ',
		'in_downvote' => '-1 Záporného hlasování ',
		'you_received' => 'Obdrželi jste',
		'message_from' => 'soukromou zprávu od',
		'wallpost_from' => 'Nový vzkaz na zdi',
	);


/*
	Omit PHP closing tag to help avoid accidental output
*/