<?php

$context['ga']['installed_version'] = '1.0';

$txt['ga'] = 'Global Announcement';
$txt['ga_title'] = 'Global Announcements Admin';
$txt['ga_description'] = 'This will display a global announcement on selected boards';

$txt['ga_empty'] = 'There are no announcements at the moment.  Click <a href="' . $scripturl . '?action=globalAnnouncementsAdmin;sa=add">here</a> to add one.';
$txt['ga_delete_confirm'] = 'Are you sure you want to delete this announcement?';
$txt['ga_change_status_confirm'] = 'Are you sure that you want to change the status of this Global Announcement?';
$txt['ga_open_preview'] = 'Open this Global Announcement in a new window?';

$txt['ga_add2'] = 'Add';
$txt['ga_edit'] = 'Edit';
$txt['ga_delete'] = 'Delete';
$txt['ga_save'] = 'Save';

//Add GA page
$txt['ga_add_title'] = 'Add a Global Announcement';
$txt['ga_subject'] = 'Announcement Subject';
$txt['ga_boards'] = 'Announcement Boards';
$txt['ga_boards_all'] = 'All Boards';
$txt['ga_boards_desc'] = 'You may select more that one board.  To select boards hold down Shift or Ctrl.  If you want to show on all boards, select "<em>All Boards</em>" <strong>ONLY</strong>.';
$txt['ga_body'] = 'Announcement Content';
$txt['ga_body_desc'] = 'Here you can use BBC and Smileys like a regular post.';
$txt['ga_enable'] = 'Enable this announcement?';
$txt['ga_options'] = 'Options';
$txt['ga_icon'] = 'Announcement Icon';
$txt['ga_count_views'] = 'Count Views?';
$txt['ga_count_views_desc'] = 'If you disable this the number of views for announcements will not be counted.';
$txt['ga_email_members'] = 'Send an announcement email to members?';
$txt['ga_order'] = 'Order';
$txt['ga_order_desc'] = 'This is the order in which the Global Announcement will display in.  Leave blank to use default sorting method.';

//Tabs
$txt['ga_main'] = 'Modify GA';
$txt['ga_add'] = 'Add GA';
$txt['ga_settings'] = 'Settings';

// GA Settings page
$txt['ga_settings2'] = 'Global Announcements Settings';
$txt['ga_enable_global'] = 'Enable Global Announcements?';
$txt['ga_sort_by'] = 'Sort by';
$txt['ga_sort_by_options'] = [
    1 => 'Time',
    2 => 'ID',
    3 => 'Subject',
    4 => 'Number of Views',
];
$txt['ga_sort_direction'] = 'Sort direction';
$txt['ga_sort_direction_options'] = [
    1 => 'Ascending',
    2 => 'Descending',
];

//Errors
$txt['ga_error_missing_subject'] = 'You left the subject field empty.  Please go back and fill in the subject field.';
$txt['ga_error_missing_boards'] = 'You did not select atleast one board to show the announcement on.  You MUST select atleast one.';
$txt['ga_error_missing_body'] = 'You left the content field empty.  Please go back and fill in the content field.';
$txt['ga_error_delete_not_allowed'] = 'What do you think you\'re doing fool.  Back off.';
$txt['ga_error_edit_id'] = 'You did not enter a valid id.  Please enter a valid id to edit';
$txt['ga_error_no_rows'] = 'No announcement was found with the id that you specified.  Please check the id and try again.';
$txt['ga_error_query_array'] = 'You think you\'re funny right?  Sorry buddy but you can\'t call an array here.  Back away before the count of 3.  1... 2... 3... BOOM!  Die you fool.';
$txt['ga_error_no_id'] = 'The id you entered is not valid or it does not exists.';
$txt['ga_error_not_allowed'] = 'Sorry but you are not allowed to see the Global Announcement you have selected.';

// The champ is here
$txt['the_champ_is_here'] = 'Ha it seems like you have found another one of my nice little eggs.  You know what you get for this?  Nada, zip zero.  Keep on looking though :P.';

// Print Page
$txt['ga_by'] = 'Global Announcement by';

// Credits page
$txt['ga_credits'] = 'Credits';
$txt['ga_version'] = 'Version installed';
$txt['ga_current_version'] = 'Current version';
$txt['ga_upgrade'] = 'It seems like you are using an outdated version of this mod.  It is advised that you upgrade to the latest version.';
$txt['ga_support'] = 'Global Announcements Support Information';
$txt['ga_thanks'] = 'Special Thanks';
$txt['ga_thanks_details'] = ' to those that helped me test and write this mod.  In no specific order: Compuart, Gobalopper, Jeremy, Grudge, akabugeyes, ghostfreak, K_4_kelly, TLM, Bigguy.  If I missed anyone just let me know.  Thanks to all of you.';
