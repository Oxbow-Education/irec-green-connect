=== WP Synchro - WordPress Migration Plugin for Database & Files ===
Contributors: wpsynchro
Donate link: https://wpsynchro.com/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=donate
Tags: migrate,clone,files,database,migration
Requires at least: 5.2
Tested up to: 6.4
Stable tag: 1.11.1
Requires PHP: 7.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0

WordPress migration plugin that migrates files, database, media, plugins, themes and whatever you want.

== Description ==

**Complete Migration Plugin for WP professionals**

The only migration tool you will ever need as a professional WordPress developer.
WP Synchro was created to be the migration plugin for developers, with a need to do customized migrations or just full migrations.
You need it done in a fast and easy way, that can be re-run very quickly without any further manual steps, like after a code update.
You can fully customize which database tables you want to move and in PRO version, which files/dirs you want to migrate.

A classic task that WP Synchro will handle for you, is keeping a local development site synchronized with a production site or a staging site in sync with a production site.
You can also push data from your staging or local development enviornment to your production site.

**WP Synchro FREE gives you:**

*   Pull/push database from one site to another site
*   Search/replace in database data (supports serialized data ofc)
*   Handles migration of database table prefixes between sites
*   Select the specific database tables you want to move or just move all
*   Clear cache after migration for popular cache plugins
*   High security - No other sites and servers are involved and all data is encrypted on transfer
*   Setup once - Run multiple times - Perfect for development/staging/production environments

**In addition to this, the PRO version gives you:**

*   File migration (such as media, plugins, themes or custom files/dirs)
*   Only migrate the difference in files, making it super fast
*   Serves a user confirmation on the added/changed/deleted files, before doing any changes
*   Customize the exact migration you need - Down to a single file
*   Support for basic authentication (.htaccess username/password)
*   Notification email on success or failure to a list of emails
*   Database backup before migration
*   WP CLI command to schedule migrations via cron or other trigger
*   Pretty much the ultimate tool for doing WordPress migrations
*   14 day trial is waiting for you to get started at [WPSynchro.com](https://wpsynchro.com/ "WP Synchro PRO")

**Typical use for WP Synchro:**

 *  Developing websites on local server and wanting to push a website to a live server or staging server
 *  Get a copy of a working production site, with both database and files, to a staging or local site for debugging or development with real data
 *  Generally moving WordPress sites from one place to another, even on a firewalled local network

**WP Synchro PRO version:**

Pro version gives you more features, such as synchronizing files, database backup, notifications, support for basic authentication, WP CLI command and much faster support.
Check out how to get PRO version at [WPSynchro.com](https://wpsynchro.com/ "WP Synchro PRO")
We have a 14 day trial waiting for you and 30 day money back guarantee. So why not try the PRO version?

== Installation ==

**Here is how you get started:**

1. Upload the plugin files to the `/wp-content/plugins/wpsynchro` directory, or install the plugin through the WordPress plugins screen directly
1. Make sure to install the plugin on all the WordPress migrations (it is needed on both ends of the synchronizing)
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Choose if data can be overwritten or be downloaded from migration in menu WP Synchro->Setup
1. Add your first migration from WP Synchro overview page and configure it
1. Run the migration
1. Enjoy
1. Rerun the same migration again next time it is needed and enjoy how easy that was

== Frequently Asked Questions ==

= Do you offer support? =

Yes we do, for both free and PRO version. But PRO version users always get priority support, so support requests for the free version will normally take some time.
Check out how to get PRO version at [WPSynchro.com](https://wpsynchro.com/ "WP Synchro site")

You can contact us at <support@wpsynchro.com> for support. Also check out the "Support" menu in WP Synchro, that provides information needed for the support request.

= Does WP Synchro do database merge? =

No. We do not merge data in database. We only migrate the data and overwrite the current.

= Where can i contact you with new ideas and bugs? =

If you have an idea for improving WP Synchro or found a bug in WP Synchro, we would love to hear from you on:
<support@wpsynchro.com>

= What is WP Synchro tested on? (WP Version, PHP, Databases)=

Currently we do automated testing on more than 300 different hosting environments with combinations of WordPress/PHP/Database versions.

WP Synchro is tested on :
 * MySQL 5.5 up to MySQL 8.0 and MariaDB from 10.0 to 10.7.
 * PHP 7.0 up to latest version
 * WordPress from 5.2 to latest version.

= Do you support multisite? =

No, not at the moment.
We have not done testing on multisite yet, so use it is at own risk.
It is currently planned for one of the next releases to support it.

== Screenshots ==

1. Shows the overview of plugin, where you start and delete the migration jobs
2. Shows the add/edit screen, where you setup a migration job
3. Shows the setup of the plugin
4. WP Synchro doing a database migration

== Changelog ==

= 1.11.1 =
 * Bugfix: Fix PHP timeout issue caused by serialized string search/replace handler, that goes into endless loop for defective serialized strings
 * Bugfix: Fix issue with some tables not being migrated when source database is MariaDB and when table does not have a primary key
 * Improvement: Improve the error reporting when database server gives errors

= 1.11.0 =
 * Bugfix: No longer have problems with migration of WordFence database tables
 * Bugfix: Make it possible to resume migrations again, as it was disabled as a mistake
 * Bugfix: No longer block connections to remote sites that do redirects, but instead just give a warning
 * Bugfix: Fix health check where PHP function get_headers() was being blocked by ModSecurity on some sites
 * Bugfix: Give error when trying to migrate "json" data type to MySQL before version 5.7, where it is not supported
 * Bugfix: Fix licensing code, that was giving PHP notices on PHP 8.2
 * Bugfix: Improve database migration, so timeouts should be much less likely to happen
 * Improvement: Improve licensing code to use database option instead of transient, to prevent license server from being overrun by requests
 * Improvement: Bump support for WP 6.4

= 1.10.0 =
 * Improvement: Support for WP 6.3
 * Improvement: Bump PHP support to minimum 7.0, instead of 5.6. Like WP core did at long last.
 * Improvement: Support for PHP 8.2 without deprecation notices
 * Improvement: Bump MariaDB version support to minimum 10.0, from 5.5
 * Improvement: Added WordPress nonces to all actions for added security
 * Improvement: Support for restrictive CSP header (Content Security Policy)
 * Improvement: Handle when remote site redirects to another location, such as WPML redirecting to a specific language subdir
 * Improvement: Handle when migrating database from MySQL 8 with collation utf8mb4_0900_ai_ci to an older version MySQL server
 * Improvement: Improve search/replace in very large serialized data, to prevent out of memory errors and vastly improved performance
 * Improvement: Optimize the way database migration was done to better handle large datasets in rows

= 1.9.1 =
 * Bump support for WP 6.2
 * Improvement: API now flushes data before returning

= 1.9.0 =
 * Bug: Fix issue where MU plugin did not load properly
 * Improvement: Ensure WP 6.1 and PHP 8.1 compatibility
 * Improvement: Improve the warning message when different versions of WP is used
 * Improvement: Add search/replaces for db for cases where the protocol part of the url is wrong in db
 * Improvement: Add check for MU plugin enabled on the target site, when file migration is done, to ensure performance and result
 * Improvement: Add "resume" button when migrations fail, which can used to attempt resume, in such cases where a simple timeout is the problem


** Only showing the last few releases - See rest of changelog in changelog.txt or in menu "Changelog" **
