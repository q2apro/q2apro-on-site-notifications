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

	/* The following code originates from q2a plugin "History" by NoahY and has been modified by q2apro.com
	 * It is licensed under GPLv3 http://www.gnu.org/licenses/gpl.html
	 * Link to plugin file: https://github.com/NoahY/q2a-history/blob/master/qa-history-check.php
	 */

	class q2apro_onsitenotifications_event {
	// main event processing function

		function process_event($event, $userid, $handle, $cookieid, $params) {

			if(!qa_opt('event_logger_to_database')) return;

			if ($event === 'q2apro_osn_plugin') {
				qa_db_query_sub(
					'INSERT INTO ^q2apro_osn_plugin_notifications(plugin_id, event_text, icon_class, user_id, created_at) ' .
					'VALUES ($, $, $, $, NOW())',
					$params['plugin_id'], $params['event_text'], $params['icon_class'], $params['user_id']
				);

				return;
			}

			// needed for function qa_post_userid_to_handle()
			require_once QA_INCLUDE_DIR.'qa-app-posts.php';

			$twoway = array(
				'a_select',
				'q_vote_up',
				'a_vote_up',
				'q_vote_down',
				'a_vote_down',
				//'a_unselect',
				//'q_vote_nil',
				//'a_vote_nil',
				//'q_flag',
				//'a_flag',
				//'c_flag',
				//'q_unflag',
				//'a_unflag',
				//'c_unflag',
				//'u_edit',
				//'u_level',
				//'u_block',
				//'u_unblock',
			 );

			 $special = array(
				'a_post',
				'c_post'
			);

			if(in_array($event, $twoway)) {

				if(strpos($event,'u_') === 0) {
					$uid = $params['userid'];
				}
				else {
					$uid = qa_db_read_one_value(
						qa_db_query_sub(
							'SELECT userid FROM ^posts WHERE postid=#',
							$params['postid']
						),
						true
					);
				}

				if($uid != $userid) {
					$ohandle = qa_post_userid_to_handle($uid);

					$oevent = 'in_'.$event;

					$paramstring='';

					foreach ($params as $key => $value) {
						$paramstring.=(strlen($paramstring) ? "\t" : '').$key.'='.$this->value_to_text($value);
					}

					// write in_ events to qa_eventlog
					qa_db_query_sub(
						'INSERT INTO ^eventlog (datetime, ipaddress, userid, handle, cookieid, event, params) '.
						'VALUES (NOW(), $, $, $, #, $, $)',
						qa_remote_ip_address(), $uid, $ohandle, $cookieid, $oevent, $paramstring
					);
				}
			}

			// comments and answers
			if(in_array($event,$special)) {
				// userid (recent C)
				$uid = qa_db_read_one_value(
					qa_db_query_sub(
						'SELECT userid FROM ^posts WHERE postid=#',
						$params['postid']
					),
					true
				);
				// userid (QA)
				$pid = qa_db_read_one_value(
					qa_db_query_sub(
						'SELECT userid FROM ^posts WHERE postid=#',
						$params['parentid']
					),
					true
				);
				// if QA poster is not the same as commenter
				if($pid != $userid) {

					$ohandle = qa_post_userid_to_handle($pid);

					switch($event) {
						case 'a_post':
								$oevent = 'in_a_question';
							break;
						case 'c_post':
							if ($params['parenttype'] == 'Q')
								$oevent = 'in_c_question';
							else
								$oevent = 'in_c_answer';
							break;
					}

					$paramstring='';

					foreach ($params as $key => $value)
						$paramstring.=(strlen($paramstring) ? "\t" : '').$key.'='.$this->value_to_text($value);

					qa_db_query_sub(
						'INSERT INTO ^eventlog (datetime, ipaddress, userid, handle, cookieid, event, params) '.
						'VALUES (NOW(), $, $, $, #, $, $)',
						qa_remote_ip_address(), $pid, $ohandle, $cookieid, $oevent, $paramstring
					);
				}

				// q2apro: added logging for comments in thread
				if($event=='c_post') {
					$oevent = 'in_c_comment';

					// check if we have more comments to the parent
					// DISTINCT: if a user has more than 1 comment just select him unique to inform him only once
					$precCommentsQuery = qa_db_query_sub('SELECT DISTINCT userid FROM `^posts`
												WHERE `parentid` = #
												AND `type` = "C"
												AND `userid` IS NOT NULL
												',
												$params['parentid']);

					while( ($comment = qa_db_read_one_assoc($precCommentsQuery,true)) !== null ) {
						$userid_CommThr = $comment['userid']; // unique

						// don't inform user that comments, and don't inform user that comments on his own question/answer
						if($userid_CommThr != $uid && $userid_CommThr != $pid) {
							$ohandle = qa_post_userid_to_handle($userid_CommThr);

							$paramstring = '';
							foreach ($params as $key => $value) {
								$paramstring .= (strlen($paramstring) ? "\t" : '').$key.'='.$this->value_to_text($value);
							}

							qa_db_query_sub(
								'INSERT INTO ^eventlog (datetime, ipaddress, userid, handle, cookieid, event, params) '.
								'VALUES (NOW(), $, $, $, #, $, $)',
								qa_remote_ip_address(), $userid_CommThr, $ohandle, $cookieid, $oevent, $paramstring
							);
						}
					}
				} // end in_c_comment

			} // end in_array
		} // end process_event


		// worker functions
		function value_to_text($value) {
			if (is_array($value))
				$text='array('.count($value).')';
			elseif (strlen($value)>40)
				$text=substr($value, 0, 38).'...';
			else
				$text=$value;

			return strtr($text, "\t\n\r", '   ');
		}

	} // end class


/*
	Omit PHP closing tag to help avoid accidental output
*/
