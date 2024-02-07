<?php
/*
	Plugin Name: On-Site-Notifications
	Plugin URI: http://www.q2apro.com/plugins/on-site-notifications
	Plugin Description: Stackoverflow-like notifications on your question2answer forum that can replace all email-notifications.
	Plugin Version: → see qa-plugin.php
	Plugin Date: → see qa-plugin.php
	Plugin Author: q2apro
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

class q2apro_onsitenotifications_page 
{
	var $directory;
	var $urltoroot;

	function load_module($directory, $urltoroot)
	{
		$this->directory = $directory;
		$this->urltoroot = $urltoroot;
	}

	// for display in admin interface under admin/pages
	function suggest_requests()
	{
		return array(
			array(
				'title' => 'On-Site-Notifications Page', // title of page
				'request' => 'eventnotify', // request name
				'nav' => 'M', // 'M'=main, 'F'=footer, 'B'=before main, 'O'=opposite main, null=none
			),
		);
	}

	// for url query
	function match_request($request)
	{
		if ($request=='eventnotify') {
			return true;
		}
		return false;
	}

	function process_request($request)
	{

		// we received post data, it is the ajax call!
		$transferString = qa_post_text('ajax');
		if( $transferString !== null ) {

			// prevent empty userid
			$userid = qa_get_logged_in_userid();
			if(empty($userid)) {
				echo 'Userid is empty!';
				return;
			}

			// ajax return all user events
			if(isset($userid) && $transferString=='receiveNotify'){
				$last_visit = qa_db_read_one_value(
					qa_db_query_sub(
						'SELECT UNIX_TIMESTAMP(meta_value) FROM ^usermeta WHERE user_id=# AND meta_key="visited_profile"',
						$userid
					), true
				);
				
				// Get amount of points per vote
				$points_multiple = qa_db_read_one_value(qa_db_query_sub('SELECT content FROM ^options WHERE title="points_multiple"'));
				$points_q_voted_up = qa_db_read_one_value(qa_db_query_sub('SELECT content FROM ^options WHERE title="points_per_q_voted_up"'));
				$points_q_voted_down = qa_db_read_one_value(qa_db_query_sub('SELECT content FROM ^options WHERE title="points_per_q_voted_down"'));
				
				$points_a_voted_up = qa_db_read_one_value(qa_db_query_sub('SELECT content FROM ^options WHERE title="points_per_a_voted_up"'));
				$points_a_voted_down = qa_db_read_one_value(qa_db_query_sub('SELECT content FROM ^options WHERE title="points_per_a_voted_down"'));
				
				$points_c_voted_up = qa_db_read_one_value(qa_db_query_sub('SELECT content FROM ^options WHERE title="points_per_c_voted_up"'));
				$points_c_voted_down = qa_db_read_one_value(qa_db_query_sub('SELECT content FROM ^options WHERE title="points_per_c_voted_down"'));
				
				$points_a_selected = qa_db_read_one_value(qa_db_query_sub('SELECT content FROM ^options WHERE title="points_a_selected"'));
				
				// Set amount of points received
				$osn_points_received = '';
				
				
				$event_query = $this->getEventsForUser($userid);

				$events = array();
				$postids = array();
				$count = 0;
				while ( ($event=qa_db_read_one_assoc($event_query,true)) !== null ) {
					if(preg_match('/postid=([0-9]+)/',$event['params'],$m) === 1) {
						$event['postid'] = (int)$m[1];
						$postids[] = (int)$m[1];
						$events[$m[1].'_'.$count++] = $event;
					}
					
					// private message
					if($event['event']=='u_message') {
						// example of $event['params']: userid=1  handle=admin  messageid=4  message=hi admin, how are you?
						$ustring = $event['params'];

						// get messageid
						if(preg_match('/messageid=([0-9]+)/',$ustring,$m) === 1) {
							$event['messageid'] = (int)$m[1];
						}

						// needed for function qa_post_userid_to_handle()
						require_once QA_INCLUDE_DIR.'qa-app-posts.php';
						// get handle from userid, memo: userid from receiver is saved in params (the acting userid is the sender)
						$event['handle'] = qa_post_userid_to_handle($event['userid']);

						// get message preview by cutting out the string
						$event['message'] = qa_html( substr($ustring,strpos($ustring,'message=')+8, strlen($ustring ?? '')-strpos($ustring,'message=')+8) );

						$events[$m[1].'_'.$count++] = $event;
					}
					// wall post
					else if ($event['event']=='u_wall_post') 
					{
						// example of $event['params']: userid=1	handle=admin	messageid=8	content=hi admin!	format=	text=hi admin!
						$ustring = $event['params'];

						// get messageid
						if(preg_match('/messageid=([0-9]+)/',$ustring,$m) === 1) {
							$event['messageid'] = (int)$m[1];
						}

						// needed for function qa_post_userid_to_handle()
						require_once QA_INCLUDE_DIR.'qa-app-posts.php';
						// get handle from userid, memo: userid from receiver is saved in params (the acting userid is the sender)
						$event['handle'] = qa_post_userid_to_handle($event['userid']);

						// get message preview by cutting out the string
						$event['message'] = qa_html( substr($ustring,strpos($ustring,'text=')+5, strlen($ustring ?? '')-strpos($ustring,'text=')+5) );

						$events[$m[1].'_'.$count++] = $event;
					}
					else if ($event['event'] === 'q2apro_osn_plugin') 
					{
						$events['_' . $count++] = $event;
					}
				}

				// get post info, also make sure that post exists
				$posts = null;
				if(!empty($postids)) {
					$post_query = qa_db_read_all_assoc(
						qa_db_query_sub(
							'SELECT postid, type, parentid, BINARY title as title FROM ^posts 
								WHERE postid IN ('.implode(',',$postids).')'
						)
					);
					foreach($post_query as $post) {
						// save postids as index in array $posts with the $post content
						$posts[(string)$post['postid']] = $post;
					}
				}

				// List all events
				$notifyBoxEvents = '<div id="nfyWrap" class="nfyWrap">
					<div class="nfyTop">'.qa_lang('q2apro_onsitenotifications_lang/my_notifications').' <span id="nfyReadClose">'.qa_lang('q2apro_onsitenotifications_lang/close').' | × |</span></div>
					<div class="nfyContainer">
						<div id="nfyContainerInbox">
					';

				foreach ($events as $postid_string => $event)
				{
					// $postid_string, e.g. 32_1 (32 is postid, 1 is global event count)
					$type = $event['event'];

					$eventName = '';
					$itemIcon = '';
					$activity_url = '';
					$linkTitle = '';
					
					if ($type == 'u_message') 
					{
						// $eventName = qa_lang('q2apro_onsitenotifications_lang/you_received').' ';
						$eventName = qa_html($event['handle']);
						$itemIcon = '
							<div class="osn-svg-wrapper nmessage">
								<svg xmlns="http://www.w3.org/2000/svg" class="osn-svg" height="28" width="28" viewbox="0 0 48 48"><path d="M7 40q-1.2 0-2.1-.9Q4 38.2 4 37V11q0-1.2.9-2.1Q5.8 8 7 8h34q1.2 0 2.1.9.9.9.9 2.1v26q0 1.2-.9 2.1-.9.9-2.1.9Zm17-15.1L7 13.75V37h34V13.75Zm0-3L40.8 11H7.25ZM7 13.75V11v26Z"></path></svg>
							</div>
						';
						$activity_url = qa_path_absolute('message').'/'.qa_html($event['handle']);
						$linkTitle = qa_lang('q2apro_onsitenotifications_lang/message_from');
					}
					else if ($type=='u_wall_post') 
					{
						$eventName = qa_html($event['handle']);
						$itemIcon = '
							<div class="osn-svg-wrapper nwallpost">
								<svg xmlns="http://www.w3.org/2000/svg" class="osn-svg" height="28" width="28" viewbox="0 0 48 48"><path d="M9 42q-1.2 0-2.1-.9Q6 40.2 6 39V9q0-1.2.9-2.1Q7.8 6 9 6h19.7v3H9v30h30V19.3h3V39q0 1.2-.9 2.1-.9.9-2.1.9Zm7.05-7.85v-3H32v3Zm0-6.35v-3H32v3Zm0-6.35v-3H32v3ZM34.6 17.8v-4.4h-4.4v-3h4.4V6h3v4.4H42v3h-4.4v4.4Z"></path></svg>
							</div>
						';
						// create link to own wall, needs handle
						require_once QA_INCLUDE_DIR.'qa-app-posts.php';
						$userhandle = qa_post_userid_to_handle($userid);
						$activity_url = qa_path_absolute('user').'/'.qa_html($userhandle).'/wall';
						$linkTitle = qa_lang('q2apro_onsitenotifications_lang/wallpost_from');
					}
					else if ($type=='q2apro_osn_plugin') 
					{
						$eventName = '';
						$itemIcon = '<div class="osn-svg-wrapper ' . $event['icon_class'] . '"></div>';
						$activity_url = '';
						$linkTitle = '';
					}
					else 
					{
						// a_post, c_post, q_vote_up, a_vote_up, q_vote_down, a_vote_down
						$postid = preg_replace('/_.*/','', $postid_string);

						// assign post content (postid,type,parentid,title) if available
						$post = @$posts[$postid];

						$params = $this->getParamsAsArray($event);

						$activity_url = '';
						$linkTitle = '';

						// comment or answer
						if (isset($post) && strpos($event['event'],'q_') !== 0 && strpos($event['event'],'in_q_') !== 0) 
						{
							if (!isset($params['parentid'])) 
							{
								$params['parentid'] = $post['parentid'];
							}

							$parent = qa_db_select_with_pending(qa_db_full_post_selectspec($userid, $params['parentid']));
							
							if ($parent['type'] === 'A') 
							{
								$parent = qa_db_select_with_pending(qa_db_full_post_selectspec($userid, $parent['parentid']));
							}

							$anchor = qa_anchor((strpos($event['event'],'a_') === 0 || strpos($event['event'],'in_a_') === 0?'A':'C'), $params['postid']);
							$activity_url = qa_q_path($parent['postid'], $parent['title'], true, strpos($event['event'],'in_a_') === 0?'A':'C', $params['postid']);
							$linkTitle = $parent['title'];
						}
						// question
						else if (isset($post)) 
						{
							if (!isset($params['title'])) 
							{
								$params['title'] = $posts[$params['postid']]['title'];
							}
							if ($params['title'] !== null) 
							{
								$qTitle = qa_db_read_one_value( qa_db_query_sub("SELECT title FROM `^posts` WHERE `postid` = ".$params['postid']." LIMIT 1"), true );
								
								if (!isset($qTitle)) 
								{
									$qTitle = '';
								}
								
								$activity_url = qa_path_absolute(qa_q_request($params['postid'], $qTitle), null, null);
								$linkTitle = $qTitle;
							}
						}
						
						// Check what type of post Question/Answer/Comment to handle targetted translation
						if (strpos($type,'q_') === 0 
						|| strpos($type,'in_q_vote') === 0 
						|| strpos($type,'in_a_question') === 0
						|| strpos($type,'in_c_question') === 0)
						{
							$eventTypeText = qa_lang('q2apro_onsitenotifications_lang/you_received_on_question');
						}
						else if (strpos($type,'in_a_vote') === 0 || strpos($type,'in_c_answer') === 0) {
							$eventTypeText = qa_lang('q2apro_onsitenotifications_lang/you_received_on_answer');
						} else if (strpos($type,'in_c_vote') === 0) {
							$eventTypeText = qa_lang('q2apro_onsitenotifications_lang/you_received_on_comment');
						} else {
							$eventTypeText = qa_lang('q2apro_onsitenotifications_lang/you_received');
						}

						// event name
						if($type=='in_c_question' || $type=='in_c_answer' || $type=='in_c_comment') { // added in_c_comment
							$eventName = qa_lang('q2apro_onsitenotifications_lang/in_comment');
							$itemIcon = '
							<div class="osn-svg-wrapper ncomment">
								<svg xmlns="http://www.w3.org/2000/svg" class="osn-svg" height="28" width="28" viewbox="0 0 50 50"><path d="M12 28h24v-3H12Zm0-6.5h24v-3H12Zm0-6.5h24v-3H12Zm32 29-8-8H7q-1.15 0-2.075-.925Q4 34.15 4 33V7q0-1.15.925-2.075Q5.85 4 7 4h34q1.2 0 2.1.925Q44 5.85 44 7ZM7 7v26h30.25L41 36.75V7H7Zm0 0v29.75V7Z"></path></svg>
							</div>
							';
						}
						else if($type=='in_q_vote_up' || $type=='in_a_vote_up') {
							$eventName = qa_lang('q2apro_onsitenotifications_lang/in_upvote');
							$itemIcon = '
							<div class="osn-svg-wrapper nvoteup">
								<svg xmlns="http://www.w3.org/2000/svg" class="osn-svg" height="28" width="28" viewbox="12 12 25 25"><path d="m14 28 10-10.05L34 28Z"></path></svg>
							</div>
							';
						}
						else if($type=='in_q_vote_down' || $type=='in_a_vote_down') {
							$eventName = qa_lang('q2apro_onsitenotifications_lang/in_downvote');
							$itemIcon = '
							<div class="osn-svg-wrapper nvotedown">
								<svg xmlns="http://www.w3.org/2000/svg" class="osn-svg" height="28" width="28" viewbox="12 14 25 25"><path d="m24 30-10-9.95h20Z"></path></svg>
							</div>
							';
						}
						else if($type=='in_a_question') {
							$eventName = qa_lang('q2apro_onsitenotifications_lang/in_answer');
							$itemIcon = '
							<div class="osn-svg-wrapper nanswer">
								<svg xmlns="http://www.w3.org/2000/svg" class="osn-svg" height="28" width="28" viewBox="0 0 50 50"><path d="m2 46 3.6-12.75q-1-2.15-1.45-4.425-.45-2.275-.45-4.675 0-4.2 1.575-7.85Q6.85 12.65 9.6 9.9q2.75-2.75 6.4-4.325Q19.65 4 23.85 4q4.2 0 7.85 1.575Q35.35 7.15 38.1 9.9q2.75 2.75 4.325 6.4Q44 19.95 44 24.15q0 4.2-1.575 7.85-1.575 3.65-4.325 6.4-2.75 2.75-6.4 4.325-3.65 1.575-7.85 1.575-2.4 0-4.675-.45T14.75 42.4Zm4.55-4.55 6.9-1.9q.8-.25 1.5-.175.7.075 1.45.375 1.8.7 3.675 1.125 1.875.425 3.775.425 7.15 0 12.15-5t5-12.15Q41 17 36 12T23.85 7Q16.7 7 11.7 12t-5 12.15q0 1.95.275 3.85.275 1.9 1.275 3.6.35.7.375 1.45.025.75-.175 1.5Zm15.8-9.25h3v-6.35h6.4v-3h-6.4v-6.4h-3v6.4h-6.4v3h6.4Zm1.45-8Z"></path></svg>
							</div>
							';
						}
						else if($type=='in_a_select') {
							$eventName = qa_lang('q2apro_onsitenotifications_lang/in_bestanswer');
							$itemIcon = '
							<div class="osn-svg-wrapper nbestanswer">
								<svg xmlns="http://www.w3.org/2000/svg" class="osn-svg" height="28" width="28" viewBox="5 7 38 38"><path d="m20 32.1-7.65-7.65 2.1-2.1L20 27.9l13.55-13.55 2.1 2.1Z"></path></svg>
							</div>
							';
						}
						else {
							// ignore other events such as in_c_flag
							continue;
						}

					} // end a_post, c_post, q_vote_up, a_vote_up, q_vote_down, a_vote_down

					$eventtime = $event['datetime'];

					$whenhtml = qa_html(qa_time_to_string(qa_opt('db_time')-$eventtime));
					
					$when = qa_lang_html_sub('main/x_ago', $whenhtml);

					// extra CSS for highlighting new events
					$cssNewEv = '';
					
					if ($eventtime > $last_visit) 
					{
						$cssNewEv = '-new';
					}
					
					// Check amount of points received
					$osn_points_received = '';
					
					if ($type == 'in_q_vote_up')
					{
						$osn_points_received = $points_multiple * $points_q_voted_up;
						$osn_points_received = '<span class="osn-gained-points">+'. $osn_points_received .'</span>';
					}
					else if($type=='in_q_vote_down') {
						$osn_points_received = $points_multiple * $points_q_voted_down;
						$osn_points_received = '<span class="osn-gained-points osn-gained-points-down">-'. $osn_points_received .'</span>';
					}
					else if($type=='in_a_vote_up') {
						$osn_points_received = $points_multiple * $points_a_voted_up;
						$osn_points_received = '<span class="osn-gained-points">+'. $osn_points_received .'</span>';
					}
					else if($type=='in_a_vote_down') {
						$osn_points_received = $points_multiple * $points_a_voted_down;
						$osn_points_received = '<span class="osn-gained-points osn-gained-points-down">-'. $osn_points_received .'</span>';
					}
					else if($type=='in_c_vote_up') {
						$osn_points_received = $points_multiple * $points_c_voted_up;
						$osn_points_received = '<span class="osn-gained-points">+'. $osn_points_received .'</span>';
					}
					else if($type=='in_c_vote_down') {
						$osn_points_received = $points_multiple * $points_c_voted_down;
						$osn_points_received = '<span class="osn-gained-points osn-gained-points-down">-'. $osn_points_received .'</span>';
					}
					else if($type=='in_a_select') {
						$osn_points_received = $points_multiple * $points_a_selected;
						$osn_points_received = '<span class="osn-gained-points">+'. $osn_points_received .'</span>';
					}

					// if post has been deleted there is no link, dont output
					// if own Wall Post, dont output
					if ($activity_url == '' && $type !== 'q2apro_osn_plugin' 
					|| $type == 'u_wall_post' && qa_post_userid_to_handle($event['userid']) == qa_get_logged_in_user_field('handle'))
					{
						continue;
					}
					else 
					{							
						
						// Add Boldness to the first word/username
						if ($type != 'u_message' && $type != 'u_wall_post')
						{
							$eventName = '<b>'.$eventName.'</b> ' . $eventTypeText;
						} else {
							$eventName = '<b>'.$eventName.'</b>';
						}
						
						$eventHtml = $type === 'q2apro_osn_plugin'
							? $event['event_text']
							: $eventName . qa_html(' '. $linkTitle); // Give space for phrase
						
						
						$notifyBoxEvents .= 
						'<div class="itemBox'.$cssNewEv.'">
							<a href="' . $activity_url . '"' . (qa_opt('q2apro_onsitenotifications_newwindow') ? ' target="_blank"' : '') . '>
								<div class="nicon">'. $itemIcon . $osn_points_received .'</div>
								<div class="nfyItemLine">
									<p class="nfyWhat">
										'. $eventHtml . ($type == 'u_message' || $type == 'u_wall_post' ? ' ' . qa_html($event['message']) : '') .'
									</p>
									<p class="nfyTime">'. $when .'</p>
								</div>
							</a>
						</div>';
					}
				} // END FOREACH

				$notifyBoxEvents .= '</div>
					</div>
				</div>
				';
				
				header('Access-Control-Allow-Origin: '.qa_path(''));
				header("Content-type: text/html; charset=utf-8");
				echo $notifyBoxEvents;

				$this->markAsReadForUserId($userid);

				exit();
			} // END AJAX RETURN
			else {
				echo 'Unexpected problem detected! No userid, no transfer string.';
				exit();
			}
		}


		/* start */
		$qa_content = qa_content_prepare();

		$qa_content['title'] = ''; // page title

		// return if not admin!
		if(qa_get_logged_in_level() < QA_USER_LEVEL_ADMIN) {
			$qa_content['error'] = '<p>Access denied</p>';
			return $qa_content;
		}
		else {
			$qa_content['custom'] = '<p>Hi Admin, it actually makes no sense to call the Ajax URL directly.</p>';
		}

		return $qa_content;
	}

	/**
	 * Update database entry so that all user notifications are seen as read
	 *
	 * @param $userid
	 */
	private function markAsReadForUserId($userid)
	{
		qa_db_query_sub(
			'INSERT INTO ^usermeta (user_id,meta_key,meta_value) VALUES(#,$,NOW()) ON DUPLICATE KEY UPDATE meta_value=NOW()',
			$userid, 'visited_profile'
		);
	}

	/**
	 * @param $event
	 * @return array
	 */
	private function getParamsAsArray($event)
	{
		$params = array();
		// explode string to array with values (memo: leave "\t", '\t' will cause errors)
		$paramsa = explode("\t", $event['params'] ?? '');
		foreach ($paramsa as $param) {
			$parama = explode('=', $param ?? '');
			if (isset($parama[1])) {
				$params[$parama[0]] = $parama[1];
			} else {
				$params[$param] = $param;
			}
		}

		return $params;
	}

	/**
	 * @param $userid
	 * @return mixed
	 */
	private function getEventsForUser($userid)
	{
		$maxEvents = qa_opt('q2apro_onsitenotifications_maxevshow'); // maximal events to show

		$currentTime = (int)qa_opt('db_time');
		$maxageTime = $currentTime - (int)qa_opt('q2apro_onsitenotifications_maxage') * 86400;

		$event_query = qa_db_query_sub(
			'(
				SELECT
					e.event,
					e.userid,
					BINARY e.params as params,
					UNIX_TIMESTAMP(e.datetime) AS datetime,
					"" `icon_class`,
					"" event_text
				FROM ^eventlog e
				WHERE
					FROM_UNIXTIME(#) <= datetime AND
					(e.userid = # AND e.event LIKE "in_%") OR
					(e.event IN ("u_message", "u_wall_post") AND e.params LIKE "userid=#\t%")
			) UNION (
				SELECT
					"q2apro_osn_plugin" `event`,
					`user_id` `userid`,
					"" `params`,
					UNIX_TIMESTAMP(`created_at`) `datetime`,
					`icon_class`,
					`event_text`
				FROM ^q2apro_osn_plugin_notifications
				WHERE FROM_UNIXTIME(#) <= `created_at` AND `user_id` = #
			)
			ORDER BY datetime DESC
			LIMIT #', // Limit
			$maxageTime, // events of last x days
			$userid,
			$userid,
			$maxageTime, // events of last x days
			$userid,
			$maxEvents
		);
		return $event_query;
	}

};
