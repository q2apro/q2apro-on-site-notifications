/*
	Plugin Name: On-Site-Notifications
	Plugin URI: http://www.q2apro.com/plugins/on-site-notifications
	Plugin Description: Facebook-like / Stackoverflow-like notifications on your question2answer forum that can replace all email-notifications.
	Plugin Version: 1.0
	Plugin Date: 2014-03-29
	Plugin Author: q2apro.com
	Plugin Author URI: http://www.q2apro.com/
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI: https://raw.githubusercontent.com/q2apro/q2apro-on-site-notifications/master/qa-plugin.php
	
	This program is free software. You can redistribute and modify it 
	under the terms of the GNU General Public License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.gnu.org/licenses/gpl.html

*/

$(document).ready(function(){

	$(document).on('click', '#nfyReadClose', function() {
		$('#nfyWrap').fadeOut(500, function(){$(this).remove() });
	});
	$('.osn-new-events-link').click(function() {
		// user clicked on N bubble again to hide event-box
		if( $('#nfyWrap').length>0 && $('#nfyWrap').is(':visible') ) {
			$('#nfyWrap').fadeOut(500, function(){$(this).remove() });
		}
		else {
			var evrequest = 'receiveNotify';
			$.ajax({
				 type: 'POST',
				 url: eventnotifyAjaxURL, // root
				 data: {ajax:evrequest},
				 cache: false,
				 success: function(data) {
					// remove Event-Box if formerly loaded
					$('#nfyWrap').fadeOut(500, function(){$(this).remove() });
					// insert ajax-loaded html 
					// $('.qa-nav-user').append(data);
					$('.osn-new-events-link').after(data);
					// make yellow notification bubble gray
					$('.ntfy-event-new').addClass('ntfy-read');
				 }
			});
		}
	});
	
	// fade out notifybox if visible on stage
	$(document).click(function(event) { 
		if($(event.target).parents().index($('#nfyWrap')) == -1) {
			if($('#nfyWrap').is(':visible')) {
				$('#nfyWrap').fadeOut(500, function(){$(this).remove() });
			}
		}        
	})

});
