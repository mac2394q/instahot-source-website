=== GamiPress - Notifications ===
Contributors: gamipress, tsunoa, rubengc, eneribs
Tags: gamipress, gamification, point, achievement, rank, badge, award, reward, credit, engagement, ajax
Requires at least: 4.4
Tested up to: 5.0
Stable tag: 1.2.5
License: GNU AGPLv3
License URI: http://www.gnu.org/licenses/agpl-3.0.html

Instantly notify of achievements, steps and/or points awards completion to your users.

== Description ==

Notifications gives you the ability to live notify to your users about new achievements, steps, points awards, points deductions, ranks and/or rank requirements completion.

While your users are interacting with your site, they will get notified without refresh the page when an action gives them something related to GamiPress.

Also, you can configure text patterns to show as example the user name to make notifications more personalized.

= Features =

* Ability to live notify to your users about new achievements, steps, points awards, points deductions, ranks and/or rank requirements completion.
* Ability to selectively disable which notify to your users.
* Ability to positioning the notification in 8 different positions.
* Ability to set the lifetime of new notifications.
* Ability to enable the click to hide on notifications.
* Customizable notification sound effects.
* Easy controls to customize the background and text colors of notifications.
* Ability to enable the notification auto hide and the delay to perform it.
* Ability to disable live notifications checks making notifications work just on page load.
* Ability to configure each notification title pattern.
* Ability to configure the achievements look (thumbnail, earners, steps, etc).
* Ability to configure the ranks look (thumbnail, earners, requirements, etc).
* Integrated with the official add-ons that add new content to achievements and ranks.

== Installation ==

= From WordPress backend =

1. Navigate to Plugins -> Add new.
2. Click the button "Upload Plugin" next to "Add plugins" title.
3. Upload the downloaded zip file and activate it.

= Direct upload =

1. Upload the downloaded zip file into your `wp-content/plugins/` folder.
2. Unzip the uploaded zip file.
3. Navigate to Plugins menu on your WordPress admin area.
4. Activate this plugin.

== Frequently Asked Questions ==

== Changelog ==

= 1.2.5 =

* **New Features**
* New setting to customize the notification width on big screens.
* Supoort to responsive notifications on small screens.

= 1.2.4 =

* **Improvements**
* Added extra checks to determine if notification sound files are correctly set.
* **Bug Fixes**
* Fixed errors caused by empty or wrong sound files sources.

= 1.2.3 =

* **Improvements**
* Improved sound effect compatibility with newer browsers.
* Avoid Notify.js conflicts by renaming plugin notify() function to gamipress_notify().

= 1.2.2 =

* **New Features**
* Added support to GamiPress 1.7.0.

= 1.2.1 =

* **Bug Fixes**
* Fixed mark already displayed notifications with a small live check delay setup.
* **Developer Notes**
* Added new hooks to make add-on more extensible.

= 1.2.0 =

**Improvements**
* Prevent empty notifications.
* Improved sound effect compatibility with older browsers.
* Reset public changelog (moved old changelog to changelog.txt file).
* **Developer Notes**
* Added new hooks to make add-on more extensible.
