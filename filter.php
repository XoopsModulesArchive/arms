<?php

// ========================================================================== //
// ArMS = Artical Menagement System                                           //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Unit version   : 0.001                                                   //
//   Started        : 12:09:20 PM 10/11/2003                                  //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 2:17:07 PM 10/12/2003                                   //
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

require __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';

function display_nptr($result, $arms_lang_idx, $arms_redirect = '')
{
    global $xoopsDB, $xoopsModule, $xoopsTpl, $xoopsUser;

    if ('' == $arms_redirect) :
        {
            $arms_redirect = XOOPS_URL;
        }

    endif;

    $my_ts = MyTextSanitizer::getInstance();

    $arms_display = [];

    $counter = 0;

    while (false !== ($row = $xoopsDB->fetchArray($result))) :
        {
            $arms_display[$counter]['art_id'] = $row['art_id'];
            $arms_display[$counter]['art_title'] = $row['art_title'];
            $arms_display[$counter]['art_desc'] = $my_ts->displayTarea($row['art_desc'], 0, 0, 0);
            $arms_display[$counter]['posttime'] = gmdate(_DATESTRING, xoops_getUserTimestamp($row['art_posttime']));
            $arms_display[$counter]['art_rate'] = arms_get_rate($row['art_ratetotal'], $row['art_ratecount']);
            $arms_display[$counter]['uid'] = $row['uid'];
            $arms_display[$counter]['uip'] = $row['uip'];

            if (is_object($xoopsUser)) :
                {
                    $user_perms = arms_get_artical_perms($row['art_id'], $uid, $arms_redirect);
                } else :
                {
                    $user_perms = arms_init_perms();
                    $user_perms['can_view_perms'] = false;
                    $user_perms['can_view_cross'] = false;
                }
            endif;

            $arms_display[$counter]['perms'] = $user_perms;

            $q_str = 'SELECT uname FROM ' . $xoopsDB->prefix('users') . ' WHERE uid=' . $row['uid'];
            $user_row = arms_fetch_first($q_str, _ME_ARMS_USER_DOESNT_EXISTS, $arms_redirect);

            $arms_display[$counter]['uname'] = $user_row['uname'];
            $arms_display[$counter]['comments'] = xoops_comment_count($xoopsModule->getVar('mid'), $row['art_id']);

            $q_str = 'SELECT sec_id, sec_title, sec_image FROM ' . $xoopsDB->prefix('arms_sections') . ' WHERE sec_id=' . $row['sec_id'];
            $sec_row = arms_fetch_first($q_str, _ME_ARMS_SECTION_DOESNT_EXISTS, $arms_redirect);

            $arms_display[$counter]['sec_id'] = $sec_row['sec_id'];
            $arms_display[$counter]['sec_title'] = $sec_row['sec_title'];
            $arms_display[$counter]['sec_image'] = $sec_row['sec_image'];

            $q_str = 'SELECT level_id, level_name FROM ' . $xoopsDB->prefix('arms_articals_levels') . ' WHERE level_id=' . $row['level_id'];
            $level_row = arms_fetch_first($q_str, _ME_ARMS_LEVEL_DOESNT_EXISTS, $arms_redirect);

            $arms_display[$counter]['level_id'] = $level_row['level_id'];
            $arms_display[$counter]['level_title'] = $level_row['level_name'];
            $counter++;
        }

    endwhile;

    $arms_lang_arr = get_arms_lang($arms_lang_idx);

    $xoopsTpl->assign('arms_lang', $arms_lang_arr);

    $xoopsTpl->assign('arms_display', $arms_display);
}

if (isset($_GET['w'])) :
    {
        $what = (string)$_GET['w'];
    } else :
    {
        $what = 'newest';
    }
endif;

// =============== //
// Newest articals //
// =============== //
if ('newest' == $what) :
    {
        // Standard tpl actions
        $GLOBALS['xoopsOption']['template_main'] = 'arms_view_nptr.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $arms_redirect = XOOPS_URL;
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_activated = 1 AND art_onhold = 0 ORDER BY art_posttime DESC LIMIT 0, 10';
        $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
        if ($xoopsDB->getRowsNum($result) > 0) :
            {
                display_nptr($result, 'ARMS_NEWEST', $arms_redirect);
            } else :
            {
                arms_error(_ME_ARMS_NO_ART_TO_SHOW, $arms_redirect);
            }
        endif;
    }
// ===================== //
// Most Popular articals //
// ===================== //
elseif ('popular' == $what) :
    {
        // Standard tpl actions
        $GLOBALS['xoopsOption']['template_main'] = 'arms_view_nptr.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $arms_redirect = XOOPS_URL;
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_activated = 1 AND art_onhold = 0 ORDER BY art_views DESC LIMIT 0, 10';
        $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
        if ($xoopsDB->getRowsNum($result) > 0) :
            {
                display_nptr($result, 'ARMS_POPULAR', $arms_redirect);
            } else :
            {
                arms_error(_ME_ARMS_NO_ART_TO_SHOW, $arms_redirect);
            }
        endif;
    }
// ================== //
// Top rated articals //
// ================== //
elseif ('toprated' == $what) :
    {
        // Standard tpl actions
        $GLOBALS['xoopsOption']['template_main'] = 'arms_view_nptr.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $arms_redirect = XOOPS_URL;
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_activated = 1 AND art_onhold = 0 ORDER BY (art_ratetotal/art_ratecount) DESC LIMIT 0, 10';
        $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
        if ($xoopsDB->getRowsNum($result) > 0) :
            {
                display_nptr($result, 'ARMS_TOP_RATED', $arms_redirect);
            } else :
            {
                arms_error(_ME_ARMS_NO_ART_TO_SHOW, $arms_redirect);
            }
        endif;
    }
endif;

// $xoopsTpl->debugging = true;
require XOOPS_ROOT_PATH . '/footer.php';
