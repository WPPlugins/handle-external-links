=== Handle External Links ===
Plugin URI: http://www.getwebinspiration.com/wordpress-plugin-handle-external-links/
Contributors: getwebinspiration
Donate link: http://www.getwebinspiration.com/wordpress-plugin-handle-external-links/
Tags: nofollow,pagerank,follow,meta,rel,rel nofollow,seo,handle external links,link rel,nofollow link,external link, external link nofollow, plugin, post, page,
Requires at least: 3
Tested up to: 3.5
Stable tag: 1.1.5
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Handle External Links gives you the power to add rel="nofollow" based on header status (i.e. 404), PageRank of destination URL and many more options.

== Description ==
Handle External Links helps you to control when to set rel="nofollow" on external links in Wordpress. You can set nofollow depending on the PageRank, 404 status, whether the URL is blacklisted or not, and  many more flexible criterias! Visit <a href="http://www.getwebinspiration.com">getwebinspiration.com</a> for more Plugin details.

== Installation ==

1. Upload the directory `handle-external-links` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Choose settings by clicking on the 'Settings' tab in WordPress and navigate to 'Handle External Links'.

== Frequently Asked Questions ==

= How to set a "best before date" for my posts? =

In your admin panel, click on the 'Settings' menu tab and navigate to 'Handle External Links'. Here you can pick a date that will affect all posts/pages older than this value.

= How does "Set Nofollow on 404's" work? =

This feature will automatically ping the URLs to check whether the page returns a 404 status or not. If there's a 404 it will set the link to rel="nofollow".

= How do I set nofollow based on PageRank? =

Navigate to the Settings page and select a PageRank from the dropdown. Note: If you select PR 9 it will result in setting nofollow on all your external links because it's practically impossible to have a greater PR.

= Will my whitelisted links ever have a rel="nofollow" attached? = 

No.

== Screenshots ==

1. Options page from the Dashboard.

2. Sample post with settings applied.

== Changelog ==
= 1.1.5 = 
* Added a whitelist and blacklist to improve the filter
* It's now possible to add target="_blank" to external URLs
* Option to set nofollow on URLs that doesn’t return a PageRank value
* Now echos a warning if the cURL extension isn't installed on the server (required)

= 1.1 =
* Minor code improvements - changed the way the plugin handles internal links

= 1.0 =
* First version was released with three different settings.

== Upgrade Notice ==

== Support ==

* [Plugin Homepage](http://www.getwebinspiration.com/wordpress-plugin-handle-external-links/)
* [Author mail](mailto:getwebinspiration@gmail.com)

== Donations ==

[Donations](http://www.getwebinspiration.com/wordpress-plugin-handle-external-links/) are very much appreciated and will be used to dedicate time to support and updates.