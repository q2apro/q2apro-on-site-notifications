# Question2Answer Plugin: On-Site-Notifications #

----------

## Description ##

Facebook-like / Stackoverflow-like notifications on your [Question2Answer](http://www.question2answer.org/) forum that can replace all email-notifications.


## Features ##

- users get notifications about the following events: incoming answer, incoming comment, question was up- or downvoted, answer was up- or downvoted, answer got selected as best, incoming private message
- yellow notify bubble on top next to the username
- easy to use for all your users
- notification box shows new events and also events of last 100 days (specify days in plugin options)
- notifies on: incoming answer, incoming comment, best answers and upvotes
- lightweight plugin that does not need any extra server resources
- available languages: de, en, fr, lt

Do something good for your users and improve their usability experience in your forum!

See [demo video here](https://www.youtube.com/watch?v=C86rdJkGP3k).


## Installation ##

See quick [video tutorial for installation](https://www.youtube.com/watch?feature=player_detailpage&v=C86rdJkGP3k#t=90) or follow these steps:

- Download the plugin as ZIP from [github](https://github.com/q2apro/q2apro-on-site-notifications).
- Make a full backup of your q2a database before installing the plugin.
- Extract the folder ``q2apro-on-site-notifications`` from the ZIP file.
- Move the folder ``q2apro-on-site-notifications`` to the ``qa-plugin`` folder of your Q2A installation.
- Use your FTP-Client to upload the folder ``q2apro-on-site-notifications`` into the qa-plugin folder of your server.
- Navigate to your site, go to **Admin -> Plugins** and check if the plugin "On-Site-Notifications" is listed.
- Change the plugin options if you like.
- Congratulations, your new plugin has been activated!


## For developers ##

It is possible to generate custom notifications from other plugins that will be displayed in the notification list. To do so, an event
needs to be fired with the `q2apro_osn_plugin` event id and with the following parameters:

  * `plugin_id`: ID of the plugin that generated the event. It is not used by the notifications plugin but it is added to the table
  in case an efficient query needs to be run against the table that contains the plugin notifications
  * `user_id`: The user ID that will receive the notification
  * `event_text`: The raw HTML that will be displayed as the notification
  * `icon_class`: A CSS class that must be present in every request that could display the notification

```php
qa_report_event('q2apro_osn_plugin', qa_get_logged_in_userid(), qa_get_logged_in_handle(), null, array(
    'plugin_id' => 'my_plugin_id',
    'user_id' => $receiverUserId,
    'event_text' => '<a href="http://site.com/user/user1">user1</a> mentioned you in <a href="http://site.com/154">this post</a>',
    'icon_class' => 'my_plugin_id_and_css_class',
));
```

## Disclaimer ##

The code is probably okay for production environments, but may not work exactly as expected. You bear the risk. Refunds will not be given!

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU General Public License for more details.


## Copyright ##

All code herein is [OpenSource](http://www.gnu.org/licenses/gpl.html). Feel free to build upon it and share with the world.


## Final Note ##

If you use the plugin:

  * Translate the plugin into your language.
  * Report any bug you find.
  * Consider a painless donation to https://www.paypal.me/q2apro

Have fun! Life is short.
