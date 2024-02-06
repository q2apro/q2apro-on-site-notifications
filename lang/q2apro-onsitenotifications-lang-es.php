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
		'enable_plugin' => 'Habilitar plugin',
		'minimum_level' => 'Nivel para acceder a esta página y editar publicaciones:',
		'plugin_disabled' => 'El plugin ha sido deshabilitado',
		'access_forbidden' => 'Acceso Prohibido.',
		'plugin_page_url' => 'Abrir página en el forum:',
		'contact' => 'Si tiene preguntas, visite ^1q2apro.com^2',
		'no_notifications_label' => 'Si no hay notificaciones, esto se mostrará en el cuadro de notificación:', // Label for notify bubble on top, next to user name
		'admin_maxeventsshow' => 'Número máximo de eventos para mostrar en el cuadro de notificación:', // extra
		'admin_newwindow' => 'Abrir enlaces de notificación en una nueva pestaña del navegador.', // extra
		'admin_rtl' => 'Idioma de derecha para izquierda (RTL). (Para versiones Q2A 1.7 y anteriores, active esto)', // extra (EN)

		// plugin
		'my_notifications' => 'Mis Notificaciones',
		'show_notifications' => 'Mostrar notificaciones',
		'one_notification' => '1 nueva notificación',
		'x_notifications' => 'nuevas notificaciones',
		'close' => 'cerrar',
		'in_answer' => 'Respuesta',
		'in_comment' => 'Comentario',
		'in_bestanswer' => 'Mejor respuesta',
		'in_upvote' => 'Voto positivo',
		'in_downvote' => 'Voto negativo',
		'you_received' => 'on:',
		'you_received_on_question' => 'en tu pregunta:',
		'you_received_on_answer' => 'en tu respuesta:',
		'you_received_on_comment' => 'en tu comentario:',
		'message_from' => 'te envió un mensaje privado:',
		'wallpost_from' => 'publicado en tu muro:',
	);


/*
	Omit PHP closing tag to help avoid accidental output
*/
