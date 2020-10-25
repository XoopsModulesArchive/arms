<?php

// ========================================================================== //
// ArMS = Artical Menagement System                                           //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Unit version   : 0.001                                                   //
//   Started        : 1:07:04 PM 10/11/2003                                   //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 2:15:38 PM 10/12/2003                                   //
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

function arms_show_jt($options)
{
    global $xoopsDB;

    // Init values

    $block['arms_secs'] = [];

    $block['arms_lang'] = [];

    // Get what we need...

    $q_str = 'SELECT sec_id, sec_title FROM ' . $xoopsDB->prefix('arms_sections') . ' ORDER BY cat_id AND sec_order';

    $result = $xoopsDB->query($q_str);

    $titles = [];

    $counter = 0;

    if ($xoopsDB->getRowsNum($result) > 0) :
        {
            while (false !== ($row = $xoopsDB->fetchArray($result))) :
                {
                    if (!is_orphened_sec($row['sec_id'])) :
                        {
                            $titles[$counter]['title'] = $row['sec_title'];
                            $counter++;
                        }
                    endif;
                }
            endwhile;
        }

    endif;

    // Get lang array

    $arms_lang_arr['go'] = _MD_ARMS_GO;

    $arms_lang_arr['show_all'] = _MD_ARMS_SHOW_ALL;

    // Assign

    $block['arms_secs'] = $titles;

    $block['arms_lang'] = $arms_lang_arr;

    return $block;
}

function arms_show_dtld_side($options)
{
    global $xoopsDB;

    // Init values

    $block['arms_cats'] = [];

    // Get what we need...

    $q_str = 'SELECT cat_id, cat_title FROM ' . $xoopsDB->prefix('arms_categories') . ' ORDER BY cat_order';

    $cat_res = $xoopsDB->query($q_str);

    $cats = [];

    $cat_counter = 0;

    if ($xoopsDB->getRowsNum($cat_res) > 0) :
        {
            while (false !== ($cat_row = $xoopsDB->fetchArray($cat_res))) :
                {
                    $cats[$cat_counter]['title'] = $cat_row['cat_title'];
                    $q_str = 'SELECT sec_id, sec_title FROM ' . $xoopsDB->prefix('arms_sections') . ' WHERE cat_id=' . $cat_row['cat_id'] . ' ORDER BY sec_order';
                    $result = $xoopsDB->query($q_str);
                    $secs = [];
                    $counter = 0;
                    if ($xoopsDB->getRowsNum($result) > 0) :
                        {
                            while (false !== ($row = $xoopsDB->fetchArray($result))) :
                                {
                                    $cats[$cat_counter]['secs'][$counter]['title'] = $row['sec_title'];
                                    $cats[$cat_counter]['secs'][$counter]['id'] = $row['sec_id'];
                                    $cats[$cat_counter]['secs'][$counter]['art_count'] = arms_get_articals($row['sec_id'], true);
                                    $counter++;
                                }
                            endwhile;
                        }
                    endif;
                    $cat_counter++;
                }
            endwhile;
        }

    endif;

    $block['arms_cats'] = $cats;

    return $block;
}

function arms_show_dtld_center($options)
{
    global $xoopsDB;

    // Init values

    $block['arms_cats'] = [];

    $block['arms_lang'] = [];

    // Get data...

    $q_str = 'SELECT cat_id, cat_title FROM ' . $xoopsDB->prefix('arms_categories') . ' ORDER BY cat_order';

    $cat_res = $xoopsDB->query($q_str);

    $cats = [];

    $cats_counter = 0;

    if ($xoopsDB->getRowsNum($cat_res) > 0) :
        {
            while (false !== ($cat_row = $xoopsDB->fetchArray($cat_res))) :
                {
                    $cats[$cats_counter]['title'] = $cat_row['cat_title'];
                    $cats[$cats_counter]['secs'] = [];
                    $q_str = 'SELECT sec_id, sec_title FROM ' . $xoopsDB->prefix('arms_sections') . ' WHERE cat_id=' . $cat_row['cat_id'] . ' ORDER BY sec_order';
                    $sec_res = $xoopsDB->query($q_str);
                    $counter = 0;
                    if ($xoopsDB->getRowsNum($sec_res) > 0) :
                        {
                            while (false !== ($sec_row = $xoopsDB->fetchArray($sec_res))) :
                                {
                                    $cats[$cats_counter]['secs'][$counter]['title'] = $sec_row['sec_title'];
                                    $cats[$cats_counter]['secs'][$counter]['id'] = $sec_row['sec_id'];
                                    $new_res = arms_get_articals($sec_row['sec_id'], false, 0, 1);
                                    if ($xoopsDB->getRowsNum($new_res) < 1) :
                                        {
                                            $cats[$cats_counter]['secs'][$counter]['new_id'] = -1;
                                        } else :
                                        {
                                            $new_row = $xoopsDB->fetchArray($new_res);
                                            $cats[$cats_counter]['secs'][$counter]['new_id'] = $new_row['art_id'];
                                            $cats[$cats_counter]['secs'][$counter]['new_title'] = $new_row['art_title'];
                                            $cats[$cats_counter]['secs'][$counter]['new_posttime'] = gmdate(_DATESTRING, xoops_getUserTimestamp($new_row['art_posttime']));
                                            $cats[$cats_counter]['secs'][$counter]['new_uid'] = $new_row['uid'];
                                            $q_str = 'SELECT uname FROM ' . $xoopsDB->prefix('users') . ' WHERE uid=' . $new_row['uid'];
                                            $user_res = $xoopsDB->query($q_str);
                                            if ($xoopsDB->getRowsNum($user_res) > 0) :
                                                {
                                                    $user_row = $xoopsDB->fetchArray($user_res);
                                                    $cats[$cats_counter]['secs'][$counter]['new_uname'] = $user_row['uname'];
                                                } else :
                                                {
                                                    $cats[$cats_counter]['secs'][$counter]['new_uname'] = _MD_ARMS_ANONYMOUS;
                                                }
                                            endif;
                                        }
                                    endif;
                                    $counter++;
                                }
                            endwhile;
                        }
                    endif;
                    $cats[$cats_counter]['secs_count'] = $xoopsDB->getRowsNum($sec_res);
                    $cats_counter++;
                }
            endwhile;
        }

    endif;

    $block['cats'] = $cats;

    $block['arms_lang']['by'] = _MD_ARMS_BY;

    $block['arms_lang']['no_arts'] = _MD_ARMS_NO_ARTS_MSG;

    return $block;
}
