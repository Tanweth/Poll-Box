<?php
/*
 * Plugin Name: At Home Polls (Advanced Sidebox Edition)
 * License: MIT (http://opensource.org/licenses/MIT)
 * Copyright © 2014 Aryndel Lamb-Marsh (aka Tanweth)
 *
 * Allows a fully-functional and sidebox-optimized poll to be displayed in a sidebox.
 * Requires MyBB 1.6.x and Advanced Sidebox.
 */

// Include a check for Advanced Sidebox
if(!defined('IN_MYBB') || !defined('IN_ASB'))
{
	die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');
}

/*
 * asb_homepoll_info()
 *
 * Provides info to ASB about the addon (including settings).
 *
 * @return: (array) the module info.
 */
function asb_homepoll_info()
{
	global $lang;

	if(!$lang->asb_addon)
	{
		$lang->load('asb_addon');
	}
	
	if(!$lang->homepoll)
	{
		$lang->load('homepoll');
	}

	return array
	(
		'title' => $lang->homepoll_title_asb,
		'description' => $lang->homepoll_description_asb,
		'author' => 'Tanweth',
		'module_site' => 'https://github.com/Tanweth/Poll-Box',
		'author_site' => 'http://kerfufflealliance.com',
		'wrap_content'	=> true,
		'version' => '2.2.1',
		'compatibility' => '2.1',
		// 'xmlhttp' => true,
		'settings' =>	array
		(
			'homepoll_fid' => array
			(
				'sid' => 'NULL',
				'name' => 'homepoll_fid',
				'title' => $lang->homepoll_fid_title,
				'description' => $lang->homepoll_fid_description,
				'optionscode' => 'text',
				'value' => ''
			),
			'homepoll_pid' => array
			(
				'sid' => 'NULL',
				'name' => 'homepoll_pid',
				'title' => $lang->homepoll_pid_title,
				'description' => $lang->homepoll_pid_description,
				'optionscode' => 'text',
				'value' => ''
			),
			'homepoll_edit' => array
			(
				'sid' => 'NULL',
				'name' => 'homepoll_edit',
				'title' => $lang->homepoll_edit_title,
				'description' => $lang->homepoll_edit_description,
				'optionscode' => 'yesno',
				'value' => 'yes'
			),
			'homepoll_closed' => array
			(
				'sid' => 'NULL',
				'name' => 'homepoll_closed',
				'title' => $lang->homepoll_closed_title,
				'description' => $lang->homepoll_closed_description,
				'optionscode' => 'yesno',
				'value' => 'yes'
			),
			'homepoll_redirect' => array
			(
				'sid' => 'NULL',
				'name' => 'homepoll_redirect',
				'title' => $lang->homepoll_redirect_title,
				'description' => $lang->homepoll_redirect_description,
				'optionscode' => 'yesno',
				'value' => 'no'
			),
/* 			'xmlhttp_on' => array
			(
				'sid' => 'NULL',
				'name' => 'xmlhttp_on',
				'title' => $lang->asb_xmlhttp_on_title,
				'description' => $lang->asb_xmlhttp_on_description,
				'optionscode' => 'text',
				'value' => '0'
			) */
		),
		'templates' => array
		(
			array
			(
				'title' => 'asb_poll',
				'template' => <<<EOF
<form action="{\$polls_script}" method="post">
	<input type="hidden" name="my_post_key" value="{\$mybb->post_code}" />
	<input type="hidden" name="action" value="vote" />
	<input type="hidden" name="pid" value="{\$poll[\'pid\']}" />
	<input type="hidden" name="redirect_url" value="{\$redirect_url}" />
	<tr>
		<td class="trow1">
			<table>
				<tr>
					<td colspan="4" class="trow1"><a href="showthread.php?tid={\$poll[\'tid\']}" style="text-decoration: none; font-weight: bold">{\$poll[\'question\']}</a><br /></td>
				</tr>
				{\$polloptions}
			</table>
			<table width="100%" align="center">
				<tr>
					<td class="trow1"><input type="submit" class="button" value="{\$lang->vote}" /></td>
				</tr>
				<tr>
					<td class="trow1" align="right"><span class="smalltext">[<a href="showthread.php?tid={\$poll[\'tid\']}">{\$lang->homepoll_thread}</a> | <a href="polls.php?action=showresults&amp;pid={\$poll[\'pid\']}">{\$lang->homepoll_results}</a>]{\$edit_poll}</span></td>
				</tr>
				<tr>
					<td colspan="2" class="trow1"><span class="smalltext">{\$publicnote}</span></td>
				</tr>
			</table>
		</td>
	</tr>
</form>
EOF
			),
			array
			(
				'title' => 'asb_poll_results',
				'template' => <<<EOF
<tr>
	<td class="trow1">
		<table>
			<tr>
				<td colspan="4" class="trow1"><a href="showthread.php?tid={\$poll[\'tid\']}" style="text-decoration: none; font-weight: bold">{\$poll[\'question\']}</a><br /><span class="smalltext">{\$pollstatus}</span><br /></td>
			</tr>
			{\$polloptions}
			<tr>
				<td class="trow2"><strong>{\$lang->total}</strong></td>
				<td class="trow2" colspan="2" align="right"><strong>{\$poll[\'totvotes\']}</strong><br /></td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="2" border="0" width="100%" align="center">
			<tr>
				<td class="trow1" align="right"><span class="smalltext">[<a href="showthread.php?tid={\$poll[\'tid\']}">{\$lang->homepoll_thread}</a> | <a href="polls.php?action=showresults&amp;pid={\$poll[\'pid\']}">{\$lang->homepoll_results}</a>]{\$edit_poll}</span></td>
			</tr>
		</table>
	</td>
</tr>
EOF
			),
			array
			(
				'title' => 'asb_poll_resultbit',
				'template' => <<<EOF
<tr>
	<td class="{\$optionbg} smalltext" width="100%" align="left">{\$option}{\$votestar}</td>
	<td class="{\$optionbg}" width="37" align="right"><span class="smalltext">{\$votes}</span></td>
	<td class="{\$optionbg}" width="45" align="right"><span class="smalltext">({\$percent}%)</span></td>
</tr>
<tr>
	<td class="{\$optionbg}" colspan="3" align="right"><img src="{\$theme[\'imgdir\']}/pollbar-s.gif" alt="" /><img src="{\$theme[\'imgdir\']}/pollbar.gif" width="{\$imagewidth}" height="10" alt="{\$percent}%" title="{\$percent}%" /><img src="{\$theme[\'imgdir\']}/pollbar-e.gif" alt="" /></td>
</tr>
EOF
			)
		)
	);
}

/*
 * asb_homepoll_build_template()
 *
 * Handles display of children of this addon at page load.
 *
 * @param - $args - (array) the specific information from the child box
 *
 * @return: (bool) true on success, false on fail/no content
 */
function asb_homepoll_build_template($args)
{
	// retrieve side box settings
	foreach(array('settings', 'template_var', 'width') as $key)
	{
		$$key = $args[$key];
	}

	// don't forget to declare your variable! will not work without this
	global $$template_var, $lang, $asb_homepoll;
	
	if(!$lang->homepoll)
	{
		$lang->load('homepoll');
	}
	
	if(!$lang->showthread)
	{
		$lang->load('showthread');
	}
	
	$asb_homepoll = asb_homepoll_build_poll($settings, $width);
	
	// If there is a poll to display . . .
	if ($asb_homepoll)
	{
		// set out template variable to the returned poll and return true
		$$template_var = $asb_homepoll;
		return true;
	}
	else
	{
		return false;
	}
}

/*
 * asb_homepoll_xmlhttp()
 *
 * Handles display of children of this addon via AJAX
 *
 * @param - $args - (array) the specific information from the child box
 * @return: n/a
 */	
/* function asb_homepoll_xmlhttp($args)
{
	global $lang;

	if(!$lang->showthread)
	{
		$lang->load('showthread');
	}

	foreach(array('settings', 'dateline', 'width') as $key)
	{
		$$key = $args[$key];
	}
	
	$asb_homepoll = asb_homepoll_build_poll($settings, $width);

	if($asb_homepoll)
	{
		return $asb_homepoll;
	}
	return 'nochange';
} */
	
/*
 * asb_homepoll_build_poll()
 * 
 * Checks which pid to use or forum to pull a poll from, then generates the poll.
 *
 * @param - $settings (array) individual side box settings passed to the module
 *
 * @param - $width - (int) the width of the column in which the child is positioned
 *
 * @return: (mixed) a (string) containing the HTML side box markup or (bool) false on fail/no content
 */
function asb_homepoll_build_poll($settings, $width)
{
	global $mybb, $lang, $db, $theme, $templates, $polls_script, $redirect_url;
	
	require_once MYBB_ROOT.'inc/class_parser.php';
	
    $parser = new postParser;
	$options = array(
		'limit' => 1
	);
	
	// Check if user supplied a pid. Run query, joining with threads table to obtain fid of poll (needed to inherit permissions).
	if ($settings['homepoll_pid'])
	{
		$pid = (int) $settings['homepoll_pid'];
	
		$query = $db->write_query("
			SELECT t.tid, t.fid, p.*
			FROM ".TABLE_PREFIX."threads t
			LEFT JOIN ".TABLE_PREFIX."polls p ON (t.tid=p.tid)
			WHERE p.pid=".$pid."
		");
		
		$poll = $db->fetch_array($query);
	}
	// Check if user supplied an fid/fids. Join with threads table so polls can be limited by fid (and so permissions can be inherited).
	elseif ($settings['homepoll_fid'])
	{
		$poll_fids = explode(',', $settings['homepoll_fid']);
		
		if (is_array($poll_fids))
		{
			$poll_fids = array_map('intval', $poll_fids);
			$poll_fids = implode(',', $poll_fids);
		}
		else
		{
			$poll_fids = (int) $settings['homepoll_fid'];
		}
			
		$query = $db->write_query("
			SELECT t.tid, t.fid, p.*
			FROM ".TABLE_PREFIX."threads t
			LEFT JOIN ".TABLE_PREFIX."polls p ON (t.tid=p.tid)
			WHERE t.fid IN (".$poll_fids.")
			ORDER BY p.pid DESC
		");
		
		$poll = $db->fetch_array($query);
	}
	
	$forumpermissions = forum_permissions($poll['fid']);
	
	// Only display if the query is not empty and user has the right to view the poll.
	if (!$poll['pid'] || ($forumpermissions['canview'] != 1 || $forumpermissions['canviewthreads'] != 1))
	{
		return false;
	}
	
	$poll['timeout'] = $poll['timeout']*60*60*24;
	$expiretime = $poll['dateline'] + $poll['timeout'];
	$now = TIME_NOW;

	// If the poll or the thread is closed or if the poll is expired, show the results.
	if($poll['closed'] == 1 || $thread['closed'] == 1 || ($expiretime < $now && $poll['timeout'] > 0))
	{
		if (!$settings['homepoll_closed'])
		{			
			return false;
		}
		$showresults = 1;
	}

	// If the user is not a guest, check if he already voted.
	if($mybb->user['uid'] != 0)
	{
		$query = $db->simple_select("pollvotes", "*", "uid='".$mybb->user['uid']."' AND pid='".$poll['pid']."'");
		while($votecheck = $db->fetch_array($query))
		{	
			$alreadyvoted = 1;
			$votedfor[$votecheck['voteoption']] = 1;
		}
	}
	else
	{
		if(isset($mybb->cookies['pollvotes'][$poll['pid']]) && $mybb->cookies['pollvotes'][$poll['pid']] !== '')
		{
			$alreadyvoted = 1;
		}
	}
	$optionsarray = explode('||~|~||', $poll['options']);
	$votesarray = explode('||~|~||', $poll['votes']);
	$poll['question'] = htmlspecialchars_uni($poll['question']);
	$polloptions = '';
	$totalvotes = 0;

	for($i = 1; $i <= $poll['numoptions']; ++$i)
	{
		$poll['totvotes'] = $poll['totvotes'] + $votesarray[$i-1];
	}

	// Loop through the poll options.
	for($i = 1; $i <= $poll['numoptions']; ++$i)
	{
		// Set up the parser options.
		$parser_options = array(
			'allow_html' => $forum['allowhtml'],
			'allow_mycode' => $forum['allowmycode'],
			'allow_smilies' => $forum['allowsmilies'],
			'allow_imgcode' => $forum['allowimgcode'],
			'allow_videocode' => $forum['allowvideocode'],
			'filter_badwords' => 1
		);

		$option = $parser->parse_message($optionsarray[$i-1], $parser_options);
		$votes = $votesarray[$i-1];
		$number = $i;

		// Mark the option the user voted for.
		if($votedfor[$number])
		{
			$optionbg = 'trow2';
			$votestar = '*';
		}
		else
		{
			$optionbg = 'trow1';
			$votestar = '';
		}

		// If the user already voted or if the results need to be shown, do so; else show voting screen.
		if($alreadyvoted || $showresults)
		{
			if(intval($votes) == '0')
			{
				$percent = '0';
			}
			else
			{
				$percent = number_format($votes / $poll['totvotes'] * 100);
			}
			$imagewidth = round(($percent/3) * ($width/50));
			$imagerowwidth = $imagewidth + 10;
			eval("\$polloptions .= \"".$templates->get("asb_poll_resultbit")."\";");
		}
		else
		{
			if($poll['multiple'] == 1)
			{
				eval("\$polloptions .= \"".$templates->get("showthread_poll_option_multiple")."\";");
			}
			else
			{
				eval("\$polloptions .= \"".$templates->get("showthread_poll_option")."\";");
			}
		}
	}

	// If there are any votes at all, all votes together will be 100%; if there are no votes, all votes together will be 0%.
	if($poll['totvotes'])
	{
		$totpercent = '100%';
	}
	else
	{
		$totpercent = '0%';
	}
	
	// Check if user is allowed to edit posts; if so, show edit poll link.
	if(!is_moderator($poll['fid'], 'caneditposts') || !$settings['homepoll_edit'])
	{
		$edit_poll = '';
	}
	else
	{
		$edit_poll = '<br/>[<a href="polls.php?&amp;action=editpoll&amp;pid='.$poll['pid'].'">'.$lang->edit_poll.'</a>]';
	}

	// Get user's current location at time of voting (or undoing vote) so we can return to it.
	$redirect_url_raw = get_current_location();
	
	// get_current_location() must be parsed to remove any path elements so that they won't be duplicated in final redirect URL.
	$bburl_parsed = parse_url($mybb->settings['bburl']);
	
	// If board's URL includes any subdirectories, remove them from redirect URL (and remove forward slashes), since get_current_location() includes them.
	if (strpos($redirect_url_raw, $bburl_parsed['path']))
	{
		$bburl_relative_strlen = strlen($bburl_parsed['path']);
		$redirect_url = $mybb->settings['bburl'] . substr($redirect_url_raw, $bburl_relative_strlen);
	}
	else
	{
		$redirect_url = $mybb->settings['bburl'] . $redirect_url_raw;
	}
	
	// Since pid both refers to poll ID and post ID, if we're on a showthread page and link includes a pid (for post ID) argument, we need to remove it from URL so that it won't be confused for poll ID.
	if (substr_count($redirect_url, 'pid'))
	{
		$redirect_url = substr($redirect_url, 0, strpos($redirect_url, '&amp;pid'));
	}
	
	// Decide what poll status to show depending on the status of the poll and whether or not the user voted already.
	if($alreadyvoted || $showresults)
	{
		if($alreadyvoted && $mybb->usergroup['canundovotes'] == 1)
		{
			// If set to redirect to poll's thread, use default MyBB behavior.
			if ($settings['homepoll_redirect'])
			{
				$pollstatus = ' [<a href="polls.php?action=do_undovote&amp;pid='.$poll['pid'].'&amp;my_post_key='.$mybb->post_code.'">'.$lang->homepoll_undo_vote.'</a>]';
			}
			else
			{
				$pollstatus = ' [<a href="inc/plugins/homepoll/polls.php?action=do_undovote&amp;pid='.$poll['pid'].'&amp;my_post_key='.$mybb->post_code.'&amp;redirect_url='.$redirect_url.'">'.$lang->homepoll_undo_vote.'</a>]';
			}
		}
		else
		{
			$pollstatus = $lang->poll_closed;
		}
		eval("\$asb_homepoll = \"" . $templates->get("asb_poll_results") . "\";");
	}
	else
	{
		$publicnote = '&nbsp;';
		if($poll['public'] == 1)
		{
			$publicnote = $lang->public_note;
		}
		
		// If set to redirect to poll's thread, use default MyBB behavior on vote submit.
		if ($settings['homepoll_redirect'])
		{
			$polls_script = 'polls.php';
		}
		else
		{
			$polls_script = 'inc/plugins/homepoll/polls.php';
		}
		
		eval("\$asb_homepoll = \"" . $templates->get("asb_poll") . "\";");
	}

	// return sidebox if your box has something to show, or false if it doesn't.
	return $asb_homepoll;
}

?>
