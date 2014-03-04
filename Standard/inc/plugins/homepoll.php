<?php
/*
 * Plugin Name: At Home Polls (Standard Edition)
 * Author: Tanweth
 * http://www.kerfufflealliance.com
 *
 * Allows a fully-functional poll to be placed on the Index and Portal pages.
 * Requires MyBB 1.6.x.
 */

// Disallow direct access to this file for security reasons
if(!defined('IN_MYBB'))
{
	die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');
}

/*
 * homepoll_info()
 * 
 * @return - (array) info on the current plugin.
 */
function homepoll_info()
{
	global $lang;

	if(!$lang->homepoll)
	{
		$lang->load('homepoll');
	}

	return array(
		'name'			=> $lang->homepoll_title,
		'description'	=> $lang->homepoll_description,
		'website'		=> 'http://github.com/Tanweth/Poll-Box',
		'author'		=> 'Tanweth',
		'authorsite'	=> 'http://kerfufflealliance.com',
		'version'		=> '2.1',
		'guid' 			=> '4f3f47c9a77abfcc0d137dd0b43fc8c3',
		'compatibility' => '16*'
	);
}

/*
 * homepoll_install()
 * 
 * Installs plugin, generating settings. Also calls homepoll_activate() function.
 */
 function homepoll_install()
 {
	global $db, $lang;
	
	if(!$lang->homepoll)
	{
		$lang->load('homepoll');
	}

	// Add settings group, then add settings
	$group = array(
		'gid'			=> 'NULL',
		'title'			=> $lang->homepoll_title,
		'name'			=> 'homepoll_group',
		'description'	=> $lang->homepoll_group_description,
		'disporder'		=> '210',
		'isdefault'		=> '0',
	);	
	$db->insert_query('settinggroups', $group);
	$gid = $db->insert_id();	

	$setting = array(
		'sid'			=> 'NULL',
		'name'			=> 'homepoll_fid',
		'title'			=> $lang->homepoll_fid_title,
		'description'	=> $lang->homepoll_fid_description,
		'optionscode'	=> 'text',
		'value'			=> '',
		'disporder'		=> '1',
		'gid'			=> (int) $gid,
	);
	$db->insert_query('settings', $setting);

	$setting = array(
		'sid'			=> 'NULL',
		'name'			=> 'homepoll_pid',
		'title'			=> $lang->homepoll_pid_title,
		'description'	=> $lang->homepoll_pid_description,
		'optionscode'	=> 'text',
		'value'			=> '',
		'disporder'		=> '2',
		'gid'			=> (int) $gid,
	);
	$db->insert_query('settings', $setting);

	$setting = array(
		'sid'			=> 'NULL',
		'name'			=> 'homepoll_index',
		'title'			=> $lang->homepoll_index_title,
		'description'	=> $lang->homepoll_index_description,
		'optionscode'	=> 'yesno',
		'value'			=> 'yes',
		'disporder'		=> '3',
		'gid'			=> (int) $gid,
	);
	$db->insert_query('settings', $setting);

	$setting = array(
		'sid'            => 'NULL',
		'name'           => 'homepoll_portal',
		'title'          => $lang->homepoll_portal_title,
		'description'    => $lang->homepoll_portal_description,
		'optionscode'    => 'yesno',
		'value'          => 'yes',
		'disporder'      => '4',
		'gid'            => (int) $gid,
	);
	$db->insert_query('settings', $setting);

	$setting = array(
		'sid'            => 'NULL',
		'name'           => 'homepoll_closed',
		'title'          => $lang->homepoll_closed_title,
		'description'    => $lang->homepoll_closed_description,
		'optionscode'    => 'yesno',
		'value'          => 'yes',
		'disporder'      => '5',
		'gid'            => (int) $gid,
	);
	$db->insert_query('settings', $setting);
	
	$setting = array(
		'sid'            => 'NULL',
		'name'           => 'homepoll_redirect',
		'title'          => $lang->homepoll_redirect_title,
		'description'    => $lang->homepoll_redirect_description,
		'optionscode'    => 'yesno',
		'value'          => 'no',
		'disporder'      => '6',
		'gid'            => (int) $gid,
	);
	$db->insert_query('settings', $setting);

	$setting = array(
		'sid'			=> 'NULL',
		'name'			=> 'homepoll_compact',
		'title'			=> $lang->homepoll_compact_title,
		'description'	=> $lang->homepoll_compact_description,
		'optionscode'	=> 'yesno',
		'value'			=> 'no',
		'disporder'		=> '7',
		'gid'			=>  (int) $gid,
	);
	$db->insert_query('settings', $setting);
	
	rebuild_settings();
 }
 
/*
 * homepoll_is_installed()
 * 
 * Checks if the plugin is installed by querying the database to see if the new settings group exists.
 * 
 * @return: (bool) true if plugin is installed, false otherwise.
 */
 function homepoll_is_installed()
 {
	global $db;
	
	$query = $db->simple_select("settinggroups", "name", "name='homepoll_group'");
	
	if($db->num_rows($query) > 0)
	{
		return true;
	}
 	return false;
 }

/*
 * homepoll_uninstall()
 * 
 * Uninstalls plugin by removing new settings. Also calls homepoll_deactivate() if applicable.
 */
 function homepoll_uninstall()
 {
	global $db;
	
	// Delete settings
	$db->delete_query("settinggroups", "name = 'homepoll_group'");
	$db->delete_query("settings", "name LIKE 'homepoll_%'");
 }
 
/*
 * homepoll_upgrade()
 * 
 * Includes special routines for upgrading from the previous version.
 */
function homepoll_upgrade()
{
	global $db, $lang;
	
	$query = $db->simple_select("settings", "name", "name='homepoll_closed'");
	
	if($db->num_rows($query) > 0)
	{
		return false;
	}
	
	if(!$lang->homepoll)
	{
		$lang->load('homepoll');
	}
	
	$query = $db->simple_select("settinggroups", "gid", "name='homepoll_group'");
	$gid = $db->fetch_field($query, 'gid');
	
	// Generate new settings added in 2.1.
	$setting = array(
		'sid'            => 'NULL',
		'name'           => 'homepoll_closed',
		'title'          => $lang->homepoll_closed_title,
		'description'    => $lang->homepoll_closed_description,
		'optionscode'    => 'yesno',
		'value'          => 'yes',
		'disporder'      => '5',
		'gid'            => (int) $gid,
	);
	$db->insert_query('settings', $setting);
	
	$setting = array(
		'sid'            => 'NULL',
		'name'           => 'homepoll_redirect',
		'title'          => $lang->homepoll_redirect_title,
		'description'    => $lang->homepoll_redirect_description,
		'optionscode'    => 'yesno',
		'value'          => 'no',
		'disporder'      => '6',
		'gid'            => (int) $gid,
	);
	$db->insert_query('settings', $setting);
	
	// Update display order of compact layout setting.
	$db->update_query("settings", array('disporder' => '7'), "name='homepoll_compact'");
	
	rebuild_settings();
}
	
/*
 * homepoll_activate()
 * 
 * Creates templates and generates edits to default templates
 */
function homepoll_activate()
{
    global $mybb, $db;
	
	// Run upgrade routine if user is upgrading.
	homepoll_upgrade();

	// Add new templates
	$template = array(
        'title'           => 'homepoll_poll',
        'template'        => $db->escape_string('
<form action="{$polls_script}" method="post">
	<input type="hidden" name="my_post_key" value="{$mybb->post_code}" />
	<input type="hidden" name="action" value="vote" />
	<input type="hidden" name="pid" value="{$poll[\'pid\']}" />
	<input type="hidden" name="this_script" value="{$this_script}" />
	<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
		<tr>
			<td colspan="4" class="thead" align="center"><a href="showthread.php?tid={$poll[\'tid\']}" style="text-decoration: none; font-weight: bold">{$poll[\'question\']}</a><br /></td>
		</tr>
		{$polloptions}
	</table>
	<table width="100%" align="center">
		<tr>
			<td><input type="submit" class="button" value="{$lang->vote}" /></td>
			<td valign="top" align="right"><span class="smalltext">[<a href="showthread.php?tid={$poll[\'tid\']}">{$lang->homepoll_show_thread}</a> | <a href="polls.php?action=showresults&amp;pid={$poll[\'pid\']}">{$lang->show_results}</a>{$edit_poll}]</span></td>
		</tr>
		<tr>
			<td colspan="2"><span class="smalltext">{$publicnote}</span></td>
		</tr>
	</table>
</form>
		'),
        'sid'            => '-1',
        'version'        => $mybb->version + 1,
        'dateline'       => TIME_NOW,
    );
	$db->insert_query('templates', $template);

	$template = array(
        'title'           => 'homepoll_results',
        'template'        => $db->escape_string('
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td class="thead" colspan="4" align="center"><a href="showthread.php?tid={$poll[\'tid\']}" style="text-decoration: none; font-weight: bold">{$poll[\'question\']}</a><br /><span class="smalltext">{$pollstatus}</span></td>
	</tr>
	{$polloptions}
	<tr>
		<td class="tfoot" align="right" colspan="2"><strong>{$lang->total}</strong></td>
		<td class="tfoot" align="center"><strong>{$lang->total_votes}</strong></td>
		<td class="tfoot" align="center"><strong>{$totpercent}</strong></td>
	</tr>
</table>
<table cellspacing="0" cellpadding="2" border="0" width="100%" align="center">
	<tr>
		<td align="left"><span class="smalltext">{$lang->you_voted}</span></td>
		<td align="right"><span class="smalltext">[<a href="showthread.php?tid={$poll[\'tid\']}">{$lang->homepoll_show_thread}</a> | <a href="polls.php?action=showresults&amp;pid={$poll[\'pid\']}">{$lang->show_results}</a>{$edit_poll}]</span></td>
	</tr>
</table>
<br />
		'),
        'sid'            => '-1',
        'version'        => $mybb->version + 1,
        'dateline'       => TIME_NOW,
    );
	$db->insert_query('templates', $template);
	
	$template = array(
        'title'           => 'homepoll_poll_compact',
        'template'        => $db->escape_string('
<form action="{$polls_script}" method="post">
	<input type="hidden" name="my_post_key" value="{$mybb->post_code}" />
	<input type="hidden" name="action" value="vote" />
	<input type="hidden" name="pid" value="{$poll[\'pid\']}" />
	<input type="hidden" name="this_script" value="{$this_script}" />
	<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
		<tr>
			<td colspan="4" class="thead">{$lang->homepoll_title_asb}</td>
		</tr>
		<tr>
			<td class="trow1" colspan="4"><a href="showthread.php?tid={$poll[\'tid\']}" style="text-decoration: none; font-weight: bold">{$poll[\'question\']}</a><br /><span class="smalltext">{$pollstatus}</span><br /></td>
		</tr>
		{$polloptions}
	</table>
	<table width="100%" align="center">
		<tr>
			<td class="trow1"><input type="submit" class="button" value="{$lang->vote}" /></td>
		</tr>
		<tr>
			<td class="trow1"><span class="smalltext">[<a href="showthread.php?tid={$poll[\'tid\']}">{$lang->homepoll_thread}</a> | <a href="polls.php?action=showresults&amp;pid={$poll[\'pid\']}">{$lang->homepoll_results}</a>]{$edit_poll}</span></td>
		</tr>
		<tr>
			<td colspan="2" class="trow1"><span class="smalltext">{$publicnote}</span></td>
		</tr>
	</table>
</form>
		'),
        'sid'            => '-1',
        'version'        => $mybb->version + 1,
        'dateline'       => TIME_NOW,
    );
	$db->insert_query('templates', $template);
	
	$template = array(
        'title'           => 'homepoll_results_compact',
        'template'        => $db->escape_string('
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
	<tr>
		<td colspan="4" class="thead">{$lang->homepoll_title_asb}</td>
	</tr>
	<tr>
		<td class="trow1" colspan="4"><a href="showthread.php?tid={$poll[\'tid\']}" style="text-decoration: none; font-weight: bold">{$poll[\'question\']}</a><br /><span class="smalltext">{$pollstatus}</span><br /></td>
	</tr>
	{$polloptions}
	<tr>
		<td class="trow2"><strong>{$lang->total}</strong></td>
		<td class="trow2" colspan="2" align="right" width="100%"><strong>{$poll[\'totvotes\']}</strong></td>
	</tr>
</table>
<table cellspacing="0" cellpadding="2" border="0" width="100%" align="center">
	<tr>
		<td align="right" class="trow1"><span class="smalltext">[<a href="showthread.php?tid={$poll[\'tid\']}">{$lang->homepoll_thread}</a> | <a href="polls.php?action=showresults&amp;pid={$poll[\'pid\']}">{$lang->homepoll_results}</a>]{$edit_poll}</span></td>
	</tr>
</table>
		'),
        'sid'            => '-1',
        'version'        => $mybb->version + 1,
        'dateline'       => TIME_NOW,
    );
	$db->insert_query('templates', $template);

	$template = array(
        'title'           => 'homepoll_resultbit_compact',
        'template'        => $db->escape_string('
<tr>
	<td class="{$optionbg} smalltext" width="100%" align="left">{$option}{$votestar}</td>
	<td class="{$optionbg}" width="37" align="right"><span class="smalltext">{$votes}</span></td>
	<td class="{$optionbg}" width="37" align="right"><span class="smalltext">({$percent}%)</span></td>
</tr>
<tr>
	<td class="{$optionbg}" colspan="3" align="right"><img src="{$theme[\'imgdir\']}/pollbar-s.gif" alt="" /><img src="{$theme[\'imgdir\']}/pollbar.gif" width="{$imagewidth}" height="10" alt="{$percent}%" title="{$percent}%" /><img src="{$theme[\'imgdir\']}/pollbar-e.gif" alt="" /></td>
</tr>
		'),
        "sid"            => "-1",
        "version"        => $mybb->version + 1,
        "dateline"        => TIME_NOW,
    );
	$db->insert_query('templates', $template);
	
	$template = array(
        'title'           => 'homepoll_resultbit_compact',
        'template'        => $db->escape_string('
<tr>
	<td class="{$optionbg} smalltext" width="100%" align="left">{$option}{$votestar}</td>
	<td class="{$optionbg}" width="37" align="right"><span class="smalltext">{$votes}</span></td>
	<td class="{$optionbg}" width="37" align="right"><span class="smalltext">({$percent}%)</span></td>
</tr>
<tr>
	<td class="{$optionbg}" colspan="3" align="right"><img src="{$theme[\'imgdir\']}/pollbar-s.gif" alt="" /><img src="{$theme[\'imgdir\']}/pollbar.gif" width="{$imagewidth}" height="10" alt="{$percent}%" title="{$percent}%" /><img src="{$theme[\'imgdir\']}/pollbar-e.gif" alt="" /></td>
</tr>
		'),
        "sid"            => "-1",
        "version"        => $mybb->version + 1,
        "dateline"        => TIME_NOW,
    );
	$db->insert_query('templates', $template);

	// Add edits to default templates
	require_once MYBB_ROOT.'/inc/adminfunctions_templates.php';
	
	find_replace_templatesets('index', '#'.preg_quote('{$header}').'#', '{$header}{$homepoll}');
	
	find_replace_templatesets('portal', '#'.preg_quote('{$header}').'#', '{$header}{$homepoll}');
	
}

/*
 * homepoll_deactivate()
 * 
 * Removes new templates and reverts edits to default templates.
 */
function homepoll_deactivate()
{
    global $db;
	
	// Delete templates
	$db->delete_query("templates", "title LIKE 'homepoll_%' AND sid= '-1'");

	// Delete edits to default templates
	require_once MYBB_ROOT.'/inc/adminfunctions_templates.php';
	
	find_replace_templatesets('index', '#'.preg_quote('{$homepoll}').'#', '');
	find_replace_templatesets('portal', '#'.preg_quote('{$homepoll}').'#', '');
} 

/*
 * homepoll_templatelist()
 * 
 * Add templates to global template list if current script is index.php or portal.php.
 */
$plugins->add_hook('global_start', 'homepoll_templatelist');
function homepoll_templatelist()
{
	global $templatelist;
	
	if(!isset($templatelist))
	{
		return false;
	}
	
	if(THIS_SCRIPT == 'index.php' || THIS_SCRIPT == 'portal.php')
	{
		$templatelist .= ',homepoll_poll,homepoll_results,showthread_poll_resultbit,homepoll_poll_compact,homepoll_poll_compact,homepoll_results_compact,homepoll_resultbit_compact';
	}
}

// Time to display the poll!
if ($mybb->settings['homepoll_index'])
{
	$plugins->add_hook('index_start', 'homepoll_poll');
}
if ($mybb->settings['homepoll_portal'])
{	
	$plugins->add_hook('portal_start', 'homepoll_poll');
}	

/*
 * homepoll_poll()
 * 
 * Checks which pid to use or forum to pull a poll from, then generates the poll.
 */
function homepoll_poll()
{
	global $mybb, $db, $templates, $theme, $lang, $this_script, $polls_script, $homepoll;
	
	$lang->load('showthread');
	$lang->load('homepoll');
    $parser = new postParser;

	$options = array(
		'limit' => 1
	);
	
	// Query if the user supplied a pid. Join with the threads table to obtain the fid of the poll (needed to inherit permissions).
	if ($mybb->settings['homepoll_pid'])
	{
		$pid = (int) $mybb->settings['homepoll_pid'];
	
		$query = $db->write_query("
			SELECT t.tid, t.fid, p.*
			FROM ".TABLE_PREFIX."threads t
			LEFT JOIN ".TABLE_PREFIX."polls p ON (t.tid=p.tid)
			WHERE p.pid=".$pid."
		");
		
		$poll = $db->fetch_array($query);
	}
	
	// Query if the user supplied an fid/fids. Join with the threads table so polls can be limited by fid (and so permissions can be inherited).
	elseif ($mybb->settings['homepoll_fid'])
	{
		$poll_fids = explode(',', $mybb->settings['homepoll_fid']);
		
		if (is_array($poll_fids))
		{
			$poll_fids = array_map('intval', $poll_fids);
			$poll_fids = implode(',', $poll_fids);
		}
		else
		{
			$poll_fids = (int) $mybb->settings['homepoll_fid'];
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
	
	$this_script = THIS_SCRIPT;
	$forumpermissions = forum_permissions($poll['fid']);
	
	// Only display if the query is not empty and user has the right to view the poll.
	if(!$poll['pid'] && ($forumpermissions['canview'] != 1 || $forumpermissions['canviewthreads'] != 1))
	{
		return false;
	}
	
	$poll['timeout'] = $poll['timeout']*60*60*24;
	$expiretime = $poll['dateline'] + $poll['timeout'];
	$now = TIME_NOW;

	// If the poll or the thread is closed or if the poll is expired, show the results.
	if($poll['closed'] == 1 || $thread['closed'] == 1 || ($expiretime < $now && $poll['timeout'] > 0))
	{
		if (!$mybb->settings['homepoll_closed'])
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
		$totalvotes += $votes;
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
			elseif ($mybb->settings['homepoll_compact'])
			{
				$percent = number_format($votes / $poll['totvotes'] * 100);
			}
			else
			{
				$percent = number_format($votes / $poll['totvotes'] * 100, 2);
			}
			
			if ($mybb->settings['homepoll_compact'])
			{
				$imagewidth = round($percent);
			}
			else
			{
				$imagewidth = round(($percent/3) * 5);
			}
			
			$imagerowwidth = $imagewidth + 10;
			
			if ($mybb->settings['homepoll_compact'])
			{
				eval("\$polloptions .= \"".$templates->get("homepoll_resultbit_compact")."\";");
			}
			else
			{
				eval("\$polloptions .= \"".$templates->get("showthread_poll_resultbit")."\";");
			}
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
	if(!is_moderator($poll['fid'], 'caneditposts'))
	{
		$edit_poll = '';
	}
	else
	{
		if (!$mybb->settings['homepoll_compact'])
		{
			$edit_poll = ' | ';
		}
		else
		{
			$edit_poll = '<br />[';
		}
		
		// If set to redirect to poll's thread, use default MyBB behavior.
		if ($mybb->settings['homepoll_redirect'])
		{
			$edit_poll .= '<a href="polls.php?action=editpoll&amp;pid='.$poll['pid'].'">'.$lang->edit_poll.'</a>';	
		}
		else
		{			
			$edit_poll .= '<a href="inc/plugins/homepoll/polls.php?&amp;action=editpoll&amp;pid='.$poll['pid'].'">'.$lang->edit_poll.'</a>';
		}
		
		if ($mybb->settings['homepoll_compact'])
		{
			$edit_poll .= ']';
		}
	}

	// Decide what poll status to show depending on the status of the poll and whether or not the user voted already.
	if($alreadyvoted || $showresults)
	{
		if($alreadyvoted && $mybb->usergroup['canundovotes'] == 1)
		{
			// If set to redirect to poll's thread, use default MyBB behavior.
			if ($mybb->settings['homepoll_redirect'])
			{
				$pollstatus = ' [<a href="inc/plugins/homepoll/polls.php?action=do_undovote&amp;pid='.$poll['pid'].'&amp;my_post_key='.$mybb->post_code.'">'.$lang->homepoll_undo_vote.'</a>]';
			}
			else
			{
				$pollstatus = ' [<a href="inc/plugins/homepoll/polls.php?action=do_undovote&amp;pid='.$poll['pid'].'&amp;my_post_key='.$mybb->post_code.'&amp;this_script='.THIS_SCRIPT.$url_append.'">'.$lang->homepoll_undo_vote.'</a>]';
			}
		}
		else
		{
			$pollstatus = $lang->poll_closed;
		}
		
		$lang->total_votes = $lang->sprintf($lang->total_votes, $totalvotes);
		
		if ($mybb->settings['homepoll_compact'])
		{
			eval("\$homepoll = \"".$templates->get("homepoll_results_compact")."\";");
		}
		else
		{
			eval("\$homepoll = \"".$templates->get("homepoll_results")."\";");
		}
	}
	else
	{
		$publicnote = '&nbsp;';
		if($poll['public'] == 1)
		{
			$publicnote = $lang->public_note;
		}
		
		// If set to redirect to poll's thread, use default MyBB behavior on vote submit.
		if ($settings['homepoll_redirect']['value'])
		{
			$polls_script = 'polls.php';
		}
		else
		{
			$polls_script = 'inc/plugins/homepoll/polls.php';
		}
		
		if ($mybb->settings['homepoll_compact'])
		{
			eval("\$homepoll = \"".$templates->get("homepoll_poll_compact")."\";");
		}
		else
		{
			eval("\$homepoll = \"".$templates->get("homepoll_poll")."\";");
		}
	}
}
?>