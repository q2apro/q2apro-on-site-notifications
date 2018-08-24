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

	class q2apro_onsitenotifications_admin {

		// initialize db-table 'eventlog' if it does not exist yet
		function init_queries($tableslc) {
			require_once QA_INCLUDE_DIR.'qa-app-users.php';
			require_once QA_INCLUDE_DIR.'qa-db-maxima.php';
			require_once QA_INCLUDE_DIR.'qa-app-options.php';

			$result = array();

			$tablename = qa_db_add_table_prefix('eventlog');

			// check if event logger has been initialized already (check for one of the options and existing table)
			if(qa_opt('event_logger_to_database') && in_array($tablename, $tableslc)) {
				// options exist, but check if really enabled
				if(qa_opt('event_logger_to_database')=='' && qa_opt('event_logger_to_files')=='') {
					// enabled database logging
					qa_opt('event_logger_to_database', 1);
				}
			}
			else {
				// not enabled, let's enable the event logger

				// set option values for event logger
				qa_opt('event_logger_to_database', 1);
				qa_opt('event_logger_to_files', '');
				qa_opt('event_logger_directory', '');
				qa_opt('event_logger_hide_header', '');

				if (!in_array($tablename, $tableslc)) {
					$result[] = 'CREATE TABLE IF NOT EXISTS ^eventlog ('.
						'datetime DATETIME NOT NULL,'.
						'ipaddress VARCHAR (15) CHARACTER SET ascii,'.
						'userid '.qa_get_mysql_user_column_type().','.
						'handle VARCHAR('.QA_DB_MAX_HANDLE_LENGTH.'),'.
						'cookieid BIGINT UNSIGNED,'.
						'event VARCHAR (20) CHARACTER SET ascii NOT NULL,'.
						'params VARCHAR (800) NOT NULL,'.
						'KEY datetime (datetime),'.
						'KEY ipaddress (ipaddress),'.
						'KEY userid (userid),'.
						'KEY event (event)'.
					') ENGINE=MyISAM DEFAULT CHARSET=utf8';
				}
			}
			// memo: would be best to check if plugin is installed in qa-plugin folder or using plugin_exists()
			// however this functionality is not available in q2a v1.6.3

			// create table qa_usermeta which stores the last visit of each user
			$tablename2 = qa_db_add_table_prefix('usermeta');
			if (!in_array($tablename2, $tableslc)) {
				$result[] =
					'CREATE TABLE IF NOT EXISTS ^usermeta (
					meta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					user_id bigint(20) unsigned NOT NULL,
					meta_key varchar(255) DEFAULT NULL,
					meta_value longtext,
					PRIMARY KEY (meta_id),
					UNIQUE (user_id,meta_key)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8'
				;
			}

			// create table qa_usermeta which stores the last visit of each user
			$tablename3 = qa_db_add_table_prefix('q2apro_osn_plugin_notifications');
			if (!in_array($tablename3, $tableslc)) {
				$result[] =
					'CREATE TABLE IF NOT EXISTS ^q2apro_osn_plugin_notifications (' .
					'plugin_id VARCHAR(50) NOT NULL, ' .
					'event_text VARCHAR(2000) NOT NULL, ' .
					'icon_class VARCHAR(50) NOT NULL, ' .
					'user_id ' . qa_get_mysql_user_column_type() . ' NOT NULL, ' .
					'created_at DATETIME NOT NULL, ' .
					'KEY ^q2apro_osn_plugin_notifications_idx1 (user_id, created_at), ' .
					'KEY ^q2apro_osn_plugin_notifications_idx2 (plugin_id, user_id, created_at)' .
					') ENGINE=MyISAM  DEFAULT CHARSET=utf8'
				;
			}

			return $result;
		} // end init_queries

		// option's value is requested but the option has not yet been set
		function option_default($option) {
			switch($option) {
				case 'q2apro_onsitenotifications_enabled':
					return 1; // true
				case 'q2apro_onsitenotifications_nill':
					return 'N'; // days
				case 'q2apro_onsitenotifications_maxage':
					return 365; // days
				case 'q2apro_onsitenotifications_maxevshow':
					return 100; // max events to show in notify box
				case 'q2apro_onsitenotifications_newwindow':
					return 1; // true
				case 'q2apro_onsitenotifications_rtl':
					return 0; // false
				default:
					return null;
			}
		}

		function allow_template($template) {
			return ($template!='admin');
		}

		function admin_form(&$qa_content){

			// process the admin form if admin hit Save-Changes-button
			$ok = null;
			if (qa_clicked('q2apro_onsitenotifications_save')) {
				qa_opt('q2apro_onsitenotifications_enabled', (bool)qa_post_text('q2apro_onsitenotifications_enabled')); // empty or 1
				qa_opt('q2apro_onsitenotifications_nill', qa_post_text('q2apro_onsitenotifications_nill')); // string
				qa_opt('q2apro_onsitenotifications_maxevshow', (int)qa_post_text('q2apro_onsitenotifications_maxevshow')); // int
				qa_opt('q2apro_onsitenotifications_newwindow', (bool)qa_post_text('q2apro_onsitenotifications_newwindow')); // int
				qa_opt('q2apro_onsitenotifications_rtl', (bool)qa_post_text('q2apro_onsitenotifications_rtl')); // int
				$ok = qa_lang('admin/options_saved');
			}

			// form fields to display frontend for admin
			$fields = array();

			$fields[] = array(
				'type' => 'checkbox',
				'label' => qa_lang('q2apro_onsitenotifications_lang/enable_plugin'),
				'tags' => 'name="q2apro_onsitenotifications_enabled"',
				'value' => qa_opt('q2apro_onsitenotifications_enabled'),
			);


			$fields[] = array(
				'type' => 'input',
				'label' => qa_lang('q2apro_onsitenotifications_lang/no_notifications_label'),
				'tags' => 'name="q2apro_onsitenotifications_nill" style="width:100px;"',
				'value' => qa_opt('q2apro_onsitenotifications_nill'),
			);

			$fields[] = array(
				'type' => 'input',
				'label' => qa_lang('q2apro_onsitenotifications_lang/admin_maxeventsshow'),
				'tags' => 'name="q2apro_onsitenotifications_maxevshow" style="width:100px;"',
				'value' => qa_opt('q2apro_onsitenotifications_maxevshow'),
			);

			$fields[] = array(
				'type' => 'checkbox',
				'label' => qa_lang('q2apro_onsitenotifications_lang/admin_newwindow'),
				'tags' => 'name="q2apro_onsitenotifications_newwindow"',
				'value' => qa_opt('q2apro_onsitenotifications_newwindow'),
			);

			$fields[] = array(
				'type' => 'checkbox',
				'label' => qa_lang('q2apro_onsitenotifications_lang/admin_rtl'),
				'tags' => 'name="q2apro_onsitenotifications_rtl"',
				'value' => qa_opt('q2apro_onsitenotifications_rtl'),
			);

			$fields[] = array(
				'type' => 'static',
				'note' => '<span style="font-size:12px;color:#789;">'.strtr( qa_lang('q2apro_onsitenotifications_lang/contact'), array(
							'^1' => '<a target="_blank" href="http://www.q2apro.com/plugins/on-site-notifications">',
							'^2' => '</a>'
						  )).'</span>',
			);

			return array(
				'ok' => ($ok && !isset($error)) ? $ok : null,
				'fields' => $fields,
				'buttons' => array(
					array(
						'label' => qa_lang_html('main/save_button'),
						'tags' => 'name="q2apro_onsitenotifications_save"',
					),
				),
			);
		}
	}


/*
	Omit PHP closing tag to help avoid accidental output
*/
