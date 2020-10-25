<?php

function b_smf_lastpost_show($options)
{
    global $xoopsModule;

    $block = [];

    if ($xoopsModule && 'smf' == $xoopsModule->dirname()) {
        return $block;
    }

    if (!defined('SMF')) {
        require_once dirname(__DIR__) . '/SSI.php';
    }

    $block['content'] = ssi_recentPosts($options[0], '', '');

    if (!isset($options[1])) {
        foreach ($block['content'] as $i => $bk) {
            $block['content'][$i]['preview'] = '';
        }
    }

    $block['blockart'] = $options[2];

    if (isset($options[3])) {
        $block['title'] = $options[3];
    }

    //print_r($block);

    return $block;
}

function b_smf_lastpost_edit($options)
{
    $form = '' . _SMF_BL_OPTION . '&nbsp;&nbsp;';

    $form .= '<br><input type="text" name="options[0]" size="3" maxlength="3" value="' . $options[0] . '">';

    $form .= '&nbsp;' . _SMF_BL_MAXLIST;

    $form .= '<br><input type="checkbox" name="options[1]"';

    if (!empty($options[1])) {
        $form .= ' checked';
    }

    $form .= '>';

    $form .= '&nbsp;' . _SMF_BL_THREADVIEW;

    $form .= '<br><input type="checkbox" name="options[3]"';

    if (!empty($options[3])) {
        $form .= ' checked';
    }

    $form .= '>';

    $form .= '&nbsp;' . _SMF_BL_THREADTITLE;

    $form .= "<br><select name='options[2]' size='1'>";

    $form .= "<option value='full'";

    if (isset($options[2]) && 'full' == $options[2]) {
        $form .= ' selected';
    }

    $form .= '> ' . _SMF_BL_OPT_FULL . ' </option>';

    $form .= "<option value='kompakt'";

    if (isset($options[2]) && 'kompakt' == $options[2]) {
        $form .= ' selected';
    }

    $form .= '> ' . _SMF_BL_OPT_KOMPAKT . ' </option>';

    $form .= "<option value='small'";

    if (isset($options[2]) && 'small' == $options[2]) {
        $form .= ' selected';
    }

    $form .= '> ' . _SMF_BL_OPT_SMALL . ' </option>';

    $form .= '</select>';

    $form .= '&nbsp;' . _SMF_BL_THREADART;

    return $form;
}

function b_smf_lasttopics_show($options)
{
    global $xoopsModule;

    $block = [];

    if ($xoopsModule && 'smf' == $xoopsModule->dirname()) {
        return $block;
    }

    if (!defined('SMF')) {
        require_once dirname(__DIR__) . '/SSI.php';
    }

    $block['content'] = ssi_recentTopics($options[0], '', '');

    if (!isset($options[1])) {
        foreach ($block['content'] as $i => $bk) {
            $block['content'][$i]['preview'] = '';
        }
    }

    $block['blockart'] = $options[2];

    if (isset($options[3])) {
        $block['title'] = $options[3];
    }

    //print_r($block);

    return $block;
}

function b_smf_lasttopics_edit($options)
{
    $form = '' . _SMF_BL_OPTION . '&nbsp;&nbsp;';

    $form .= '<br><input type="text" name="options[0]" size="3" maxlength="3" value="' . $options[0] . '">';

    $form .= '&nbsp;' . _SMF_BL_MAXLIST;

    $form .= '<br><input type="checkbox" name="options[1]"';

    if (!empty($options[1])) {
        $form .= ' checked';
    }

    $form .= '>';

    $form .= '&nbsp;' . _SMF_BL_THREADVIEW;

    $form .= '<br><input type="checkbox" name="options[3]"';

    if (!empty($options[3])) {
        $form .= ' checked';
    }

    $form .= '>';

    $form .= '&nbsp;' . _SMF_BL_THREADTITLE;

    $form .= "<br><select name='options[2]' size='1'>";

    $form .= "<option value='full'";

    if (isset($options[2]) && 'full' == $options[2]) {
        $form .= ' selected';
    }

    $form .= '> ' . _SMF_BL_OPT_FULL . ' </option>';

    $form .= "<option value='kompakt'";

    if (isset($options[2]) && 'kompakt' == $options[2]) {
        $form .= ' selected';
    }

    $form .= '> ' . _SMF_BL_OPT_KOMPAKT . ' </option>';

    $form .= "<option value='small'";

    if (isset($options[2]) && 'small' == $options[2]) {
        $form .= ' selected';
    }

    $form .= '> ' . _SMF_BL_OPT_SMALL . ' </option>';

    $form .= '</select>';

    $form .= '&nbsp;' . _SMF_BL_THREADART;

    return $form;
}

function b_smf_boardstats_show($options)
{
    global $xoopsModule;

    $block = [];

    if ($xoopsModule && 'smf' == $xoopsModule->dirname()) {
        return $block;
    }

    if (!defined('SMF')) {
        require_once dirname(__DIR__) . '/SSI.php';
    }

    $block['content'] = ssi_boardStats('');

    return $block;
}

function b_smf_boardnews_show($options)
{
    global $xoopsModule;

    $block = [];

    if ($xoopsModule && 'smf' == $xoopsModule->dirname()) {
        return $block;
    }

    if (!defined('SMF')) {
        require_once dirname(__DIR__) . '/SSI.php';
    }

    $block['content'] = ssi_news('');

    return $block;
}
