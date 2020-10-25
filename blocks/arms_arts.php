<?php

// ========================================================================== //
// ArMS = Artical Menagement System                                           //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Unit version   : 0.001                                                   //
//   Started        : 2:15:30 PM 10/12/2003                                   //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 1:07:14 PM 10/11/2003                                   //
//   Last update by : Ilija Studen                                            //
//                                                                            //
//   Change log:                                                              //
//   -----------                                                              //
//   - started in ArMS 0.3                                                    //
//                                                                            //
// ========================================================================== //
// (c) 2003. by Ilija Studen                                                  //
//              (mail: ilija_studen@yahoo.com)                                //
//              (home: ilija.ionbee.net)                                      //
// ========================================================================== //

require_once XOOPS_ROOT_PATH . '/modules/arms/language/english/main.php';
require_once XOOPS_ROOT_PATH . '/modules/arms/includes/functions_general.php';
require_once XOOPS_ROOT_PATH . '/modules/arms/includes/functions_db.php';

function arms_art_block_disp($result)
{
    global $xoopsDB;

    $res = [];

    $counter = 0;

    while (false !== ($row = $xoopsDB->fetchArray($result))) :
        {
            $res[$counter]['title'] = $row['art_title'];
            $res[$counter]['id'] = $row['art_id'];
            $res[$counter]['uid'] = $row['uid'];

            $q_str = 'SELECT uname FROM ' . $xoopsDB->prefix('users') . ' WHERE uid=' . $row['uid'];
            $user_row = arms_fetch_first($q_str, _ME_ARMS_USER_DOESNT_EXISTS);

            $q_str = 'SELECT sec_id, sec_title, sec_image FROM ' . $xoopsDB->prefix('arms_sections') . ' WHERE sec_id=' . $row['sec_id'];
            $sec_row = arms_fetch_first($q_str, _ME_ARMS_SECTION_DOESNT_EXISTS);

            $res[$counter]['sec_id'] = $sec_row['sec_id'];
            $res[$counter]['sec_title'] = $sec_row['sec_title'];
            $res[$counter]['uname'] = $user_row['uname'];
            $res[$counter]['posttime'] = gmdate(_DATESTRING, xoops_getUserTimestamp($row['art_posttime']));
            $res[$counter]['rate'] = arms_get_rate($row['art_ratetotal'], $row['art_ratecount']);
            $counter++;
        }

    endwhile;

    return $res;
}

function arts_show_new($options)
{
    global $xoopsDB;

    $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_activated = 1 AND art_onhold = 0 ORDER BY art_posttime DESC LIMIT 0, 10';

    $result = $xoopsDB->query($q_str);

    $block['arms_arts'] = arms_art_block_disp($result);

    return $block;
}

function arts_show_pop($options)
{
    global $xoopsDB;

    $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_activated = 1 AND art_onhold = 0 ORDER BY art_views DESC LIMIT 0, 10';

    $result = $xoopsDB->query($q_str);

    $block['arms_arts'] = arms_art_block_disp($result);

    return $block;
}

function arts_show_top($options)
{
    global $xoopsDB;

    $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_activated = 1 AND art_onhold = 0 ORDER BY (art_ratetotal/art_ratecount) DESC LIMIT 0, 10';

    $result = $xoopsDB->query($q_str);

    $block['arms_arts'] = arms_art_block_disp($result);

    return $block;
}
