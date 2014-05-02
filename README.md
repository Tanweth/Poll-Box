At Home Polls (formerly Poll Box)
========

<p align="center">
  <img title="At Home Polls" alt="At Home Polls" src="http://kerfufflealliance.com/pictures/homepoll/logo_homepoll.jpg" />
</p>

* Version: 2.2.1
* Compatibility: MyBB 1.6.x (last tested on 1.6.12)
* Author: Tanweth
* GitHub: https://github.com/Tanweth/Poll-Box
* Release thread: http://community.mybb.com/thread-145875.html
* Website: http://kerfufflealliance.com

A plugin for the MyBB forum software that allows a fully-functional poll to be displayed on your home pages. Now includes two versions:

* Standard: A standard MyBB plugin that displays a poll on your Index and Portal pages. This version has no dependencies (other than MyBB 1.6).

* Advanced Sidebox (ASB): Requires Advanced Sidebox 2.1 or later (found here: https://github.com/WildcardSearch/Advanced-Sidebox). This module allows you to display a sidebox-optimized poll in a sidebox on any page. It integrates directly with Advanced Sidebox (there's no MyBB plugin to install).

##Features

* Display the latest poll from a forum or forums, or specify a specific poll to display.

* If the member hasn't voted yet, it will display voting options. Otherwise it will display the results.

* Includes a link to the original thread of the poll.

* Inherits permissions from the poll's forum, so it won't display for users who aren't supposed to be able to see it.

* NEW: After users vote, choose either to redirect them back to the page where they voted or to the poll's thread.

* NEW: Choose whether or not to display latest poll if it is closed.

* Compact layout optimized for sideboxes (default in ASB edition, available as setting in standard edition).

##How to Install

* Standard Edition: Upload the files in the Standard directory, and Install & Activate the plugin from the Configuration -> Plugins area of your Admin CP.

* ASB Edition: Simply upload what's in the ASB directory, and Advanced Sidebox will automatically install the module.

##Upgrading

* Standard Edition: Simply deactivate the old plugin version, upload the new version, and reactivate it.

* ASB Edition: In your Admin CP, go to Configure > Advanced Sidebox > Manage Modules. Find the line for "Poll," click on the Options link, then click "Delete." Then follow the installation instructions as normal.

If you previously used Poll Box, you may want to remove /inc/languages/english/asb_poll_box.lang.php for tidiness (it's harmless if it stays there).

##Setting It Up

* Advanced Sidebox: As with any sidebox, you must go to Admin CP -> Configuration -> Advanced Sidebox, and drag the "Poll" module to whichever side you want it to display. Then select which scripts (pages) you want the poll to display on in the resulting popup.

* Standard: The poll should automatically be visible on the Index and Portal pages after activation (though it may not be if the templates for these pages are modified, see the Troubleshooting section below if you're having an issue).

* Under Settings in the popup (ASB) or the At Home Polls settings group in the Settings section of your Admin CP (Standard), enter the fid(s) for the forum(s) you want the latest poll to be pulled from. You can find the fid in the URL for the forum (/forumdisplay.php?fid=<fid>, or /forum-<fid>.html" if you have search engine-friendly URLs on). If you have Google SEO or some other custom URL rules which make the fid not appear in the URL, you can find it in the URL attached to the New Thread button (/newthread.php?fid=<fid>).

* If you'd prefer to specify a specific poll to display, you can enter its pid in the second dialog box under Settings. You can find the pid in the "Show Results" URL for the poll (polls.php?action=showresults&pid=<pid>).

Troubleshooting & Customization

* If you are experiencing issues with the ASB edition, make sure you have the latest version of ASB (2.0.5 at the time of this readme).

* On the standard edition, simple template edits are made to place the poll box on the Index and Portal pages. If these do not show up for any reason, or if you wish to move the location of the poll box, simply go to the index and portal templates and place the variable {$homepoll} wherever you want it to appear.

##Support

If you notice a bug or have a suggestion, you should report it in the Issues sections of the GitHub page: https://github.com/Tanweth/Poll-Box

You can also ask for support (bug-related or not) in the release thread: http://community.mybb.com/thread-145875.html

##Changelog

* 2.2.1
	* Added compatibility with ASB v2.1. If you do not use the ASB Edition, there is no reason to upgrade.

* 2.2
	* Globalized the $homepoll variable on the Standard Edition. Now you can add the poll to any page of your forums by placing "{$homepoll}" in any template (with some exceptions, including the header template). By default it still only displays on Index and Portal, and display on those pages can still be enabled/disabled in settings.
	* Improved the way the redirect back to the page where a poll action was taken is handled. It should now work on any page of the forums without issue. However, the redirect after editing a poll has been removed entirely. That implementation was more complex than I felt was worth to keep it around.
	* Fixed an issue where the pollbar graphic wouldn't appear on the Show Results page on the ASB Edition.
	* Cached the version number to make future upgrades easier (Standard only).

* 2.1
	* Now when users vote (or do any other poll action), they're redirected to the page where they voted rather than the poll's thread. The original behavior can be restored with a setting. There are two exceptions to this:
		* ASB: The member profile pages. On these pages, the user is redirected to the forum index.
		* Standard: Users are redirected to the forum index when editing the poll, even if they commenced the action from the Portal.
	* Added a setting to disable display of the poll if it is closed.
	* Standard: Fixed some display issues and made other improvements to the compact layout.
	* Standard: Templates are now added to the global template list when applicable, so that they aren't generating unnecessary database queries.
	* Improved the code's compliance with the MyBB Development Standards.
	* General code optimizations.
	* Switched the license to the MIT License. It says pretty much what my old corny license did, but in a more standard way. :P

* 2.0.3
	* Corrected an issue on the ASB Edition where multiple poll sideboxes on the same page would display the same number of total votes.
	* Added an edit poll link visible to moderators of the poll's forum on both editions. This can be enabled and disabled with a setting on the ASB Edition (it is enabled by default). 

* 2.0.2
	* Changed the way the total votes are displayed on the ASB Edition and compact layout, since it tended to take up two lines when the vote count reached double digits after the 2.0.1 edits.
	* Added an install routine that controls settings to the Standard Edition. This way you can deactivate it for an update without losing your custom settings.

* 2.0.1 - Made the ASB sidebox (or compact version on Standard) scale better to lower widths:
	* The width of the pollbars is now smaller on the compact version (Standard), and dynamically adjusts to the width of the sidebox (ASB).
	* The "Show Thread" and "Show Results" links now simply say "Thread" and "Results" so that they are more likely to fit on one line (ASB & Standard if compact layout).
	* Percentages are now represented as whole numbers with no decimal places to reduce the amount of space used (ASB & Standard if compact layout).
	* Fixed an issue where a piece of text was directly entered in the template rather than read from the language file.
	* Fixed the name of the settings group (previously called "Polls At Home") (Standard).

* 2.0
	* Renamed to At Home Polls (from Poll Box).
	* Added a MyBB plugin for those who prefer not to use Advanced Sidebox.
	* Fixed an issue introduced in Advanced Sidebox 2.0.5 whereby template edits would not be saved.
	* Fixed an issue where a postParser error would prevent viewing certain pages if the Poll module was enabled on those pages.
	* Rewrote most descriptions and instructions to be more helpful.
	* Other minor code revisions.
	* Minor changes to the compact layout to improve display.

* 1.0.2 - Added info on module and all settings to the language file, as this was previously overlooked (*smacks forehead*).

* 1.0.1 - Added "return true" if the sidebox has something to display, so that it will display for users who set "Show Sideboxes With No Content?" to No in Advanced Sidebox.

##Special Thanks

* Wildcard - for making Advanced Sidebox (an awesome plugin), and for routinely assisting me with issues.
* krafdi.de - for the Poll on Index plugin (which inspired this plugin).