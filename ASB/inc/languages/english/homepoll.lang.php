<?php
/*
 * Plugin Name: At Home Polls
 * License: MIT (http://opensource.org/licenses/MIT)
 * Copyright © 2014 Aryndel Lamb-Marsh (aka Tanweth)
 *
 * Allows a fully-functional and sidebox-optimized poll to be placed in a sidebox (ASB Edition) or on the Index and Portal pages (Standard Edition).
 * Requires MyBB 1.6.x and Advanced Sidebox 2.0.5 or later (if using ASB Edition).
 */
 
// Title and description for the plugin/module (Standard).
$l['homepoll_title'] = "At Home Polls";
$l['homepoll_description'] = "Displays a fully-functional MyBB poll on the Index and Portal pages, specified by forum or poll ID.";

// Title and description for the plugin/module (ASB).
$l['homepoll_title_asb'] = "Poll";
$l['homepoll_description_asb'] = "Displays a fully-functional and sidebox-optimized MyBB poll, specified by forum or poll ID.";

// Title and description for settings.
$l['homepoll_fid_title'] = "Forum to Display Polls From";
$l['homepoll_fid_description'] = "To use the latest poll from a forum (or forums), enter a list of the fids of the forum(s) to pull from, separated by commas. The fid can be found in URL for the forum (forumdisplay.php?fid=<strong>123</strong>, or forum-<strong>123</strong>.html).";
$l['homepoll_pid_title'] = "Specific Poll to Display";
$l['homepoll_pid_description'] = "To specify exactly which poll to use, enter the pid of the poll here (found in the Show Results URL of the poll: polls.php?action=showresults&pid=<strong>123</strong>).";
$l['homepoll_edit_title'] = "Show Edit Poll Link for Moderators?";
$l['homepoll_edit_description'] = "If yes, moderators with permissions in the poll's forum will see an edit poll link.";
$l['homepoll_closed_title'] = "Display Closed Polls?";
$l['homepoll_closed_description'] = "If no, polls that are closed will not be displayed.";
$l['homepoll_redirect_title'] = "Redirect to Thread on Vote?";
$l['homepoll_redirect_description'] = "If yes, the user will be redirected to the thread that contains the poll upon voting.";

// Title and description for settings (Standard only).
$l['homepoll_group_description'] = "Choose which forum or poll to use and where and how the poll is displayed.";
$l['homepoll_index_title'] = "Display on Forum Index?";
$l['homepoll_index_description'] = "If yes, the selected poll will appear on the index page of the forum.";
$l['homepoll_portal_title'] = "Display on Portal?";
$l['homepoll_portal_description'] = "If yes, the selected poll will appear on the Portal page.";
$l['homepoll_compact_title'] = "Use Compact Layout?";
$l['homepoll_compact_description'] = "If yes, the poll will be displayed in a more compact format better for displaying in small spaces.";

// Custom text in poll.
$l['homepoll_undo_vote'] = "Undo Vote";
$l['homepoll_show_thread'] = "Show Thread";
$l['homepoll_thread'] = "Thread";
$l['homepoll_results'] = "Results";
?>
