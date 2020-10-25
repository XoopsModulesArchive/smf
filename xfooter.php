<?php

$buffer = ob_get_contents();
ob_end_clean();
ob_start();
// --- This means that the buffer may be xoopsfix'd twice - see above ob_start.
echo ob_xoopsfix($buffer);
if (!in_array('main', $context['template_layers'], true)) {
    mysqli_select_db($GLOBALS['xoopsDB']->conn, $db_name);

    die();
}
if (1 == $xheader) {
    require XOOPS_ROOT_PATH . '/footer.php';
}
