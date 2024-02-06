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
		'enable_plugin' => 'Ativar Plugin',
		'minimum_level' => 'Nível para acessar esta página e editar postagens:',
		'plugin_disabled' => 'O plugin foi desativado.',
		'access_forbidden' => 'Acesso proibido.',
		'plugin_page_url' => 'Abrir página no fórum:',
		'contact' => 'Para perguntas, visite ^1q2apro.com^2',
		'no_notifications_label' => 'Se não houver notificações, isto será exibido na caixa de notificação:', // Label for notify bubble on top, next to user name
		'admin_maxeventsshow' => 'Número máximo de eventos a serem exibidos na caixa de notificações:', // extra
		'admin_newwindow' => 'Abrir links de notificação em uma nova aba do navegador.', // extra
		'admin_rtl' => 'Idiomas da direita para a esquerda (RTL). (Para versões do Q2A 1.7 e anteriores, ative esta opção)', // extra (EN)

		// plugin
		'my_notifications' => 'Minhas notificações',
		'show_notifications' => 'Mostrar notificações',
		'one_notification' => '1 nova notificação',
		'x_notifications' => 'novas notificações',
		'close' => 'fechar',
		'in_answer' => 'Resposta',
		'in_comment' => 'Comentário',
		'in_bestanswer' => 'Melhor resposta',
		'in_upvote' => 'Voto positivo',
		'in_downvote' => 'Voto negativo',
		'you_received' => 'em:',
		'you_received_on_question' => 'na sua pergunta:',
		'you_received_on_answer' => 'na sua resposta:',
		'you_received_on_comment' => 'no seu comentário:',
		'message_from' => 'enviou uma mensagem privada:',
		'wallpost_from' => 'publicou no seu mural:',
	);


/*
	Omit PHP closing tag to help avoid accidental output
*/
