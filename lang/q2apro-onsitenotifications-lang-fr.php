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
		'enable_plugin' => 'Activer le plugin', // Enable Plugin (checkbox)
		'minimum_level' => 'Niveau pour accéder à cette page et à la fonction de modification des posts :', // Level to access this page and edit posts:
		'plugin_disabled' => 'Le plugin a été désactivé.', // Plugin has been disabled.
		'access_forbidden' => 'Accès interdit.', // Access forbidden.
		'plugin_page_url' => 'Ouvrir la page plugin dans le forum:', // Open page in forum:
		'contact' => 'Si vous avez des questions, visitez  ^1q2apro.com^2.', // For questions please visit ^1q2apro.com^2
		'no_notifications_label' => 'S\'il n\'y a pas de notifications, ce sera affiché sur la bulle notifier:', // Label for notify bubble on top, next to user name
		'admin_maxeventsshow' => 'Nombre maximal d\'événements à afficher dans la boîte de notification:', // extra
		'admin_newwindow' => 'Ouvrez les liens de notification dans un nouvel onglet du navigateur.', // extra (EN)
		'admin_rtl' => 'Langues de droite à gauche (RTL). (Pour Q2A v1.7 et versions antérieures, activez cette option)', // extra (EN)

		// plugin
		'my_notifications' => 'Mes notifications',
		'show_notifications' => 'Afficher les notifications',
		'one_notification' => '1 nouvelle notification',
		'x_notifications' => 'nouvelles notifications',
		'close' => 'fermer',
		'in_answer' => 'Répondre',
		'in_comment' => 'Commentaire',
		'in_bestanswer' => 'Meilleure réponse',
		'in_upvote' => 'Vote positif',
		'in_downvote' => 'Vote négatif',
		'you_received' => 'à:',
		'you_received_on_question' => 'sur ta question:',
		'you_received_on_answer' => 'sur ta réponse:',
		'you_received_on_comment' => 'sur ton commentaire:',
		'message_from' => 't\'a envoyé un message privé:',
		'wallpost_from' => 'a posté sur ton mur:',
	);


/*
	Omit PHP closing tag to help avoid accidental output
*/
