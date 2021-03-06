<?php
/**********************************************************************************
 * convert.php                                                                     *
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

//$xoopsOption['nocommon'] = 1;
require_once '../../../mainfile.php';
$xoopsLogger->activated = false;
$time_start = time();

$GLOBALS['required_php_version'] = '4.1.0';
$GLOBALS['required_mysql_version'] = '3.23.28';

initialize_inputs();

show_header();

if (!empty($_GET['step']) && (1 == $_GET['step'] || 2 == $_GET['step'])) {
    echo '
			<div class="panel">
				<h2>Converting...</h2>';
}

if (function_exists('doStep' . $_GET['step'])) {
    call_user_func('doStep' . $_GET['step']);
}

if (!empty($_GET['step']) && (1 == $_GET['step'] || 2 == $_GET['step'])) {
    echo '
			</div>';
}

show_footer();

function initialize_inputs()
{
    global $sourcedir;

    // Clean up after unfriendly php.ini settings.

    @set_magic_quotes_runtime(0);

    error_reporting(E_ALL);

    ignore_user_abort(true);

    define('SMF', 1);

    ob_start();

    if ('user' == @ini_get('session.saveHandler')) {
        @ini_set('session.saveHandler', 'files');
    }

    @session_start();

    // Add slashes, as long as they aren't already being added.

    if (function_exists('get_magic_quotes_gpc') && 0 != @get_magic_quotes_gpc()) {
        foreach ($_POST as $k => $v) {
            $_POST[$k] = stripslashes($v);
        }
    }

    // This is really quite simple; if ?delete is on the URL, delete the converter...

    if (isset($_GET['delete'])) {
        @unlink(__FILE__);

        if (0 != preg_match('~_to_smf\.(php|sql)$~', $_SESSION['convert_script'])) {
            @unlink(__DIR__ . '/' . $_SESSION['convert_script']);
        }

        $_SESSION['convert_script'] = null;

        exit;
    }

    // The current step - starts at 0.

    $_GET['step'] = (int)@$_GET['step'];

    $_REQUEST['start'] = (int)@$_REQUEST['start'];

    // Check for the password...

    if (isset($_POST['db_pass'])) {
        $_SESSION['convert_db_pass'] = $_POST['db_pass'];
    } elseif (isset($_SESSION['convert_db_pass'])) {
        $_POST['db_pass'] = $_SESSION['convert_db_pass'];
    }

    if (isset($_SESSION['convert_paths']) && !isset($_POST['path_from']) && !isset($_POST['path_to'])) {
        [$_POST['path_from'], $_POST['path_to']] = $_SESSION['convert_paths'];
    } elseif (isset($_POST['path_from']) || isset($_POST['path_to'])) {
        if (isset($_POST['path_from'])) {
            $_POST['path_from'] = '/' == mb_substr($_POST['path_from'], -1) ? mb_substr($_POST['path_from'], 0, -1) : $_POST['path_from'];
        }

        if (isset($_POST['path_to'])) {
            $_POST['path_to'] = '/' == mb_substr($_POST['path_to'], -1) ? mb_substr($_POST['path_to'], 0, -1) : $_POST['path_to'];
        }

        $_SESSION['convert_paths'] = [@$_POST['path_from'], @$_POST['path_to']];
    }

    if (isset($_SESSION['convert_script']) && file_exists(__DIR__ . '/' . $_SESSION['convert_script']) && 0 != preg_match('~_to_smf\.(php|sql)$~', $_SESSION['convert_script'])) {
        if ('.php' == mb_substr($_SESSION['convert_script'], -4)) {
            preparse_php(__DIR__ . '/' . $_SESSION['convert_script']);
        } else {
            preparse_sql(__DIR__ . '/' . $_SESSION['convert_script']);
        }
    } else {
        unset($_SESSION['convert_script']);
    }
}

function preparse_sql($script_filename)
{
    global $convert_data;

    $fp = fopen($script_filename, 'rb');

    $data = fread($fp, 4096);

    fclose($fp);

    $convert_data['parameters'] = [];

    // This loads name, settings, table_test, from_prefix, defines, and globals.

    preg_match_all('~---\~ ([^:]+):\s*(.+?)\s*\n~', $data, $matches);

    for ($i = 0, $n = count($matches[1]); $i < $n; $i++) {
        // String value?

        if (in_array($matches[1][$i], ['name', 'table_test', 'from_prefix', 'version', 'database_type'], true)) {
            $convert_data[$matches[1][$i]] = stripslashes(mb_substr(trim($matches[2][$i]), 1, -1));
        } // No... so it must be an array.

        else {
            if (false === mb_strpos($matches[2][$i], '"')) {
                preg_match_all('~()([^,]+?)(,|$)~', trim($matches[2][$i]), $values);
            } else {
                preg_match_all('~(")?([^,]+?)\\1(,|$)~', trim($matches[2][$i]), $values);
            }

            if (!isset($convert_data[$matches[1][$i]])) {
                $convert_data[$matches[1][$i]] = [];
            }

            $convert_data[$matches[1][$i]] = array_merge($convert_data[$matches[1][$i]], $values[2]);
        }
    }

    if (empty($convert_data['defines'])) {
        $convert_data['defines'] = [];
    }

    if (empty($convert_data['globals'])) {
        $convert_data['globals'] = [];
    }

    if (empty($convert_data['settings'])) {
        $convert_data['settings'] = [];
    }

    if (empty($convert_data['variable'])) {
        $convert_data['variable'] = [];
    }

    if (!empty($convert_data['parameters'])) {
        foreach ($convert_data['parameters'] as $i => $param) {
            if (is_array($param)) {
                continue;
            }

            [$id, $label] = explode('=', $param);

            if (false !== mb_strpos($id, ' ')) {
                [$id, $type] = explode(' ', $id);
            } else {
                $type = 'text';
            }

            $convert_data['parameters'][$i] = [
                'id' => $id,
                'label' => $label,
                'type' => $type,
            ];

            $convert_data['globals'][] = $id;
        }
    }

    foreach ($convert_data['globals'] as $k => $v) {
        $v = trim($v);

        $convert_data['globals'][$k] = '$' == $v[0] ? mb_substr($v, 1) : $v;
    }

    if (isset($_POST['path_to']) && !empty($_GET['step'])) {
        loadSettings();
    }
}

function preparse_php($script_filename)
{
    global $convert_data;

    $preparsing = true;

    require $script_filename;

    if (empty($convert_data['parameters'])) {
        $convert_data['parameters'] = [];
    }

    if (empty($convert_data['defines'])) {
        $convert_data['defines'] = [];
    }

    if (empty($convert_data['globals'])) {
        $convert_data['globals'] = [];
    }

    if (empty($convert_data['settings'])) {
        $convert_data['settings'] = [];
    }

    if (empty($convert_data['variable'])) {
        $convert_data['variable'] = [];
    }

    foreach ($convert_data['globals'] as $k => $v) {
        $v = trim($v);

        $convert_data['globals'][$k] = '$' == $v[0] ? mb_substr($v, 1) : $v;
    }

    if (isset($_POST['path_to']) && !empty($_GET['step'])) {
        loadSettings();
    }
}

function loadSettings()
{
    global $convert_data, $from_prefix, $to_prefix, $convert_dbs;

    foreach ($convert_data['defines'] as $define) {
        $define = explode('=', $define);

        define($define[0], $define[1] ?? '1');
    }

    foreach ($convert_data['globals'] as $global) {
        global $$global;
    }

    // Cannot find Settings.php?

    if (!file_exists($_POST['path_to'] . '/Settings.php')) {
        show_header();

        return doStep0('This converter was unable to find SMF in the path you specified.<br><br>Please double check the path, and that it is already installed there.');
    }

    $found = empty($convert_data['settings']);

    foreach ($convert_data['settings'] as $file) {
        $found |= file_exists($_POST['path_from'] . $file);
    }

    /*
        Check if open_basedir is enabled.  If it's enabled and the converter file was not found then that means
        that the user hasn't moved the files to the public html dir.  With this enabled and the file not found, we can't go anywhere from here.
    */

    if ('' != @ini_get('open_basedir') && !$found) {
        show_header();

        return doStep0('The converter detected that your host has open_basedir enabled on this server.  Please ask your host to disable this setting or try moving the contents of your ' . $convert_data['name'] . ' to the public html folder of your site.');
    }

    if (!$found) {
        show_header();

        return doStep0('Unable to find the settings for ' . $convert_data['name'] . '.  Please double check the path and try again.');
    }

    // Any parameters to speak of?

    if (!empty($convert_data['parameters']) && !empty($_SESSION['convert_parameters'])) {
        foreach ($convert_data['parameters'] as $param) {
            if (isset($_POST[$param['id']])) {
                $_SESSION['convert_parameters'][$param['id']] = $_POST[$param['id']];
            }
        }

        // Should already be global'd.

        foreach ($_SESSION['convert_parameters'] as $k => $v) {
            $$k = $v;
        }
    } elseif (!empty($convert_data['parameters'])) {
        $_SESSION['convert_parameters'] = [];

        foreach ($convert_data['parameters'] as $param) {
            if (isset($_POST[$param['id']])) {
                $_SESSION['convert_parameters'][$param['id']] = $_POST[$param['id']];
            } else {
                $_SESSION['convert_parameters'][$param['id']] = null;
            }
        }

        foreach ($_SESSION['convert_parameters'] as $k => $v) {
            $$k = $v;
        }
    }

    // Everything should be alright now... no cross server includes, we hope...

    require_once $_POST['path_to'] . '/Settings.php';

    $GLOBALS['boardurl'] = $boardurl;

    if ($_SESSION['convert_db_pass'] != $db_passwd) {
        show_header();

        return doStep0('The database password you entered was incorrect.  Please make sure you are using the right password (for the SMF user!) and try it again.  If in doubt, use the password from Settings.php in the SMF installation.');
    }

    if (isset($_SESSION['convert_parameters']['database_type']) && !isset($convert_data['database_type'])) {
        $convert_data['database_type'] = $_SESSION['convert_parameters']['database_type'];
    }

    if (isset($convert_data['database_type']) && (function_exists($convert_data['database_type'] . '_query') || function_exists($convert_data['database_type'] . '_exec') || ('ado' == $convert_data['database_type'] && class_exists('com')))) {
        $convert_dbs = $convert_data['database_type'];

        if (isset($convert_data['connect_string'])) {
            $connect_string = eval('return \'' . $convert_data['connect_string'] . '\';');
        } elseif (isset($_SESSION['convert_parameters']['connect_string'])) {
            $connect_string = $_SESSION['convert_parameters']['connect_string'];
        }

        if ('odbc' == $convert_dbs) {
            $GLOBALS['odbc_connection'] = odbc_connect($connect_string, '', '');
        } elseif ('ado' == $convert_dbs) {
            $GLOBALS['ado_connection'] = new COM('ADODB.Connection');

            $GLOBALS['ado_connection']->Open($connect_string);

            register_shutdown_function(create_function('', '$GLOBALS[\'ado_connection\']->Close();'));
        }
    } elseif (isset($convert_data['database_type'])) {
        show_header();

        return doStep0('PHP doesn\'t support the database type this converter was written for, \'' . $convert_data['database_type'] . '\'.');
    } else {
        $convert_dbs = 'mysql';
    }

    // Persist?

    if (empty($db_persist)) {
        mysql_connect($db_server, $db_user, $db_passwd);
    } else {
        mysql_pconnect($db_server, $db_user, $db_passwd);
    }

    if (false === mb_strpos($db_prefix, '.')) {
        $to_prefix = is_numeric(mb_substr($db_prefix, 0, 1)) ? $db_name . '.' . $db_prefix : '`' . $db_name . '`.' . $db_prefix;
    } else {
        $to_prefix = $db_prefix;
    }

    foreach ($convert_data['variable'] as $eval_me) {
        eval($eval_me);
    }

    foreach ($convert_data['settings'] as $file) {
        if (file_exists($_POST['path_from'] . $file) && empty($convert_data['flatfile'])) {
            require_once $_POST['path_from'] . $file;
        }
    }

    if (isset($convert_data['from_prefix'])) {
        $from_prefix = eval('return \'' . $convert_data['from_prefix'] . '\';');
    }

    if (0 != preg_match('~^`[^`]+`.\d~', $from_prefix)) {
        $from_prefix = strtr($from_prefix, ['`' => '']);
    }

    if (0 == $_REQUEST['start'] && empty($_GET['substep']) && empty($_GET['cstep']) && (1 == $_GET['step'] || 2 == $_GET['step']) && isset($convert_data['table_test'])) {
        $result = convert_query(
            '
			SELECT COUNT(*)
			FROM ' . eval('return \'' . $convert_data['table_test'] . '\';'),
            true
        );

        if (false === $result) {
            show_header();

            doStep0(
                'Sorry, the database connection information used in the specified installation of SMF cannot access the installation of '
                . $convert_data['name']
                . '.  This may either mean that the installation doesn\'t exist, or that the MySQL account used does not have permissions to access it.<br><br>The error MySQL gave was: '
                . $GLOBALS['xoopsDB']->error()
            );
        }

        convert_free_result($result);
    }
}

function find_convert_scripts()
{
    if (isset($_REQUEST['convert_script'])) {
        if ('' != $_REQUEST['convert_script'] && 0 != preg_match('~^[a-z0-9\-_\.]*_to_smf\.(sql|php)$~i', $_REQUEST['convert_script'])) {
            $_SESSION['convert_script'] = preg_replace('~[\.]+~', '.', $_REQUEST['convert_script']);

            if ('.php' == mb_substr($_SESSION['convert_script'], -4)) {
                preparse_php(__DIR__ . '/' . $_SESSION['convert_script']);
            } else {
                preparse_sql(__DIR__ . '/' . $_SESSION['convert_script']);
            }
        } else {
            $_SESSION['convert_script'] = null;
        }
    }

    $preparsing = true;

    $dir = dir(__DIR__);

    $scripts = [];

    while ($entry = $dir->read()) {
        if ('_to_smf.sql' != mb_substr($entry, -11) && '_to_smf.php' != mb_substr($entry, -11)) {
            continue;
        }

        $fp = fopen(__DIR__ . '/' . $entry, 'rb');

        $data = fread($fp, 4096);

        fclose($fp);

        if ('_to_smf.sql' == mb_substr($entry, -11)) {
            if (0 != preg_match('~---\~ name:\s*"(.+?)"~', $data, $match)) {
                $scripts[] = ['path' => $entry, 'name' => $match[1]];
            }
        } elseif ('_to_smf.php' == mb_substr($entry, -11)) {
            if (0 != preg_match('~\$convert_data =~', $data)) {
                require __DIR__ . '/' . $entry;

                $scripts[] = ['path' => $entry, 'name' => $convert_data['name']];
            }
        }
    }

    $dir->close();

    if (isset($_SESSION['convert_script'])) {
        if (count($scripts) > 1) {
            $GLOBALS['possible_scripts'] = $scripts;
        }

        return false;
    }

    if (1 == count($scripts)) {
        $_SESSION['convert_script'] = basename($scripts[0]['path']);

        if ('.php' == mb_substr($_SESSION['convert_script'], -4)) {
            preparse_php(__DIR__ . '/' . $_SESSION['convert_script']);
        } else {
            preparse_sql(__DIR__ . '/' . $_SESSION['convert_script']);
        }

        return false;
    }

    echo '
		<div class="panel">
			<h2>Which software are you using?</h2>';

    if (!empty($scripts)) {
        echo '
			<h3>The converter found multiple conversion data files.  Please choose the one you wish to use.</h3>

			<ul>';

        foreach ($scripts as $script) {
            echo '
				<li><a href="', $_SERVER['PHP_SELF'], '?convert_script=', $script['path'], '">', $script['name'], '</a> <em>(', $script['path'], ')</em></li>';
        }

        echo '
			</ul>

			<h2>It\'s not here!</h2>
			<h3>If the software you\'re looking for doesn\'t appear above, please check to see if it is available for download at <a href="http://www.simplemachines.org/">www.simplemachines.org</a>.  If it isn\'t, we may be able to write one for you - just ask us!</h3>

			If you\'re having any other problems with this converter, don\'t hesitate to look for help on our <a href="http://www.simplemachines.org/community/index.php">forum</a>.';
    } else {
        echo '
			<h3>The converter did not find any conversion data files.  Please check to see if the one you want is available for download at <a href="http://www.simplemachines.org/">www.simplemachines.org</a>.  If it isn\'t, we may be able to write one for you - just ask us!</h3>

			After you download it, simply upload it into the same folder as <b>this convert.php file</b>.  If you\'re having any other problems with this converter, don\'t hesitate to look for help on our <a href="http://www.simplemachines.org/community/index.php">forum</a>.<br>
			<br>
			<a href="', $_SERVER['PHP_SELF'], '?convert_script=">Try again</a>';
    }

    echo '
		</div>';

    return true;
}

function doStep0($error_message = null)
{
    global $convert_data;

    if (find_convert_scripts()) {
        return true;
    }

    // If these aren't set (from an error..) default to the current directory.

    if (!isset($_POST['path_from'])) {
        $_POST['path_from'] = __DIR__;
    }

    if (!isset($_POST['path_to'])) {
        $_POST['path_to'] = __DIR__;
    }

    $test_from = empty($convert_data['settings']);

    foreach ($convert_data['settings'] as $s) {
        $test_from |= file_exists($_POST['path_from'] . $s);
    }

    $test_to = file_exists($_POST['path_to'] . '/Settings.php');

    // Was an error message specified?

    if (null !== $error_message) {
        echo '
			<div class="error_message">
				<div style="color: red;">', $error_message, '</div>
			</div>
			<br>';
    }

    echo '
			<div class="panel">
				<form action="', $_SERVER['PHP_SELF'], '?step=1', isset($_REQUEST['debug']) ? '&amp;debug=' . $_REQUEST['debug'] : '', '" method="post">
					<h2>Before you continue</h2>
					<div style="margin-bottom: 2ex;">This converter assumes you have already installed SMF and that your installation is working properly.  It copies posts and data from your &quot;source&quot; installation of ', $convert_data['name'], ' into SMF, so it won\'t work without an installation of SMF.  All or some of the data in your installation of SMF will be <b>overwritten</b>.</div>';

    if (empty($convert_data['flatfile'])) {
        echo '
					<div style="margin-bottom: 2ex;">If the two softwares are installed in separate directories, the MySQL account SMF was installed using will need access to the other database.  Either way, both must be installed on the same MySQL server.</div>';
    }

    echo '

					<h2>Where are they?</h2>
					<h3>The converter should only need to know where the two installations are, after which it should be able to handle everything for itself.</h3>

					<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
						<tr>
							<td width="20%" valign="top" class="textbox"><label for="path_to">Path to SMF:</label></td>
							<td style="padding-bottom: 1ex;">
								<input type="text" name="path_to" id="path_to" value="', $_POST['path_to'], '" size="60">
								<div style="font-style: italic; font-size: smaller;">', $test_to ? 'This may be the right path.' : 'You will need to change the value in this box.', '</div>
							</td>';

    if (!empty($convert_data['settings'])) {
        echo '
						</tr><tr>
							<td valign="top" class="textbox"><label for="path_from">Path to ', $convert_data['name'], ':</label></td>
							<td style="padding-bottom: 1ex;">
								<input type="text" name="path_from" id="path_from" value="', $_POST['path_from'], '" size="60">
								<div style="font-style: italic; font-size: smaller;">', $test_from ? 'This may be the right path.' : 'You will need to change the value in this box.', '</div>
							</td>';
    }

    if (!empty($convert_data['parameters'])) {
        foreach ($convert_data['parameters'] as $param) {
            echo '
						</tr><tr>';

            if ('text' == $param['type']) {
                echo '
							<td valign="top" class="textbox"><label for="', $param['id'], '">', $param['label'], ':</label></td>
							<td style="padding-bottom: 1ex;">
								<input type="text" name="', $param['id'], '" id="', $param['id'], '" value="" size="60">';
            } elseif ('checked' == $param['type'] || 'checkbox' == $param['type']) {
                echo '
							<td valign="top" class="textbox"></td>
							<td style="padding-bottom: 1ex;">
								<input type="hidden" name="', $param['id'], '" value="0">
								<label for="', $param['id'], '"><input type="checkbox" name="', $param['id'], '" id="', $param['id'], '" value="1"', 'checked' == $param['type'] ? ' checked' : '', '> ', $param['label'], '</label>';
            }

            echo '
							</td>';
        }
    }

    echo '
						</tr><tr>
							<td valign="top" class="textbox" style="padding-top: 2ex;"><label for="db_pass">SMF database password:</label></td>
							<td valign="top" style="padding-top: 2ex; padding-bottom: 1ex;">
								<input type="password" name="db_pass" size="30" class="text">
								<div style="font-style: italic; font-size: smaller;">The MySQL password (for verification only.)</div>
							</td>
						</tr>
					</table>

					<div align="right" style="margin: 1ex; margin-top: 0;"><input name="b" type="submit" value="Continue" class="submit"></div>
				</form>
			</div>';

    if (!empty($GLOBALS['possible_scripts'])) {
        echo '
			<div class="panel">
				<h2>Not this software?</h2>
				<h3>If this is the wrong software, you can go back and <a href="', $_SERVER['PHP_SELF'], '?convert_script=">pick a different data file</a>.</h3>
			</div>';
    }

    if (null !== $error_message) {
        show_footer();

        exit;
    }
}

function doStep1()
{
    global $from_prefix, $to_prefix, $convert_data;

    if ('.php' == mb_substr($_SESSION['convert_script'], -4)) {
        return run_php_converter();
    }

    foreach ($convert_data['globals'] as $global) {
        global $$global;
    }

    $_GET['substep'] = (int)@$_GET['substep'];

    $lines = file(__DIR__ . '/' . $_SESSION['convert_script']);

    $current_type = 'sql';

    $current_data = '';

    $substep = 0;

    $last_step = '';

    $special_table = null;

    $special_code = null;

    foreach ($lines as $line_number => $line) {
        $do_current = $substep >= $_GET['substep'];

        // Get rid of any comments in the beginning of the line...

        if ('/*' === mb_substr(trim($line), 0, 2)) {
            $line = preg_replace('~/\*.+?\*/~', '', $line);
        }

        if ('' === trim($line)) {
            continue;
        }

        if ('---' === trim(mb_substr($line, 0, 3))) {
            $type = mb_substr($line, 3, 1);

            if ('' != trim($current_data) && '}' !== $type) {
                echo '
			Error in convert script - line ', $line_number, '!<br>';
            }

            if (' ' == $type) {
                if ($do_current && 0 != $_GET['substep']) {
                    echo ' Successful.<br>';

                    flush();
                }

                $last_step = htmlspecialchars(rtrim(mb_substr($line, 4)), ENT_QUOTES | ENT_HTML5);

                if ($do_current) {
                    echo $last_step, empty($_SESSION['convert_debug']) ? '' : '<br>';

                    pastTime($substep);
                }
            } elseif ('#' == $type) {
                if (!empty($_SESSION['convert_debug']) && $do_current) {
                    if ('---#' == trim($line)) {
                        echo ' done.<br>';
                    } else {
                        echo '&nbsp;&nbsp;&nbsp;', htmlspecialchars(rtrim(mb_substr($line, 4)), ENT_QUOTES | ENT_HTML5);
                    }
                }

                if ($substep < $_GET['substep'] && $substep + 1 >= $_GET['substep']) {
                    echo $last_step, empty($_SESSION['convert_debug']) ? '' : '<br>';
                }

                // Small step!

                pastTime(++$substep);
            } elseif ('{' == $type) {
                $current_type = 'code';
            } elseif ('}' == $type) {
                $current_type = 'sql';

                if (!$do_current) {
                    $current_data = '';

                    continue;
                }

                if (null !== $special_table) {
                    $special_code = $current_data;
                } else {
                    if (false === eval($current_data)) {
                        echo '
			<b>Error in convert script ', $_SESSION['convert_script'], ' on line ', $line_number, '!</b><br>';
                    }
                }

                // Done with code!

                $current_data = '';
            } elseif ('*' == $type) {
                if ($substep < $_GET['substep'] && $substep + 1 >= $_GET['substep']) {
                    echo $last_step, empty($_SESSION['convert_debug']) ? ' ' : '<br>';
                }

                if (null === $special_table) {
                    $special_table = strtr(trim(mb_substr($line, 4)), ['{$to_prefix}' => $to_prefix]);

                    if (0 != preg_match('~^([^ ()]+?)( \(update .+?\))? (\d+)$~', trim($special_table), $match)) {
                        $special_table = $match[1];

                        $special_update = '' != $match[2] ? mb_substr($match[2], 9, -1) : '';

                        $special_limit = empty($match[3]) ? 500 : (int)$match[3];
                    } elseif (0 != preg_match('~^([^ ()]+?) \(update (.+?)\)$~', trim($special_table), $match)) {
                        $special_table = $match[1];

                        $special_update = $match[2];

                        $special_limit = 200;
                    } else {
                        $special_update = false;

                        $special_limit = 500;
                    }
                } else {
                    $special_table = null;

                    $special_code = null;
                }

                // Increase the substep slightly...

                pastTime(++$substep);
            }

            continue;
        }

        $current_data .= $line;

        if (';' === mb_substr(rtrim($current_data), -1) && 'sql' === $current_type) {
            if (!$do_current) {
                $current_data = '';

                continue;
            }

            $current_data = strtr(mb_substr(rtrim($current_data), 0, -1), ['{$from_prefix}' => $from_prefix, '{$to_prefix}' => $to_prefix]);

            if (false !== mb_strpos($current_data, '{$')) {
                $current_data = eval('return \'' . addcslashes($current_data, '\\"') . '\';');
            }

            if (isset($convert_table) && null !== $convert_table && false !== mb_strpos($current_data, '%d')) {
                preg_match('~FROM [(]?([^\s,]+)~i', $convert_data, $match);

                if (!empty($match)) {
                    $result = convert_query(
                        "
						SELECT COUNT(*)
						FROM $match[1]"
                    );

                    [$special_max] = convert_fetch_row($result);

                    $GLOBALS['xoopsDB']->freeRecordSet($result);
                } else {
                    $special_max = 0;
                }
            } else {
                $special_max = 0;
            }

            if (null === $special_table) {
                convert_query($current_data);
            } elseif (false !== $special_update) {
                while (true) {
                    pastTime($substep);

                    if (false !== mb_strpos($current_data, '%d')) {
                        $special_result = convert_query(sprintf($current_data, $_REQUEST['start'], $_REQUEST['start'] + $special_limit - 1) . "\n" . 'LIMIT ' . $special_limit);
                    } else {
                        $special_result = convert_query($current_data . "\n" . 'LIMIT ' . $_REQUEST['start'] . ', ' . $special_limit);
                    }

                    while (false !== ($row = convert_fetch_assoc($special_result))) {
                        if (null !== $special_code) {
                            eval($special_code);
                        }

                        if (empty($no_add) && count($row) >= 2) {
                            $setString = [];

                            foreach ($row as $k => $v) {
                                if ($k != $special_update) {
                                    $setString[] = "$k = '" . addslashes($v) . "'";
                                }
                            }

                            convert_query(
                                '
								UPDATE ' . $special_table . '
								SET ' . implode(', ', $setString) . "
								WHERE $special_update = '" . $row[$special_update] . "'
								LIMIT 1"
                            );
                        } else {
                            $no_add = false;
                        }
                    }

                    $_REQUEST['start'] += $special_limit;

                    if (empty($special_max) && convert_num_rows($special_result) < $special_limit) {
                        break;
                    } elseif (!empty($special_max) && 0 == convert_num_rows($special_result) && $_REQUEST['start'] > $special_max) {
                        break;
                    }

                    convert_free_result($special_result);
                }
            } else {
                // Are we doing attachments?  They're going to want a few things...

                if ($special_table == $to_prefix . 'attachments') {
                    if (!isset($ID_ATTACH, $attachmentUploadDir)) {
                        $result = convert_query(
                            "
							SELECT MAX(ID_ATTACH) + 1
							FROM {$to_prefix}attachments"
                        );

                        [$ID_ATTACH] = $GLOBALS['xoopsDB']->fetchRow($result);

                        $GLOBALS['xoopsDB']->freeRecordSet($result);

                        $result = convert_query(
                            "
							SELECT value
							FROM {$to_prefix}settings
							WHERE variable = 'attachmentUploadDir'
							LIMIT 1"
                        );

                        [$attachmentUploadDir] = $GLOBALS['xoopsDB']->fetchRow($result);

                        $GLOBALS['xoopsDB']->freeRecordSet($result);

                        if (empty($ID_ATTACH)) {
                            $ID_ATTACH = 1;
                        }
                    }
                }

                while (true) {
                    pastTime($substep);

                    if (false !== mb_strpos($current_data, '%d')) {
                        $special_result = convert_query(sprintf($current_data, $_REQUEST['start'], $_REQUEST['start'] + $special_limit - 1) . "\n" . 'LIMIT ' . $special_limit);
                    } else {
                        $special_result = convert_query($current_data . "\n" . 'LIMIT ' . $_REQUEST['start'] . ', ' . $special_limit);
                    }

                    $rows = [];

                    $keys = [];

                    while (false !== ($row = convert_fetch_assoc($special_result))) {
                        if (null !== $special_code) {
                            eval($special_code);
                        }

                        // Here we have various bits of custom code for some known problems global to all converters.

                        if ($special_table == $to_prefix . 'members') {
                            // Let's ensure there are no illegal characters.

                            $row['memberName'] = preg_replace('/[<>&"\'=\\\]/is', '', $row['memberName']);

                            $row['realName'] = trim($row['realName'], " \t\n\r\x0B\0\xA0");

                            if (false !== mb_strpos($row['realName'], '<') || false !== mb_strpos($row['realName'], '>') || false !== mb_strpos($row['realName'], '& ')) {
                                $row['realName'] = htmlspecialchars($row['realName'], ENT_QUOTES);
                            } else {
                                $row['realName'] = strtr($row['realName'], ['\'' => '&#039;']);
                            }
                        }

                        if (empty($no_add)) {
                            $rows[] = "'" . implode("', '", addslashes_recursive($row)) . "'";
                        } else {
                            $no_add = false;
                        }

                        if (empty($keys)) {
                            $keys = array_keys($row);
                        }
                    }

                    if (isset($ignore) && true === $ignore) {
                        $ignore = 'IGNORE';
                    } else {
                        $ignore = '';
                    }

                    if (!empty($rows)) {
                        convert_query(
                            "
							INSERT $ignore INTO $special_table
								(" . implode(', ', $keys) . ')
							VALUES (' . implode(
                                '),
								(',
                                $rows
                            ) . ')'
                        );
                    }

                    $_REQUEST['start'] += $special_limit;

                    if (empty($special_max) && convert_num_rows($special_result) < $special_limit) {
                        break;
                    } elseif (!empty($special_max) && 0 == convert_num_rows($special_result) && $_REQUEST['start'] > $special_max) {
                        break;
                    }

                    convert_free_result($special_result);
                }
            }

            $_REQUEST['start'] = 0;

            $special_code = null;

            $current_data = '';
        }
    }

    echo ' Successful.<br>';

    flush();

    $_GET['substep'] = 0;

    $_REQUEST['start'] = 0;

    return doStep2();
}

function run_php_converter()
{
    global $from_prefix, $to_prefix, $convert_data;

    foreach ($convert_data['globals'] as $global) {
        global $$global;
    }

    $_GET['substep'] = (int)@$_GET['substep'];

    $_GET['cstep'] = (int)@$_GET['cstep'];

    require __DIR__ . '/' . $_SESSION['convert_script'];

    if (function_exists('load_converter_settings')) {
        load_converter_settings();
    }

    for ($_GET['cstep'] = max(1, $_GET['cstep']); function_exists('convertStep' . $_GET['cstep']); $_GET['cstep']++) {
        call_user_func('convertStep' . $_GET['cstep']);

        $_GET['substep'] = 0;

        pastTime(0);

        echo ' Successful.<br>';

        flush();
    }

    $_GET['substep'] = 0;

    $_REQUEST['start'] = 0;

    return doStep2();
}

function doStep2()
{
    global $convert_data, $from_prefix, $to_prefix, $modSettings;

    $_GET['step'] = '2';

    echo 'Recalculating forum statistics... ';

    if ($_GET['substep'] <= 0) {
        // Get all members with wrong number of personal messages.

        $request = convert_query(
            "
			SELECT mem.ID_MEMBER, COUNT(pmr.ID_PM) AS realNum, mem.instantMessages
			FROM {$to_prefix}members AS mem
				LEFT JOIN {$to_prefix}pm_recipients AS pmr ON (mem.ID_MEMBER = pmr.ID_MEMBER AND pmr.deleted = 0)
			GROUP BY mem.ID_MEMBER
			HAVING realNum != instantMessages"
        );

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            convert_query(
                "
				UPDATE {$to_prefix}members
				SET instantMessages = $row[realNum]
				WHERE ID_MEMBER = $row[ID_MEMBER]
				LIMIT 1"
            );

            pastTime(0);
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        $request = convert_query(
            "
			SELECT mem.ID_MEMBER, COUNT(pmr.ID_PM) AS realNum, mem.unreadMessages
			FROM {$to_prefix}members AS mem
				LEFT JOIN {$to_prefix}pm_recipients AS pmr ON (mem.ID_MEMBER = pmr.ID_MEMBER AND pmr.deleted = 0 AND pmr.is_read = 0)
			GROUP BY mem.ID_MEMBER
			HAVING realNum != unreadMessages"
        );

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            convert_query(
                "
				UPDATE {$to_prefix}members
				SET unreadMessages = $row[realNum]
				WHERE ID_MEMBER = $row[ID_MEMBER]
				LIMIT 1"
            );

            pastTime(0);
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        pastTime(1);
    }

    if ($_GET['substep'] <= 1) {
        $request = convert_query(
            "
			SELECT ID_BOARD, MAX(ID_MSG) AS ID_LAST_MSG, MAX(modifiedTime) AS lastEdited
			FROM {$to_prefix}messages
			GROUP BY ID_BOARD"
        );

        $modifyData = [];

        $modifyMsg = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            convert_query(
                "
				UPDATE {$to_prefix}boards
				SET ID_LAST_MSG = $row[ID_LAST_MSG], ID_MSG_UPDATED = $row[ID_LAST_MSG]
				WHERE ID_BOARD = $row[ID_BOARD]
				LIMIT 1"
            );

            $modifyData[$row['ID_BOARD']] = [
                'last_msg' => $row['ID_LAST_MSG'],
                'lastEdited' => $row['lastEdited'],
            ];

            $modifyMsg[] = $row['ID_LAST_MSG'];
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        // Are there any boards where the updated message is not the last?

        if (!empty($modifyMsg)) {
            $request = convert_query(
                "
				SELECT ID_BOARD, ID_MSG, modifiedTime, posterTime
				FROM {$to_prefix}messages
				WHERE ID_MSG IN (" . implode(',', $modifyMsg) . ')'
            );

            while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
                // Have we got a message modified before this was posted?

                if (max($row['modifiedTime'], $row['posterTime']) < $modifyData[$row['ID_BOARD']]['lastEdited']) {
                    // Work out the ID of the message (This seems long but it won't happen much.

                    $request2 = convert_query(
                        "
						SELECT ID_MSG
						FROM {$to_prefix}messages
						WHERE modifiedTime = " . $modifyData[$row['ID_BOARD']]['lastEdited'] . '
						LIMIT 1'
                    );

                    if (0 != $GLOBALS['xoopsDB']->getRowsNum($request2)) {
                        [$ID_MSG] = $GLOBALS['xoopsDB']->fetchRow($request2);

                        convert_query(
                            "
							UPDATE {$to_prefix}boards
							SET ID_MSG_UPDATED = $ID_MSG
							WHERE ID_BOARD = $row[ID_BOARD]
							LIMIT 1"
                        );
                    }

                    $GLOBALS['xoopsDB']->freeRecordSet($request2);
                }
            }

            $GLOBALS['xoopsDB']->freeRecordSet($request);
        }

        pastTime(2);
    }

    if ($_GET['substep'] <= 2) {
        $request = convert_query(
            "
			SELECT ID_GROUP
			FROM {$to_prefix}membergroups
			WHERE minPosts = -1"
        );

        $all_groups = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            $all_groups[] = $row['ID_GROUP'];
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        $request = convert_query(
            "
			SELECT ID_BOARD, memberGroups
			FROM {$to_prefix}boards
			WHERE FIND_IN_SET(0, memberGroups)"
        );

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            convert_query(
                "
				UPDATE {$to_prefix}boards
				SET memberGroups = '" . implode(',', array_unique(array_merge($all_groups, explode(',', $row['memberGroups'])))) . "'
				WHERE ID_BOARD = $row[ID_BOARD]
				LIMIT 1"
            );
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        pastTime(3);
    }

    if ($_GET['substep'] <= 3) {
        // Get the number of messages...

        $result = convert_query(
            "
			SELECT COUNT(*) AS totalMessages, MAX(ID_MSG) AS maxMsgID
			FROM {$to_prefix}messages"
        );

        $row = $GLOBALS['xoopsDB']->fetchArray($result);

        $GLOBALS['xoopsDB']->freeRecordSet($result);

        // Update the latest member.  (highest ID_MEMBER)

        $result = convert_query(
            "
			SELECT ID_MEMBER AS latestMember, realName AS latestRealName
			FROM {$to_prefix}members
			ORDER BY ID_MEMBER DESC
			LIMIT 1"
        );

        if ($GLOBALS['xoopsDB']->getRowsNum($result)) {
            $row += $GLOBALS['xoopsDB']->fetchArray($result);
        }

        $GLOBALS['xoopsDB']->freeRecordSet($result);

        // Update the member count.

        $result = convert_query(
            "
			SELECT COUNT(*) AS totalMembers
			FROM {$to_prefix}members"
        );

        $row += $GLOBALS['xoopsDB']->fetchArray($result);

        $GLOBALS['xoopsDB']->freeRecordSet($result);

        // Get the number of topics.

        $result = convert_query(
            "
			SELECT COUNT(*) AS totalTopics
			FROM {$to_prefix}topics"
        );

        $row += $GLOBALS['xoopsDB']->fetchArray($result);

        $GLOBALS['xoopsDB']->freeRecordSet($result);

        convert_query(
            "
			REPLACE INTO {$to_prefix}settings
				(variable, value)
			VALUES ('latestMember', '$row[latestMember]'),
				('latestRealName', '$row[latestRealName]'),
				('totalMembers', '$row[totalMembers]'),
				('totalMessages', '$row[totalMessages]'),
				('maxMsgID', '$row[maxMsgID]'),
				('totalTopics', '$row[totalTopics]'),
				('disableHashTime', " . (time() + 7776000) . ')'
        );

        $request = convert_query(
            "
			SELECT ID_MSG,posterIP FROM {$to_prefix}messages"
        );

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            convert_query(
                "
				  UPDATE {$to_prefix}messages SET posterIP =
					'" . long2ip($row['posterIP']) . "'
					WHERE ID_MSG=" . $row['ID_MSG']
            );
        }

        pastTime(4);
    }

    if ($_GET['substep'] <= 4) {
        $request = convert_query(
            "
			SELECT uid
			FROM {$to_prefix}members"
        );

        $tusers = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchRow($request))) {
            $tusers[] = $row[0];
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        foreach ($tusers as $tuser) {
            $request = convert_query(
                "
			  SELECT count(*)
			  FROM {$to_prefix}messages WHERE ID_MEMBER=$tuser"
            );

            [$tcount] = $GLOBALS['xoopsDB']->fetchRow($request);

            $tcount = (int)$tcount;

            $request = convert_query("UPDATE {$to_prefix}members SET posts=$tcount WHERE uid = $tuser");
        }

        $request = convert_query(
            "
			SELECT ID_GROUP, minPosts
			FROM {$to_prefix}membergroups
			WHERE minPosts != -1
			ORDER BY minPosts DESC"
        );

        $post_groups = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            $post_groups[$row['minPosts']] = $row['ID_GROUP'];
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        $request = convert_query(
            "
			SELECT ID_MEMBER, posts
			FROM {$to_prefix}members"
        );

        $mg_updates = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            $group = 4;

            foreach ($post_groups as $min_posts => $group_id) {
                if ($row['posts'] >= $min_posts) {
                    $group = $group_id;

                    break;
                }
            }

            $mg_updates[$group][] = $row['ID_MEMBER'];
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        foreach ($mg_updates as $group_to => $update_members) {
            convert_query(
                "
				UPDATE {$to_prefix}members
				SET ID_POST_GROUP = $group_to
				WHERE ID_MEMBER IN (" . implode(', ', $update_members) . ')
				LIMIT ' . count($update_members)
            );
        }

        // This isn't completely related, but should be rather quick.

        convert_query(
            "
			UPDATE {$to_prefix}members
			SET ICQ = ''
			WHERE ICQ = '0'"
        );

        pastTime(5);
    }

    if ($_GET['substep'] <= 5) {
        // Needs to be done separately for each board.

        $result_boards = convert_query(
            "
			SELECT ID_BOARD
			FROM {$to_prefix}boards"
        );

        $boards = [];

        while (false !== ($row_boards = $GLOBALS['xoopsDB']->fetchArray($result_boards))) {
            $boards[] = $row_boards['ID_BOARD'];
        }

        $GLOBALS['xoopsDB']->freeRecordSet($result_boards);

        foreach ($boards as $ID_BOARD) {
            // Get the number of topics, and iterate through them.

            $result_topics = convert_query(
                "
				SELECT COUNT(*)
				FROM {$to_prefix}topics
				WHERE ID_BOARD = $ID_BOARD"
            );

            [$num_topics] = $GLOBALS['xoopsDB']->fetchRow($result_topics);

            $GLOBALS['xoopsDB']->freeRecordSet($result_topics);

            // Find how many messages are in the board.

            $result_posts = convert_query(
                "
				SELECT COUNT(*)
				FROM {$to_prefix}messages
				WHERE ID_BOARD = $ID_BOARD"
            );

            [$num_posts] = $GLOBALS['xoopsDB']->fetchRow($result_posts);

            $GLOBALS['xoopsDB']->freeRecordSet($result_posts);

            // Fix the board's totals.

            convert_query(
                "
				UPDATE {$to_prefix}boards
				SET numTopics = $num_topics, numPosts = $num_posts
				WHERE ID_BOARD = $ID_BOARD
				LIMIT 1"
            );
        }

        pastTime(6);
    }

    if ($_GET['substep'] <= 6) {
        $request = convert_query(
            "
			SELECT COUNT(*)
			FROM {$to_prefix}topics"
        );

        [$topics] = $GLOBALS['xoopsDB']->fetchRow($request);

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        while ($_REQUEST['start'] < $topics) {
            $request = convert_query(
                "
				SELECT ID_TOPIC, (COUNT(*) - 1) AS numReplies
				FROM {$to_prefix}messages
				WHERE ID_TOPIC > $_REQUEST[start]
					AND ID_TOPIC <= $_REQUEST[start] + 100
				GROUP BY ID_TOPIC
				LIMIT 100"
            );

            while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
                convert_query(
                    "
					UPDATE {$to_prefix}topics
					SET numReplies = $row[numReplies]
					WHERE ID_TOPIC = $row[ID_TOPIC]
					LIMIT 1"
                );
            }

            $GLOBALS['xoopsDB']->freeRecordSet($request);

            $_REQUEST['start'] += 100;

            pastTime(6);
        }

        $_REQUEST['start'] = 0;

        pastTime(7);
    }

    // Fix ID_CAT, ID_PARENT, and childLevel.

    if ($_GET['substep'] <= 7) {
        // First, let's get an array of boards and parents.

        $request = convert_query(
            "
			SELECT ID_BOARD, ID_PARENT, ID_CAT
			FROM {$to_prefix}boards"
        );

        $child_map = [];

        $cat_map = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            $child_map[$row['ID_PARENT']][] = $row['ID_BOARD'];

            $cat_map[$row['ID_BOARD']] = $row['ID_CAT'];
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        // Let's look for any boards with obviously invalid parents...

        foreach ($child_map as $parent => $dummy) {
            if (0 != $parent && !isset($cat_map[$parent])) {
                // Perhaps it was supposed to be their ID_CAT?

                foreach ($dummy as $board) {
                    if (empty($cat_map[$board])) {
                        $cat_map[$board] = $parent;
                    }
                }

                $child_map[0] = array_merge($child_map[0] ?? [], $dummy);

                unset($child_map[$parent]);
            }
        }

        // The above ID_PARENTs and ID_CATs may all be wrong; we know ID_PARENT = 0 is right.

        $solid_parents = [[0, 0]];

        $fixed_boards = [];

        while (!empty($solid_parents)) {
            [$parent, $level] = array_pop($solid_parents);

            if (!isset($child_map[$parent])) {
                continue;
            }

            // Fix all of this board's children.

            foreach ($child_map[$parent] as $board) {
                if (0 != $parent) {
                    $cat_map[$board] = $cat_map[$parent];
                }

                $fixed_boards[$board] = [$parent, $cat_map[$board], $level];

                $solid_parents[] = [$board, $level + 1];
            }
        }

        foreach ($fixed_boards as $board => $fix) {
            convert_query(
                "
				UPDATE {$to_prefix}boards
				SET ID_PARENT = " . (int)$fix[0] . ', ID_CAT = ' . (int)$fix[1] . ', childLevel = ' . (int)$fix[2] . '
				WHERE ID_BOARD = ' . (int)$board . '
				LIMIT 1'
            );
        }

        // Leftovers should be brought to the root.  They had weird parents we couldn't find.

        if (count($fixed_boards) < count($cat_map)) {
            convert_query(
                "
				UPDATE {$to_prefix}boards
				SET childLevel = 0, ID_PARENT = 0" . (empty($fixed_boards) ? '' : '
				WHERE ID_BOARD NOT IN (' . implode(', ', array_keys($fixed_boards)) . ')')
            );
        }

        // Last check: any boards not in a good category?

        $request = convert_query(
            "
			SELECT ID_CAT
			FROM {$to_prefix}categories"
        );

        $real_cats = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            $real_cats[] = $row['ID_CAT'];
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        $fix_cats = [];

        foreach ($cat_map as $board => $cat) {
            if (!in_array($cat, $real_cats, true)) {
                $fix_cats[] = $cat;
            }
        }

        if (!empty($fix_cats)) {
            convert_query(
                "
				INSERT INTO {$to_prefix}categories
					(name)
				VALUES ('General Category')"
            );

            $catch_cat = $GLOBALS['xoopsDB']->getInsertId();

            convert_query(
                "
				UPDATE {$to_prefix}boards
				SET ID_CAT = " . (int)$catch_cat . '
				WHERE ID_CAT IN (' . implode(', ', array_unique($fix_cats)) . ')'
            );
        }

        pastTime(8);
    }

    if ($_GET['substep'] <= 8) {
        $request = convert_query(
            "
			SELECT c.ID_CAT, c.catOrder, b.ID_BOARD, b.boardOrder
			FROM {$to_prefix}categories AS c
				LEFT JOIN {$to_prefix}boards AS b ON (b.ID_CAT = c.ID_CAT)
			ORDER BY c.catOrder, b.childLevel, b.boardOrder, b.ID_BOARD"
        );

        $catOrder = -1;

        $boardOrder = -1;

        $curCat = -1;

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            if ($curCat != $row['ID_CAT']) {
                $curCat = $row['ID_CAT'];

                if (++$catOrder != $row['catOrder']) {
                    convert_query(
                        "
						UPDATE {$to_prefix}categories
						SET catOrder = $catOrder
						WHERE ID_CAT = $row[ID_CAT]
						LIMIT 1"
                    );
                }
            }

            if (!empty($row['ID_BOARD']) && ++$boardOrder != $row['boardOrder']) {
                convert_query(
                    "
					UPDATE {$to_prefix}boards
					SET boardOrder = $boardOrder
					WHERE ID_BOARD = $row[ID_BOARD]
					LIMIT 1"
                );
            }
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        pastTime(9);
    }

    if ($_GET['substep'] <= 9) {
        convert_query(
            "
			ALTER TABLE {$to_prefix}boards
			ORDER BY boardOrder"
        );

        convert_query(
            "
			ALTER TABLE {$to_prefix}smileys
			ORDER BY LENGTH(code) DESC"
        );

        pastTime(10);
    }

    if ($_GET['substep'] <= 10) {
        $request = convert_query(
            "
			SELECT COUNT(*)
			FROM {$to_prefix}attachments"
        );

        [$attachments] = $GLOBALS['xoopsDB']->fetchRow($request);

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        while ($_REQUEST['start'] < $attachments) {
            $request = convert_query(
                "
				SELECT ID_ATTACH, filename, attachmentType
				FROM {$to_prefix}attachments
				WHERE ID_THUMB = 0
					AND (RIGHT(filename, 4) IN ('.gif', '.jpg', '.png', '.bmp') OR RIGHT(filename, 5) = '.jpeg')
					AND width = 0
					AND height = 0
				LIMIT $_REQUEST[start], 500"
            );

            if (0 == $GLOBALS['xoopsDB']->getRowsNum($request)) {
                break;
            }

            while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
                if (1 == $row['attachmentType']) {
                    $filename = $modSettings['custom_avatar_dir'] . '/' . $row['filename'];
                } else {
                    $filename = getAttachmentFilename($row['filename'], $row['ID_ATTACH']);
                }

                // Probably not one of the converted ones, then?

                if (!file_exists($filename)) {
                    continue;
                }

                $size = @getimagesize($filename);

                if (!empty($size) && !empty($size[0]) && !empty($size[1])) {
                    convert_query(
                        "
						UPDATE {$to_prefix}attachments
						SET
							width = " . (int)$size[0] . ',
							height = ' . (int)$size[1] . "
						WHERE ID_ATTACH = $row[ID_ATTACH]
						LIMIT 1"
                    );
                }
            }

            $GLOBALS['xoopsDB']->freeRecordSet($request);

            // More?

            $_REQUEST['start'] += 500;

            pastTime(10);
        }

        $_REQUEST['start'] = 0;

        pastTime(11);
    }

    echo ' Successful.<br>';

    return doStep3();
}

function doStep3()
{
    global $boardurl, $convert_data;

    echo '
				<h2 style="margin-top: 2ex;">Conversion Complete</h2>
				<h3>Congratulations, the conversion has completed successfully.  If you have or had any problems with this converter, or need help using SMF, please feel free to <a href="http://www.simplemachines.org/community/index.php">look to us for support</a>.</h3>';

    if (is_writable(__DIR__) && is_writable(__FILE__)) {
        echo '
				<div style="margin: 1ex; font-weight: bold;">
					<label for="delete_self"><input type="checkbox" id="delete_self" onclick="doTheDelete();"> Please check this box to delete the converter right now for security reasons.</label> (doesn\'t work on all servers.)
				</div>
				<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
					function doTheDelete()
					{
						var theCheck = document.getElementById ? document.getElementById("delete_self") : document.all.delete_self;
						var tempImage = new Image();

						tempImage.src = "', $_SERVER['PHP_SELF'], '?delete=1&" + (new Date().getTime());
						tempImage.width = 0;
						theCheck.disabled = true;
					}
				// ]]></script>
				<br>';
    }

    echo '
				Now that everything is converted over, <a href="', $boardurl, '/index.php">your SMF installation</a> should have all the posts, boards, and members from the ', $convert_data['name'], ' installation.<br>
				<br>
				We hope you had a smooth transition!';

    return true;
}

function show_header()
{
    global $convert_data;

    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
	<title>', isset($convert_data['name']) ? $convert_data['name'] . ' to ' : '', 'SMF Converter</title>
		<style type="text/css">
			body
			{
				background-color: #E5E5E8;
				margin: 0px;
				padding: 0px;
			}
			body, td
			{
				color: #000000;
				font-size: small;
				font-family: verdana, sans-serif;
			}
			div#header
			{
				background-image: url(Themes/default/images/catbg.jpg);
				background-repeat: repeat-x;
				background-color: #88A6C0;
				padding: 22px 4% 12px 4%;
				color: white;
				font-family: Georgia, serif;
				font-size: xx-large;
				border-bottom: 1px solid black;
				height: 40px;
			}
			div#content
			{
				padding: 20px 30px;
			}
			div.error_message
			{
				border: 2px dashed red;
				background-color: #E1E1E1;
				margin: 1ex 4ex;
				padding: 1.5ex;
			}
			div.panel
			{
				border: 1px solid gray;
				background-color: #F6F6F6;
				margin: 1ex 0;
				padding: 1.2ex;
			}
			div.panel h2
			{
				margin: 0;
				margin-bottom: 0.5ex;
				padding-bottom: 3px;
				border-bottom: 1px dashed black;
				font-size: 14pt;
				font-weight: normal;
			}
			div.panel h3
			{
				margin: 0;
				margin-bottom: 2ex;
				font-size: 10pt;
				font-weight: normal;
			}
			form
			{
				margin: 0;
			}
			td.textbox
			{
				padding-top: 2px;
				font-weight: bold;
				white-space: nowrap;
				padding-', empty($txt['lang_rtl']) ? 'right' : 'left', ': 2ex;
			}
		</style>
	</head>
	<body>
		<div id="header">
			<div title="Bahamut!">', isset($convert_data['name']) ? $convert_data['name'] . ' to ' : '', 'SMF Converter</div>
		</div>
		<div id="content">';
}

// Show the footer.
function show_footer()
{
    echo '
		</div>
	</body>
</html>';
}

// Check if we've passed the time limit..
function pastTime($substep = null)
{
    global $time_start;

    @set_time_limit(300);

    if (function_exists('apache_reset_timeout')) {
        apache_reset_timeout();
    }

    if (isset($_GET['substep']) && $_GET['substep'] < $substep) {
        $_GET['substep'] = $substep;
    }

    if (time() - $time_start < 10) {
        return;
    }

    echo '
			<i>Incomplete.</i><br>

			<h2 style="margin-top: 2ex;">Not quite done yet!</h2>
			<h3>
				This conversion has paused to avoid overloading your server, and hence not working properly.<br>
				Don\'t worry though, <b>nothing\'s wrong</b> - simply click the <label for="continue">continue button</label> below to start the converter from where it left off.
			</h3>

			<form action="', $_SERVER['PHP_SELF'], '?step=', $_GET['step'], isset($_GET['substep']) ? '&amp;substep=' . $_GET['substep'] : '', isset($_GET['cstep']) ? '&amp;cstep=' . $_GET['cstep'] : '', '&amp;start=', $_REQUEST['start'], '" method="post" name="autoSubmit">
				<div align="right" style="margin: 1ex;"><input name="b" type="submit" value="Continue"></div>
			</form>
			<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
				window.onload = doAutoSubmit;
				var countdown = 3;

				function doAutoSubmit()
				{
					if (countdown == 0)
						document.autoSubmit.submit();
					else if (countdown == -1)
						return;

					document.autoSubmit.b.value = "Continue (" + countdown + ")";
					countdown--;

					setTimeout("doAutoSubmit();", 1000);
				}
			// ]]></script>';

    show_footer();

    exit;
}

function removeAllAttachments()
{
    global $to_prefix;

    $result = convert_query(
        "
		SELECT value
		FROM {$to_prefix}settings
		WHERE variable = 'attachmentUploadDir'
		LIMIT 1"
    );

    [$attachmentUploadDir] = $GLOBALS['xoopsDB']->fetchRow($result);

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    // !!! This should probably be done in chunks too.

    $result = convert_query(
        "
		SELECT ID_ATTACH, filename
		FROM {$to_prefix}attachments"
    );

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        // We're duplicating this from below because it's slightly different for getting current ones.

        $clean_name = strtr($row['filename'], 'ŠŽšžŸÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåçèéêëìíîïñòóôõöøùúûüýÿ', 'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy');

        $clean_name = strtr($clean_name, ['Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss', 'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u']);

        $clean_name = preg_replace(['/\s/', '/[^\w_\.\-]/'], ['_', ''], $clean_name);

        $enc_name = $row['ID_ATTACH'] . '_' . strtr($clean_name, '.', '_') . md5($clean_name);

        $clean_name = preg_replace('~\.[\.]+~', '.', $clean_name);

        if (file_exists($attachmentUploadDir . '/' . $enc_name)) {
            $filename = $attachmentUploadDir . '/' . $enc_name;
        } else {
            $filename = $attachmentUploadDir . '/' . $clean_name;
        }

        @unlink($filename);
    }

    $GLOBALS['xoopsDB']->freeRecordSet($result);
}

function getAttachmentFilename($filename, $attachment_id)
{
    // Remove special accented characters - ie. sí (because they won't write to the filesystem well.)

    $clean_name = strtr($filename, 'ŠŽšžŸÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåçèéêëìíîïñòóôõöøùúûüýÿ', 'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy');

    $clean_name = strtr($clean_name, ['Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss', 'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u']);

    // Get rid of dots, spaces, and other weird characters.

    $clean_name = preg_replace(['/\s/', '/[^\w_\.\-]/'], ['_', ''], $clean_name);

    return $attachment_id . '_' . strtr($clean_name, '.', '_') . md5($clean_name);
}

function addslashes_recursive($var)
{
    if (!is_array($var)) {
        return addslashes($var);
    }  

    foreach ($var as $k => $v) {
        $var[$k] = addslashes_recursive($v);
    }

    return $var;
}

// Update the Settings.php file.
function updateSettingsFile($config_vars)
{
    return;
    // Load the file.

    $settingsArray = file($_POST['path_to'] . '/Settings.php');

    if (1 == count($settingsArray)) {
        $settingsArray = preg_preg_split('~[\r\n]~', $settingsArray[0]);
    }

    for ($i = 0, $n = count($settingsArray); $i < $n; $i++) {
        // Don't trim or bother with it if it's not a variable.

        if ('$' != mb_substr($settingsArray[$i], 0, 1)) {
            continue;
        }

        $settingsArray[$i] = trim($settingsArray[$i]) . "\n";

        // Look through the variables to set....

        foreach ($config_vars as $var => $val) {
            if (0 == strncasecmp($settingsArray[$i], '$' . $var, 1 + mb_strlen($var))) {
                $comment = mb_strstr(mb_substr($settingsArray[$i], mb_strpos($settingsArray[$i], ';')), '#');

                $settingsArray[$i] = '$' . $var . ' = ' . $val . ';' . ('' == $comment ? "\n" : "\t\t" . $comment);

                // This one's been 'used', so to speak.

                unset($config_vars[$var]);
            }
        }

        if (trim(mb_substr($settingsArray[$i], 0, 2)) == '?' . '>') {
            $end = $i;
        }
    }

    // This should never happen, but apparently it is happening.

    if (empty($end) || $end < 10) {
        $end = count($settingsArray) - 1;
    }

    // Still more?  Add them at the end.

    if (!empty($config_vars)) {
        $settingsArray[$end++] = '';

        foreach ($config_vars as $var => $val) {
            $settingsArray[$end++] = '$' . $var . ' = ' . $val . ';' . "\n";
        }

        $settingsArray[$end] = '?' . '>';
    }

    // Sanity error checking: the file needs to be at least 10 lines.

    if (count($settingsArray) < 10) {
        return;
    }

    // Blank out the file - done to fix a oddity with some servers.

    $fp = fopen($_POST['path_to'] . '/Settings.php', 'wb');

    fclose($fp);

    // Now actually write.

    $fp = fopen($_POST['path_to'] . '/Settings.php', 'r+b');

    $lines = count($settingsArray);

    for ($i = 0; $i < $lines - 1; $i++) {
        fwrite($fp, strtr($settingsArray[$i], "\r", ''));
    }

    // The last line should have no \n.

    fwrite($fp, rtrim($settingsArray[$i]));

    fclose($fp);
}

function convert_query($string, $return_error = false)
{
    global $convert_dbs, $to_prefix;

    $string = str_replace('_smf_members', '_users', $string);

    // Debugging?

    if (isset($_REQUEST['debug'])) {
        $_SESSION['convert_debug'] = !empty($_REQUEST['debug']);
    }

    if (trim($string) == 'TRUNCATE ' . $GLOBALS['to_prefix'] . 'attachments') {
        removeAllAttachments();
    }

    if ('mysql' != $convert_dbs) {
        $clean = '';

        $old_pos = 0;

        $pos = -1;

        while (true) {
            $pos = mb_strpos($string, '\'', $pos + 1);

            if (false === $pos) {
                break;
            }

            $clean .= mb_substr($string, $old_pos, $pos - $old_pos);

            while (true) {
                $pos1 = mb_strpos($string, '\'', $pos + 1);

                $pos2 = mb_strpos($string, '\\', $pos + 1);

                if (false === $pos1) {
                    break;
                } elseif (false === $pos2 || $pos2 > $pos1) {
                    $pos = $pos1;

                    break;
                }

                $pos = $pos2 + 1;
            }

            $clean .= '%s';

            $old_pos = $pos + 1;
        }

        $clean .= mb_substr($string, $old_pos);

        $clean = trim(preg_replace('~\s+~s', ' ', $clean));

        if (false === mb_strpos($string, $to_prefix)) {
            preg_match('~limit (\d+)(?:, (\d+))?\s*$~is', $string, $limit);

            if (!empty($limit)) {
                $string = preg_replace('~limit (\d+)(?:, (\d+))?$~is', '', $string);

                if (!isset($limit[2])) {
                    $limit[2] = $limit[1];

                    $limit[1] = 0;
                }
            }

            if ('odbc' == $convert_dbs) {
                if (!empty($limit)) {
                    $string = preg_replace('~^\s*select~is', 'SELECT TOP ' . ($limit[1] + $limit[2]), $string);
                }

                $result = @odbc_exec($GLOBALS['odbc_connection'], $string);

                if (!empty($limit) && !empty($limit[1])) {
                    odbc_fetch_row($result, $limit[1]);
                }
            } elseif ('ado' == $convert_dbs) {
                if (!empty($limit)) {
                    $string = preg_replace('~^\s*select~is', 'SELECT TOP ' . ($limit[1] + $limit[2]), $string);
                }

                if (PHP_VERSION >= 5) {
                    eval(
                    '
						try
						{
							$result = $GLOBALS[\'ado_connection\']->Execute($string);
						}
						catch (com_exception $err)
						{
							$result = false;
						}'
                    );
                } else {
                    $result = @$GLOBALS['ado_connection']->Execute($string);
                }

                if (false !== $result && !empty($limit) && !empty($limit[1])) {
                    $result->Move($limit[1], 1);
                }
            }

            $not_mysql = true;
        } else {
            $result = @$GLOBALS['xoopsDB']->queryF($string);
        }
    } else {
        $result = @$GLOBALS['xoopsDB']->queryF($string);
    }

    if (false !== $result || $return_error) {
        return $result;
    }

    if (empty($not_mysql)) {
        $mysql_error = $GLOBALS['xoopsDB']->error();

        $mysql_errno = $GLOBALS['xoopsDB']->errno();

        // Error numbers:

        //    1016: Can't open file '....MYI'

        //    2013: Lost connection to server during query.

        if (1016 == $mysql_errno) {
            if (0 != preg_match('~(?:\'([^\.\']+)~', $mysql_error, $match) && !empty($match[1])) {
                $GLOBALS['xoopsDB']->queryF(
                    "
					REPAIR TABLE $match[1]"
                );
            }

            $result = $GLOBALS['xoopsDB']->queryF($string);

            if (false !== $result) {
                return $result;
            }
        } elseif (2013 == $mysql_errno) {
            $result = $GLOBALS['xoopsDB']->queryF($string);

            if (false !== $result) {
                return $result;
            }
        }
    } elseif ('odbc' == $convert_dbs) {
        $mysql_error = odbc_errormsg($GLOBALS['odbc_connection']);
    } elseif ('ado' == $convert_dbs) {
        $error = $GLOBALS['ado_connection']->Errors[0];

        $mysql_error = $error->Description;
    }

    // Get the query string so we pass everything.

    if (isset($_REQUEST['start'])) {
        $_GET['start'] = $_REQUEST['start'];
    }

    $query_string = '';

    foreach ($_GET as $k => $v) {
        $query_string .= '&' . $k . '=' . $v;
    }

    if (0 != mb_strlen($query_string)) {
        $query_string = '?' . strtr(mb_substr($query_string, 1), ['&' => '&amp;']);
    }

    echo '
			<b>Unsuccessful!</b><br>

			This query:<blockquote>' . nl2br(htmlspecialchars(trim($string), ENT_QUOTES | ENT_HTML5)) . ';</blockquote>

			Caused the error:<br>
			<blockquote>' . nl2br(htmlspecialchars($mysql_error, ENT_QUOTES | ENT_HTML5)) . '</blockquote>

			<form action="', $_SERVER['PHP_SELF'], $query_string, '" method="post">
				<input type="submit" value="Try again">
			</form>
		</div>';

    show_footer();

    die;
}

function convert_free_result($result)
{
    // ADO?

    if (!is_resource($result) && is_object($result)) {
        $result->Close();

        return;
    }

    $type = get_resource_type($result);

    if ('mysql result' == $type) {
        $GLOBALS['xoopsDB']->freeRecordSet($result);
    } elseif ('odbc result' == $type) {
        odbc_free_result($result);
    }
}

function convert_fetch_assoc($result)
{
    // Okay, the hardest is ADO (Windows only, by the way.)

    if (!is_resource($result) && is_object($result)) {
        if ($result->EOF) {
            return false;
        }

        $row = [];

        $fields = $result->Fields;

        for ($i = 0, $n = $fields->Count; $i < $n; $i++) {
            $field = $fields[$i];

            $row[$field->Name] = $field->Value;
        }

        $result->MoveNext();

        return $row;
    }

    $type = get_resource_type($result);

    if ('mysql result' == $type) {
        return $GLOBALS['xoopsDB']->fetchArray($result);
    } elseif ('odbc result' == $type) {
        return odbc_fetch_array($result);
    }
}

function convert_fetch_row($result)
{
    if (!is_resource($result) && is_object($result)) {
        if ($result->EOF) {
            return false;
        }

        $row = [];

        $fields = $result->Fields;

        for ($i = 0, $n = $fields->Count; $i < $n; $i++) {
            $row[] = $fields[$i]->Value;
        }

        $result->MoveNext();

        return $row;
    }

    $type = get_resource_type($result);

    if ('mysql result' == $type) {
        return $GLOBALS['xoopsDB']->fetchRow($result);
    } elseif ('odbc_result' == $type) {
        return array_values(odbc_fetch_array($result));
    }
}

function convert_num_rows($result)
{
    if (!is_resource($result) && is_object($result)) {
        $fields = $result->Fields;

        return $fields->Count;
    }

    $type = get_resource_type($result);

    if ('mysql result' == $type) {
        return $GLOBALS['xoopsDB']->getRowsNum($result);
    } elseif ('odbc result' == $type) {
        return odbc_num_rows($result);
    }
}

function alterTable($tableName, $knownKeys = '', $knownColumns = '', $alterColumns = '', $reverseKeys = false, $reverseColumns = false)
{
    global $to_prefix;

    // Shorten this up

    $to_table = $to_prefix . $tableName;

    // Get the keys

    if (!empty($knownKeys)) {
        $request = convert_query(
            "
			SHOW KEYS
			FROM $to_table"
        );

        $availableKeys = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            $availableKeys[] = $row['Key_name'];
        }

        // Flip the keys.

        array_flip($availableKeys);
    } else {
        $knownKeys = [];
    }

    // Are we dealing with columns also?

    if (!empty($knownColumns)) {
        $request = convert_query(
            "
			SHOW COLUMNS
			FROM $to_table"
        );

        $availableColumns = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            $availableColumns[] = $row['Field'];
        }

        array_flip($availableColumns);
    } else {
        $knownColumns = [];
    }

    // Column to alter

    if (!empty($alterColumns) && is_array($alterColumns)) {
        $alterColumns = $alterColumns;
    } else {
        $alterColumns = [];
    }

    // Check indexes

    foreach ($knownKeys as $key => $value) {
        // If we are dropping keys then it should unset the known keys if it's NOT available

        if (false === $reverseKeys && !in_array($key, $availableKeys, true)) {
            unset($knownKeys[$key], $knownKeys[$key]);
        } // Since we are in reverse and we are adding then unknown the known keys that are available

        elseif (true === $reverseKeys && in_array($key, $availableKeys, true)) {
            unset($knownKeys[$key], $knownKeys[$key]);
        }
    }

    // Check columns

    foreach ($knownColumns as $column => $value) {
        // Here we reverse things.  If the column is not in then we must add it.

        if (false === $reverseColumns && in_array($column, $availableColumns, true)) {
            unset($knownColumns[$column], $knownColumns[$column]);
        } // If it's in then we must unset it.

        elseif (true === $reverseColumns && !in_array($column, $availableColumns, true)) {
            unset($knownColumns[$column], $knownColumns[$column]);
        }
    }

    // Now merge the three

    $alter = array_merge($alterColumns, $knownKeys, $knownColumns);

    // Now lets see what we want to do with them

    $clause = '';

    foreach ($alter as $key) {
        $clause .= "
		$key,";
    }

    // Lets do some altering

    convert_query(
        "
		ALTER TABLE $to_table" . mb_substr($clause, 0, -1)
    );
}
?>
