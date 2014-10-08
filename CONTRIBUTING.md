# Plugin: q2apro-on-site-notifications #

Please read the following carefully.

## Bug reports

Before you [post a bug](https://github.com/q2apro/q2apro-on-site-notifications/issues), please try the following steps that might resolve your issue: 

### Check list to solve the Problem

1. Have you checked the admin panel if the plugin is there and enabled? Go to `admin/plugins`. Have you clicked "Initialize database for this module" (in case it appeared).
2. Remove the history plugin, see if it works now.
3. Login with a different account, vote on your answer. Login with the other account, see if there is a vote. Alternatively: Ask a question, use a different account to answer it, see if you get a notification.
4. Check your server files (the root) for the `error_log` file. Report the content in your issue.
5. Open Firefox Developer Tools > Network. Check if there is a request sent to the server when you click on the "N" (notification bubble) next to the username.
6. Check your database if there are entries in the table `qa_eventlog`.
7. Check your database table `qa_options` if there is "event_logger_to_database" == 1.
8. Check your database table `qa_options` if you find the options "q2apro_onsitenotifications_enabled", "q2apro_onsitenotifications_nill", "q2apro_onsitenotifications_maxage", "q2apro_onsitenotifications_maxevshow" with values.
9. Check your HTML source if the two files "script.js" ans "styles.css" are correctly linked (search for `qa-plugin/q2apro-on-site-notifications/`).
10. Is there the table `qa_usermeta` in your database?
11. Check that the field `site_url` in your database table `qa_options` matches all your URLs.
12. Rewrite all your domains to one domain (e.g. q2apro.com → www.q2apro.com). To do so, use .htaccess. Next is an example how to rewrite all *domains/xyz* to *www.q2apro.com/xyz* (of course, replace *q2apro.com* with your domain name that matches the field `site_url`):
 
	`RewriteCond %{HTTP_HOST} .`  
	`RewriteCond %{HTTP_HOST} !^www\.q2apro\.com [NC]`  
	`RewriteRule (.*) http://www.q2apro.com/$1 [R=301,L]`  

13. Do you use a custom theme? Maybe this is changing the layout and breaking the plugin. We cannot help you for free with that (see note below<sup>1</sup>).


If these steps did not solve your problem and if your issue has not been reported already, then post your detailed bug report. 

### How to do a good Bug Report  

When you post a bug, please note down:

- plugin version and question2answer version
- browser and operating system
- all error messages you get and screenshots of the problem (be as descriptive as possible)
- describe what you did to make the bug appear (it should be reproducible)
- your website with the problem 


## Pull requests

For advanced users: If you have found the cause of a bug and resolved it, you can submit the patch back to the  repository. Create a fork of the repo, make the changes in your fork, then submit a pull request. **All pull requests must be made to the dev branch of Q2A.** The master branch is the current, stable release version.

If you wish to implement a feature, you should start a discussion in the issue list. We welcome all ideas but they may not be appropriate for the Q2A core.

<br />

<sup>1</sup>Note: If you use a custom theme of a third party and the on-site-notifications-plugin does not work with this theme, we cannot help you for free as we don't have the time to dig into foreign codes. Please contact the developer of the custom theme instead.
