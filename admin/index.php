<?php

// ========================================================================== //
// ArMS = Article Management System                                           //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Unit version   : 0.002                                                   //
//   Started        : 9:56:38 AM 9/10/2003                                    //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 11:12:56 AM 10/22/2003                                  //
//   Last update by : Ilija Studen                                            //
//                                                                            //
//   Change log:                                                              //
//   -----------                                                              //
//   - categories main, articals main, display articals subroutines added     //
//     (2:41:19 PM 10/8/2003 by Ilija Studen)                                 //
//                                                                            //
// ========================================================================== //
// (c) 2003. by Ilija Studen                                                  //
//              (mail: ilija_studen@yahoo.com)                                //
//              (home: ilija.ionbee.net)                                      //
// ========================================================================== //

require dirname(__DIR__, 3) . '/include/cp_header.php';
require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/language/english/main.php';
require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/includes/functions_general.php';
require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/includes/functions_db.php';
require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/admin/functions.php';

$include_footer = true;

if (isset($_GET['w'])) :
    {
        $what = (string)$_GET['w'];
    } else :
    {
        $what = '';
    }
endif;

// ==================== //
// Categories Main Page //
// ==================== //
if ('cats' == $what) :
    {
        // Standard tpl actions
        $GLOBALS['xoopsOption']['template_main'] = 'arms_admin_categories_main.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $q_str = 'SELECT cat_id, cat_title, cat_order FROM ' . $xoopsDB->prefix('arms_categories') . ' ORDER BY cat_order';
        if ($result = $xoopsDB->query($q_str)) :
            {
                $arms_cats = [];
                $counter = 0;
                while (false !== ($row = $xoopsDB->fetchArray($result))) :
                    {
                        $arms_cats[$counter]['id'] = $row['cat_id'];
                        $arms_cats[$counter]['title'] = $row['cat_title'];
                        $arms_cats[$counter]['order'] = $row['cat_order'];
                        $counter++;
                    }
                endwhile;

                $arms_lang_arr = get_arms_lang('ARMS_ADMIN_CATEGORIES_MAIN');
                $xoopsTpl->assign('arms_lang', $arms_lang_arr);
                $xoopsTpl->assign('arms_categories', $arms_cats);
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    }
// ================== //
// Sections Main Page //
// ================== //
elseif ('sections' == $what) :
    {
        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_admin_sections_main.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // For testing purpose only...
        // error_reporting (E_ALL);

        // Lets get categories...
        $q_str = 'SELECT cat_title FROM ' . $xoopsDB->prefix('arms_categories') . ' ORDER BY cat_title';
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        print_and_die(_ME_ARMS_NO_CATS);
                    }
                endif;

                $arms_cats = [];
                $counter = 0;
                while (false !== ($row = $xoopsDB->fetchArray($result))) :
                    {
                        $arms_cats[$counter]['title'] = $row['cat_title'];
                        $counter++;
                    }
                endwhile;
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        $q_str = 'SELECT sec_id, sec_title, sec_image, sec_order FROM ' . $xoopsDB->prefix('arms_sections') . ' ORDER BY sec_order';
        $result = $xoopsDB->query($q_str) or print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
        $sections = [];
        if ($xoopsDB->getRowsNum($result) > 0) :
            {
                $counter = 0;
                while (false !== ($row = $xoopsDB->fetchArray($result))) {
                    $sections[$counter]['id'] = $row['sec_id'];

                    $sections[$counter]['title'] = $row['sec_title'];

                    $sections[$counter]['image'] = $row['sec_image'];

                    $sections[$counter]['order'] = $row['sec_order'];

                    $sections[$counter]['orphened'] = is_orphened_sec($row['sec_id']);

                    $counter++;
                }
            }
        endif;

        // Get images...
        $image_files = list_files(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/images/section/');

        // Assign variables...
        $arms_lang_arr = get_arms_lang('ARMS_ADMIN_SECTIONS_MAIN');
        $xoopsTpl->assign('arms_images', $image_files);
        $xoopsTpl->assign('arms_admin_sections', $sections);
        $xoopsTpl->assign('arms_cats', $arms_cats);
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
    }
// ================== //
// Articals Main Page //
// ================== //
elseif ('arts' == $what) :
    {
        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_admin_articals_main.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // Get total articals
        $q_str = 'SELECT count(art_id) AS total_arts FROM ' . $xoopsDB->prefix('arms_articals');
        if ($result = $xoopsDB->query($q_str)) :
            {
                $row = $xoopsDB->fetchArray($result);
                $total_arts = $row['total_arts'];
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
        // Get waiting...
        $q_str = 'SELECT count(art_id) AS total_waiting FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_activated = 0 AND art_onhold = 0';
        if ($result = $xoopsDB->query($q_str)) :
            {
                $row = $xoopsDB->fetchArray($result);
                $total_waiting = $row['total_waiting'];
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        $arms_lang_arr = get_arms_lang('ARMS_ADMIN_ARTICALS_MAIN');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_total_arts', $total_arts);
        $xoopsTpl->assign('arms_waiting', $total_waiting);
    }
// ================ //
// Display Articals //
// ================ //
elseif ('displayart' == $what) :
    {
        function arms_art_arr($result)
        {
            global $xoopsDB;

            $return = [];

            $counter = 0;

            if ($xoopsDB->getRowsNum($result) < 1) :
                {
                    return $return;
                }

            endif;

            while (false !== ($row = $xoopsDB->fetchArray($result))) :
                {
                    $return[$counter]['id'] = $row['art_id'];
                    $return[$counter]['title'] = $row['art_title'];
                    $return[$counter]['activated'] = $row['art_activated'];
                    $return[$counter]['onhold'] = $row['art_onhold'];
                    $counter++;
                }

            endwhile;

            return $return;
        }

        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_admin_display_articals.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $display_value = validate_text_field($_POST['display_value'], _MD_ARMS_DISPLAY);
        $display_what = validate_text_field($_POST['display_what'], _MD_ARMS_DISPLAY);
        // ========================= //
        // Search by artical name... //
        // ========================= //
        if (_MD_ARMS_OPT_ART_NAME == $display_what) :
            {
                $q_str = 'SELECT art_id, art_title, art_activated, art_title, art_onhold FROM ' . $xoopsDB->prefix('arms_articals') . " WHERE art_title = '$display_value'";
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        $art_arr = arms_art_arr($result);
                    } else :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            }
        // ====================== //
        // Search by section name //
        // ====================== //
        elseif (_MD_ARMS_OPT_SEC_NAME == $display_what) :
            {
                // Get section ID
                $q_str = 'SELECT sec_id FROM ' . $xoopsDB->prefix('arms_sections') . " WHERE sec_title='$display_value'";
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        if ($xoopsDB->getRowsNum($result) > 1) :
                            {
                                print_and_die(_ME_ARMS_SECTION_DOESNT_EXISTS);
                            }
                        endif;
                        $row = $xoopsDB->fetchArray($result);
                        $sec_id = $row['sec_id'];
                    } else :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;

                $q_str = 'SELECT art_id, art_title, art_activated, art_title, art_onhold FROM ' . $xoopsDB->prefix('arms_articals') . " WHERE sec_id = $sec_id";
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        $art_arr = arms_art_arr($result);
                    } else :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            }
        // ======================= //
        // Search by category name //
        // ======================= //
        elseif (_MD_ARMS_OPT_CAT_NAME == $display_what) :
            {
                // Lets get category id
                $q_str = 'SELECT cat_id FROM ' . $xoopsDB->prefix('arms_categories') . " WHERE cat_title = '$display_value'";
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        if ($xoopsDB->getRowsNum($result) < 1) :
                            {
                                print_and_die(_ME_ARMS_CAT_DONT_EXISTS);
                            }
                        endif;
                        $row = $xoopsDB->fetchArray($result);
                        $cat_id = $row['cat_id'];
                    } else :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;

                // Lets get sections
                $q_str = 'SELECT sec_id FROM ' . $xoopsDB->prefix('arms_sections') . " WHERE cat_id = $cat_id";
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        if ($xoopsDB->getRowsNum($result) < 1) :
                            {
                                print_and_die(_ME_ARMS_NO_SECS_IN_CAT);
                            }
                        endif;

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
                                $in .= $row['sec_id'];
                            }
                        endwhile;
                        $in .= ')';
                    } else :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;

                $q_str = 'SELECT art_id, art_title, art_activated, art_title, art_onhold FROM ' . $xoopsDB->prefix('arms_articals') . " WHERE sec_id IN $in GROUP BY sec_id";
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        $art_arr = arms_art_arr($result);
                    } else :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            }
        // ================ //
        // Search by author //
        // ================ //
        elseif (_MD_ARMS_OPT_AUTHOR_NAME == $display_what) :
            {
                // Get UID
                $q_str = 'SELECT uid FROM ' . $xoopsDB->prefix('users') . " WHERE uname='$display_value'";
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        if ($xoopsDB->getRowsNum($result) < 1) :
                            {
                                print_and_die(_ME_ARMS_USER_DOESNT_EXISTS);
                            }
                        endif;
                        $row = $xoopsDB->fetchArray($result);
                        $uid = $row['uid'];
                    } else :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;

                $q_str = 'SELECT art_id, art_title, art_activated, art_title, art_onhold FROM ' . $xoopsDB->prefix('arms_articals') . " WHERE uid = $uid";
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        $art_arr = arms_art_arr($result);
                    } else :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            }
        // =============== //
        // Query condition //
        // =============== //
        elseif (_MD_ARMS_OPT_WHERE_PART == $display_what) :
            {
                $q_str = 'SELECT art_id, art_title, art_activated, art_title, art_onhold FROM ' . $xoopsDB->prefix('arms_articals') . " WHERE $display_value";
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        $art_arr = arms_art_arr($result);
                    } else :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            }
        endif;
        $arms_lang_arr = get_arms_lang('ARMS_ADMIN_DISPLAY_ARTS');
        $xoopsTpl->assign('arms_arts', $art_arr);
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
    }
// ================ //
// Levels Main Page //
// ================ //
elseif ('levels' == $what) :
    {
        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_admin_levels_main.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // Lets get data...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals_levels') . ' ORDER BY level_name';
        if ($result = $xoopsDB->query($q_str)) :
            {
                $level_arr = [];
                $counter = 0;
                while (false !== ($row = $xoopsDB->fetchArray($result))) :
                    {
                        $levels_arr[$counter]['level_id'] = $row['level_id'];
                        $levels_arr[$counter]['level_name'] = $row['level_name'];
                        $levels_arr[$counter]['level_image'] = $row['level_image'];
                        $counter++;
                    }
                endwhile;
                $image_files = list_files(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/images/level/');
                $arms_lang_arr = get_arms_lang('ARMS_ADMIN_LEVELS_MAIN');
                $xoopsTpl->assign('arms_lang', $arms_lang_arr);
                $xoopsTpl->assign('arms_admin_levels', $levels_arr);
                $xoopsTpl->assign('arms_config', $xoopsModuleConfig);
                $xoopsTpl->assign('arms_images', $image_files);
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    }
// ========== //
// Moderators //
// ========== //
elseif ('moderators' == $what) :
    {
        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_admin_moderators_main.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $q_str = 'SELECT sec_id, sec_title FROM ' . $xoopsDB->prefix('arms_sections') . ' ORDER BY sec_order';
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        print_and_die(_MI_ARMS_NO_SECTIONS);
                    }
                endif;
                $arms_mods = [];
                $counter = 0;
                while (false !== ($row = $xoopsDB->fetchArray($result))) {
                    $arms_mods[$counter] = $row;

                    $arms_mods[$counter]['mod'] = get_arms_moderators($row['sec_id']);

                    $counter++;
                }
                $arms_lang_arr = get_arms_lang('ARMS_ADMIN_MODERATORS_MAIN');
                $xoopsTpl->assign('arms_lang', $arms_lang_arr);
                $xoopsTpl->assign('arms_mods', $arms_mods);
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    }
// Update ArMS
elseif ('update' == $what) :
    {
        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_admin_forms_update.html';
        require XOOPS_ROOT_PATH . '/header.php';

        require_once __DIR__ . '/update.php';
        $arms_lang_arr = get_arms_lang('ARMS_ADMIN_UPDATE');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_updates', $arms_update);
    } else :
    {
        $arms_data = [];
        $include_footer = false;
        // Get categories...
        $q_str = 'SELECT count(cat_id) AS total_cats FROM ' . $xoopsDB->prefix('arms_categories');
        if ($result = $xoopsDB->query($q_str)) :
            {
                $row = $xoopsDB->fetchArray($result);
                $arms_data['total_cats'] = $row['total_cats'];
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
        // Get sections
        $q_str = 'SELECT count(sec_id) AS total_secs FROM ' . $xoopsDB->prefix('arms_sections');
        if ($result = $xoopsDB->query($q_str)) :
            {
                $row = $xoopsDB->fetchArray($result);
                $arms_data['total_secs'] = $row['total_secs'];
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
        // Get articals
        $q_str = 'SELECT count(art_id) AS total_arts FROM ' . $xoopsDB->prefix('arms_articals');
        if ($result = $xoopsDB->query($q_str)) :
            {
                $row = $xoopsDB->fetchArray($result);
                $arms_data['total_arts'] = $row['total_arts'];
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
        // Get waiting...
        $q_str = 'SELECT count(art_id) AS waiting FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_activated = 0 AND art_onhold = 0';
        if ($result = $xoopsDB->query($q_str)) :
            {
                $row = $xoopsDB->fetchArray($result);
                $arms_data['waiting'] = $row['waiting'];
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
        $arms_data['arms_logo'] = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/images/arms_logo.gif';
        require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/admin/display_index.php';
        arms_display_index($arms_data);
    }
endif;

// For testing only...
// $xoopsTpl->debugging = true;

// Including footer
if ($include_footer) :
    {
        require XOOPS_ROOT_PATH . '/footer.php';
    }
endif;
