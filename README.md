Poll-Box
========

* Version: 1.0
* Compatibility: MyBB 1.6.x (last tested on 1.6.10)
* Author: Tanweth
* Website: http://kerfufflealliance.com

REQUIRED: Advanced Sidebox (last tested on 2.0). Found here: https://github.com/WildcardSearch/Advanced-Sidebox

A module for the Advanced Sidebox plugin by Wildcard for myBB. It displays an existing MyBB poll in a sidebox.

This module was inspired by the Poll on Index plugin by krafdi.de.

Features

* Identify the poll to show either by pid (to show a specific poll), or fid (to show the latest poll in the given forum or forums).

* If the member hasn't voted yet, it will display voting options. Otherwise it will display the results.

* A sidebox-optimized poll layout.

* Includes a link to the original thread of the poll.

* Inherits permissions from the poll's forum, so it won't display for members who aren't supposed to be able to see it.

How to Install

* Simply upload the file, and Advanced Sidebox will automatically install the module.

Setting It Up

* As with any sidebox in Advanced Sidebox you must go to Admin CP -> Configuration -> Advanced Sidebox, and drag the "Poll" module to whichever side you want it to display.

* Select which scripts (pages) you want the poll to display on.

* Under Settings, enter the fid(s) for the forum(s) you want the latest poll to be pulled from. You can find the fid in the URL for the forum (/forumdisplay.php?fid=<fid>, or /forum-<fid>.html" if you have search engine-friendly URLs on). If you have Google SEO or some other custom URL rules which make the fid not appear in the URL, you can find it in the URL attached to the New Thread button (/newthread.php?fid=129).

* If you'd prefer to specify a specific poll to display, you can enter its pid in the second dialog box under Settings. You can find the pid in the "Show Results" URL for the poll (polls.php?action=showresults&pid=<pid>)

Known Issues

* When you cast your vote from the sidebox, it automatically redirects to the thread where the poll is. Some may prefer this, but some may prefer that it redirect back to the page where the vote was cast. As far as I know, the only way to do the latter is to modify the poll.php core file and change the redirect behavior. If there is demand for it, I may provide an option to replace the core file with one that changes the redirect behavior.

