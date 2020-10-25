<?php

/*
User Email System
Version 1.1.2
by:vbgamer45
http://www.smfhacks.com
*/
function template_main()
{
    global $scripturl, $context, $user_info, $txt;

    echo '<div class="tborder" >
<form method="POST" action="' . $scripturl . '?action=useremail&sa=save">
<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#FFFFFF" width="100%" height="129">
  <tr>
    <td width="50%" colspan="2" height="19" align="center" class="catbg">
    <b>', $txt['user_email_sendemailheader'], '</b></td>
  </tr>
  <tr>
    <td width="28%" height="19" class="windowbg2"><span class="gen"><b>', $txt['user_email_recipient'], '</b></span></td>
    <td width="72%" height="19" class="windowbg2">' . $context['user_email_name'] . '<input type="hidden" name="userid" value="' . $context['user_email_id'] . '"></td>
  </tr>';

    //Show the Guest email form field

    if ($user_info['is_guest']) {
        echo '
	  <tr>
	    <td width="28%" height="19" class="windowbg2"><span class="gen"><b>', $txt['user_email_youremail'], '</b></span></td>
	    <td width="72%" height="19" class="windowbg2"><input type="text" name="guestemail" value=""></td>
	  </tr>';
    }

    echo '
  <tr>
    <td width="28%" height="22" class="windowbg2"><span class="gen"><b>', $txt['user_email_subject'], '</b></span></td>
    <td width="72%" height="22" class="windowbg2"><input type="text" name="subject" size="64"></td>
  </tr>
  <tr>
    <td width="28%" height="19" valign="top" class="windowbg2"><span class="gen"><b>', $txt['user_email_messagebody'], '</b></span></td>
    <td width="72%" height="19" class="windowbg2"><textarea rows="6" name="message" cols="54"></textarea></td>
  </tr>
  <tr>
    <td width="28%" height="19" class="windowbg2"><span class="gen"><b>', $txt['user_email_options'], '</b></span></td>
    <td width="72%" height="19" class="windowbg2">
    <input type="checkbox" name="sendcopy" value="ON" checked><b><span class="gen">', $txt['user_email_sendcopy'], '</span></b></td>
  </tr>
  <tr>
    <td width="28%" colspan="2" height="26" align="center" class="windowbg2">
    <input type="submit" value="', $txt['user_email_sendemail'], '" name="submit"></td>

  </tr>
</table>
</form>
</div>';

    //Copryright must remain.

    echo '<br><div align="center"><a href="http://www.smfhacks.com" target="blank">User Email System</a></div>';
}

function template_send()
{
    global $scripturl, $txt;

    echo '
<div>
	<table border="0" width="80%" cellspacing="0" align="center" cellpadding="4" class="tborder">
		<tr class="titlebg">
			<td>', $txt['user_email_emailsend'], '</td>
		</tr>

		<tr class="windowbg">
			<td style="padding: 3ex;">
				', $txt['user_email_emailsend'], ' ', sprintf($txt['user_email_emailsendtoreturn'], $scripturl), '
			</td>
		</tr>
	</table>
</div>';

    //Copryright must remain.

    echo '<br><div align="center"><a href="http://www.smfhacks.com" target="blank">User Email System</a></div>';
}
