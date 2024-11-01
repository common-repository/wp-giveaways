=== WP Giveaways ===
Contributors: zoranc
Donate link: http://bit.ly/1dIyktC
Tags: giveaway, giveaways, sweepstake, sweepstakes, encourage subscriptions, prize, gift, subscription, program, raffle, system, lottery
Author URI: http://zoranc.co/
Plugin URI: http://zoranc.co/wp-giveaways/
Requires at least: 3.0
Tested up to: 3.9
Stable tag: 1.0.1
License: GPLv2

== Description ==

Encourage subscriptions to your site/newsletters with a giveaway/sweepstakes program.

**Features:**

* Pick a random winner(s) from a pool of subscribers (or any role you select)

* Set up a custom email template used to email the prize

* You can attach files or simply email out the links to the prize(s)

* You can create as many giveaways as you wish

* Each giveaway can be set as a recurring giveaway or a onetime deal

* You can limit the contestant pool to the subscribers that registered since the giveaway was initially published

* View the history for each of the giveaways in terms of draw dates and the associated winners

* You can choose how many winners to announce for each giveaway
 
* Each giveaway has a shortcode that allows you to show the date of the next scheduled draw.

* Utilizes WP Cron to schedule the draw

== Installation ==
Installation procedure and the documentation for this plugin is hosted on the [official WP Giveaways forum](http://zoranc.co/support/topic/wp-giveaways-installation-guide/).

== Frequently Asked Questions ==

= Integration with MailPoet? =

This comes as a premium addon. You can get it [here](http://store.zoranc.co/downloads/wp-giveaways-addon-mailpoet-integration/)

= Integration with AWeber? =

Coming soon as a premium extension.

= I can't see the WP Giveaways anywhere in my dashboard? =

This can be an issue if there are a few plugins competing for the menu position. You can change the menu position by

Editing 

`wp-content/plugins/wp-giveaways/includes/custom-post-type-giveaways.php`
look for the following line (LINE 41):
`'menu_position'       => 28,`

and try changing 28 to 27, 26 ... until the `Giveaways` menu item appears

= How does the scheduling with WP Cron work? =

All the giveaways are checked twice daily to determine if a draw is due and whether a winner should be picked and emailed. 

WP Cron gets triggered only when a visitor browses your site so if your website has low traffic, this may be an issue that would require a workaround as the draw would occur only when someone visits your site after the draw date is scheduled to occur. For really high traffic sites wp cron might have been disabled to improve performance. 

In both of these cases, there are available workarounds. I suggest reading a free, in depth tutorial at [tutsplus](http://code.tutsplus.com/articles/insights-into-wp-cron-an-introduction-to-scheduling-tasks-in-wordpress--wp-23119)

If you are still not too sure on how to achieve this, I am available for contract work. You can contact me [here](http://zoranc.co/contact/)

= How can I check all the scheduled crons? =

You can download this free wordpress plugin by Simon Wheatley - [Cron GUI](http://wordpress.org/extend/plugins/cron-view/)

= What are the available Filter Hooks? =

You can find the list of available filter hooks [here](http://zoranc.co/support/topic/filter-hooks/). I will expand on each hook and provide relative examples in the future.

For documantation, support questions, feedback and ideas, please use the [official WP Giveaways forum](http://zoranc.co/support/wp-giveaways).

== Changelog ==
= 1.0.1 =
bugfix: prevent Giveaway history custom field from being overwritten when saving giveaway post
= 1.0.0 =
Initial Release

== Upgrade Notice ==


== Screenshots ==
1. Giveaway Options
