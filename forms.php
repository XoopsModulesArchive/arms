<?php

// ========================================================================== //
// ArMS = Artical Menagment System                                            //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Version:       : 0.1                                                     //
//   Started        : 7:16:02 PM 9/12/2003                                    //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 12:06:00 PM 9/10/2003                                   //
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

require __DIR__ . '/header.php';
require XOOPS_ROOT_PATH . '/modules/arms/includes/functions_code.php';

// Are you registered???
if (!is_object($xoopsUser)) :
    {
        arms_error(_ME_ARMS_REG_ONLY);
    }
endif;

if (isset($_GET['w'])) :
    {
        $what = (string)$_GET['w'];
    } else :
    {
        $what = '';
    }
endif;

// =========== //
// Add artical //
// =========== //
if ('addart' == $what) :
    {
        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_forms_add_artical.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // Lets get sections
        $q_str = 'SELECT sec_title FROM ' . $xoopsDB->prefix('arms_sections') . ' ORDER BY sec_title';
        if ($result = $xoopsDB->query($q_str)) :
            {
                $arms_sections = [];
                $counter = 0;

                // No sections???
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_MI_ARMS_NO_SECTIONS);
                    }
                endif;

                while (false !== ($row = $xoopsDB->fetchArray($result))) {
                    $arms_sections[$counter]['title'] = $row['sec_title'];

                    $counter++;
                }
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets get levels
        $q_str = 'SELECT level_name FROM ' . $xoopsDB->prefix('arms_articals_levels') . ' ORDER BY level_name';
        if ($result = $xoopsDB->query($q_str)) :
            {
                $arms_levels = [];
                $counter = 0;
                // No levels???
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_NO_LEVELS);
                    }
                endif;

                while (false !== ($row = $xoopsDB->fetchArray($result))) {
                    $arms_levels[$counter]['title'] = $row['level_name'];

                    $counter++;
                }
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        $arms_lang_arr = get_arms_lang('ARMS_ADD_ARTICAL');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_sections', $arms_sections);
        $xoopsTpl->assign('arms_levels', $arms_levels);
    }
// ============ //
// Edit artical //
// ============ //
elseif ('editart' == $what) :
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
        $GLOBALS['xoopsOption']['template_main'] = 'arms_forms_edit_artical.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // Lets get artical data...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_id=' . $idx;
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_ARTICAL_DOESNT_EXISTS);
                    }
                endif;

                $arms_art_row = $xoopsDB->fetchArray($result);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Author or coautor or error
        if (($arms_art_row['uid'] != $xoopsUser->uid()) && !$xoopsUser->isAdmin()) :
            {
                $q_str = 'SELECT p_id FROM ' . $xoopsDB->prefix('arms_permissions') . " WHERE art_id=$idx AND uid=" . $xoopsUser->uid();
                $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_ACCESS);
                    }
                endif;
            }
        endif;

        // Lets find section data...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_sections') . ' WHERE sec_id=' . $arms_art_row['sec_id'];
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_SECTION_DOESNT_EXISTS);
                    }
                endif;

                $arms_sec_row = $xoopsDB->fetchArray($result);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets find level data...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals_levels') . ' WHERE level_id=' . $arms_art_row['level_id'];
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_LEVEL_DOESNT_EXISTS);
                    }
                endif;

                $arms_level_row = $xoopsDB->fetchArray($result);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets get user data...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('users') . ' WHERE uid=' . $arms_art_row['uid'];
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_USER_DOESNT_EXISTS);
                    }
                endif;

                $arms_user_row = $xoopsDB->fetchArray($result);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets get pages
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_pages') . ' WHERE art_id=' . $idx;
        if ($result = $xoopsDB->query($q_str)) :
            {
                $arms_pages = [];
                $counter = 0;
                while (false !== ($row = $xoopsDB->fetchArray($result))) {
                    $arms_pages[$counter] = $row;

                    $counter++;
                }
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets get permissions
        $q_str = 'SELECT p_id, uid, can_edit_pages, can_add_pages, can_delete_pages FROM ' . $xoopsDB->prefix('arms_permissions') . " WHERE art_id=$idx";
        if ($result = $xoopsDB->query($q_str)) :
            {
                $arms_permissions = [];
                $counter = 0;
                while (false !== ($row = $xoopsDB->fetchArray($result))) :
                    {
                        // User name
                        $q_str = 'SELECT uname FROM ' . $xoopsDB->prefix('users') . ' WHERE uid=' . $row['uid'];
                        if ($user_res = $xoopsDB->query($q_str)) :
                            {
                                if ($xoopsDB->getRowsNum($user_res) < 1) :
                                    {
                                        $user_name = _ME_ARMS_USER_DOESNT_EXISTS;
                                    } else :
                                    {
                                        $user_row = $xoopsDB->fetchArray($user_res);
                                        $user_name = $user_row['uname'];
                                    }
                                endif;
                            } else :
                            {
                                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                            }
                        endif;
                        $arms_permissions[$counter]['pid'] = $row['p_id'];
                        $arms_permissions[$counter]['uid'] = $row['uid'];
                        $arms_permissions[$counter]['uname'] = $user_name;
                        $arms_permissions[$counter]['can_edit'] = $row['can_edit_pages'];
                        $arms_permissions[$counter]['can_add'] = $row['can_add_pages'];
                        $arms_permissions[$counter]['can_delete'] = $row['can_delete_pages'];
                        $counter++;
                    }
                endwhile;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets get cross sections
        $q_str = 'SELECT sec_id, sec_title FROM ' . $xoopsDB->prefix('arms_sections') . ' ORDER BY cat_id AND sec_order';
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_MI_ARMS_NO_SECTIONS);
                    }
                endif;
                $arms_cross = [];
                $counter = 0;
                while (false !== ($row = $xoopsDB->fetchArray($result))) :
                    {
                        if ($row['sec_id'] != $arms_sec_row['sec_id']) :
                            {
                                $arms_cross[$counter]['id'] = $row['sec_id'];
                                $arms_cross[$counter]['title'] = $row['sec_title'];
                                $q_str = 'SELECT cs_id FROM ' . $xoopsDB->prefix('arms_cross_section') . ' WHERE sec_id=' . $row['sec_id'] . " AND art_id = $idx";
                                if ($cross_res = $xoopsDB->query($q_str)) :
                                    {
                                        if ($xoopsDB->getRowsNum($cross_res) > 0) :
                                            {
                                                $arms_cross[$counter]['crossed'] = 1;
                                            } else :
                                            {
                                                $arms_cross[$counter]['crossed'] = 0;
                                            }
                                        endif;
                                    } else :
                                    {
                                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                                    }
                                endif;
                                $counter++;
                            }
                        endif;
                    }
                endwhile;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        $user_perms = arms_get_artical_perms($idx, $xoopsUser->uid());

        $arms_lang_arr = get_arms_lang('ARMS_EDIT_ARTICAL');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_artical', $arms_art_row);
        $xoopsTpl->assign('arms_section', $arms_sec_row);
        $xoopsTpl->assign('arms_level', $arms_level_row);
        $xoopsTpl->assign('arms_user', $arms_user_row);
        $xoopsTpl->assign('arms_pages', $arms_pages);
        $xoopsTpl->assign('arms_permissions', $arms_permissions);
        $xoopsTpl->assign('arms_perms_count', count($arms_permissions));
        $xoopsTpl->assign('arms_cross', $arms_cross);
        $xoopsTpl->assign('arms_cross_count', count($arms_cross));
        $xoopsTpl->assign('user_perms', $user_perms);
    }
// ================= //
// Edit Artical Data //
// ================= //
elseif ('editartdata' == $what) :
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

        $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/forms.php?w=editart&idx=$idx";

        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_forms_edit_artical_data.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $user_perms = arms_get_artical_perms($idx, $xoopsUser->uid(), $arms_redirect);
        if (!$user_perms['can_edit_art']) :
            {
                arms_error(_ME_ARMS_ACCESS, $arms_redirect);
            }
        endif;

        // Lets get artical data...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_id=' . $idx;
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_ARTICAL_DOESNT_EXISTS, $arms_redirect);
                    }
                endif;

                $art_row = $xoopsDB->fetchArray($result);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;

        // Lets get sections...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_sections') . ' ORDER BY sec_title';
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_MI_ARMS_NO_SECTIONS, $arms_redirect);
                    }
                endif;
                $arms_sections = [];
                $counter = 0;
                while (false !== ($row = $xoopsDB->fetchArray($result))) {
                    if ($row['sec_id'] == $art_row['sec_id']) :
                        {
                            $art_section = $row['sec_title'];
                        } else :
                        {
                            $arms_sections[$counter]['title'] = $row['sec_title'];
                            $counter++;
                        }

                    endif;
                }
                if ('' == $art_section) :
                    {
                        arms_error(_ME_ARMS_SECTION_DOESNT_EXISTS, $arms_redirect);
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;

        // Lets get levels...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals_levels') . ' ORDER BY level_name';
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_NO_LEVELS, $arms_redirect);
                    }
                endif;
                $arms_levels = [];
                $counter = 0;
                while (false !== ($row = $xoopsDB->fetchArray($result))) {
                    if ($row['level_id'] == $art_row['level_id']) :
                        {
                            $art_level = $row['level_name'];
                        } else :
                        {
                            $arms_levels[$counter]['title'] = $row['level_name'];
                            $counter++;
                        }

                    endif;
                }
                if ('' == $art_level) :
                    {
                        arms_error(_ME_ARMS_LEVEL_DOESNT_EXISTS, $arms_redirect);
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;

        // Prepare data for edit
        $my_ts = MyTextSanitizer::getInstance();
        $art_row['art_title'] = $my_ts->htmlSpecialChars($art_row['art_title']);
        $art_row['art_desc'] = $my_ts->htmlSpecialChars($art_row['art_desc']);

        $arms_lang_arr = get_arms_lang('ARMS_EDIT_ARTICAL_DATE');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_artical', $art_row);
        $xoopsTpl->assign('arms_level', $art_level);
        $xoopsTpl->assign('arms_levels', $arms_levels);
        $xoopsTpl->assign('arms_section', $art_section);
        $xoopsTpl->assign('arms_sections', $arms_sections);
    }
// ============================ //
// Delete artical and all pages //
// ============================ //
elseif ('deleteart' == $what) :
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
        $GLOBALS['xoopsOption']['template_main'] = 'arms_forms_confirm.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $user_perms = arms_get_artical_perms($idx, $xoopsUser->uid());
        if (!$user_perms['can_delete_art']) :
            {
                arms_error(_ME_ARMS_ACCESS);
            }
        endif;

        $arms_action = 'posting.php?w=deleteart&idx=' . $idx;
        $arms_lang_arr = get_arms_lang('ARMS_CONFIRM_DELETE_ART');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_form_action', $arms_action);
    }
// ================ //
// Activate artical //
// ================ //
elseif ('activateart' == $what) :
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
        $GLOBALS['xoopsOption']['template_main'] = 'arms_forms_confirm.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $user_perms = arms_get_artical_perms($idx, $xoopsUser->uid());
        if (!$user_perms['can_activate_art']) :
            {
                arms_error(_ME_ARMS_ACCESS);
            }
        endif;

        $arms_action = 'posting.php?w=activateart&idx=' . $idx;
        $arms_lang_arr = get_arms_lang('ARMS_CONFIRM_ACTIVATE_ART');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_form_action', $arms_action);
    }
// ================== //
// Deactivate artical //
// ================== //
elseif ('deactivateart' == $what) :
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
        $GLOBALS['xoopsOption']['template_main'] = 'arms_forms_confirm.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $user_perms = arms_get_artical_perms($idx, $xoopsUser->uid());
        if (!$user_perms['can_activate_art']) :
            {
                arms_error(_ME_ARMS_ACCESS);
            }
        endif;

        $arms_action = 'posting.php?w=deactivateart&idx=' . $idx;
        $arms_lang_arr = get_arms_lang('ARMS_CONFIRM_DEACTIVATE_ART');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_form_action', $arms_action);
    }
// ======== //
// Add Page //
// ======== //
elseif ('addpage' == $what) :
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

        $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/forms.php?w=editart&idx=$idx";

        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_forms_add_page.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $user_perms = arms_get_artical_perms($idx, $xoopsUser->uid());
        if (!$user_perms['can_add_page']) :
            {
                arms_error(_ME_ARMS_ACCESS, $arms_redirect);
            }
        endif;

        // Lets get artical data...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_id=' . $idx;
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_ARTICAL_DOESNT_EXISTS);
                    }
                endif;

                $art_row = $xoopsDB->fetchArray($result);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets get next order
        $q_str = 'SELECT page_order FROM ' . $xoopsDB->prefix('arms_pages') . ' WHERE art_id=' . $idx . ' ORDER BY page_order DESC LIMIT 0, 1';
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        $next_order = 1;
                    } else :
                    {
                        $row = $xoopsDB->fetchArray($result);
                        $next_order = ++$row['page_order'];
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets get levels
        $q_str = 'SELECT level_name FROM ' . $xoopsDB->prefix('arms_articals_levels') . ' ORDER BY level_name';
        if ($result = $xoopsDB->query($q_str)) :
            {
                $arms_levels = [];
                $counter = 0;
                // No levels???
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_NO_LEVELS);
                    }
                endif;

                while (false !== ($row = $xoopsDB->fetchArray($result))) {
                    $arms_levels[$counter]['title'] = $row['level_name'];

                    $counter++;
                }
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        $arms_lang_arr = get_arms_lang('ARMS_ADD_PAGE');
        $form_action = 'posting.php?w=addpage&idx=' . $art_row['art_id'];
        $page_desc = arms_text_area('page_desc');
        $page_text = arms_text_area('page_text');
        $page_emotions = amrs_emotions('page_text');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_form_action', $form_action);
        $xoopsTpl->assign('arms_page_desc', $page_desc);
        $xoopsTpl->assign('arms_page_text', $page_text);
        $xoopsTpl->assign('arms_page_emotions', $page_emotions);
        $xoopsTpl->assign('arms_artical', $art_row);
        $xoopsTpl->assign('arms_levels', $arms_levels);
        $xoopsTpl->assign('arms_next_order', $next_order);
    }
// ========= //
// Edit Page //
// ========= //
elseif ('editpage' == $what) :
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
        $GLOBALS['xoopsOption']['template_main'] = 'arms_forms_edit_page.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // Lets get page data...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_pages') . ' WHERE page_id=' . $idx;
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_PAGE_DOESNT_EXISTS);
                    }
                endif;
                $page_row = $xoopsDB->fetchArray($result);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        $user_perms = arms_get_artical_perms($page_row['art_id'], $xoopsUser->uid());
        if (!$user_perms['can_edit_page']) :
            {
                arms_error(_ME_ARMS_ACCESS);
            }
        endif;

        // Artical data...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_id=' . $page_row['art_id'];
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_ARTICAL_DOESNT_EXISTS);
                    }
                endif;

                $art_row = $xoopsDB->fetchArray($result);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets get level data...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals_levels') . ' ORDER BY level_name';
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_NO_LEVELS);
                    }
                endif;
                $arms_levels = [];
                $counter = 0;
                while (false !== ($row = $xoopsDB->fetchArray($result))) {
                    if ($row['level_id'] == $page_row['level_id']) :
                        {
                            $page_level = $row['level_name'];
                        } else :
                        {
                            $arms_levels[$counter]['title'] = $row['level_name'];
                            $counter++;
                        }

                    endif;
                }
                if ('' == $page_level) :
                    {
                        arms_error(_ME_ARMS_LEVEL_DOESNT_EXISTS);
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Prepare data for edit
        $my_ts = MyTextSanitizer::getInstance();
        $page_row['page_title'] = $my_ts->htmlSpecialChars($page_row['page_title']);
        $page_row['page_desc'] = $my_ts->htmlSpecialChars($page_row['page_desc']);
        $page_row['page_text'] = $my_ts->htmlSpecialChars($page_row['page_text']);

        $arms_lang_arr = get_arms_lang('ARMS_ADD_PAGE');
        $page_desc = arms_text_area('page_desc', 60, 15, null, $page_row['page_desc']);
        $page_text = arms_text_area('page_text', 60, 15, null, $page_row['page_text']);
        $page_emotions = amrs_emotions('page_text');

        $arms_lang_arr = get_arms_lang('ARMS_EDIT_PAGE');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_page_desc', $page_desc);
        $xoopsTpl->assign('arms_page_text', $page_text);
        $xoopsTpl->assign('arms_page_emotions', $page_emotions);
        $xoopsTpl->assign('arms_page', $page_row);
        $xoopsTpl->assign('arms_artical', $art_row);
        $xoopsTpl->assign('arms_level', $page_level);
        $xoopsTpl->assign('arms_levels', $arms_levels);
    }
// =========== //
// Delete Page //
// =========== //
elseif ('deletepage' == $what) :
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
        $GLOBALS['xoopsOption']['template_main'] = 'arms_forms_confirm.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $user_perms = arms_get_artical_perms($idx, $xoopsUser->uid());
        if (!$user_perms['can_delete_page']) :
            {
                arms_error(_ME_ARMS_ACCESS);
            }
        endif;

        $arms_action = 'posting.php?w=deletepage&idx=' . $idx;
        $arms_lang_arr = get_arms_lang('ARMS_CONFIRM_DELETE_PAGE');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_form_action', $arms_action);
    }
// ====================== //
// Delete User Permission //
// ====================== //
elseif ('deleteperm' == $what) :
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
        $GLOBALS['xoopsOption']['template_main'] = 'arms_forms_confirm.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $q_str = 'SELECT art_id FROM ' . $xoopsDB->prefix('arms_permissions') . " WHERE p_id = $idx";
        $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
        if ($xoopsDB->getRowsNum($result) > 0) :
            {
                $row = $xoopsDB->fetchArray($result);
                $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/forms.php?w=editart&idx=' . $row['art_id'];
            } else :
            {
                $arms_redirect = '';
            }
        endif;

        $user_perms = arms_get_artical_perms($idx, $xoopsUser->uid());
        if (!$user_perms['can_edit_art']) :
            {
                arms_error(_ME_ARMS_ACCESS, $arms_redirect);
            }
        endif;

        $arms_action = "posting.php?w=deleteperm&idx=$idx";
        $arms_lang_arr = get_arms_lang('ARMS_CONFIRM_DELETE_PERM');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_form_action', $arms_action);
    } elseif ('clearperms' == $what) :
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
        $GLOBALS['xoopsOption']['template_main'] = 'arms_forms_confirm.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $user_perms = arms_get_artical_perms($idx, $xoopsUser->uid());
        if (!$user_perms['can_edit_art']) :
            {
                arms_error(_ME_ARMS_ACCESS);
            }
        endif;

        $arms_action = "posting.php?w=clearperms&idx=$idx";
        $arms_lang_arr = get_arms_lang('ARMS_CONFIRM_CLEAR_PERMS');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_form_action', $arms_action);
    }
// ============== //
// Change On Hold //
// ============== //
elseif ('changeonhold' == $what) :
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
        $GLOBALS['xoopsOption']['template_main'] = 'arms_forms_confirm.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $user_perms = arms_get_artical_perms($idx, $xoopsUser->uid());
        if (!$user_perms['can_view_onhold']) :
            {
                arms_error(_ME_ARMS_ACCESS);
            }
        endif;

        $arms_action = "posting.php?w=changeonhold&idx=$idx";
        $arms_lang_arr = get_arms_lang('ARMS_CONFIRM_CHANGE_ONHOLD');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_form_action', $arms_action);
    }
endif;

// For testing purpose only...
// $xoopsTpl->debugging = true;
// $xoopsTpl->force_compile = true;

// Including footer
require XOOPS_ROOT_PATH . '/footer.php';
