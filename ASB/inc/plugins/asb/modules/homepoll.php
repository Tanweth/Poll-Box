<?php
/*
 * Plugin Name: At Home Polls (Advanced Sidebox Edition)
 * Author: Tanweth
 * http://www.kerfufflealliance.com
 *
 * Allows a fully-functional and sidebox-optimized poll to be displayed in a sidebox.
 * Requires MyBB 1.6.x and Advanced Sidebox 2.0.5 or later.
 */

// Include a check for Advanced Sidebox
if(!defined("IN_MYBB") || !defined("IN_ASB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

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
		"title" => $lang->homepoll_title_asb,
		"description" => $lang->homepoll_description_asb,
		"wrap_content"	=> true,
		"version" => "2.0.2",
		"settings" =>	array
		(
			"homepoll_fid" => array
			(
				"sid" => "NULL",
				"name" => "homepoll_fid",
				"title" => $lang->homepoll_fid_title,
				"description" => $lang->homepoll_fid_description,
				"optionscode" => "text",
				"value" => ''
			),
			"homepoll_pid" => array
			(
				"sid" => "NULL",
				"name" => "homepoll_pid",
				"title" => $lang->homepoll_pid_title,
				"description" => $lang->homepoll_pid_description,
				"optionscode" => "text",
				"value" => ''
			)
		),
		"templates" => array
		(
			array
			(
				"title" => "asb_poll",
				"template" => <<<EOF
<form action="polls.php" method="post">
	<input type="hidden" name="my_post_key" value="{\$mybb->post_code}" />
	<input type="hidden" name="action" value="vote" />
	<input type="hidden" name="pid" value="{\$poll[\'pid\']}" />
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
					<td class="trow1" align="right"><span class="smalltext"><a href="showthread.php?tid={\$poll[\'tid\']}">{\$lang->homepoll_thread}</a> | <a href="polls.php?action=showresults&amp;pid={\$poll[\'pid\']}">{\$lang->homepoll_results}</a></span></td>
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
				"title" => "asb_poll_results",
				"template" => <<<EOF
<tr>
	<td class="trow1">
		<table>
			<tr>
				<td colspan="4" class="trow1"><a href="showthread.php?tid={\$poll[\'tid\']}" style="text-decoration: none; font-weight: bold">{\$poll[\'question\']}</a><br /><span class="smalltext">{\$pollstatus}</span><br /></td>
			</tr>
			{\$polloptions}
			<tr>
				<td class="trow2"><strong>{\$lang->total}</strong></td>
				<td class="trow2" colspan="2" align="right"><strong>{\$lang->homepoll_total}</strong></td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="2" border="0" width="100%" align="center">
			<tr>
				<td class="trow1" align="right"><span class="smalltext">[<a href="showthread.php?tid={\$poll[\'tid\']}">{\$lang->homepoll_thread}</a> | <a href="polls.php?action=showresults&amp;pid={\$poll[\'pid\']}">{\$lang->homepoll_results}</a>]</span></td>
			</tr>
		</table>
	</td>
</tr>
EOF
			),
			array
			(
				"title" => "asb_poll_resultbit",
				"template" => <<<EOF
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

function asb_homepoll_build_template($args)
{
	// retrieve side box settings
	foreach(array('settings', 'template_var', 'width') as $key)
	{
		$$key = $args[$key];
	}

	// don't forget to declare your variable! will not work without this
	global $$template_var;
	
	global $mybb, $db, $templates, $theme, $lang, $pollbox;
	
	$lang->load("showthread");
	$lang->load("homepoll");
    $parser = new postParser;

	$options = array(
		"limit" => 1
	);
	
	require_once MYBB_ROOT."inc/class_parser.php";
	
	// Query if the user supplied a pid. Join with the threads table to obtain the fid of the poll (needed to inherit permissions).
	if (!empty($settings['homepoll_pid']['value']))
	{
		$pid = intval($settings['homepoll_pid']['value']);
	
		$query = $db->write_query("
			SELECT t.tid, t.fid, p.*
			FROM ".TABLE_PREFIX."threads t
			LEFT JOIN ".TABLE_PREFIX."polls p ON (t.tid=p.tid)
			WHERE p.pid=".$pid."
		");
		
		$poll = $db->fetch_array($query);
	}
	
	// Query if the user supplied an fid/fids. Join with the threads table so polls can be limited by fid (and so permissions can be inherited).
	elseif (!empty($settings['homepoll_fid']['value']))
	{
		$poll_fids = explode(',', $settings['homepoll_fid']['value']);
		
		if (is_array($poll_fids))
		{
			foreach ($poll_fids as $fid)
			{
				$fid_array[] = intval($fid);
			}
			
			$poll_fids = implode(',', $fid_array);
		}
		else
		{
			$poll_fids = intval($settings['homepoll_fid']['value']);
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
	if (!empty($poll['pid']) && ($forumpermissions['canview'] != 1 || $forumpermissions['canviewthreads'] != 1))
	{
		$poll = '';
	}
	
    if (!empty($poll))
    {
		$poll['timeout'] = $poll['timeout']*60*60*24;
		$expiretime = $poll['dateline'] + $poll['timeout'];
		$now = TIME_NOW;

		// If the poll or the thread is closed or if the poll is expired, show the results.
		if($poll['closed'] == 1 || $thread['closed'] == 1 || ($expiretime < $now && $poll['timeout'] > 0))
		{
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
			if(isset($mybb->cookies['pollvotes'][$poll['pid']]) && $mybb->cookies['pollvotes'][$poll['pid']] !== "")
			{
				$alreadyvoted = 1;
			}
		}
		$optionsarray = explode("||~|~||", $poll['options']);
		$votesarray = explode("||~|~||", $poll['votes']);
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
				"allow_html" => $forum['allowhtml'],
				"allow_mycode" => $forum['allowmycode'],
				"allow_smilies" => $forum['allowsmilies'],
				"allow_imgcode" => $forum['allowimgcode'],
				"allow_videocode" => $forum['allowvideocode'],
				"filter_badwords" => 1
			);

			$option = $parser->parse_message($optionsarray[$i-1], $parser_options);
			$votes = $votesarray[$i-1];
			$totalvotes += $votes;
			$number = $i;

			// Mark the option the user voted for.
			if($votedfor[$number])
			{
				$optionbg = "trow2";
				$votestar = "*";
			}
			else
			{
				$optionbg = "trow1";
				$votestar = "";
			}

			// If the user already voted or if the results need to be shown, do so; else show voting screen.
			if($alreadyvoted || $showresults)
			{
				if(intval($votes) == "0")
				{
					$percent = "0";
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
			$totpercent = "100%";
		}
		else
		{
			$totpercent = "0%";
		}

		// Check if user is allowed to edit posts; if so, show "edit poll" link.
		if(!is_moderator($fid, 'caneditposts'))
		{
			$edit_poll = '';
		}
		else
		{
			$edit_poll = " | <a href=\"polls.php?action=editpoll&amp;pid={$poll['pid']}\">{$lang->edit_poll}</a>";
		}

		// Decide what poll status to show depending on the status of the poll and whether or not the user voted already.
		if($alreadyvoted || $showresults)
		{
			if($alreadyvoted && $mybb->usergroup['canundovotes'] == 1)
			{
				$pollstatus .= " [<a href=\"polls.php?action=do_undovote&amp;pid={$poll['pid']}&amp;my_post_key={$mybb->post_code}\">{$lang->homepoll_undo_vote}</a>]";
			}
			else
			{
				$pollstatus = $lang->poll_closed;
			}
			$lang->homepoll_total = $lang->sprintf($lang->homepoll_total, $totalvotes);
			eval("\$" . $template_var . " = \"".$templates->get("asb_poll_results")."\";");
		}
		else
		{
			$publicnote = '&nbsp;';
			if($poll['public'] == 1)
			{
				$publicnote = $lang->public_note;
			}
			eval("\$" . $template_var . " = \"".$templates->get("asb_poll")."\";");
		}
	
		// return true if your box has something to show, or false if it doesn't.
		return true;
    }
	else
	{
		// no content
		return false;
	}
}

?>
