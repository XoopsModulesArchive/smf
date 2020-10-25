<?php
/*
User Email System
Version 1.2
by:vbgamer45
http://www.smfhacks.com
*/

if (!defined('SMF')) {
    die('Hacking attempt...');
}

function UserEmailMain()
{
    global $context, $mbname, $webmaster_email, $ID_MEMBER, $txt, $db_prefix, $sourcedir, $user_info;

    //Check if the current user can send emails

    isAllowedTo('send_useremail');

    if (isset($_GET['sa'])) {
        if ('save' == $_GET['sa']) {
            @$subject = htmlspecialchars($_POST['subject'], ENT_QUOTES);

            if ('' == $subject) {
                fatal_error($txt['user_email_subjectfailed'], false);
            }

            @$message = htmlspecialchars($_POST['message'], ENT_QUOTES);

            if ('' == $message) {
                fatal_error($txt['user_email_messagefailed'], false);
            }

            @$userid = (int)$_POST['userid'];

            if (empty($userid)) {
                fatal_error($txt['user_email_nouser'], false);
            }

            $request = db_query("SELECT realName, hideEmail, emailAddress FROM {$db_prefix}members WHERE ID_MEMBER = $userid LIMIT 1", __FILE__, __LINE__);

            $row = $GLOBALS['xoopsDB']->fetchArray($request);

            if (1 == $row['hideEmail'] && !allowedTo('admin_forum')) {
                fatal_error($txt['user_email_cannouser'], false);
            }

            $rec = $row['realName'];

            $rec_email = $row['emailAddress'];

            $GLOBALS['xoopsDB']->freeRecordSet($request);

            //Show the Guest email form field

            if (!$user_info['is_guest']) {
                $request2 = db_query("SELECT realName, emailAddress FROM {$db_prefix}members WHERE ID_MEMBER = $ID_MEMBER LIMIT 1", __FILE__, __LINE__);

                $row2 = $GLOBALS['xoopsDB']->fetchArray($request2);

                $sec_name = $row2['realName'];

                $sec_email = $row2['emailAddress'];

                $GLOBALS['xoopsDB']->freeRecordSet($request2);
            } else {
                @$guestemail = htmlspecialchars($_POST['guestemail'], ENT_QUOTES);

                if ('' == $guestemail) {
                    fatal_error($txt['user_email_selfmailrequire'], false);
                }

                if (!is_valid_email($guestemail)) {
                    fatal_error($txt['user_email_selfmailfail'], false);
                }

                $sec_name = 'Guest';

                $sec_email = $guestemail;
            }

            $m = sprintf($txt['selfmailbodytext'], $rec, $sec_name, $mbname, $webmaster_email);

            $m .= strip_tags($message);

            //For send mail function

            require_once $sourcedir . '/Subs-Post.php';

            //Send email to member

            sendmail($rec_email, $subject, $m);

            //Check if it should send the sender a copy of email

            @$sendcopy = $_POST['sendcopy'];

            if ('ON' == $sendcopy) {
                sendmail($sec_email, $subject, $m);
            }

            //Show template that mail was sent

            loadTemplate('User_Email');

            //Load the main User Email template

            $context['sub_template'] = 'send';

            //Set the page title

            $context['page_title'] = $mbname . $txt['user_email_mailissend'];
        }
    } else {
        $u = (int)$_GET['u'];

        $request = db_query("SELECT realName,hideEmail FROM {$db_prefix}members WHERE ID_MEMBER = $u LIMIT 1", __FILE__, __LINE__);

        $row = $GLOBALS['xoopsDB']->fetchArray($request);

        $context['user_email_name'] = $row['realName'];

        $context['user_email_id'] = $u;

        if (1 == $row['hideEmail'] && !allowedTo('admin_forum')) {
            fatal_error($txt['user_email_cannouser'], false);
        }

        //Load the main User Email template

        loadTemplate('User_Email');

        //Load the main User Email template

        $context['sub_template'] = 'main';

        //Set the page title

        $context['page_title'] = $mbname . ' - ' . $txt['user_email_title'];
    }
}

function is_valid_email($email)
{
    return preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s]+\.+[a-z]{2,6}))$#si', $email);
}
