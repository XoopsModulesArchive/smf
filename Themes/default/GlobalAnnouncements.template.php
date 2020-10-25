<?php
/********************************************************************************
 * GlobalAnnouncements.template.php                                              *
 * ----------------------------------------------------------------------------- *
 * This shows the announcements that have been made.                             *
 * ********************************************************************************
 * Software version:               Global Announcements 0.1                      *
 * Software by:                    Juan "JayBachatero" Hernandez                 *
 * Copyright (c) 2006 by:          Juan "JayBachatero" Hernandez                 *
 * Contact:                        Jay@JayBachatero.com                          *
 * Website:                        JayBachatero.com                              *
 * ============================================================================= *
 * This mod is free software; you may redistribute it and/or modify it as long   *
 * as you credit me for the original mod. This mod is distributed in the hope    *
 * that it is and will be useful, but WITHOUT ANY WARRANTIES; without even any   *
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.      *
 *                                                                               *
 * All SMF copyrights are still in effect. Anything not mine is theirs. Enjoy!   *
 * Some code found in here is copy written code by SMF, therefore it can not be  *
 * redistributed without official consent from myself or SMF.                    *
 ********************************************************************************/

/*
   This template was copied off from Display.template.php to maintain the same
   layout as SMF's Diplay page.
*/

if (!defined('SMF')) {
    die('Hacking Attempt...');
}

function template_main()
{
    global $context, $txt, $settings, $modSettings, $options, $scripturl;

    // These are some cache image buttons we may want.

    $reply_button = create_button('quote.gif', 145, 'smf240', 'align="middle"');

    $modify_button = create_button('modify.gif', 66, 17, 'align="middle"');

    $remove_button = create_button('delete.gif', 121, 31, 'align="middle"');

    $split_button = create_button('split.gif', 'smf251', 'smf251', 'align="middle"');

    // Top tree link

    echo '
	<div style="margin-bottom: 2px;">
		<a name="top"></a>
		', theme_linktree(), '
	</div>';

    // Build the normal button array.

    $normal_buttons = [
        'print' => ['text' => 465, 'image' => 'print.gif', 'lang' => true, 'custom' => 'target="_blank"', 'url' => $scripturl . '?action=globalAnnouncements;sa=print;id=' . $context['globalAnnouncement']['id']],
    ];

    // Show the button strip.

    echo '
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td align="right" style="padding-right: 1ex;">
					<table cellpadding="0" cellspacing="0">
						<tr>
							', template_button_strip($normal_buttons, 'bottom'), '
						</tr>
					</table>
				</td>
			</tr>
		</table>';

    // Show the topic information - icon, subject, etc.

    echo '
		<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder" style="border-bottom: 0;">
			<tr class="catbg3">
				<td valign="middle" width="2%" style="padding-left: 6px;">
					<img src="', $settings['images_url'], '/topic/normal_post.gif" align="bottom" alt="">
				</td>
				<td width="13%"> ', $txt[29], '</td>
				<td valign="middle" width="85%" style="padding-left: 6px;" id="top_subject">
					', $txt[118], ': ', $context['globalAnnouncement']['subject'], ' &nbsp;(', $txt[641], ' ', $context['globalAnnouncement']['numViews'], ' ', $txt[642], ')
				</td>
			</tr>
		</table>';

    echo '
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="bordercolor">
		<tr><td style="padding: 1px 1px 0 1px;">
		<table width="100%" cellpadding="3" cellspacing="0" border="0">
			<tr>
				<td class="windowbg">';

    // Show information about the poster of this message.

    echo '
				<table width="100%" cellpadding="5" cellspacing="0" style="table-layout: fixed;">
					<tr>
						<td valign="top" width="16%" rowspan="2" style="overflow: hidden;">
							<b>', $context['globalAnnouncement']['member']['link'], '</b>
							<div class="smalltext">';

    // Show the member's custom title, if they have one.

    if (isset($context['globalAnnouncement']['member']['title']) && '' != $context['globalAnnouncement']['member']['title']) {
        echo '
								', $context['globalAnnouncement']['member']['title'], '<br>';
    }

    // Show the member's primary group (like 'Administrator') if they have one.

    if (isset($context['globalAnnouncement']['member']['group']) && '' != $context['globalAnnouncement']['member']['group']) {
        echo '
								', $context['globalAnnouncement']['member']['group'], '<br>';
    }

    // Don't show these things for guests.

    if (!$context['globalAnnouncement']['member']['is_guest']) {
        // Show the post group if and only if they have no other group or the option is on, and they are in a post group.

        if ((empty($settings['hide_post_group']) || '' == $context['globalAnnouncement']['member']['group']) && '' != $context['globalAnnouncement']['member']['post_group']) {
            echo '
								', $context['globalAnnouncement']['member']['post_group'], '<br>';
        }

        echo '
								', $context['globalAnnouncement']['member']['group_stars'], '<br>';

        // Is karma display enabled?  Total or +/-?

        if ('1' == $modSettings['karmaMode']) {
            echo '
								<br>
								', $modSettings['karmaLabel'], ' ', $context['globalAnnouncement']['member']['karma']['good'] - $context['globalAnnouncement']['member']['karma']['bad'], '<br>';
        } elseif ('2' == $modSettings['karmaMode']) {
            echo '
								<br>
								', $modSettings['karmaLabel'], ' +', $context['globalAnnouncement']['member']['karma']['good'], '/-', $context['globalAnnouncement']['member']['karma']['bad'], '<br>';
        }

        // Show online and offline buttons?

        if (!empty($modSettings['onlineEnable']) && !$context['globalAnnouncement']['member']['is_guest']) {
            echo '
								', allowedTo('pm_send') ? '<a href="' . $context['globalAnnouncement']['member']['online']['href'] . '" title="' . $context['globalAnnouncement']['member']['online']['label'] . '">' : '', $settings['use_image_buttons'] ? '<img src="'
                                                                                                                                                                                                                                                             . $context['globalAnnouncement']['member']['online']['image_href']
                                                                                                                                                                                                                                                             . '" alt="'
                                                                                                                                                                                                                                                             . $context['globalAnnouncement']['member']['online']['text']
                                                                                                                                                                                                                                                             . '" border="0" style="margin-top: 2px;">' : $context['globalAnnouncement']['member']['online']['text'], allowedTo(
                                                                                                                                                                                                                                                                 'pm_send'
                                                                                                                                                                                                                                                             ) ? '</a>' : '', $settings['use_image_buttons'] ? '<span class="smalltext"> ' . $context['globalAnnouncement']['member']['online']['text'] . '</span>' : '', '<br><br>';
        }

        // Show the member's gender icon?

        if (!empty($settings['show_gender']) && '' != $context['globalAnnouncement']['member']['gender']['image']) {
            echo '
								', $txt[231], ': ', $context['globalAnnouncement']['member']['gender']['image'], '<br>';
        }

        // Show how many posts they have made.

        echo '
								', $txt[26], ': ', $context['globalAnnouncement']['member']['posts'], '<br>
								<br>';

        // Show avatars, images, etc.?

        if (!empty($settings['show_user_images']) && empty($options['show_no_avatars']) && !empty($context['globalAnnouncement']['member']['avatar']['image'])) {
            echo '
								<div style="overflow: auto; width: 100%;">', $context['globalAnnouncement']['member']['avatar']['image'], '</div><br>';
        }

        // Show their personal text?

        if (!empty($settings['show_blurb']) && '' != $context['globalAnnouncement']['member']['blurb']) {
            echo '
								', $context['globalAnnouncement']['member']['blurb'], '<br>
								<br>';
        }

        // This shows the popular messaging icons.

        echo '
								', $context['globalAnnouncement']['member']['icq']['link'], '
								', $context['globalAnnouncement']['member']['msn']['link'], '
								', $context['globalAnnouncement']['member']['aim']['link'], '
								', $context['globalAnnouncement']['member']['yim']['link'], '<br>';

        // Show the profile, website, email address, and personal message buttons.

        if ($settings['show_profile_buttons']) {
            // Don't show the profile button if you're not allowed to view the profile.

            if ($context['globalAnnouncement']['member']['can_view_profile']) {
                echo '
								<a href="', $context['globalAnnouncement']['member']['href'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/icons/profile_sm.gif" alt="' . $txt[27] . '" title="' . $txt[27] . '" border="0">' : $txt[27]), '</a>';
            }

            // Don't show an icon if they haven't specified a website.

            if ('' != $context['globalAnnouncement']['member']['website']['url']) {
                echo '
								<a href="', $context['globalAnnouncement']['member']['website']['url'], '" title="' . $context['globalAnnouncement']['member']['website']['title'] . '" target="_blank">', ($settings['use_image_buttons'] ? '<img src="'
                                                                                                                                                                                                                                             . $settings['images_url']
                                                                                                                                                                                                                                             . '/www_sm.gif" alt="'
                                                                                                                                                                                                                                             . $txt[515]
                                                                                                                                                                                                                                             . '" border="0">' : $txt[515]), '</a>';
            }

            // Don't show the email address if they want it hidden.

            if (empty($context['globalAnnouncement']['member']['hide_email'])) {
                echo '
								<a href="mailto:', $context['globalAnnouncement']['member']['email'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt[69] . '" title="' . $txt[69] . '" border="0">' : $txt[69]), '</a>';
            }

            // Since we know this person isn't a guest, you *can* message them.

            if (allowedTo('pm_send')) {
                echo '
								<a href="', $scripturl, '?action=pm;sa=send;u=', $context['globalAnnouncement']['member']['id'], '" title="', $context['globalAnnouncement']['member']['online']['label'], '">', $settings['use_image_buttons'] ? '<img src="'
                                                                                                                                                                                                                                                  . $settings['images_url']
                                                                                                                                                                                                                                                  . '/im_'
                                                                                                                                                                                                                                                  . ($context['globalAnnouncement']['member']['online']['is_online'] ? 'on' : 'off')
                                                                                                                                                                                                                                                  . '.gif" alt="'
                                                                                                                                                                                                                                                  . $context['globalAnnouncement']['member']['online']['label']
                                                                                                                                                                                                                                                  . '" border="0">' : $context['globalAnnouncement']['member']['online']['label'], '</a>';
            }
        }
    } // Otherwise, show the guest's email.

    elseif (empty($context['globalAnnouncement']['member']['hide_email'])) {
        echo '
								<br>
								<br>
								<a href="mailto:', $context['globalAnnouncement']['member']['email'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt[69] . '" title="' . $txt[69] . '" border="0">' : $txt[69]), '</a>';
    }

    // Done with the information about the poster... on to the post itself.

    echo '
							</div>
						</td>
						<td valign="top" width="85%" height="100%">
							<table width="100%" border="0"><tr>
								<td valign="middle"><a href="', $context['globalAnnouncement']['href'], '"><img src="', $settings['images_url'] . '/post/' . $context['globalAnnouncement']['icon'] . '.gif" alt="" border="0"></a></td>
								<td valign="middle">
									<div style="font-weight: bold;" id="subject_', $context['globalAnnouncement']['id'], '">
										<a href="', $context['globalAnnouncement']['href'], '">', $context['globalAnnouncement']['subject'], '</a>
									</div>';

    // If this is the first post, (#0) just say when it was posted - otherwise give the reply #.

    echo '
									<div class="smalltext">&#171; <b>', $txt[30], ':</b> ', $context['globalAnnouncement']['time'], ' &#187;</div></td>
								<td align="', !$context['right_to_left'] ? 'right' : 'left', '" valign="bottom" height="20" style="font-size: smaller;">';

    // Can the user modify the contents of this post?

    if (allowedTo('global_announcements_admin')) {
        echo '
					<a href="', $context['globalAnnouncement']['edit'], '">', $modify_button, '</a>';
    }

    // How about... even... remove it entirely?!

    if (allowedTo('global_announcements_admin')) {
        echo '
					<a href="', $context['globalAnnouncement']['delete'], ';sesc=', $context['session_id'], '" onClick="confirm(\'', $txt['ga_delete_confirm'], '\')">', $remove_button, '</a>';
    }

    // Show the post itself, finally!

    echo '
								</td>
							</tr></table>
							<hr width="100%" size="1" class="hrcolor">
							<div class="post">
								', $context['globalAnnouncement']['body'], '
							</div>
						</td>
					</tr>';

    // Now for the attachments, signature, ip logged, etc...

    echo '
					<tr>
						<td valign="bottom" class="smalltext" width="85%">
							<table width="100%" border="0" style="table-layout: fixed;"><tr>
								<td valign="bottom" class="smalltext" id="modified_', $context['globalAnnouncement']['id'], '">';

    // Show " Last Edit: Time by Person " if this post was edited.

    if ($settings['show_modify'] && !empty($context['globalAnnouncement']['modifiedName'])) {
        echo '
									&#171; <i>', $txt[211], ': ', $context['globalAnnouncement']['modifiedTime'], ' ', $txt[525], ' ', $context['globalAnnouncement']['modifiedName'], '</i> &#187;';
    }

    echo '
								</td>
							</tr></table>';

    // Show the member's signature?

    if (!empty($context['globalAnnouncement']['member']['signature']) && empty($options['show_no_signatures'])) {
        echo '
							<hr width="100%" size="1" class="hrcolor">
							<div class="signature">', $context['globalAnnouncement']['member']['signature'], '</div>';
    }

    echo '
						</td>
					</tr>
				</table>
			</td></tr>
		</table></td></tr>';

    echo '
			<tr><td style="padding: 0 0 1px 0;"></td></tr>
		</table>';

    echo '
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td align="right" style="padding-right: 1ex;">
					<table cellpadding="0" cellspacing="0">
						<tr>
							', template_button_strip($normal_buttons, 'top', true), '
						</tr>
					</table>
				</td>
			</tr>
		</table>';

    // Show breadcrumbs at the bottom too?

    echo '
		<div>', theme_linktree(), '<br></div>';
}

// Taken from PrintPage template.
function template_print()
{
    global $context, $settings, $options, $txt;

    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '">
		<title>', $txt[668], ' - ', $context['subject'], '</title>
		<style type="text/css">
			body
			{
				color: black;
				background-color: white;
			}
			body, td, .normaltext
			{
				font-family: Verdana, arial, helvetica, serif;
				font-size: small;
			}
			*, a:link, a:visited, a:hover, a:active
			{
				color: black !important;
			}
			table
			{
				empty-cells: show;
			}
			.code
			{
				font-size: x-small;
				font-family: monospace;
				border: 1px solid black;
				margin: 1px;
				padding: 1px;
			}
			.quote
			{
				font-size: x-small;
				border: 1px solid black;
				margin: 1px;
				padding: 1px;
			}
			.smalltext, .quoteheader, .codeheader
			{
				font-size: x-small;
			}
			.largetext
			{
				font-size: large;
			}
			hr
			{
				height: 1px;
				border: 0;
				color: black;
				background-color: black;
			}
		</style>';

    /* Internet Explorer 4/5 and Opera 6 just don't do font sizes properly. (they are big...)
        Thus, in Internet Explorer 4, 5, and Opera 6 this will show fonts one size smaller than usual.
        Note that this is affected by whether IE 6 is in standards compliance mode.. if not, it will also be big.
        Standards compliance mode happens when you use xhtml... */

    if ($context['browser']['needs_size_fix']) {
        echo '
		<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/fonts-compat.css">';
    }

    echo '
	</head>
	<body>
		<h1 class="largetext">', $context['forum_name'], '</h1>
		<h2 class="normaltext">', $txt['ga'], ' => ', $context['subject'], ' => ', $txt[195], ': ', $context['member'], ' ', $txt[176], ' ', $context['time'] . '</h2>

		<table width="90%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>';

    echo '
					<br>
					<hr size="2" width="100%">
					', $txt[196], ': <b>', $context['subject'], '</b><br>
					', $txt['ga_by'], ': <b>', $context['member'], '</b> ', $txt[176], ' <b>', $context['time'], '</b>
					<hr>
					<div style="margin: 0 5ex;">', $context['body'], '</div>';

    echo '
					<br><br>
					<div align="center" class="smalltext">', theme_copyright(), '</div>
				</td>
			</tr>
		</table>
	</body>
</html>';
}
