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

	class qa_html_theme_layer extends qa_html_theme_base {

		function head_script(){
			qa_html_theme_base::head_script();
			// only load if enabled and user logged-in
			if(qa_opt('q2apro_onsitenotifications_enabled') && qa_is_logged_in()) {
				
				// Don't Lazy Load these to avoid layout shift
				$this->output('
				<style type="text/css">
					/* On Site Notifications - Q2A */
					#osnbox {
						position: relative;
						display: inline-block;
						vertical-align: middle;
						border-radius: 30px;
						max-width: 43px;
						max-height: 43px;
					}
					.osn-bell {
						position: relative;
						display: inline-block;
						padding: .5rem;
						color: inherit;
						text-align: center;
						cursor: pointer;
					}
					.ntfy-read, .ntfy-event-nill {
						display: none;
					}
				</style>
				');
				
				$this->output('
					<link rel="preload" as="style" href="'.QA_HTML_THEME_LAYER_URLTOROOT.'css/osn-styles.min.css?v=44" onload="this.onload=null;this.rel=\'stylesheet\'">
					<noscript><link rel="stylesheet" href="'.QA_HTML_THEME_LAYER_URLTOROOT.'css/osn-styles.min.css?v=44"></noscript>
				');

				// hack for snow theme (q2a v1.6) to position the notification box more to the right
				if(qa_opt('site_theme')=='Snow') {
					$this->output('
					<style type="text/css">
						/* On Site Notifications - Q2A */
						.osn-bell {
							padding: 0rem 0.4rem;
						}
						body.qa-theme-snow .nfyWrap {
							left: 0;
							right: initial !important;
						}
					</style>
					');
				}

				// from q2a v1.7 we can use: $isRTL = $this->isRTL; but prior q2a versions can not, so we provide an admin option
				if(qa_opt('q2apro_onsitenotifications_rtl') || $this->isRTL) {
					$this->output('
					<style type="text/css">
						/* On Site Notifications - Q2A */
						.nfyWrap {
							right: initial;
							left: 0;
							text-align: right !important;
						}
						.nfyContainer {
							direction: rtl !important;
							text-align: right !important;
						}
						#nfyReadClose {
							float: left;
						}
						.itemBox {
							padding-right: initial;
							padding-left: 1rem;
						}
					</style>
					');
				}

			} // end enabled
		} // end head_script
		
		public function footer()
		{
			qa_html_theme_base::footer();
			
			if(qa_opt('q2apro_onsitenotifications_enabled') && qa_is_logged_in()) {
				$this->output('
				<script type="text/javascript">
					// On Site Notifications - Q2A
					var eventnotifyAjaxURL = "'.qa_path('eventnotify').'";
				</script>
				');
				$this->output('<script type="text/javascript" src="'.QA_HTML_THEME_LAYER_URLTOROOT.'js/script.js?v=34"></script>');
			}
		}

		function nav_user_search() {
			/* The following code originates from q2a plugin "History" by NoahY and has been modified by q2apro.com
			 * It is licensed under GPLv3 http://www.gnu.org/licenses/gpl.html
			 * Link to plugin: https://github.com/NoahY/q2a-history
			 */
			$userid = qa_get_logged_in_userid();
			if(qa_opt('q2apro_onsitenotifications_enabled') && $userid) {

				$last_visit = $this->getLastVisitForUser($userid);

				// select and count all in_eventcount that are newer as last visit
				$eventcount = $this->getEventCount($last_visit, $userid);

				// q2apro notification tooltip
				if ($eventcount > 0) {
					if ($eventcount == 1) {  // only one event
						$tooltip = qa_lang('q2apro_onsitenotifications_lang/one_notification');
					} else {
						$tooltip = $eventcount.' '.qa_lang('q2apro_onsitenotifications_lang/x_notifications');
					}
					$classSuffix = 'new';  // add notify bubble to user navigation highlighted
				}
				else {
					$tooltip = qa_lang('q2apro_onsitenotifications_lang/show_notifications');
					$eventcount = qa_opt('q2apro_onsitenotifications_nill');
					$classSuffix = 'nill';  // add notify bubble to user navigation
				}

				$html = '<div id="osnbox">
							<div class="osn-new-events-link osn-bell" title="'.$tooltip.'">
								<svg xmlns="http://www.w3.org/2000/svg" class="osn-svg" height="24" width="24" viewbox="0 0 50 50"><path d="M8 38v-3h4.2V19.7q0-4.2 2.475-7.475Q17.15 8.95 21.2 8.1V6.65q0-1.15.825-1.9T24 4q1.15 0 1.975.75.825.75.825 1.9V8.1q4.05.85 6.55 4.125t2.5 7.475V35H40v3Zm16-14.75ZM24 44q-1.6 0-2.8-1.175Q20 41.65 20 40h8q0 1.65-1.175 2.825Q25.65 44 24 44Zm-8.8-9h17.65V19.7q0-3.7-2.55-6.3-2.55-2.6-6.25-2.6t-6.275 2.6Q15.2 16 15.2 19.7Z"></path></svg>
								<span class="notifybub ntfy-event-'. $classSuffix.'">'.$eventcount.'</span>
							</div>
						</div>';

				// add to user panel
				if(qa_opt('site_theme')=='Snow' || qa_opt('site_theme')=='Classic' || qa_opt('site_theme')=='Candy') {
					// Fix for old themes
					$this->content['loggedin']['suffix'] = @$this->content['loggedin']['suffix']. ' ' . $html;
				} else {
					// Output normal - Modern themes
					$this->output($html);
				}
			}

			qa_html_theme_base::nav_user_search();
		}
		
		

		/**
		 * @param int $last_visit
		 * @param mixed $userid
		 * @return int
		 */
		private function getEventCount($last_visit, $userid)
		{
			$currentTime = (int)qa_opt('db_time');
			$maxageTime = $currentTime - (int)qa_opt('q2apro_onsitenotifications_maxage') * 86400;
			$fromTime = max($maxageTime, $last_visit);

			$eventlogCount = qa_db_read_one_value(qa_db_query_sub(
				'SELECT COUNT(event) FROM ^eventlog ' .
				'WHERE datetime >= FROM_UNIXTIME(#) AND (' .
				'(userid = # AND event LIKE "in_%") OR ' .
				'(event IN ("u_message", "u_wall_post") AND params LIKE "userid=#\t%")' .
				')',
				$last_visit,
				$userid,
				$userid
			));

			$pluginCount = qa_db_read_one_value(qa_db_query_sub(
				'SELECT COUNT(*) FROM ^q2apro_osn_plugin_notifications ' .
				'WHERE user_id = # AND created_at >= FROM_UNIXTIME(#)',
				$userid, $fromTime
			));

			return $eventlogCount + $pluginCount;
		}

		/**
		 * @param $userid
		 * @return int
		 */
		private function getLastVisitForUser($userid)
		{
			$last_visit = (int)qa_db_read_one_value(
				qa_db_query_sub(
					'SELECT UNIX_TIMESTAMP(meta_value) FROM ^usermeta WHERE user_id=# AND meta_key=$',
					$userid, 'visited_profile'
				),
				true
			);

			// first time visitor, we set the last visit manually in the past
			if (is_null($last_visit)) {
				$last_visit = 0;
			}
			return $last_visit;
		}

	} // end qa_html_theme_layer
