=== Embed Bible Passages ===
Contributors: drmikegreen
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XR9J849YUCJ3A
Tags: inline Bible passages, Bible reading plans, shortcode, Bible, Book of Common Prayer, Lectionary, daily readings, ESV Bible Web Service
Requires at least: 2.7
Tested up to: 4.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides the ability to embed Bible Readings Plans into a post or page using shortcode.

== Description ==

Provides the ability to embed Bible reading plans from the ESV Bible Web Service (http://www.esvapi.org/api#readingPlanQuery) into a post or page using shortcode of the form [embed_bible_passage reading_plan='bcp']. See the screenshots for an example of how to use this tag.

The values of reading_plan can be:
    bcp						- Book of Common Prayer
    lsb						- Literary Study Bible
    esv-study-bible			- ESV Study Bible
    every-day-in-the-word	- Every Day in the Word
    one-year-tract			- M'Cheyne One-Year Reading Plan
    outreach				- Outreach
    outreach-nt				- Outreach New Testament
    through-the-bible		- Through the Bible in a Year
The default reading plan is bcp.

Note that only the bcp and through-the-bible options have been tested for this plugin. The other options are provided by the ESV Bible Web Service and should also work.

For more information about these reading plans, please see the ESVBible.org Devotions area (http://www.esvbible.org/ and select Plans menu item).

The page opens with the plan reading for the current date, as set on the client computer. An optional date picker calendar is available to enable users to choose readings for other dates.

A tag to embed the current date is also available [embed_passage_date], although this is deprecated in favor of using the date picker calendar.

The readings are provided with a link to an audio file to enable users to listen to the readings. For iPad and iPhone this file is in mp3 format. For all other systems it is in the ESV Bible Web Service API default Flash format.

Copyright 2011-2017 M.D. Green, SaeSolved:: LLC

== License ==

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

== Installation ==

1. Apply for an access code at http://www.esvapi.org/signup.

1. Extract the embed-bible-passages folder and place in the wp-content/plugins folder. Enable the plugin.

1. Enter your access code at your site's WordPress Dashboard under Settings -> Embed Bible Passages.

1. Select which default audio format you wish to use, MP3 or Flash.

1. Select whether or not to provide the ability for users to select passages for days other than the current day by clicking on a calendar.

1. Select whether or not to optionally show a "Powered by" attribution at the bottom of pages.

1. Create pages and/or posts containing the shortcode of the form [embed_bible_passage reading_plan='bcp'].

NOTE THAT THE COPYRIGHT NOTICE FROM THE SOURCE OF THE TEXT CROSSWAY BIBLE MUST BE KEPT ON THE PAGE (protected variable $esv_copyright).

== Frequently Asked Questions ==

= Where can I see working examples of this plugin? =

http://church-savior.com/todaysbiblereadings/

http://www.thebiblechallenge.org/

= Firefox for Android plays all audios on the page whenever it is opened =

This has been corrected in version 1.5.

= "Listen" doesn't work or work conveniently on mobile devices. How can this be corrected? =

This has been (at least largely) corrected in version 1.4. The plugin now passes all of the tests at http://www.responsinator.com/.

= "Listen" is displayed in very large font. How can this be corrected? =

This phenomenon appears to be caused by some other plugin or theme setting which surrounds the code which displays the word "Listen" to be displayed to be enclosed by div tags which impose the style of the class used in those tags on the contents of that pair of div tags. Changing the style of the class should correct this problem.

In addition, Version 1.2 contains a change to the way the Scriptures are loaded into the page with the intention that other plugin or theme settings will not be able to enclose the code which displays the word "Listen" in div tags.

= How can the date be made to be that of the local time zone? =

Version 1.3 presents the Bible readings for the day according to the time set on the user's computer.

== Screenshots ==

1. Sample input for page of Through the Bible in a Year.

2. Sample result for page of Through the Bible in a Year.

3. Settings page.

== Upgrade Notice ==

= 1.6.2 =
This version cleans up some input screen layout issues.

= 1.6.1 =
This version corrects the path to wp-includes.

= 1.6 =
This version provides an option to place the calendar inside the first block of text. (Calendar will always be above the text for screens less than 480px wide.)

= 1.5.2 =
This version delays loading the Bible Readings until the HTML page is completely loaded to help resolve formating issues in some cases.

= 1.5.1 =
This version inserts a "Loading" image in place of the Bible Readings when a new date is selected. This image is replaced by the Bible Readings when they are loaded.

= 1.5 =
This version: 

1. Uses the WordPress audio shortcode method to implement the audio player for MP3, thereby effecting improved fallback options for a variety of devices.

1. Also corrects a problem with Firefox for Android which, before this update, was playing all audios on the page whenever it was opened.

= 1.4.2 =
This version has an AJAX loading spinner with a transparent background to make it compatible with more designs.

= 1.4.1 =
This version uses a relative AJAX URL to avoid false cross-domain blocking when more than one URL points to the same site.

= 1.4 =
This version:

1. Provides the ability to choose MP3 or Flash for the format of the audio.

1. Makes MP3 the default for Android, as it has been for iPhone and iPad since version 0.7.

1. Uses HTML5 audio tags if MP3 is in use making listening to the audio much more convenient on mobile devices. Provides fallback to embedded audio or a link for browsers which cannot handle HTML5 audio tags.

= 1.3 =
This version causes the client's time to be used to set the date of the Bible readings loaded initially.

= 1.2 =
This version causes Scriptures to be loaded by jQuery both initially and by Datepicker (if it is used) to help achieve uniformity in formatting. Additionally, Datepicker code is only loaded when Datepicker is used.

= 1.1 =
This version changes loading readings from jQuery.load() to the WordPress AJAX system (http://codex.wordpress.org/AJAX_in_Plugins).

= 1.0 =
This version cleans up version contol issues in order to bring the plugin to version 1.0 status.

= 0.71 =
This version corrects committment of revision to repository in order to include new file created for version 0.7.

= 0.7 =
This version implements:

1. IMPORTANT CHANGE: A correction to the shortcode naming system: The shortcode is now [embed_bible_passage reading_plan='bcp']. (reading-plan has been changed to reading_plan. See http://codex.wordpress.org/Shortcode_API.)

1. The ability to use other than the default reading plan.

1. An optional ability for users to select passages for days other than the current day by clicking on a calendar.

1. An mp3 audio file is used for iPhone and iPad, rather than the default Flash audio file.

= 0.211 =
This version improves the documentation and corrects the link in the "Powered by" attribution at the bottom of pages.

= 0.21 =
This version adds the ability to optionally show a "Powered by" attribution at the bottom of pages.

== Changelog ==
= 1.6.2 =
Cleans up some input screen layout issues.

= 1.6.1 =
Bugfix. Corrects path to wp-includes.

= 1.6 =
Provides an option to places the calendar inside the first block of text. (Calendar will always be above the text for screens less than 480px wide.)

= 1.5.2 =
Delays loading the Bible Readings until the HTML page is completely loaded to help resolve formating issues in some cases.

= 1.5.1 =
Adds inserting a "Loading" image in place of the Bible Readings when a new date is selected. This image is replaced by the Bible Readings when they are loaded.

1.5 =
1. Now uses the WordPress audio shortcode method to implement the audio player for MP3, thereby effecting improved fallback options for a variety of devices.

1. Corrected a problem with Firefox for Android which, before this update, was playing all audios on the page whenever it was opened.

= 1.4.2 =
Uses an AJAX loading spinner with a transparent background to make it compatible with more designs.

= 1.4.1 =
Uses a relative AJAX URL to avoid false cross-domain blocking when more than one URL points to the same site.

= 1.4 =
1. Provided the ability to choose MP3 or Flash for the format of the audio.

1. Made MP3 the default for Android, as it has been for iPhone and iPad since version 0.7, since Adobe no longer supports Android.

1. Added the use of HTML5 audio tags if MP3 is in use and provided fallback to embedded audio or a link for browsers which cannot handle HTML5 audio tags.

1. Reordered the administrative panel.

1. Added "Loading" image to page. This image is replaced by the Bible Readings when they are loaded.

= 1.3 =
The client's time is used to set the date of the Bible readings loaded initially.

= 1.2 =
1. Scriptures are loaded by jQuery both initially and by Datepicker (if it is used) to help achieve uniformity in formatting.

1. JavaScript for Datepicker only loaded to footer when Datepicker is used.

= 1.1 =
Change loading readings from jQuery.load() to the WordPress AJAX system (http://codex.wordpress.org/AJAX_in_Plugins).

= 1.0 =
Cleanup to bring to version 1.0 status.

= 0.71 =
Correction to committment of revision to repository in order to include new file created for version 0.7.

= 0.7 =
1. IMPORTANT CHANGE: A correction to the shortcode naming system: The shortcode is now [embed_bible_passage reading_plan='bcp']. (reading-plan has been changed to reading_plan. See http://codex.wordpress.org/Shortcode_API.)

1. The ability to use other than the default reading plan.

1. An optional ability for users to select passages for days other than the current day by clicking on a calendar.

1. An mp3 audio file is used for iPhone and iPad, rather than the default Flash audio file.

= 0.211 =
Improves the documentation and corrects the link in the "Powered by" attribution at the bottom of pages.

= 0.21 =
Adds the ability to optionally show a "Powered by" attribution at the bottom of pages.
