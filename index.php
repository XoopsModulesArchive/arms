<?php

// ========================================================================== //
// ArMS = Artical Menagement System                                           //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Unit version   : 0.002                                                   //
//   Started        : 6:01:22 PM 9/9/2003                                     //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 12:06:00 PM 9/10/2003                                   //
//   Last update by : Ilija Studen                                            //
//                                                                            //
//   Change log:                                                              //
//   -----------                                                              //
//   - I reprogrammed entire file (because of categories and crossposting)    //
//     (11:23:07 PM 10/9/2003 by Ilija Studen)                                //
//   - waiting contents are counted and their count is displayed on index (if //
//     you are admin)                                                         //
//     (12:12:29 PM 10/11/2003 by Ilija Studen)                               //
//                                                                            //
// ========================================================================== //
// (c) 2003. by Ilija Studen                                                  //
//              (mail: ilija_studen@yahoo.com)                                //
//              (home: ilija.ionbee.net)                                      //
// ========================================================================== //

require __DIR__ . '/header.php';

// Standard tpl operations
$GLOBALS['xoopsOption']['template_main'] = 'arms_index.html';
require XOOPS_ROOT_PATH . '/header.php';

// I use XOOPS_URL for redirection. If there is errors you`ll be redirected to
// site index...

// Lets get categories...
$q_str = 'SELECT cat_title, cat_desc, cat_id FROM ' . $xoopsDB->prefix('arms_categories') . ' ORDER BY cat_order';
$result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), XOOPS_URL);
if ($xoopsDB->getRowsNum($result) < 1) :
    {
        arms_error(_ME_ARMS_NO_CATS, XOOPS_URL);
    }
endif;
$arms_cats = [];
$counter = 0;
while (false !== ($row = $xoopsDB->fetchArray($result))) :
    {
        $arms_cats[$counter]['title'] = $row['cat_title'];
        $arms_cats[$counter]['desc'] = $row['cat_desc'];

        // Get sections and arts count...
        $q_str = 'SELECT sec_id, sec_title, sec_desc, sec_image FROM ' . $xoopsDB->prefix('arms_sections') . ' WHERE cat_id=' . $row['cat_id'] . ' ORDER BY sec_order';
        $sec_res = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), XOOPS_URL);
        $arms_cats[$counter]['secs'] = [];
        $sec_counter = 0;
        $cat_arts_count = 0;
        while (false !== ($sec_row = $xoopsDB->fetchArray($sec_res))) :
            {
                $arms_cats[$counter]['secs'][$sec_counter]['id'] = $sec_row['sec_id'];
                $arms_cats[$counter]['secs'][$sec_counter]['title'] = $sec_row['sec_title'];
                $arms_cats[$counter]['secs'][$sec_counter]['desc'] = $sec_row['sec_desc'];
                // Sec images are queried if you want to place them on your tpls...
                $arms_cats[$counter]['secs'][$sec_counter]['image'] = $sec_row['sec_image'];
                $arms_cats[$counter]['secs'][$sec_counter]['mods'] = get_arms_moderators($sec_row['sec_id']);
                $arms_cats[$counter]['secs'][$sec_counter]['art_count'] = arms_get_articals($sec_row['sec_id'], true);
                $cat_arts_count += $arms_cats[$counter]['secs'][$sec_counter]['art_count'];
                $sec_counter++;
            }
        endwhile;
        $arms_cats[$counter]['arts_count'] = $cat_arts_count;
        $counter++;
    }
endwhile;

// Get waiting...
$q_str = 'SELECT count(art_id) AS waiting FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_activated = 0 AND art_onhold = 0';
$result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), XOOPS_URL);
$row = $xoopsDB->fetchArray($result);

$arms_lang_arr = get_arms_lang('ARMS_PAGE_INDEX');
$xoopsTpl->assign('arms_lang', $arms_lang_arr);
$xoopsTpl->assign('arms_cats', $arms_cats);
$xoopsTpl->assign('arms_waiting', $row['waiting']);
$xoopsTpl->assign('arms_config', $xoopsModuleConfig);

// For testing purpose only...
// $xoopsTpl->debugging = true;
// $xoopsTpl->force_compile = true;

// Including footer
require XOOPS_ROOT_PATH . '/footer.php';
