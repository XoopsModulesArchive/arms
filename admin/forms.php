<?php

// ========================================================================== //
// ArMS = Article Management System                                           //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Unit version   : 0.002                                                   //
//   Started        : 8:16:27 PM 9/10/2003                                    //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 11:10:10 AM 10/22/2003                                  //
//   Last update by : Ilija Studen                                            //
//                                                                            //
//   Change log:                                                              //
//   -----------                                                              //
//   - added editcat and delete cat subroutines                               //
//     (9:27:42 PM 10/5/2003 by Ilija Studen)                                 //
//   - changed editsec subroutine                                             //
//     (9:28:03 PM 10/5/2003 by Ilija Studen)                                 //
//                                                                            //
// ========================================================================== //
// (c) 2003. by Ilija Studen                                                  //
//              (mail: ilija_studen@yahoo.com)                                //
//              (home: ilija.ionbee.net)                                      //
// ========================================================================== //

require dirname(__DIR__, 3) . '/include/cp_header.php';
require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/language/english/main.php';
require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/includes/functions_general.php';
require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/admin/functions.php';

// What are we doing???
if (isset($_GET['w'])) :
    {
        $what = (string)$_GET['w'];
    } else :
    {
        $what = '';
    }
endif;

// ============= //
// Edit Category //
// ============= //
if ('editcat' == $what) :
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
        $GLOBALS['xoopsOption']['template_main'] = 'arms_admin_forms_edit_cat.html';
        require XOOPS_ROOT_PATH . '/header.php';

        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_categories') . " WHERE cat_id=$idx";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        print_and_die(_ME_ARMS_CAT_DONT_EXISTS);
                    }
                endif;
                $cat_row = $xoopsDB->fetchArray($result);
                $arms_lang_arr = get_arms_lang('ARMS_ADMIN_EDIT_CAT');
                $xoopsTpl->assign('arms_lang', $arms_lang_arr);
                $xoopsTpl->assign('arms_category', $cat_row);
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    }
// =============== //
// Delete Category //
// =============== //
elseif ('deletecat' == $what) :
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

        $form_action = 'posting.php?w=deletecat&idx=' . $idx;
        $arms_lang_arr = get_arms_lang('ARMS_ADMIN_DELETE_CAT');
        $xoopsTpl->assign('arms_form_action', $form_action);
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
    }
// ============ //
// Edit Section //
// ============ //
elseif ('editsec' == $what) :
    {
        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_admin_forms_edit_section.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // For testing purpose only...
        // error_reporting (E_ALL);

        // What is index of section???
        if (isset($_GET['idx'])) :
            {
                $idx = $_GET['idx'];
            } else :
            {
                print_and_die(_ME_ARMS_PARAMS);
            }
        endif;

        // Get data...
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_sections') . ' WHERE sec_id=' . $idx;
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        $sec_row = $xoopsDB->fetchArray($result);
                    } else :
                    {
                        print_and_die(_ME_ARMS_SECTION_DOESNT_EXISTS);
                    }
                endif;
            } else :
            {
                print_and_die(sprinf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Get categories...
        $q_str = 'SELECT cat_id, cat_title FROM ' . $xoopsDB->prefix('arms_categories') . ' ORDER BY cat_title';
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        print_and_die(_ME_ARMS_NO_CATS);
                    }
                endif;

                $arms_cats = [];
                $counter = 0;
                while (false !== ($cat_row = $xoopsDB->fetchArray($result))) :
                    {
                        if ($cat_row['cat_id'] == $sec_row['cat_id']) :
                            {
                                $cat_title = $cat_row['cat_title'];
                            } else :
                            {
                                $arms_cats[$counter]['title'] = $cat_row['cat_title'];
                                $counter++;
                            }
                        endif;
                    }
                endwhile;
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        $image_files = list_files(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/images/section/');

        $arms_lang_arr = get_arms_lang('ARMS_ADMIN_EDIT_SECTION');
        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
        $xoopsTpl->assign('arms_section', $sec_row);
        $xoopsTpl->assign('arms_category', $cat_title);
        $xoopsTpl->assign('arms_cats', $arms_cats);
        $xoopsTpl->assign('arms_images', $image_files);
    }
// ============== //
// Delete section //
// ============== //
elseif ('deletesec' == $what) :
    {
        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_admin_forms_delete_section.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // For testing purpose only...
        // error_reporting (E_ALL);

        // What is index of section???
        if (isset($_GET['idx'])) :
            {
                $idx = $_GET['idx'];
            } else :
            {
                print_and_die(_ME_ARMS_PARAMS);
            }
        endif;

        // Does section exists???
        $q_str = 'SELECT sec_id, sec_title FROM ' . $xoopsDB->prefix('arms_sections');
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        $other_sections = [];
                        $sec_data = [];
                        $counter = 0;
                        while (false !== ($row = $xoopsDB->fetchArray($result))) {
                            if ($row['sec_id'] == $idx) :
                                {
                                    $sec_data['id'] = $idx;
                                    $sec_data['title'] = $row['sec_title'];
                                } else :
                                {
                                    $other_sections[$counter]['title'] = $row['sec_title'];
                                    $counter++;
                                }

                            endif;
                        }
                        // Is there section with supplied index...
                        if (count($sec_data) < 1) :
                            {
                                print_and_die(_ME_ARMS_SECTION_DOESNT_EXISTS);
                            }
                        endif;
                        $arms_lang_arr = get_arms_lang('ARMS_ADMIN_DELETE_SECTION');
                        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
                        $xoopsTpl->assign('arms_section', $sec_data);
                        $xoopsTpl->assign('arms_other_sections', $other_sections);
                        $xoopsTpl->assign('arms_other_count', count($other_sections));
                    } else :
                    {
                        print_and_die(_MI_ARMS_NO_SECTIONS);
                    }
                endif;
            } else :
            {
                print_and_die(sprinf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    }
// ========== //
// Edit Level //
// ========== //
elseif ('editlevel' == $what) :
    {
        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_admin_forms_edit_level.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // For testing purpose only...
        // error_reporting (E_ALL);

        // What is index of section???
        if (isset($_GET['idx'])) :
            {
                $idx = $_GET['idx'];
            } else :
            {
                print_and_die(_ME_ARMS_PARAMS);
            }
        endif;

        // Get data...
        $q_str = 'SELECT level_id, level_name, level_desc, level_image FROM ' . $xoopsDB->prefix('arms_articals_levels') . ' WHERE level_id=' . $idx;
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        $row = $xoopsDB->fetchArray($result);
                        $image_files = list_files(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/images/level/');
                        $arms_lang_arr = get_arms_lang('ARMS_ADMIN_EDIT_LEVEL');
                        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
                        $xoopsTpl->assign('arms_level', $row);
                        $xoopsTpl->assign('arms_images', $image_files);
                    } else :
                    {
                        print_and_die(_ME_ARMS_LEVEL_DOESNT_EXISTS);
                    }
                endif;
            } else :
            {
                print_and_die(sprinf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    }
// ============ //
// Delete Level //
// ============ //
elseif ('deletelevel' == $what) :
    {
        // Standard tpl operations
        $GLOBALS['xoopsOption']['template_main'] = 'arms_admin_forms_delete_level.html';
        require XOOPS_ROOT_PATH . '/header.php';

        // For testing purpose only...
        error_reporting(E_ALL);

        // What is index of section???
        if (isset($_GET['idx'])) :
            {
                $idx = $_GET['idx'];
            } else :
            {
                print_and_die(_ME_ARMS_PARAMS);
            }
        endif;

        // Does level exists???
        $q_str = 'SELECT level_id, level_name, level_image FROM ' . $xoopsDB->prefix('arms_articals_levels');
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        $level_data = [];
                        $other_levels = [];
                        $counter = 0;
                        while (false !== ($row = $xoopsDB->fetchArray($result))) {
                            if ($row['level_id'] == $idx) :
                                {
                                    $level_data = $row;
                                } else :
                                {
                                    $other_levels[$counter]['name'] = $row['level_name'];
                                    $counter++;
                                }

                            endif;
                        }
                        $arms_lang_arr = get_arms_lang('ARMS_ADMIN_DELETE_LEVEL');
                        $xoopsTpl->assign('arms_lang', $arms_lang_arr);
                        $xoopsTpl->assign('arms_level', $level_data);
                        $xoopsTpl->assign('arms_other_levels', $other_levels);
                        $xoopsTpl->assign('arms_other_count', count($other_levels));
                    } else :
                    {
                        print_and_die(_ME_ARMS_LEVEL_DOESNT_EXISTS);
                    }
                endif;
            } else :
            {
                print_and_die(sprinf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    }
endif;

// For testing purpose only...
// $xoopsTpl->debugging = true;

// Including footer
require XOOPS_ROOT_PATH . '/footer.php';
