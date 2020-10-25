<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 xoops.org                           //
//                       <https://www.xoops.org>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
// Project: The XOOPS Project   																						 //
// Modified : Dirk Herrmann (Alfred)                                         //
// Site: http://www.simple-xoops.de                                          //
// ------------------------------------------------------------------------- //
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}

function smf_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB, $xoopsUser;

    $ret = [];

    $rboards = '';

    $r = 0;

    $groups = ['-1', '0'];

    if ($xoopsUser) {
        $sql = 'SELECT additionalGroups,ID_GROUP FROM ' . $xoopsDB->prefix('users') . ' WHERE uid=' . $xoopsUser->uid();

        $result = $xoopsDB->query($sql);

        $row = $xoopsDB->fetchArray($result);

        if (0 != (int)$row['ID_GROUP']) {
            $groups[] = (int)$row['ID_GROUP'];
        }

        $addgroups = explode(',', $row['additionalGroups']);

        foreach ($addgroups as $agr) {
            if (!in_array($agr, $groups, true)) {
                $groups[] = (int)$agr;
            }
        }
    }

    $sql = 'SELECT ID_BOARD,memberGroups FROM ' . $xoopsDB->prefix('smf_boards') . ' ORDER BY ID_BOARD ASC';

    $result = $xoopsDB->query($sql);

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $mgroups = explode(',', $row['memberGroups']);

        $lock = 0;

        foreach ($groups as $group) {
            if (in_array($group, $mgroups, true)) {
                $lock = 1;
            }
        }

        if (1 == $lock) {
            if ($r > 0) {
                $rboards .= ',';
            }

            $rboards .= $row['ID_BOARD'];

            $r = 1;
        }
    }

    if (0 == mb_strlen($rboards)) {
        return $ret;
    }

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('smf_messages') . ' WHERE';

    if (0 != $userid) {
        $sql .= ' ID_MEMBER=' . $userid . ' AND ';
    }

    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " ((body LIKE '%$queryarray[0]%' OR subject LIKE '%$queryarray[0]%')";

        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";

            $sql .= "(body LIKE '%$queryarray[$i]%' OR subject LIKE '%$queryarray[$i]%')";
        }

        $sql .= ') AND ';
    }

    $sql .= ' ID_BOARD IN (' . $rboards . ') ';

    $sql .= 'ORDER BY posterTime DESC';

    $result = $xoopsDB->query($sql, $limit, $offset);

    $i = 0;

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        //$ret[$i]['image'] = "images/evennews.gif";

        $ret[$i]['link'] = 'index.php?topic=' . $row['ID_TOPIC'] . '.msg' . $row['ID_MSG'] . '#msg' . $row['ID_MSG'];

        $ret[$i]['title'] = $row['subject'];

        $ret[$i]['time'] = $row['posterTime'];

        $ret[$i]['uid'] = $row['ID_MEMBER'];

        $i++;
    }

    return $ret;
}
