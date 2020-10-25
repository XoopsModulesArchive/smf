<?php
/********************************************************************************
 * GlobalAnnouncementsAdmin.template.php                                         *
 * ----------------------------------------------------------------------------- *
 * This is where you add, edit and delete Global Announcements.                  *
 * ********************************************************************************
 * Software version:               phpMusicLibrary 0.1 Alpha                     *
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

if (!defined('SMF')) {
    die('Hacking attempt...');
}

function template_main()
{
    global $txt, $scripturl, $context, $modSettings, $settings;

    echo '
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		function openPreview(url)
		{
			if (confirm("' . $txt['ga_open_preview'] . '"))
				window.open(url);
			else
				window.open(url, \'_self\');
		}
	// ]]></script>';

    echo '
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor" align="center">
			<tr class="catbg">
				<td align="left" colspan="3">
					<b>', $txt[139], ':</b> ', $context['page_index'], '
				</td>
			</tr>
			<tr class="titlebg">
				<td>
					', $txt[70], '
				</td>
				<td>
					', $txt[109], '
				</td>
				<td>
					', $txt[317], '
				</td>
			</tr>';

    //Check if there are global announcements.

    if (empty($context['globalAnnouncements'])) {
        echo '
			<tr>
				<td class="windowbg" colspan="4">
					', $txt['ga_empty'], '
				</td>
			</tr>';
    } else {
        //Lets initialize $alternate.

        $alternate = true;

        //Load the global announcements.

        foreach ($context['globalAnnouncements'] as $globalAnnouncement) {
            // Alternate colors.

            if (0 == $globalAnnouncement['ga']['enabled']) {
                $image = '<a href="'
                         . $globalAnnouncement['ga']['changeStatus']
                         . ';sesc='
                         . $context['session_id']
                         . '" onclick="return confirm(\''
                         . $txt['ga_change_status_confirm']
                         . '\')"><img src="'
                         . $settings['images_url']
                         . '/icons/package_old.gif" alt="" align="left" style="padding: 3px;"></a>';
            } else {
                $image = '<a href="'
                         . $globalAnnouncement['ga']['changeStatus']
                         . ';sesc='
                         . $context['session_id']
                         . '" onclick="return confirm(\''
                         . $txt['ga_change_status_confirm']
                         . '\')"><img src="'
                         . $settings['images_url']
                         . '/icons/package_installed.gif" alt="" align="left" style="padding: 3px;"></a>';
            }

            echo '
			<tr ', $alternate ? 'class="windowbg2"' : 'class="windowbg"', '>
				<td>
					', $image, '
					<a href="', $globalAnnouncement['ga']['delete'], ';sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt['ga_delete_confirm'], '\')"><img src="', $settings['images_url'] . '/icons/delete.gif" alt="', $txt['ga_delete'], '" align="right"></a>
					<a href="javascript:openPreview(\'', $globalAnnouncement['ga']['preview'], '\')"><img src="', $settings['images_url'], '/buttons/search.gif" alt="" align="right"></a>
					', $globalAnnouncement['ga']['link'], '
				</td>
				<td width="20%">
					', $globalAnnouncement['member']['link'], '
				</td>
				<td width="20%">
					', $globalAnnouncement['ga']['date'], '
				</td>
			</tr>';

            //Make alternate false.

            $alternate = !$alternate;
        }
    }

    //Close the table and bottom pages

    echo '
			<tr class="catbg">
				<td align="left" colspan="4">
					<b>', $txt[139], ':</b> ', $context['page_index'], '
				</td>
			</tr>
		</table>';
}

function template_addGA()
{
    global $txt, $context, $scripturl, $settings;

    // Post_box_name, post_form

    $context['post_box_name'] = 'body';

    $context['post_form'] = 'addGA';

    // Load the spell checker?

    if ($context['show_spellchecking']) {
        echo '
				<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/spellcheck.js"></script>';
    }

    // Start the javascript... and boy is there a lot.

    echo '
				<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[';

    // Start with message icons - and any missing from this theme.

    echo '
					var icon_urls = {';

    foreach ($context['icons'] as $icon) {
        echo '
						"', $icon['value'], '": "', $icon['url'], '"', $icon['is_last'] ? '' : ',';
    }

    echo '
					};';

    // The actual message icon selector.

    echo '
					function showimage()
					{
						document.images.icons.src = icon_urls[document.forms.addGA.icon.options[document.forms.addGA.icon.selectedIndex].value];
					}
				</script>';

    echo '
				<form action="', $scripturl, '?action=globalAnnouncementsAdmin;sa=add" method="post" name="addGA" id="addGA" accept-charset="', $context['character_set'], '" style="padding:0; margin: 0;">';

    // If the user wants to see how their message looks - the preview table is where it's at!

    echo '
					<div id="preview_section"', isset($context['preview_message']) ? '' : ' style="display: none;"', '>
						<table border="0" width="100%" cellspacing="1" cellpadding="3" class="bordercolor" align="center" style="table-layout: fixed;">
							<tr class="titlebg">
								<td id="preview_subject">', empty($context['preview_subject']) ? '' : $context['preview_subject'], '</td>
							</tr>
							<tr>
								<td class="windowbg" width="100%">
									<div id="preview_body" class="post">', empty($context['preview_message']) ? '' : $context['preview_message'], '</div>
								</td>
							</tr>
						</table><br>
					</div>

					<table border="0" cellspacing="0" cellpadding="4" align="center" width="100%" class="tborder">
						<tr class="titlebg">
							<td colspan="2">', $txt['ga_add_title'], '</td>
						</tr>
						<tr class="windowbg2">
							<td width="30%">
								<strong>', $txt['ga_icon'], '</strong>
							</td>
							<td>
								<select name="icon" id="icon" onchange="showimage()">';

    // Loop through each message icon allowed, adding it to the drop down list.

    foreach ($context['icons'] as $icon) {
        echo '
									<option value="', $icon['value'], '">', $icon['name'], '</option>';
    }

    echo '
								</select>
								<img src="', $context['icon_url'], '" name="icons" hspace="15" alt="">
							</td>
						</tr>
						<tr class="windowbg2">
							<td colspan="2">
								<hr size="1" width="100%" class="hrcolor">
							</td>
						</tr>
						<tr class="windowbg2">
							<td valign="top" width="30%">
								<strong>', $txt['ga_subject'], '</strong>
							</td>
							<td>
								<input type="text" name="subject" value="', $context['preview_subject2'] ?? '', '" style="width: 40%;">
							</td>
						</tr>
						<tr class="windowbg2">
							<td colspan="2">
								<hr size="1" width="100%" class="hrcolor">
							</td>
						</tr>
						<tr class="windowbg2">
							<td valign="top">
								<strong>', $txt['ga_boards'], '<br></strong>
								<span class="smalltext">', $txt['ga_boards_desc'], '</span>
							</td>
							<td>
								<select name="boards[]" size="15" multiple="multiple" style="width: 55%;">
									<option value="0">' . $txt['ga_boards_all'] . '</option>';

    foreach ($context['jump_to'] as $category) {
        echo '
									<option disabled="disabled">----------------------------------------------------</option>
									<option disabled="disabled">', $category['name'], '</option>
									<option disabled="disabled">----------------------------------------------------</option>';

        foreach ($category['boards'] as $board) {
            echo '
									<option value="', $board['id'], '" ', isset($context['preview_boards']) ? (in_array($board['id'], $context['preview_boards'], true) ? 'selected="selected"' : '') : '', '> '
                                                                                                                                                                                                      . str_repeat('&nbsp;&nbsp;&nbsp; ', $board['child_level'])
                                                                                                                                                                                                      . '|--- '
                                                                                                                                                                                                      . $board['name']
                                                                                                                                                                                                      . '</option>';
        }
    }

    echo '
								</select>
							</td>
						</tr>
						<tr class="windowbg2">
							<td colspan="2">
								<hr size="1" width="100%" class="hrcolor">
							</td>
						</tr>';

    // Smileys and bbc

    theme_postbox2('');

    echo '
						<tr class="titlebg2">
							<td valign="top" colspan="2" align="center">
								<strong>', $txt['ga_options'], '</strong>
							</td>
						</tr>
						<tr class="windowbg2">
							<td valign="top" colspan="2">
								<input type="checkbox" name="enabled" id="enabled" checked> <label for="enabled"><strong>', $txt['ga_enable'], '</strong></label><br>
								<input type="checkbox" name="countViews" id="countViews" checked> <label for="countViews"><strong>', $txt['ga_count_views'], '</strong></label><br>
									<span class="smalltext" style="padding-left: 25px;">', $txt['ga_count_views_desc'], '</span><br>';

    // Check if they can email members.

    if (allowedTo('send_mail')) {
        echo '
								<input type="checkbox" name="emailMembers" id="emailMembers"> <label for="emailMembers"><strong>', $txt['ga_email_members'], '</strong></label><br>';
    }

    echo '
								<label for="oder"><strong>', $txt['ga_order'], '</strong></label>: <input type="text" name="gaOrder" id="order" value="" size="3"><br>
									<span class="smalltext" style="padding-left: 25px;">', $txt['ga_order_desc'], '</span><br>

							</td>
						</tr>
						<tr class="windowbg2">
							<td colspan="2">
								<hr size="1" width="100%" class="hrcolor">
							</td>
						</tr>
						<tr class="windowbg2">
							<td class="windowbg2" colspan="2" align="center" valign="middle">
								<input type="hidden" name="sc" value="', $context['session_id'], '">
								<input type="submit" name="preview" value="', $txt[507], '"  accesskey="p">
								<input type="submit" name="add" value="', $txt['ga_add'], '" accesskey"s">';

    // Check spelling

    if ($context['show_spellchecking']) {
        echo '
								<input type="button" value="', $txt['spell_check'], '" onclick="spellCheck(\'addGA\', \'body\');">';
    }

    echo '
							</td>
						</tr>
					</table>
				</form>';

    // A hidden form to post data to the spell checking window.

    if ($context['show_spellchecking']) {
        echo '
				<form action="', $scripturl, '?action=spellcheck" method="post" accept-charset="', $context['character_set'], '" name="spell_form" id="spell_form" target="spellWindow">
					<input type="hidden" name="spellstring" value="">
				</form>';
    }
}

function template_editGA()
{
    global $context, $scripturl, $txt, $ID_MEMBER, $settings;

    //Make life easier and user $context['ga_edit'] instead of $context['globalAnnouncements']['edit'];

    $context['ga_edit'] = $context['globalAnnouncements']['edit'];

    $context['ga_boards'] = $context['ga_edit']['ga']['boards'];

    // Post_box_name, post_form

    $context['post_box_name'] = 'body';

    $context['post_form'] = 'editGA';

    // Load the spell checker?

    if ($context['show_spellchecking']) {
        echo '
				<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/spellcheck.js"></script>';
    }

    // Start the javascript... and boy is there a lot.

    echo '
				<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[';

    // Start with message icons - and any missing from this theme.

    echo '
					var icon_urls = {';

    foreach ($context['icons'] as $icon) {
        echo '
						"', $icon['value'], '": "', $icon['url'], '"', $icon['is_last'] ? '' : ',';
    }

    echo '
					};';

    // The actual message icon selector.

    echo '
					function showimage()
					{
						document.images.icons.src = icon_urls[document.forms.editGA.icon.options[document.forms.editGA.icon.selectedIndex].value];
					}
				</script>';

    echo '
				<form action="', $scripturl, '?action=globalAnnouncementsAdmin;sa=edit;id=', $context['ga_edit']['ga']['id'], '" method="post" name="editGA" id="editGA" accept-charset="', $context['character_set'], '" style="padding:0; margin: 0;">';

    // If the user wants to see how their message looks - the preview table is where it's at!

    echo '
					<div id="preview_section"', isset($context['preview_message']) ? '' : ' style="display: none;"', '>
						<table border="0" width="100%" cellspacing="1" cellpadding="3" class="bordercolor" align="center" style="table-layout: fixed;">
							<tr class="titlebg">
								<td id="preview_subject">', empty($context['preview_subject']) ? '' : $context['preview_subject'], '</td>
							</tr>
							<tr>
								<td class="windowbg" width="100%">
									<div id="preview_body" class="post">', empty($context['preview_message']) ? '' : $context['preview_message'], '</div>
								</td>
							</tr>
						</table><br>
					</div>

					<table border="0" cellspacing="0" cellpadding="4" align="center" width="100%" class="tborder">
						<tr class="titlebg">
							<td colspan="2">', $txt['ga_add_title'], '</td>
						</tr>
						<tr class="windowbg2">
							<td width="30%">
								<strong>', $txt['ga_icon'], '</strong>
							</td>
							<td>
								<select name="icon" id="icon" onchange="showimage()">';

    // Loop through each message icon allowed, adding it to the drop down list.

    foreach ($context['icons'] as $icon) {
        echo '
									<option value="', $icon['value'], '"', $icon['value'] == $context['ga_edit']['ga']['icon'] ? ' selected="selected"' : '', '>', $icon['name'], '</option>';
    }

    //Override icon_url.

    $context['icon_url'] = $settings['images_url'] . '/post/' . $context['ga_edit']['ga']['icon'] . '.gif';

    echo '
								</select>
								<img src="', $context['icon_url'], '" name="icons" hspace="15" alt="">
							</td>
						</tr>
						<tr class="windowbg2">
							<td colspan="2">
								<hr size="1" width="100%" class="hrcolor">
							</td>
						</tr>
						<tr class="windowbg2">
							<td valign="top" width="30%">
								<strong>', $txt['ga_subject'], '</strong>
							</td>
							<td>
								<input type="text" name="subject" value="', $context['preview_subject2'] ?? $context['ga_edit']['ga']['subject'], '" style="width: 40%;">
							</td>
						</tr>
						<tr class="windowbg2">
							<td colspan="2">
								<hr size="1" width="100%" class="hrcolor">
							</td>
						</tr>
						<tr class="windowbg2">
							<td valign="top">
								<strong>', $txt['ga_boards'], '<br></strong>
								<span class="smalltext">', $txt['ga_boards_desc'], '</span>
							</td>
							<td>
								<select name="boards[]" size="15" multiple="multiple" style="width: 55%;">
									<option value="0" ', in_array(0, ($context['preview_boards'] ?? $context['ga_boards']), true) ? 'selected="selected"' : '', '>' . $txt['ga_boards_all'] . '</option>';

    foreach ($context['jump_to'] as $category) {
        echo '
									<option disabled="disabled">----------------------------------------------------</option>
									<option disabled="disabled">', $category['name'], '</option>
									<option disabled="disabled">----------------------------------------------------</option>';

        foreach ($category['boards'] as $board) {
            echo '
									<option value="', $board['id'], '" ', in_array($board['id'], ($context['preview_boards'] ?? $context['ga_boards']), true) ? 'selected="selected"' : '', '> '
                                                                                                                                                                                                                         . str_repeat('&nbsp;&nbsp;&nbsp; ', $board['child_level'])
                                                                                                                                                                                                                         . '|--- '
                                                                                                                                                                                                                         . $board['name']
                                                                                                                                                                                                                         . '</option>';
        }
    }

    echo '
								</select>
							</td>
						</tr>
						<tr class="windowbg2">
							<td colspan="2">
								<hr size="1" width="100%" class="hrcolor">
							</td>
						</tr>';

    // Smileys and bbc

    theme_postbox2($context['ga_edit']['ga']['body']);

    echo '
						<tr class="titlebg2">
							<td valign="top" colspan="2" align="center">
								<strong>', $txt['ga_options'], '</strong>
							</td>
						</tr>
						<tr class="windowbg2">
							<td valign="top" colspan="2">
								<input type="checkbox" name="enabled" id="enabled" ', 1 == $context['ga_edit']['ga']['enabled'] ? 'checked' : '', '> <label for="enabled"><strong>', $txt['ga_enable'], '</strong></label><br>
								<input type="checkbox" name="countViews" id="countViews" ', 1 == $context['ga_edit']['ga']['countViews'] ? 'checked' : '', '> <label for="countViews"><strong>', $txt['ga_count_views'], '</strong></label><br>
									<span class="smalltext" style="padding-left: 25px;">', $txt['ga_count_views_desc'], '</span><br>
								<label for="oder"><strong>', $txt['ga_order'], '</strong></label>: <input type="text" name="gaOrder" id="order" value="', '999' == $context['ga_edit']['ga']['order'] ? '' : $context['ga_edit']['ga']['order'], '" size="3"><br>
									<span class="smalltext" style="padding-left: 25px;">', $txt['ga_order_desc'], '</span><br>
							</td>
						</tr>
						<tr class="windowbg2">
							<td colspan="2">
								<hr size="1" width="100%" class="hrcolor">
							</td>
						</tr>
						<tr class="windowbg2">
							<td class="windowbg2" colspan="2" align="center" valign="middle">
								<input type="hidden" name="sc" value="', $context['session_id'], '">
								<input type="hidden" name="ID_GA" value="', $context['ga_edit']['ga']['id'], '">
								<input type="submit" name="preview" value="', $txt[507], '" accesskey="p">';

    // Check spelling

    if ($context['show_spellchecking']) {
        echo '
								<input type="button" value="', $txt['spell_check'], '" onclick="spellCheck(\'editGA\', \'body\');">';
    }

    echo '

								<input type="submit" name="edit" value="', $txt['ga_edit'], '">
							</td>
						</tr>
					</table>
				</form>';

    // A hidden form to post data to the spell checking window.

    if ($context['show_spellchecking']) {
        echo '
				<form action="', $scripturl, '?action=spellcheck" method="post" accept-charset="', $context['character_set'], '" name="spell_form" id="spell_form" target="spellWindow">
					<input type="hidden" name="spellstring" value="">
				</form>';
    }
}

// This function displays all the stuff you'd expect to see with a message box, the box, BBC buttons and of course smileys.
function template_postbox2($message)
{
    global $context, $settings, $options, $txt, $modSettings;

    if (!empty($context['preview_message2'])) {
        //Prepare data for editing.

        $context['preview_message2'] = un_htmlspecialchars($context['preview_message2']);

        // Remove special formatting we don't want anymore.

        $context['preview_message2'] = preg_replace('~<br(?: /)?' . '>~i', "\n", $context['preview_message2']);
    }

    // Assuming BBC code is enabled then print the buttons and some javascript to handle it.

    if ($context['show_bbc']) {
        echo '
						<tr class="windowbg2">
							<td valign="top">
								<strong>', $txt['ga_body'], '</strong><br>
								<span class="smalltext">', $txt['ga_body_desc'], '</span>
							</td>
							<td valign="middle">
								<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
									function bbc_highlight(something, mode)
									{
										something.style.backgroundImage = "url(" + smf_images_url + (mode ? "/bbc/bbc_hoverbg.gif)" : "/bbc/bbc_bg.gif)");
									}
								// ]]></script>';

        // The below array makes it dead easy to add images to this page. Add it to the array and everything else is done for you!

        $context['bbc_tags'] = [];

        $context['bbc_tags'][] = [
            'bold' => ['code' => 'b', 'before' => '[b]', 'after' => '[/b]', 'description' => $txt[253]],
            'italicize' => ['code' => 'i', 'before' => '[i]', 'after' => '[/i]', 'description' => $txt[254]],
            'underline' => ['code' => 'u', 'before' => '[u]', 'after' => '[/u]', 'description' => $txt[255]],
            'strike' => ['code' => 's', 'before' => '[s]', 'after' => '[/s]', 'description' => $txt[441]],
            [],
            'glow' => ['code' => 'glow', 'before' => '[glow=red,2,300]', 'after' => '[/glow]', 'description' => $txt[442]],
            'shadow' => ['code' => 'shadow', 'before' => '[shadow=red,left]', 'after' => '[/shadow]', 'description' => $txt[443]],
            'move' => ['code' => 'move', 'before' => '[move]', 'after' => '[/move]', 'description' => $txt[439]],
            [],
            'pre' => ['code' => 'pre', 'before' => '[pre]', 'after' => '[/pre]', 'description' => $txt[444]],
            'left' => ['code' => 'left', 'before' => '[left]', 'after' => '[/left]', 'description' => $txt[445]],
            'center' => ['code' => 'center', 'before' => '[center]', 'after' => '[/center]', 'description' => $txt[256]],
            'right' => ['code' => 'right', 'before' => '[right]', 'after' => '[/right]', 'description' => $txt[446]],
            [],
            'hr' => ['code' => 'hr', 'before' => '[hr]', 'description' => $txt[531]],
            [],
            'size' => ['code' => 'size', 'before' => '[size=10pt]', 'after' => '[/size]', 'description' => $txt[532]],
            'face' => ['code' => 'font', 'before' => '[font=Verdana]', 'after' => '[/font]', 'description' => $txt[533]],
        ];

        $context['bbc_tags'][] = [
            'flash' => ['code' => 'flash', 'before' => '[flash=200,200]', 'after' => '[/flash]', 'description' => $txt[433]],
            'img' => ['code' => 'img', 'before' => '[img]', 'after' => '[/img]', 'description' => $txt[435]],
            'url' => ['code' => 'url', 'before' => '[url]', 'after' => '[/url]', 'description' => $txt[257]],
            'email' => ['code' => 'email', 'before' => '[email]', 'after' => '[/email]', 'description' => $txt[258]],
            'ftp' => ['code' => 'ftp', 'before' => '[ftp]', 'after' => '[/ftp]', 'description' => $txt[434]],
            [],
            'table' => ['code' => 'table', 'before' => '[table]', 'after' => '[/table]', 'description' => $txt[436]],
            'tr' => ['code' => 'td', 'before' => '[tr]', 'after' => '[/tr]', 'description' => $txt[449]],
            'td' => ['code' => 'td', 'before' => '[td]', 'after' => '[/td]', 'description' => $txt[437]],
            [],
            'sup' => ['code' => 'sup', 'before' => '[sup]', 'after' => '[/sup]', 'description' => $txt[447]],
            'sub' => ['code' => 'sub', 'before' => '[sub]', 'after' => '[/sub]', 'description' => $txt[448]],
            'tele' => ['code' => 'tt', 'before' => '[tt]', 'after' => '[/tt]', 'description' => $txt[440]],
            [],
            'code' => ['code' => 'code', 'before' => '[code]', 'after' => '[/code]', 'description' => $txt[259]],
            'quote' => ['code' => 'quote', 'before' => '[quote]', 'after' => '[/quote]', 'description' => $txt[260]],
            [],
            'list' => ['code' => 'list', 'before' => '[list]\n[li]', 'after' => '[/li]\n[li][/li]\n[/list]', 'description' => $txt[261]],
        ];

        // Here loop through the array, printing the images/rows/separators!

        foreach ($context['bbc_tags'][0] as $image => $tag) {
            // Is there a "before" part for this bbc button? If not, it can't be a button!!

            if (isset($tag['before'])) {
                // Is this tag disabled?

                if (!empty($context['disabled_tags'][$tag['code']])) {
                    continue;
                }

                // If there's no after, we're just replacing the entire selection in the post box.

                if (!isset($tag['after'])) {
                    echo '
								<a href="javascript:void(0);" onclick="replaceText(\'', $tag['before'], '\', document.forms.', $context['post_form'], '.', $context['post_box_name'], '); return false;">';
                } // On the other hand, if there is one we are surrounding the selection ;).

                else {
                    echo '
								<a href="javascript:void(0);" onclick="surroundText(\'', $tag['before'], '\', \'', $tag['after'], '\', document.forms.', $context['post_form'], '.', $context['post_box_name'], '); return false;">';
                }

                // Okay... we have the link. Now for the image and the closing </a>!

                echo '<img onmouseover="bbc_highlight(this, true);" onmouseout="if (window.bbc_highlight) bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/', $image, '.gif" align="bottom" width="23" height="22" alt="', $tag['description'], '" title="', $tag['description'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;"></a>';
            } // I guess it's a divider...

            else {
                echo '
									<img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;">';
            }
        }

        // Print a drop down list for all the colors we allow!

        if (!isset($context['disabled_tags']['color'])) {
            echo '

								<select onchange="surroundText(\'[color=\' + this.options[this.selectedIndex].value.toLowerCase() + \']\', \'[/color]\', document.forms.', $context['post_form'], '.', $context['post_box_name'], '); this.selectedIndex = 0; document.forms.', $context['post_form'], '.', $context['post_box_name'], '.focus(document.forms.', $context['post_form'], '.', $context['post_box_name'], '.caretPos);" style="margin-bottom: 1ex;">
									<option value="" selected="selected">', $txt['change_color'], '</option>
									<option value="Black">', $txt[262], '</option>
									<option value="Red">', $txt[263], '</option>
									<option value="Yellow">', $txt[264], '</option>
									<option value="Pink">', $txt[265], '</option>
									<option value="Green">', $txt[266], '</option>
									<option value="Orange">', $txt[267], '</option>
									<option value="Purple">', $txt[268], '</option>
									<option value="Blue">', $txt[269], '</option>
									<option value="Beige">', $txt[270], '</option>
									<option value="Brown">', $txt[271], '</option>
									<option value="Teal">', $txt[272], '</option>
									<option value="Navy">', $txt[273], '</option>
									<option value="Maroon">', $txt[274], '</option>
									<option value="LimeGreen">', $txt[275], '</option>
								</select>';
        }

        echo '<br>';

        // Print the buttom row of buttons!

        foreach ($context['bbc_tags'][1] as $image => $tag) {
            if (isset($tag['before'])) {
                // Is this tag disabled?

                if (!empty($context['disabled_tags'][$tag['code']])) {
                    continue;
                }

                // If there's no after, we're just replacing the entire selection in the post box.

                if (!isset($tag['after'])) {
                    echo '<a href="javascript:void(0);" onclick="replaceText(\'', $tag['before'], '\', document.forms.', $context['post_form'], '.', $context['post_box_name'], '); return false;">';
                } // On the other hand, if there is one we are surrounding the selection ;).

                else {
                    echo '<a href="javascript:void(0);" onclick="surroundText(\'', $tag['before'], '\', \'', $tag['after'], '\', document.forms.', $context['post_form'], '.', $context['post_box_name'], '); return false;">';
                }

                // Okay... we have the link. Now for the image and the closing </a>!

                echo '<img onmouseover="bbc_highlight(this, true);" onmouseout="if (window.bbc_highlight) bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/', $image, '.gif" align="bottom" width="23" height="22" alt="', $tag['description'], '" title="', $tag['description'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;"></a>';
            } // I guess it's a divider...

            else {
                echo '<img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;">';
            }
        }

        echo '
							</td>
						</tr>';
    }

    // Now start printing all of the smileys.

    if (!empty($context['smileys']['postform'])) {
        echo '
						<tr class="windowbg2">
							<td align="right"></td>
							<td valign="middle">';

        // Show each row of smileys ;).

        foreach ($context['smileys']['postform'] as $smiley_row) {
            foreach ($smiley_row['smileys'] as $smiley) {
                echo '
								<a href="javascript:void(0);" onclick="replaceText(\' ', $smiley['code'], '\', document.forms.', $context['post_form'], '.', $context['post_box_name'], '); return false;"><img src="', $settings['smileys_url'], '/', $smiley['filename'], '" align="bottom" alt="', $smiley['description'], '" title="', $smiley['description'], '"></a>';
            }

            // If this isn't the last row, show a break.

            if (empty($smiley_row['last'])) {
                echo '<br>';
            }
        }

        // If the smileys popup is to be shown... show it!

        if (!empty($context['smileys']['popup'])) {
            echo '
								<a href="javascript:moreSmileys();">[', $txt['more_smileys'], ']</a>';
        }

        echo '
							</td>
						</tr>';
    }

    // If there are additional smileys then ensure we provide the javascript for them.

    if (!empty($context['smileys']['popup'])) {
        echo '
					<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
						var smileys = [';

        foreach ($context['smileys']['popup'] as $smiley_row) {
            echo '
							[';

            foreach ($smiley_row['smileys'] as $smiley) {
                echo '
								["', $smiley['code'], '","', $smiley['filename'], '","', $smiley['js_description'], '"]';

                if (empty($smiley['last'])) {
                    echo ',';
                }
            }

            echo ']';

            if (empty($smiley_row['last'])) {
                echo ',';
            }
        }

        echo '];
						var smileyPopupWindow;

						function moreSmileys()
						{
							var row, i;

							if (smileyPopupWindow)
								smileyPopupWindow.close();

							smileyPopupWindow = window.open("", "add_smileys", "toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,width=480,height=220,resizable=yes");
							smileyPopupWindow.document.write(\'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\n<html>\');
							smileyPopupWindow.document.write(\'\n\t<head>\n\t\t<title>', $txt['more_smileys_title'], '</title>\n\t\t<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/style.css">\n\t</head>\');
							smileyPopupWindow.document.write(\'\n\t<body style="margin: 1ex;">\n\t\t<table width="100%" cellpadding="5" cellspacing="0" border="0" class="tborder">\n\t\t\t<tr class="titlebg"><td align="left">', $txt['more_smileys_pick'], '</td></tr>\n\t\t\t<tr class="windowbg"><td align="left">\');

							for (row = 0; row < smileys.length; row++)
							{
								for (i = 0; i < smileys[row].length; i++)
								{
									smileys[row][i][2] = smileys[row][i][2].replace(/"/g, \'&quot;\');
									smileyPopupWindow.document.write(\'<a href="javascript:void(0);" onclick="window.opener.replaceText(&quot; \' + smileys[row][i][0] + \'&quot;, window.opener.document.forms.', $context['post_form'], '.', $context['post_box_name'], '); window.focus(); return false;"><img src="', $settings['smileys_url'], '/\' + smileys[row][i][1] + \'" alt="\' + smileys[row][i][2] + \'" title="\' + smileys[row][i][2] + \'" style="padding: 4px;" border="0"></a> \');
								}
								smileyPopupWindow.document.write("<br>");
							}

							smileyPopupWindow.document.write(\'</td></tr>\n\t\t\t<tr><td align="center" class="windowbg"><a href="javascript:window.close();\\">', $txt['more_smileys_close_window'], '</a></td></tr>\n\t\t</table>\n\t</body>\n</html>\');
							smileyPopupWindow.document.close();
						}
					// ]]></script>';
    }

    // Finally the most important bit - the actual text box to write in!

    echo '
						<tr class="windowbg2">
							<td valign="top" align="right"></td>
							<td>
								<textarea class="editor" name="body" cols="50" rows="', $context['post_box_rows'], '" style="width: 85%;" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onchange="storeCaret(this);">', empty($context['preview_message2']) ? $message : $context['preview_message2'], '</textarea>
							</td>
						</tr>';
}

function template_manage_settings()
{
    global $context, $modSettings, $txt, $scripturl;

    echo '
	<form action="', $scripturl, '?action=globalAnnouncementsAdmin;sa=settings" method="post" accept-charset="', $context['character_set'], '">
		<table border="0" cellspacing="0" cellpadding="4" align="center" width="80%" class="tborder">
			<tr class="titlebg">
				<td colspan="2">', $txt['ga_settings2'], '</td>
			</tr><tr class="windowbg2">
				<th width="50%" align="right"><label for="global_announcements_enable">', $txt['ga_enable_global'], '</label> <span style="font-weight: normal;">(<a href="', $scripturl, '?action=helpadmin;help=global_announcements_enable" onclick="return reqWin(this.href);">?</a>)</span>:</th>
				<td>
					<input type="checkbox" name="global_announcements_enable" id="global_announcements_enable"', empty($modSettings['global_announcements_enable']) ? '' : ' checked', ' class="check">
				</td>
			</tr><tr class="windowbg2">
				<th width="50%" align="right">
					<label for="global_announcements_sort_by">', $txt['ga_sort_by'], '</label>:
				</th>
				<td valign="top">
					<select name="global_announcements_sort_by" id="global_announcements_sort_by">';

    // The sections.

    foreach ($txt['ga_sort_by_options'] as $id => $option) {
        echo '
						<option value="', $id, '" ', isset($context['global_announcements_sort_by']) && $id == $context['global_announcements_sort_by'] ? 'selected="selected"' : '', '>', $option, '</option>';
    }

    echo '
					</select>
				</td>
			</tr><tr class="windowbg2">
				<th width="50%" align="right">
					<label for="global_announcements_sort_direction">', $txt['ga_sort_direction'], '</label>:
				</th>
				<td valign="top">
					<select name="global_announcements_sort_direction" id="global_announcements_sort_direction">';

    // The sections.

    foreach ($txt['ga_sort_direction_options'] as $id => $option) {
        echo '
						<option value="', $id, '" ', isset($context['global_announcements_sort_direction']) && $id == $context['global_announcements_sort_direction'] ? 'selected="selected"' : '', '>', $option, '</option>';
    }

    echo '
					</select>
				</td>
			</tr><tr class="windowbg2">
				<td align="right" colspan="2">
					<input type="submit" name="save_settings" value="', $txt['ga_save'], '">
				</td>
			</tr>
		</table>
		<input type="hidden" name="sc" value="', $context['session_id'], '">
	</form>';
}

function template_gaCredits()
{
    global $txt, $context;

    // Show the version info.

    echo '
		<table width="100%" cellpadding="5" cellspacing="0" border="0" class="tborder">
			<tr class="titlebg">
				<td>', $txt['ga_support'], '</td>
			</tr><tr>
				<td class="windowbg2">
					<b>', $txt['support_versions'], ':</b><br>
					', $txt['ga_version'], ':
					<b>', $context['ga']['installed_version'], '</b><br>
					', $txt['ga_current_version'], ':
					<b>', $context['ga']['current_version'], '</b>';

    // Check if its up to date.

    if ((float)$context['ga']['installed_version'] < (float)$context['ga']['current_version']) {
        echo '
					<br>
					<span style="color: red;">' . $txt['ga_upgrade'] . '</span>';
    }

    echo '

				</td>
			</tr>
		</table>';

    echo '
		<br>
		<table width="100%" cellpadding="5" cellspacing="0" border="0" class="tborder">
			<tr class="titlebg">
				<td>', $txt['ga_thanks'], '</td>
			</tr><tr>
				<td class="windowbg2">
					', $txt['ga_thanks'], '
					', $txt['ga_thanks_details'], '
				</td>
			</tr>
		</table>';
}
