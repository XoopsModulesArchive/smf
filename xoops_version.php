<?php
/**********************************************************************************
 * xoops_version.php                                                               *
 * **********************************************************************************
 * SMF: Simple Machines Forum                                                      *
 * Open-Source Project Inspired by Zef Hemel (zef@zefhemel.com)                    *
 * =============================================================================== *
 * Software Version:           SMF 1.1                                             *
 * Software by:                Simple Machines (http://www.simplemachines.org)     *
 * Copyright 2006 by:          Simple Machines LLC (http://www.simplemachines.org) *
 *           2001-2006 by:     Lewis Media (http://www.lewismedia.com)             *
 * Support, News, Updates at:  http://www.simplemachines.org                       *
 * **********************************************************************************
 * This program is free software; you may redistribute it and/or modify it under   *
 * the terms of the provided license as published by Simple Machines LLC.          *
 *                                                                                 *
 * This program is distributed in the hope that it is and will be useful, but      *
 * WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
 * or FITNESS FOR A PARTICULAR PURPOSE.                                            *
 *                                                                                 *
 * See the "license.txt" file for details of the Simple Machines license.          *
 * The latest version can always be found at http://www.simplemachines.org.        *
 **********************************************************************************/

$modversion['name'] = _MI_SMF_NAME;
$modversion['version'] = '1.1.2';
$modversion['simpleversion'] = '1.1.5';
$modversion['simplename'] = 'smf';
$modversion['simpleid'] = 4;
$modversion['description'] = 'This Module will display SMF in Xoops';
$modversion['credits'] = 'Simple Machines Forum';
$modversion['author'] = 'Theodore Hildebrandt / Dirk Herrmann';
$modversion['help'] = '';
$modversion['license'] = 'SMF LICENSE';
$modversion['official'] = 1;
$modversion['image'] = 'images/smf_logo.png';
$modversion['dirname'] = 'smf';

$modversion['onInstall'] = 'sql/install.php';
$modversion['onUpdate'] = 'sql/upgrade.php';
$modversion['onUninstall'] = 'sql/uninstall.php';

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = 'smf_attachments';
$modversion['tables'][1] = 'smf_ban_groups';
$modversion['tables'][2] = 'smf_ban_items';
$modversion['tables'][3] = 'smf_boards';
$modversion['tables'][4] = 'smf_board_permissions';
$modversion['tables'][5] = 'smf_calendar';
$modversion['tables'][6] = 'smf_calendar_holidays';
$modversion['tables'][7] = 'smf_categories';
$modversion['tables'][8] = 'smf_collapsed_categories';
$modversion['tables'][9] = 'smf_log_actions';
$modversion['tables'][10] = 'smf_log_activity';
$modversion['tables'][11] = 'smf_log_banned';
$modversion['tables'][12] = 'smf_log_boards';
$modversion['tables'][13] = 'smf_log_errors';
$modversion['tables'][14] = 'smf_log_floodcontrol';
$modversion['tables'][15] = 'smf_log_karma';
$modversion['tables'][16] = 'smf_log_mark_read';
$modversion['tables'][17] = 'smf_log_notify';
$modversion['tables'][18] = 'smf_log_online';
$modversion['tables'][19] = 'smf_log_polls';
$modversion['tables'][20] = 'smf_log_search_messages';
$modversion['tables'][21] = 'smf_log_search_results';
$modversion['tables'][22] = 'smf_log_search_subjects';
$modversion['tables'][23] = 'smf_log_search_topics';
$modversion['tables'][24] = 'smf_log_topics';
$modversion['tables'][25] = 'smf_membergroups';
//$modversion['tables'][26]	= "smf_members";
$modversion['tables'][27] = 'smf_messages';
$modversion['tables'][28] = 'smf_message_icons';
$modversion['tables'][29] = 'smf_moderators';
$modversion['tables'][30] = 'smf_package_servers';
$modversion['tables'][31] = 'smf_permissions';
$modversion['tables'][32] = 'smf_personal_messages';
$modversion['tables'][33] = 'smf_pm_recipients';
$modversion['tables'][34] = 'smf_polls';
$modversion['tables'][35] = 'smf_poll_choices';
//$modversion['tables'][36]	= "smf_sessions";
$modversion['tables'][37] = 'smf_settings';
$modversion['tables'][38] = 'smf_smileys';
$modversion['tables'][39] = 'smf_themes';
$modversion['tables'][40] = 'smf_topics';
$modversion['tables'][41] = 'smf_ob_googlebot_stats';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Menu/Sub Menu
$modversion['hasMain'] = 1; //make 0 to not have this appear in main menu
$modversion['sub'][1]['name'] = _MI_SMF_UNREADPOSTS;
$modversion['sub'][1]['url'] = 'index.php?action=unread';
$modversion['sub'][2]['name'] = _MI_SMF_HELP;
$modversion['sub'][2]['url'] = 'index.php?action=help';

//$modversion['templates'][1]['file'] = 'smf_index.html';
//$modversion['templates'][1]['description'] = '';

$modversion['config'][1]['name'] = 'smf_path';
$modversion['config'][1]['title'] = '_MI_SMF_PATH';
$modversion['config'][1]['description'] = '_MI_SMF_PATH_DESC';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = XOOPS_ROOT_PATH . '/modules/' . $modversion['dirname'];

$modversion['config'][2]['name'] = 'wrapped';
$modversion['config'][2]['title'] = '_MI_SMF_WRAPPED';
$modversion['config'][2]['description'] = '_MI_SMF_WRAPPED_DESC';
$modversion['config'][2]['formtype'] = 'yesno';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 1;

$modversion['config'][3]['name'] = 'showlblock';
$modversion['config'][3]['title'] = '_MI_SMF_SHOWLBLOCK';
$modversion['config'][3]['description'] = '_MI_SMF_SHOWLBLOCK_DESC';
$modversion['config'][3]['formtype'] = 'yesno';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 1;

$modversion['config'][4]['name'] = 'showrblock';
$modversion['config'][4]['title'] = '_MI_SMF_SHOWRBLOCK';
$modversion['config'][4]['description'] = '_MI_SMF_SHOWRBLOCK_DESC';
$modversion['config'][4]['formtype'] = 'yesno';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 0;

$modversion['config'][5]['name'] = 'xoopsregister';
$modversion['config'][5]['title'] = '_MI_SMF_REGISTERXOOPS';
$modversion['config'][5]['description'] = '_MI_SMF_REGISTERXOOPS_DESC';
$modversion['config'][5]['formtype'] = 'yesno';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = 0;

$i = 1;
$modversion['blocks'][$i]['file'] = 'smf_block.php';
$modversion['blocks'][$i]['name'] = _MI_SMF_BNAME_POST;
$modversion['blocks'][$i]['description'] = _MI_SMF_BNAME_POSTDESC;
$modversion['blocks'][$i]['show_func'] = 'b_smf_lastpost_show';
$modversion['blocks'][$i]['edit_func'] = 'b_smf_lastpost_edit';
$modversion['blocks'][$i]['options'] = '5|0|full|1';
$modversion['blocks'][$i]['template'] = 'smf_block_lastpost.html';
$i++;

$modversion['blocks'][$i]['file'] = 'smf_block.php';
$modversion['blocks'][$i]['name'] = _MI_SMF_BNAME_TOPIC;
$modversion['blocks'][$i]['description'] = _MI_SMF_BNAME_TOPICDESC;
$modversion['blocks'][$i]['show_func'] = 'b_smf_lasttopics_show';
$modversion['blocks'][$i]['edit_func'] = 'b_smf_lasttopics_edit';
$modversion['blocks'][$i]['options'] = '5|0|full|1';
$modversion['blocks'][$i]['template'] = 'smf_block_lasttopic.html';
$i++;

$modversion['blocks'][$i]['file'] = 'smf_block.php';
$modversion['blocks'][$i]['name'] = _MI_SMF_BNAME2;
$modversion['blocks'][$i]['description'] = _MI_SMF_BNAME2DESC;
$modversion['blocks'][$i]['show_func'] = 'b_smf_boardstats_show';
$modversion['blocks'][$i]['template'] = 'smf_block_boardstats.html';
$i++;

$modversion['blocks'][$i]['file'] = 'smf_block.php';
$modversion['blocks'][$i]['name'] = _MI_SMF_BNAME3;
$modversion['blocks'][$i]['description'] = _MI_SMF_BNAME3DESC;
$modversion['blocks'][$i]['show_func'] = 'b_smf_boardnews_show';
$modversion['blocks'][$i]['template'] = 'smf_block_boardnews.html';
$i++;

unset($i);

// Comments
$modversion['hasComments'] = 0;

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'smf_search';
