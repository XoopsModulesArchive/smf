<?php
// Version: 1.1; Settings

function template_options()
{
    global $context, $settings, $options, $scripturl, $txt;

    $context['theme_options'] = [
        [
            'id' => 'show_board_desc',
            'label' => $txt[732],
            'default' => true,
        ],
        [
            'id' => 'show_children',
            'label' => $txt['show_children'],
            'default' => true,
        ],
        [
            'id' => 'show_no_avatars',
            'label' => $txt['show_no_avatars'],
            'default' => true,
        ],
        [
            'id' => 'show_no_signatures',
            'label' => $txt['show_no_signatures'],
            'default' => true,
        ],
        [
            'id' => 'show_no_censored',
            'label' => $txt['show_no_censored'],
            'default' => true,
        ],
        [
            'id' => 'return_to_post',
            'label' => $txt['return_to_post'],
            'default' => true,
        ],
        [
            'id' => 'no_new_reply_warning',
            'label' => $txt['no_new_reply_warning'],
            'default' => true,
        ],
        [
            'id' => 'view_newest_first',
            'label' => $txt['recent_posts_at_top'],
            'default' => true,
        ],
        [
            'id' => 'view_newest_pm_first',
            'label' => $txt['recent_pms_at_top'],
            'default' => true,
        ],
        [
            'id' => 'popup_messages',
            'label' => $txt['popup_messages'],
            'default' => true,
        ],
        [
            'id' => 'copy_to_outbox',
            'label' => $txt['copy_to_outbox'],
            'default' => true,
        ],
        [
            'id' => 'auto_notify',
            'label' => $txt['auto_notify'],
            'default' => true,
        ],
        [
            'id' => 'calendar_start_day',
            'label' => $txt['calendar_start_day'],
            'options' => [
                0 => $txt['days'][0],
                1 => $txt['days'][1],
                6 => $txt['days'][6],
            ],
            'default' => true,
        ],
        [
            'id' => 'display_quick_reply',
            'label' => $txt['display_quick_reply'],
            'options' => [
                0 => $txt['display_quick_reply1'],
                1 => $txt['display_quick_reply2'],
                2 => $txt['display_quick_reply3'],
            ],
            'default' => true,
        ],
        [
            'id' => 'display_quick_mod',
            'label' => $txt['display_quick_mod'],
            'options' => [
                0 => $txt['display_quick_mod_none'],
                1 => $txt['display_quick_mod_check'],
                2 => $txt['display_quick_mod_image'],
            ],
            'default' => true,
        ],
    ];
}

function template_settings()
{
    global $context, $settings, $options, $scripturl, $txt;

    $context['theme_settings'] = [
        [
            'id' => 'header_logo_url',
            'label' => $txt['header_logo_url'],
            'description' => $txt['header_logo_url_desc'],
            'type' => 'text',
        ],
        [
            'id' => 'number_recent_posts',
            'label' => $txt['number_recent_posts'],
            'description' => $txt['number_recent_posts_desc'],
            'type' => 'number',
        ],
        [
            'id' => 'display_who_viewing',
            'label' => $txt['who_display_viewing'],
            'options' => [
                0 => $txt['who_display_viewing_off'],
                1 => $txt['who_display_viewing_numbers'],
                2 => $txt['who_display_viewing_names'],
            ],
        ],
        [
            'id' => 'smiley_sets_default',
            'label' => $txt['smileys_default_set_for_theme'],
            'options' => $context['smiley_sets'],
        ],
        [
            'id' => 'show_modify',
            'label' => $txt[383],
        ],
        [
            'id' => 'show_member_bar',
            'label' => $txt[510],
        ],
        [
            'id' => 'linktree_link',
            'label' => $txt[522],
        ],
        [
            'id' => 'show_profile_buttons',
            'label' => $txt[523],
        ],
        [
            'id' => 'show_mark_read',
            'label' => $txt[618],
        ],
        [
            'id' => 'linktree_inline',
            'label' => $txt['smf105'],
            'description' => $txt['smf106'],
        ],
        [
            'id' => 'show_sp1_info',
            'label' => $txt['smf200'],
        ],
        [
            'id' => 'allow_no_censored',
            'label' => $txt['allow_no_censored'],
        ],
        [
            'id' => 'show_bbc',
            'label' => $txt[740],
        ],
        [
            'id' => 'additional_options_collapsable',
            'label' => $txt['additional_options_collapsable'],
        ],
        [
            'id' => 'enable_news',
            'label' => $txt[379],
        ],
        [
            'id' => 'show_newsfader',
            'label' => $txt[387],
        ],
        [
            'id' => 'newsfader_time',
            'label' => $txt[739],
            'type' => 'number',
        ],
        [
            'id' => 'show_user_images',
            'label' => $txt[384],
        ],
        [
            'id' => 'show_blurb',
            'label' => $txt[385],
        ],
        [
            'id' => 'show_latest_member',
            'label' => $txt[382],
        ],
        [
            'id' => 'use_image_buttons',
            'label' => $txt[521],
        ],
        [
            'id' => 'show_gender',
            'label' => $txt[386],
        ],
        [
            'id' => 'hide_post_group',
            'label' => $txt['hide_post_group'],
            'description' => $txt['hide_post_group_desc'],
        ],
    ];
}
