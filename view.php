<?php

// ========================================================================== //
// ArMS = Article Management System                                           //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Unit version   : 0.003                                                   //
//   Started        : 10:23:00 AM 9/14/2003                                   //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 9:49:09 PM 10/11/2003                                   //
//   Last update by : Ilija Studen                                            //
//                                                                            //
//   Change log:                                                              //
//   -----------                                                              //
//   NOTE: If you are changing this file please take a look at                //
//         changelog.txt                                                      //
//                                                                            //
// ========================================================================== //
// (c) 2003. by Ilija Studen                                                  //
//              (mail: ilija_studen@yahoo.com)                                //
//              (home: ilija.ionbee.net)                                      //
// ========================================================================== //

function arms_page_limits($page)
{
    global $xoopsModuleConfig;

    $return = [];

    $page = (int)$page;

    if (1 == $page) :
        {
            $return['down'] = 0;
            $return['up'] = $xoopsModuleConfig['art_per_page'];
        } else :
        {
            $return['down'] = (($page - 1) * $xoopsModuleConfig['art_per_page']);
            $return['up'] = $xoopsModuleConfig['art_per_page'];
        }

    endif;

    return $return;
}  // end arms_page_limits

function arms_get_waiting($result)
{
    global $xoopsDB;

    $counter = 0;

    $waiting = [];

    while (false !== ($sec_row = $xoopsDB->fetchArray($result))) {
        $waiting[$counter]['sec_title'] = $sec_row['sec_title'];

        $waiting[$counter]['sec_id'] = $sec_row['sec_id'];

        // Lets get articals

        $q_str = 'SELECT art_id, art_title, uid FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_activated = 0 AND sec_id=' . $sec_row['sec_id'];

        if ($art_result = $xoopsDB->query($q_str)) :
            {
                $arms_art = [];
                if ($xoopsDB->getRowsNum($art_result) > 0) :
                    {
                        $second_counter = 0;
                        while (false !== ($row = $xoopsDB->fetchArray($art_result))) {
                            $q_str = 'SELECT uname FROM ' . $xoopsDB->prefix('users') . ' WHERE uid=' . $row['uid'];

                            $user_row = arms_fetch_first($q_str, _ME_ARMS_USER_DOESNT_EXISTS);

                            $arms_art[$second_counter] = $row;

                            $arms_art[$second_counter]['uname'] = $user_row['uname'];

                            $second_counter++;
                        }
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }

        endif;

        $waiting[$counter]['art_count'] = count($arms_art);

        $waiting[$counter]['articals'] = $arms_art;

        $counter++;
    } // end while

    return $waiting;
} // end arms_get_waiting

require __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';

if (isset($_GET['w'])) :
    {
        $what = (string)$_GET['w'];
    } else :
    {
        $what = 'art';
    }
endif;

// ============ //
// View Section //
// ============ //
if ('sec' == $what) :
    {
        // Get index...
        if (isset($_GET['idx'])) :
            {
                $idx = (int)$_GET['idx'];
            } else :
            {
                arms_error(_ME_ARMS_PARAMS);
            }
        endif;

        $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/';

        if (is_object($xoopsUser)) :
            {
                $uid = $xoopsUser->uid();
            } else :
            {
                $uid = -1;
            }
        endif;

        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_view_section.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // Page handling...
        $arms_on_page = 1;
        if (isset($_GET['page'])) :
            {
                $arms_on_page = (int)$_GET['page'];
            }
        endif;

        // Lets get articals count (for page handling)
        $articals_count = arms_get_articals($idx, true, -1, -1, $arms_redirect);

        if ($articals_count < 1) :
            {
                arms_error(_ME_ARMS_NO_ART_IN_SEC);
            }
        endif;

        // Endless loop fix (7:22:59 PM 10/20/2003 by Ilija Studen)
        if ($xoopsModuleConfig['art_per_page'] < 1) :
            {
                $xoopsModuleConfig['art_per_page'] = 1;
            }
        endif;

        // Lets handle page...
        $counter = 0;
        $arms_pages = [];
        $tmp_art_count = $articals_count;
        do {
            $tmp_page = $counter + 1;

            $arms_pages[$counter]['page_num'] = $tmp_page;

            $arms_pages[$counter]['is_current'] = false;

            if ($arms_on_page == $tmp_page) :
                {
                    $arms_pages[$counter]['is_current'] = true;
                }

            endif;

            // Add ',' after it or not...

            $arms_pages[$counter]['is_last'] = false;

            if ($tmp_art_count < ($xoopsModuleConfig['art_per_page'] * 2)) :
                {
                    $arms_pages[$counter]['is_last'] = true;
                }

            endif;

            $tmp_art_count -= $xoopsModuleConfig['art_per_page'];

            $counter++;
        } while ($tmp_art_count >= $xoopsModuleConfig['art_per_page']);

        $arms_current_page['is_first'] = false;
        if (1 == $arms_on_page) :
            {
                $arms_current_page['is_first'] = true;
            } else :
            {
                $arms_current_page['prev_link'] = XOOPS_URL . "/modules/arms/view.php?w=sec&idx=$idx&page=" . ($arms_on_page - 1);
                $arms_current_page['first_link'] = XOOPS_URL . "/modules/arms/view.php?w=sec&idx=$idx";
            }
        endif;

        $arms_current_page['is_last'] = false;
        if (($arms_on_page * $xoopsModuleConfig['art_per_page']) >= $articals_count) :
            {
                $arms_current_page['is_last'] = true;
            } else :
            {
                $arms_current_page['next_link'] = XOOPS_URL . "/modules/arms/view.php?w=sec&idx=$idx&page=" . ($arms_on_page + 1);
                $arms_current_page['last_link'] = XOOPS_URL . "/modules/arms/view.php?w=sec&idx=$idx&page=" . count($arms_pages);
            }
        endif;

        // Lets get section data...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_sections') . ' WHERE sec_id=' . $idx;
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_MI_ARMS_NO_SECTIONS);
                    }
                endif;
                $sec_row = $xoopsDB->fetchArray($result);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;

        // Other sections (for jump to)
        $q_str = 'SELECT sec_title FROM ' . $xoopsDB->prefix('arms_sections') . ' WHERE sec_id <> ' . $sec_row['sec_id'] . ' ORDER BY cat_id AND sec_order';
        $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
        $jump_to = [];
        $counter = 0;
        if ($xoopsDB->getRowsNum($result) > 0) :
            {
                while (false !== ($row = $xoopsDB->fetchArray($result))) :
                    {
                        $jump_to[$counter]['title'] = $row['sec_title'];
                        $counter++;
                    }
                endwhile;
            }
        endif;

        $arms_limit = arms_page_limits($arms_on_page);

        // Now lets pull page articals data from database...

        if ($result = arms_get_articals($idx, false, $arms_limit['down'], $arms_limit['up'], $arms_redirect)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_NO_ART_ON_PAGE);
                    }
                endif;

                $arms_articals = [];
                $counter = 0;
                while (false !== ($art_row = $xoopsDB->fetchArray($result))) {
                    $my_ts = MyTextSanitizer::getInstance();

                    $arms_articals[$counter]['id'] = $art_row['art_id'];

                    $arms_articals[$counter]['uip'] = $art_row['uip'];

                    $arms_articals[$counter]['title'] = $my_ts->htmlSpecialChars($art_row['art_title']);

                    $arms_articals[$counter]['desc'] = $my_ts->displayTarea($art_row['art_desc'], 0, 0, 0);

                    $arms_articals[$counter]['posttime'] = gmdate(_DATESTRING, xoops_getUserTimestamp($art_row['art_posttime']));

                    $arms_articals[$counter]['rate'] = arms_get_rate($art_row['art_ratetotal'], $art_row['art_ratecount']);

                    $arms_articals[$counter]['updatecount'] = $art_row['art_updatecount'];

                    $arms_articals[$counter]['is_activated'] = $art_row['art_activated'];

                    $arms_articals[$counter]['perms'] = arms_get_artical_perms($art_row['art_id'], $uid, $arms_redirect);

                    $arms_articals[$counter]['level_id'] = $art_row['level_id'];

                    // Query level title

                    $q_str = 'SELECT level_name, level_image FROM ' . $xoopsDB->prefix('arms_articals_levels') . ' WHERE level_id=' . $art_row['level_id'];

                    if ($tmp_res = $xoopsDB->query($q_str)) :
                        {
                            if ($xoopsDB->getRowsNum($tmp_res) < 1) :
                                {
                                    arms_error(_ME_ARMS_NO_LEVELS);
                                }
                            endif;
                            $tmp_row = $xoopsDB->fetchArray($tmp_res);
                            $arms_articals[$counter]['level_title'] = $tmp_row['level_name'];
                            $arms_articals[$counter]['level_image'] = $tmp_row['level_image'];
                        } else :
                        {
                            arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                        }

                    endif;

                    $arms_articals[$counter]['user_id'] = $art_row['uid'];

                    // Query username

                    $q_str = 'SELECT uname FROM ' . $xoopsDB->prefix('users') . ' WHERE uid=' . $art_row['uid'];

                    if ($tmp_res = $xoopsDB->query($q_str)) :
                        {
                            if ($xoopsDB->getRowsNum($tmp_res) < 1) :
                                {
                                    arms_error(_ME_ARMS_USER_DOESNT_EXISTS);
                                }
                            endif;
                            $tmp_row = $xoopsDB->fetchArray($tmp_res);
                            $arms_articals[$counter]['user_name'] = $tmp_row['uname'];
                        } else :
                        {
                            arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                        }

                    endif;

                    // Quiry pages count...

                    $q_str = 'SELECT page_id FROM ' . $xoopsDB->prefix('arms_pages') . ' WHERE art_id=' . $art_row['art_id'];

                    if ($tmp_res = $xoopsDB->query($q_str)) :
                        {
                            $arms_articals[$counter]['pages'] = $xoopsDB->getRowsNum($tmp_res);
                        } else :
                        {
                            arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                        }

                    endif;

                    $counter++;
                } // End while...
            } else :
            {
                arms_error(_ME_ARMS_NO_ART_ON_PAGE, $arms_redirect);
            }
        endif;

        // Lets assign data... Uh, finally!
        $arms_lang_arr = get_arms_lang('ARMS_VIEW_SECTION');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_section', $sec_row);
        $xoopsTpl->assign('arms_articals_count', $articals_count);
        $xoopsTpl->assign('arms_current_page', $arms_current_page);
        $xoopsTpl->assign('arms_section_pages', $arms_pages);
        $xoopsTpl->assign('arms_articals', $arms_articals);
        $xoopsTpl->assign('arms_jump_to', $jump_to);
        $xoopsTpl->assign('arms_jt_count', count($jump_to));
        $xoopsTpl->assign('arms_config', $xoopsModuleConfig);
    }
// ============ //
// View artical //
// ============ //
elseif ('art' == $what) :
    {
        // Get index...
        if (isset($_GET['idx'])) :
            {
                $idx = (int)$_GET['idx'];
            } else :
            {
                arms_error(_ME_ARMS_PARAMS);
            }
        endif;

        $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname();

        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_view_artical.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // Update views...
        //$q_str = 'UPDATE ' . $xoopsDB->prefix('arms_articals') . " SET art_views = art_views + 1 WHERE art_id=$idx";
        //if(!$xoopsDB->query($q_str)):
        //  { arms_error( sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect ); }
        //endif;

        // Page handling...
        $arms_on_page = 1;
        if (isset($_GET['page'])) :
            {
                $arms_on_page = (int)$_GET['page'];
            }
        endif;

        $my_ts = MyTextSanitizer::getInstance();

        if (is_object($xoopsUser)) :
            {
                $user_perms = arms_get_artical_perms($idx, $xoopsUser->uid(), $arms_redirect);
            } else :
            {
                $user_perms = arms_init_perms();
                $user_perms['can_view_perms'] = false;
                $user_perms['can_view_cross'] = false;
            }
        endif;

        // Lets get artical data... for start...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_id=' . $idx;
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_ARTICAL_DOESNT_EXISTS, $arms_redirect);
                    }
                endif;
                $art_row = $xoopsDB->fetchArray($result);

                // Redirect...
                $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/view.php?w=sec&idx=' . $art_row['sec_id'];

                $art_row['art_title'] = $my_ts->htmlSpecialChars($art_row['art_title']);
                $art_row['art_desc'] = $my_ts->displayTarea($art_row['art_desc'], 0, 0, 0);
                $art_row['art_rate'] = arms_get_rate($art_row['art_ratetotal'], $art_row['art_ratecount']);
                $art_row['art_posttime'] = gmdate(_DATESTRING, xoops_getUserTimestamp($art_row['art_posttime']));
                // Get level name
                $q_str = 'SELECT level_name FROM ' . $xoopsDB->prefix('arms_articals_levels') . ' WHERE level_id=' . $art_row['level_id'];
                if ($tmp_res = $xoopsDB->query($q_str)) :
                    {
                        if ($xoopsDB->getRowsNum($tmp_res) < 1) :
                            {
                                arms_error(_ME_ARMS_NO_LEVELS, $arms_redirect);
                            }
                        endif;
                        $tmp_row = $xoopsDB->fetchArray($tmp_res);
                        $art_row['level_title'] = $tmp_row['level_name'];
                    } else :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
                    }
                endif;
                // Get section name
                $q_str = 'SELECT sec_title, sec_id FROM ' . $xoopsDB->prefix('arms_sections') . ' WHERE sec_id=' . $art_row['sec_id'];
                if ($tmp_res = $xoopsDB->query($q_str)) :
                    {
                        if ($xoopsDB->getRowsNum($tmp_res) < 1) :
                            {
                                arms_error(_MI_ARMS_NO_SECTIONS, $arms_redirect);
                            }
                        endif;
                        $tmp_row = $xoopsDB->fetchArray($tmp_res);
                        $art_row['sec_title'] = $tmp_row['sec_title'];
                    } else :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;

        // Get jump to sections
        $q_str = 'SELECT sec_title FROM ' . $xoopsDB->prefix('arms_sections') . ' ORDER BY cat_id AND sec_order';
        $jt_res = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
        $arms_jump_to = [];
        $jt_counter = 0;
        if ($xoopsDB->getRowsNum($jt_res) > 0) :
            {
                while (false !== ($jt_row = $xoopsDB->fetchArray($jt_res))) :
                    {
                        $arms_jump_to[$jt_counter]['title'] = $jt_row['sec_title'];
                        $jt_counter++;
                    }
                endwhile;
            }
        endif;

        // Now lets get pages...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_pages') . " WHERE art_id=$idx ORDER BY page_order";
        if ($result = $xoopsDB->query($q_str)) :
            {
                $arms_artical_pages = $xoopsDB->getRowsNum($result);
                if ($arms_artical_pages < 1) :
                    {
                        arms_error(_ME_ARMS_NO_PAGES_IN_ART, $arms_redirect);
                    }
                endif;

                $arms_current_page = [];
                $arms_pages = [];
                $counter = 0;
                while (false !== ($row = $xoopsDB->fetchArray($result))) :
                    {
                        // Notice that I don`t use page_order field to determin current page...
                        // This way provides that pages are indexed from 1 .. page count even
                        // if values of page_order field are not syncronised!
                        if (($arms_on_page - 1) == $counter) :
                            {
                                $arms_current_page = $row;

                                $arms_current_page['is_first'] = false;
                                if (0 == $counter) :
                                    {
                                        $arms_current_page['is_first'] = true;
                                    } else :
                                    {
                                        $arms_current_page['prev_page_link'] = XOOPS_URL . "/modules/arms/view.php?w=art&idx=$idx&page=" . ($arms_on_page - 1);
                                    }
                                endif;

                                $arms_current_page['is_last'] = false;
                                if ($arms_artical_pages == ($counter + 1)) :
                                    {
                                        $arms_current_page['is_last'] = true;
                                    } else :
                                    {
                                        $arms_current_page['next_page_link'] = XOOPS_URL . "/modules/arms/view.php?w=art&idx=$idx&page=" . ($arms_on_page + 1);
                                    }
                                endif;

                                $arms_current_page['page_num'] = $counter + 1;
                                $arms_current_page['page_title'] = $my_ts->htmlSpecialChars($arms_current_page['page_title']);
                                $arms_current_page['page_desc'] = $my_ts->displayTarea($arms_current_page['page_desc'], 0, 0, 0);
                                $arms_current_page['page_text'] = $my_ts->displayTarea($arms_current_page['page_text'], $arms_current_page['page_allow_html'], $arms_current_page['page_allow_emotions'], $arms_current_page['page_allow_bbcode']);
                                $arms_current_page['page_posttime'] = gmdate(_DATESTRING, xoops_getUserTimestamp($arms_current_page['page_posttime']));
                                // Get level name
                                $q_str = 'SELECT level_name FROM ' . $xoopsDB->prefix('arms_articals_levels') . ' WHERE level_id=' . $arms_current_page['level_id'];
                                if ($tmp_res = $xoopsDB->query($q_str)) :
                                    {
                                        if ($xoopsDB->getRowsNum($tmp_res) < 1) :
                                            {
                                                arms_error(_ME_ARMS_NO_LEVELS, $arms_redirect);
                                            }
                                        endif;
                                        $tmp_row = $xoopsDB->fetchArray($tmp_res);
                                        $arms_current_page['page_level_title'] = $tmp_row['level_name'];
                                    } else :
                                    {
                                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
                                    }
                                endif;
                                // Get user name...
                                $q_str = 'SELECT uname FROM ' . $xoopsDB->prefix('users') . ' WHERE uid=' . $arms_current_page['uid'];
                                if ($tmp_res = $xoopsDB->query($q_str)) :
                                    {
                                        if ($xoopsDB->getRowsNum($tmp_res) < 1) :
                                            {
                                                arms_error(_ME_ARMS_USER_DOESNT_EXISTS, $arms_redirect);
                                            }
                                        endif;
                                        $tmp_row = $xoopsDB->fetchArray($tmp_res);
                                        $arms_current_page['user_name'] = $tmp_row['uname'];
                                    } else :
                                    {
                                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
                                    }
                                endif;

                                // Update views...
                                inc_field_value('arms_pages', 'page_views', $arms_current_page['page_id'], 'page_id');
                                $arms_pages[$counter]['id'] = $row['page_id'];
                                $arms_pages[$counter]['page_num'] = $counter + 1;
                                $arms_pages[$counter]['title'] = $arms_current_page['page_title'];
                                $arms_pages[$counter]['is_current'] = true;
                                $counter++;
                            } else :
                            {
                                $arms_pages[$counter]['id'] = $row['page_id'];
                                $arms_pages[$counter]['page_num'] = $counter + 1;
                                $arms_pages[$counter]['title'] = $my_ts->htmlSpecialChars($row['page_title']);
                                $arms_pages[$counter]['link'] = XOOPS_URL . "/modules/arms/view.php?w=art&idx=$idx&page=" . ($counter + 1);
                                $arms_pages[$counter]['is_current'] = false;
                                $counter++;
                            }
                        endif;
                    }
                endwhile;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;
        $arms_lang_arr = get_arms_lang('ARMS_VIEW_ART');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_artical', $art_row);
        $xoopsTpl->assign('arms_page', $arms_current_page);
        $xoopsTpl->assign('arms_pages', $arms_pages);
        $xoopsTpl->assign('arms_jump_to', $arms_jump_to);
        $xoopsTpl->assign('arms_jt_count', count($arms_jump_to));
        $xoopsTpl->assign('arms_perms', $user_perms);
        $xoopsTpl->assign('arms_config', $xoopsModuleConfig);

        require XOOPS_ROOT_PATH . '/include/comment_view.php';
    }
// =========== //
// My Articals //
// =========== //
elseif ('myart' == $what) :
    {
        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_view_my.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname();

        if (!is_object($xoopsUser)) :
            {
                arms_error(_ME_ARMS_REG_ONLY, $arms_redirect);
            }
        endif;
        // Lets get them...
        $q_str = 'SELECT art_id, art_title, art_activated FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE uid=' . $xoopsUser->uid() . ' ORDER BY art_posttime';
        if ($result = $xoopsDB->query($q_str)) :
            {
                $arms_my_art = [];
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        $counter = 0;
                        while (false !== ($row = $xoopsDB->fetchArray($result))) :
                            {
                                $perms = arms_get_artical_perms($row['art_id'], $xoopsUser->uid(), $arms_redirect);
                                $arms_my_art[$counter] = $row;
                                $arms_my_art[$counter]['perms'] = $perms;
                                $counter++;
                            }
                        endwhile;
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;

        // Get with permissions...
        $q_str = 'SELECT DISTINCT art_id FROM ' . $xoopsDB->prefix('arms_permissions') . ' WHERE uid=' . $xoopsUser->uid() . ' AND (can_edit_pages = 1 OR can_add_pages = 1 OR can_delete_pages = 1)';
        $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
        $arms_perm = [];
        $in = '';
        if ($xoopsDB->getRowsNum($result) > 0) :
            {
                $in = '(';
                $is_first = true;
                while (false !== ($row = $xoopsDB->fetchArray($result))) :
                    {
                        if ($is_first) :
                            {
                                $is_first = false;
                            } else :
                            {
                                $in .= ',';
                            }
                        endif;
                        $in .= $row['art_id'];
                    }
                endwhile;
                $in .= ')';
            }
        endif;
        if ('' != $in) :
            {
                $q_str = 'SELECT art_id, art_title, art_activated FROM ' . $xoopsDB->prefix('arms_articals') . " WHERE art_id IN $in ORDER BY art_posttime";
                $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        $counter = 0;
                        while (false !== ($row = $xoopsDB->fetchArray($result))) :
                            {
                                $arms_perm[$counter] = $row;
                                $counter++;
                            }
                        endwhile;
                    }
                endif;
            }
        endif;

        $arms_lang_arr = get_arms_lang('ARMS_VIEW_MY');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_articals', $arms_my_art);
        $xoopsTpl->assign('arms_extra', $arms_perm);
    }
// =============== //
// View Level Info //
// =============== //
elseif ('level' == $what) :
    {
        // Get index...
        if (isset($_GET['idx'])) :
            {
                $idx = (int)$_GET['idx'];
            } else :
            {
                arms_error(_ME_ARMS_PARAMS);
            }
        endif;

        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_view_level.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // Lets get level data...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals_levels') . ' WHERE level_id=' . $idx;
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_LEVEL_DOESNT_EXISTS);
                    }
                endif;
                $level_row = $xoopsDB->fetchArray($result);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
        $arms_lang_arr = get_arms_lang('ARMS_VIEW_LEVEL');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_level', $level_row);
    }
// ================ //
// Waiting contents //
// ================ //
elseif ('waiting' == $what) :
    {
        // Get index...
        if (isset($_GET['idx'])) :
            {
                $idx = (string)$_GET['idx'];
            } else :
            {
                arms_error(_ME_ARMS_PARAMS);
            }
        endif;

        $arms_redirect = XOOPS_URL . '/modules/arms/';
        $arms_is_moderator = false;
        $arms_is_admin = false;

        // Check index and persmissions...
        if (is_numeric($idx)) :
            {
                $idx = (int)$idx;
                if (is_object($xoopsUser) and $xoopsModuleConfig['mod_activate']) :
                    {
                        $arms_is_moderator = is_section_moderator($idx, $xoopsUser->uid());
                    }
                endif;
                $arms_redirect = XOOPS_URL . '/modules/arms/view.php?w=sec&idx=' . $idx;
            } else :
            {
                if ('all' == $idx) :
                    {
                        if (is_object($xoopsUser) and $xoopsUser->isAdmin($xoopsModule->mid())) :
                            {
                                $arms_is_admin = true;
                            }
                        endif;
                    } else :
                    {
                        arms_error(_ME_ARMS_PARAMS, $arms_redirect);
                    }
                endif;
            }
        endif;

        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_view_waiting.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // ======================== //
        // Section waiting contents //
        // ======================== //
        if ($arms_is_moderator) :
            {
                $q_str = 'SELECT sec_title, sec_id FROM ' . $xoopsDB->prefix('arms_sections') . ' WHERE sec_id=' . $idx;
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        if ($xoopsDB->getRowsNum($result) < 1) :
                            {
                                arms_error(_MI_ARMS_NO_SECTIONS, $arms_redirect);
                            }
                        endif;

                        $arms_waiting = arms_get_waiting($result);
                    } else :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            }
        // ===================== //
        // All waiting contenths //
        // ===================== //
        elseif ($arms_is_admin) :
            {
                $q_str = 'SELECT sec_title, sec_id FROM ' . $xoopsDB->prefix('arms_sections') . ' ORDER BY sec_order';
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        if ($xoopsDB->getRowsNum($result) < 1) :
                            {
                                arms_error(_MI_ARMS_NO_SECTIONS, $arms_redirect);
                            }
                        endif;

                        $arms_waiting = arms_get_waiting($result);
                    } else :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            } else :
            {
                arms_error(_ME_ARMS_ACCESS, $arms_redirect);
            }
        endif;

        $arms_lang_arr = get_arms_lang('ARMS_VIEW_WAITING');
        $xoopsTpl->assign('arms_waiting', $arms_waiting);
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
    }
// ============ //
// View Votelog //
// ============ //
elseif ('votelog' == $what) :
    {
        // Get index...
        if (isset($_GET['idx'])) :
            {
                $idx = (string)$_GET['idx'];
            } else :
            {
                arms_error(_ME_ARMS_PARAMS);
            }
        endif;

        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_view_votelog.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // Redirection...
        $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname(); // Initial value...
        $q_str = 'SELECT sec_id FROM ' . $xoopsDB->prefix('arms_articals') . " WHERE art_id=$idx";
        $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
        if ($xoopsDB->getRowsNum($result) > 0) :
            {
                $row = $xoopsDB->fetchArray($result);
                $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/view.php?w=sec&idx=' . $row['sec_id'];
            }
        endif;

        // Full view (admin, mods) or just stats...
        $view_ip = false;
        if (is_object($xoopsUser)) :
            {
                $user_perms = arms_get_artical_perms($idx, $xoopsUser->uid(), $arms_redirect);
                $view_ip = $user_perms['can_view_uip'];
            }
        endif;

        // Lets get data...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_votelog') . " WHERE art_id=$idx";
        $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
        if ($xoopsDB->getRowsNum($result) < 1) :
            {
                arms_error(_MM_ARMS_NO_VOTES, $arms_redirect);
            }
        endif;
        $arms_vote_log = [];
        $counter = 0;
        while (false !== ($row = $xoopsDB->fetchArray($result))) :
            {
                $arms_vote_log[$counter] = $row;
                $arms_vote_log[$counter]['posttime'] = gmdate(_DATESTRING, xoops_getUserTimestamp($row['vote_time']));
                $q_str = 'SELECT uname FROM ' . $xoopsDB->prefix('users') . ' WHERE uid=' . $row['uid'];
                $user_res = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
                if ($xoopsDB->getRowsNum($user_res) < 1) :
                    {
                        $arms_vote_log[$counter]['uname'] = '???';
                    } else :
                    {
                        $user_row = $xoopsDB->fetchArray($user_res);
                        $arms_vote_log[$counter]['uname'] = $user_row['uname'];
                    }
                endif;
                $counter++;
            }
        endwhile;

        $q_str = 'SELECT art_ratetotal, art_ratecount FROM ' . $xoopsDB->prefix('arms_articals') . " WHERE art_id=$idx";
        $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
        if ($xoopsDB->getRowsNum($result) < 1) :
            {
                arms_error(_MM_ARMS_NO_VOTES, $arms_redirect);
            }
        endif;
        $arms_votes = $xoopsDB->fetchArray($result);
        $arms_rate = arms_get_rate($arms_votes['art_ratetotal'], $arms_votes['art_ratecount']);
        if (0 == $arms_rate) :
            {
                arms_error(_MM_ARMS_NO_VOTES, $arms_redirect);
            }
        endif;

        $arms_lang_arr = get_arms_lang('ARMS_VIEW_VOTELOG');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_show_log', $view_ip);
        $xoopsTpl->assign('arms_votelog', $arms_vote_log);
        $xoopsTpl->assign('arms_total_votes', $arms_votes['art_ratecount'] - 1);
        $xoopsTpl->assign('arms_rate', $arms_rate);
    } else :
    {
        redirect_header(XOOPS_URL . '/modules/arms/index.php', 1, _ME_ARMS_PARAMS);
    }
endif;

// For testing purpose only...
// $xoopsTpl->debugging = true;
// $xoopsTpl->force_compile = true;

// Including footer
require XOOPS_ROOT_PATH . '/footer.php';
