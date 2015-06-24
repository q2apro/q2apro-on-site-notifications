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
				$this->output('<script type="text/javascript">
						var eventnotifyAjaxURL = "'.qa_path('eventnotify').'";
					</script>');  
				$this->output('<script type="text/javascript" src="'.QA_HTML_THEME_LAYER_URLTOROOT.'script.js"></script>');
				$this->output('<link rel="stylesheet" type="text/css" href="'.QA_HTML_THEME_LAYER_URLTOROOT.'styles.css">');
				
				// hack for snow flat theme (q2a v1.7) to show the notification icon outside the user's drop down
				if(qa_opt('site_theme')=='SnowFlat') {
					$this->output('
					<script type="text/javascript">
						$(document).ready(function(){
							// $("#osnbox").detach().appendTo(".qam-account-items-wrapper");
							var elem = $("#osnbox").detach();
							$(".qam-account-items-wrapper").prepend(elem);
						});
					</script>
					');
				}
				
				// hack for snow theme (q2a v1.6) to position the notification box more to the right
				if(qa_opt('site_theme')=='Snow') {
					$this->output('
					<style type="text/css">
						#nfyWrap {
							left:-100px;
						}
					</style>
					');
				}
			
				// from q2a v1.7 we can use: $isRTL = $this->isRTL; but prior q2a versions can not, so we provide an admin option				
				if(qa_opt('q2apro_onsitenotifications_rtl')) {
					$this->output('
					<style type="text/css">
						#nfyReadClose {
							float:left !important;
						}
						.nfyWrap .nfyTop {
							text-align:right;
						}
						.nfyContainer {
							direction: rtl !important;
							text-align: right !important;
						}
						.nfyWrap .nfyFooter {
							text-align:left;
						}
						.nfyIcon {
							float:right;
						}
						.nfyWrap .nfyItemLine {
							float:right;
							margin-right:5px;
						}
						/* Snow Flat hacks */
						.qam-account-items-wrapper #osnbox {
							float: right;
							margin-right:-30px;
						}
						.qam-account-items-wrapper .nfyWrap {
							top: 31px;
							left: 0;
						}
					</style>
					');
				}
				
			} // end enabled
		} // end head_script
		
		function doctype() {
			/* The following code originates from q2a plugin "History" by NoahY and has been modified by q2apro.com
			 * It is licensed under GPLv3 http://www.gnu.org/licenses/gpl.html
			 * Link to plugin: https://github.com/NoahY/q2a-history
			 */
			$userid = qa_get_logged_in_userid();
			if(qa_opt('q2apro_onsitenotifications_enabled') && $userid) {

				$last_visit = qa_db_read_one_value(
					qa_db_query_sub(
						'SELECT UNIX_TIMESTAMP(meta_value) FROM ^usermeta WHERE user_id=# AND meta_key=$',
						$userid, 'visited_profile'
					),
					true
				);

				// first time visitor, we set the last visit manually in the past
				if(is_null($last_visit)) {
					$last_visit = '1981-03-31 06:25:00';
				}
				
				// select and count all in_eventcount that are newer as last visit
				$eventcount = qa_db_read_one_value(
					qa_db_query_sub(
						'SELECT COUNT(event) FROM ^eventlog 
								WHERE FROM_UNIXTIME(#) <= datetime 
								AND DATE_SUB(CURDATE(),INTERVAL # DAY) <= datetime 
								AND (
								(userid=# AND event LIKE "in_%")
								OR ((event LIKE "u_message" OR event LIKE "u_wall_post") AND params LIKE "userid=#\t%")
								)
								',
								$last_visit,
								qa_opt('q2apro_onsitenotifications_maxage'), 
								$userid, 
								$userid
					)
				);
				
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
							<a class="osn-new-events-link" title="'.$tooltip.'"><span class="notifybub ntfy-event-'. $classSuffix.'">'.$eventcount.'</span></a>
						</div>';
				
				// add to user panel
				$this->content['loggedin']['suffix'] = @$this->content['loggedin']['suffix']. ' ' . $html;
			}
			
			qa_html_theme_base::doctype();
		}

	} // end qa_html_theme_layer
	
/*
	Omit PHP closing tag to help avoid accidental output
*/
