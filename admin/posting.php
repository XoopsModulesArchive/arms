<?php

// ========================================================================== //
// ArMS = Article Management System                                           //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   File Version   : 0.002                                                   //
//   Started        : 5:26:24 PM 9/10/2003                                    //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 11:14:42 AM 10/22/2003                                  //
//   Last update by : Ilija Studen                                            //
//                                                                            //
//   Change log:                                                              //
//   -----------                                                              //
//   - add, edit, delete cateogory and reorder categories subroutins added    //
//     (9:27:42 PM 10/5/2003 by Ilija Studen)                                 //
//   - add, edit section subroutins edited                                    //
//     (6:30:37 PM 10/6/2003 by Ilija Studen)                                 //
//   - reorder sections subroutine added                                      //
//     (10:59:46 PM 10/6/2003 by Ilija Studen)                                //
//                                                                            //
// ========================================================================== //
// (c) 2003. by Ilija Studen                                                  //
//              (mail: ilija_studen@yahoo.com)                                //
//              (home: ilija.ionbee.net)                                      //
// ========================================================================== //

// Internal functions...

function next_field_value($db_table, $db_field)
{
    global $xoopsDB;

    $q_str = "SELECT $db_field FROM " . $xoopsDB->prefix($db_table) . " ORDER BY $db_field DESC";

    if (!($result = $xoopsDB->query($q_str))) :
        {
            print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
        }

    endif;

    if ($xoopsDB->getRowsNum($result) > 0) :
        {
            $row = $xoopsDB->fetchArray($result);
        } else :
        {
            return 1;
        }

    endif;

    return ++$row[$db_field];
}

// Posting starts here...
require dirname(__DIR__, 3) . '/include/cp_header.php';
require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/language/english/main.php';
require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/includes/functions_general.php';
require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/admin/functions.php';

// What are we posting???
if (isset($_GET['w'])) :
    {
        $what = (string)$_GET['w'];
    } elseif (isset($_POST['w'])) :
    {
        $what = (string)$_POST['w'];
    } else :
    {
        $what = '';
    }
endif;

// ============ //
// Add Category //
// ============ //
if ('addcat' == $what) :
    {
        $cat_title = validate_text_field($_POST['cat_title'], _MD_ARMS_WORD_TITLE);

        // Does category exists???
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_categories') . " WHERE cat_title='" . $cat_title . "'";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        print_and_die(sprintf(_ME_ARMS_CAT_EXISTS, $cat_title));
                    }
                endif;
            } else :
            {
                print_and_die(sprint(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        $cat_desc = validate_text_field($_POST['cat_desc'], _MD_ARMS_DESC);
        if (isset($_POST['cat_order']) and is_numeric($_POST['cat_order'])) :
            {
                $cat_order = (int)$_POST['cat_order'];
            } else :
            {
                $cat_order = next_field_value('arms_categories', 'cat_order');
            }
        endif;

        // Lets insert data...
        $q_str = sprintf(
            "INSERT INTO %s (cat_title, cat_desc, cat_order) VALUES ('%s', '%s', %s)",
            $xoopsDB->prefix('arms_categories'),
            $cat_title,
            $cat_desc,
            $cat_order
        );
        if (!$xoopsDB->query($q_str)) :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            } else :
            {
                redirect_header('index.php?w=cats', 1, sprintf(_ME_ARMS_CAT_ADDED, $cat_title));
            }
        endif;
    }
// ================== //
// Reorder Categories //
// ================== //
elseif ('reordercat' == $what) :
    {
        $q_str = 'SELECT cat_id FROM ' . $xoopsDB->prefix('arms_categories') . ' ORDER BY cat_order';
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        print_and_die(_ME_ARMS_NO_CATS);
                    }
                endif;
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets reorder them...
        while (false !== ($row = $xoopsDB->fetchArray($result))) :
            {
                $order_fld = 'catorder' . $row['cat_id'];
                if (isset($_POST[$order_fld]) and is_numeric($_POST[$order_fld])) :
                    {
                        $new_order = (int)$_POST[$order_fld];
                    } else :
                    {
                        $new_order = 0;
                    }
                endif;

                $q_str = sprintf(
                    'UPDATE %s SET cat_order = %s WHERE cat_id = %s',
                    $xoopsDB->prefix('arms_categories'),
                    $new_order,
                    $row['cat_id']
                );
                if (!$xoopsDB->query($q_str)) :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            }
        endwhile;

        redirect_header('index.php?w=cats', 1, _MM_ARMS_CATS_REORDERED);
    }
// ============= //
// Edit Category //
// ============= //
elseif ('editcat' == $what) :
    {
        if (isset($_GET['idx'])) :
            {
                $idx = (int)$_GET['idx'];
            } else :
            {
                print_and_die(_ME_ARMS_PARAMS);
            }
        endif;

        $cat_title = validate_text_field($_POST['cat_title'], _MD_ARMS_WORD_TITLE);

        // Duplicate section???
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_categories') . " WHERE cat_title = '" . $cat_title . "' AND cat_id <> $idx";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        print_and_die(sprintf(_ME_ARMS_CAT_EXISTS, $cat_title));
                    }
                endif;
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        $cat_desc = validate_text_field($_POST['cat_desc'], _MD_ARMS_DESC);
        if (isset($_POST['cat_order']) and is_numeric($_POST['cat_order'])) :
            {
                $cat_order = (int)$_POST['cat_order'];
            } else :
            {
                $cat_order = next_field_value('arms_categories', 'cat_order');
            }
        endif;

        // Update data...
        $q_str = sprintf(
            "UPDATE %s SET cat_title = '%s', cat_desc = '%s', cat_order = %s WHERE cat_id = %s",
            $xoopsDB->prefix('arms_categories'),
            $cat_title,
            $cat_desc,
            $cat_order,
            $idx
        );
        if ($xoopsDB->query($q_str)) :
            {
                redirect_header('index.php?w=cats', 1, _MM_ARMS_CAT_UPDATED);
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
        if (isset($_GET['idx'])) :
            {
                $idx = (int)$_GET['idx'];
            } else :
            {
                print_and_die(_ME_ARMS_PARAMS);
            }
        endif;

        // Is action confirmed???
        $confirmed = false;
        if (isset($_POST['arms_confirm']) and ('confirmed' == (string)$_POST['arms_confirm'])) :
            {
                $confirmed = true;
            }
        endif;

        if (!$confirmed) :
            {
                print_and_die(_ME_ARMS_NOT_CONFIRMED);
            }
        endif;

        // Category exists???
        $q_str = 'SELECT cat_title FROM ' . $xoopsDB->prefix('arms_categories') . " WHERE cat_id=$idx";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        print_and_die(_ME_ARMS_CAT_DONT_EXISTS);
                    }
                endif;

                $row = $xoopsDB->fetchArray($result);
                $cat_title = $row['cat_title'];
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        $q_str = 'DELETE FROM ' . $xoopsDB->prefix('arms_categories') . " WHERE cat_id=$idx";
        if ($xoopsDB->query($q_str)) :
            {
                redirect_header('index.php?w=cats', 1, sprintf(_MM_ARMS_CAT_DELETED, $cat_title));
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    }
// =========== //
// Add Section //
// =========== //
elseif ('addsec' == $what) :
    {
        $sec_title = validate_text_field($_POST['sec_title'], _MD_ARMS_WORD_TITLE);
        // Does section already exists???
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_sections') . " WHERE sec_title='" . $sec_title . "'";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        print_and_die(sprintf(_ME_ARMS_SECTION_EXISTS, $sec_title));
                    }
                endif;
            } else :
            {
                print_and_die(sprint(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets get category ID
        $cat_title = validate_text_field($_POST['sec_category'], _MD_ARMS_CATEGORY);
        $q_str = 'SELECT cat_id FROM ' . $xoopsDB->prefix('arms_categories') . " WHERE cat_title='$cat_title'";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        print_and_die(_ME_ARMS_NO_CATS);
                    }
                endif;
                $row = $xoopsDB->fetchArray($result);
                $cat_id = $row['cat_id'];
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        $sec_desc = validate_text_field($_POST['sec_desc'], _MD_ARMS_DESC);

        // Section image
        $sec_image = validate_text_field($_POST['sec_image'], _MD_ARMS_SECTION_IMAGE);
        if ('- - - - -' == $sec_image) :
            {
                $sec_image = '';
            }
        endif;

        // Section order
        if (isset($_POST['sec_order']) and is_numeric($_POST['sec_order'])) :
            {
                $sec_order = (int)$_POST['sec_order'];
            } else :
            {
                $sec_order = next_field_value('arms_sections', 'sec_order');
            }
        endif;

        // Lets insert new section...
        $q_str = sprintf(
            "INSERT INTO %s (sec_title, sec_desc, sec_order, cat_id, sec_image) VALUES ('%s', '%s', %s, %s, '%s')",
            $xoopsDB->prefix('arms_sections'),
            $sec_title,
            $sec_desc,
            $sec_order,
            $cat_id,
            $sec_image
        );
        if (!$xoopsDB->query($q_str)) :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            } else :
            {
                redirect_header('index.php?w=sections', 1, sprintf(_MM_ARMS_ADD_SECTION, $sec_title));
            }
        endif;
    }
// ================ //
// Reorder Sections //
// ================ //
elseif ('reordersecs' == $what) :
    {
        $q_str = 'SELECT sec_id FROM ' . $xoopsDB->prefix('arms_sections') . ' ORDER BY sec_order';
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        print_and_die(_MI_ARMS_NO_SECTIONS);
                    }
                endif;
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets reorder them...
        while (false !== ($row = $xoopsDB->fetchArray($result))) :
            {
                $order_fld = 'secorder' . $row['sec_id'];
                if (isset($_POST[$order_fld]) and is_numeric($_POST[$order_fld])) :
                    {
                        $new_order = (int)$_POST[$order_fld];
                    } else :
                    {
                        $new_order = 0;
                    }
                endif;

                $q_str = sprintf(
                    'UPDATE %s SET sec_order = %s WHERE sec_id = %s',
                    $xoopsDB->prefix('arms_sections'),
                    $new_order,
                    $row['sec_id']
                );
                if (!$xoopsDB->query($q_str)) :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            }
        endwhile;

        redirect_header('index.php?w=sections', 1, _MM_ARMS_SEC_REORDERED);
    }
// ============ //
// Edit Section //
// ============ //
elseif ('editsec' == $what) :
    {
        if (isset($_GET['idx'])) :
            {
                $idx = (int)$_GET['idx'];
            } else :
            {
                print_and_die(_ME_ARMS_PARAMS);
            }
        endif;

        $sec_title = validate_text_field($_POST['sec_title'], _MD_ARMS_WORD_TITLE);
        $sec_desc = validate_text_field($_POST['sec_desc'], _MD_ARMS_DESC);

        // Lets get old section data...
        $q_str = 'SELECT sec_title, sec_desc FROM ' . $xoopsDB->prefix('arms_sections') . ' WHERE sec_id=' . $idx;
        if ($result = $xoopsDB->query($q_str)) :
            {
                $row = $xoopsDB->fetchArray($result);
                // New section name (remember that it needs to unique)???
                if ($row['sec_title'] != $sec_title) :
                    {
                        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_sections') . " WHERE sec_title='" . $sec_title . "'";
                        if ($result = $xoopsDB->query($q_str)) :
                            {
                                if ($xoopsDB->getRowsNum($result) > 0) :
                                    {
                                        print_and_die(sprintf(_ME_ARMS_SECTION_EXISTS, $sec_title));
                                    }
                                endif;
                            } else :
                            {
                                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                            }
                        endif;
                    }
                endif;
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Section image
        $sec_image = validate_text_field($_POST['sec_image'], _MD_ARMS_SECTION_IMAGE);
        if ('- - - - -' == $sec_image) :
            {
                $sec_image = '';
            }
        endif;

        // Section order
        if (isset($_POST['sec_order']) and is_numeric($_POST['sec_order'])) :
            {
                $sec_order = (int)$_POST['sec_order'];
            } else :
            {
                $sec_order = next_field_value('arms_sections', 'sec_order');
            }
        endif;

        // Lets update data...
        $q_str = sprintf("UPDATE %s SET sec_title = '%s', sec_desc='%s', sec_image = '%s', sec_order = %s WHERE sec_id=%s", $xoopsDB->prefix('arms_sections'), $sec_title, $sec_desc, $sec_image, $sec_order, $idx);
        if ($xoopsDB->query($q_str)) :
            {
                redirect_header('index.php?w=sections', 1, sprintf(_MM_ARMS_SECTION_UPDATED, $row['sec_title']));
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    }
// ============== //
// Delete section //
// ============== //
elseif ('deletesec' == $what) :
    {
        // Is this operation confirmed???
        if (isset($_POST['confirm']) and ('confirmed' == (string)$_POST['confirm'])) :
            {
                $confirmed = true;
            } else :
            {
                $confirmed = false;
            }
        endif;

        if (!$confirmed) :
            {
                print_and_die(_ME_ARMS_NOT_CONFIRMED);
            }
        endif;

        // Collect section index...
        if (isset($_GET['idx'])) :
            {
                $idx = (int)$_GET['idx'];
            } else :
            {
                print_and_die(_ME_ARMS_PARAMS);
            }
        endif;

        $move_to = validate_text_field($_POST['move_to'], _MD_ARMS_MOVE_ARTICALS);

        // Lets delete section
        $q_str = 'DELETE FROM ' . $xoopsDB->prefix('arms_sections') . ' WHERE sec_id=' . $idx;
        if (!$xoopsDB->query($q_str)) :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lest move or delete articals...
        if (_MD_ARMS_OP_DELETE == $move_to) :
            {
                // Lets delete articals...
                $q_str = 'DELETE FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE sec_id=' . $idx;
                if (!$xoopsDB->query($q_str)) :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            } else :
            {
                // Where do we need to move articals???
                $q_str = 'SELECT sec_id FROM ' . $xoopsDB->prefix('arms_sections') . " WHERE sec_title='" . $move_to . "'";
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        $row = $xoopsDB->fetchArray($result);
                        $move_to_idx = $row['sec_id'];
                    } else :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;

                // Now update data...
                $q_str = 'UPDATE ' . $xoopsDB->prefix('arms_articals') . ' SET sec_id=' . $move_to_idx . ' WHERE sec_id=' . $idx;
                if (!$xoopsDB->query($q_str)) :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            }
        endif;

        // Lets tell people what have we done...
        redirect_header('index.php?w=sections', 1, _MM_ARMS_SECTION_DELETED);
    }
// ========= //
// Add Level //
// ========= //
elseif ('addlevel' == $what) :
    {
        $level_title = validate_text_field($_POST['level_title'], _MD_ARMS_WORD_TITLE);
        // Does level already exists???
        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals_levels') . " WHERE level_name='" . $level_title . "'";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        print_and_die(sprintf(_ME_ARMS_LEVEL_EXISTS, $level_title));
                    }
                endif;
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
        $level_desc = validate_text_field($_POST['level_desc'], _MD_ARMS_DESC);

        // Level image can be empty...
        if (isset($_POST['level_image']) and ('- - - - -' != $_POST['level_image'])) :
            {
                $level_image = (string)$_POST['level_image'];
            } else :
            {
                $level_image = '';
            }
        endif;

        // Lets insert data
        if ('' == $level_image) :
            {
                $q_str = sprintf("INSERT INTO %s (level_name, level_desc) VALUES ('%s', '%s')", $xoopsDB->prefix('arms_articals_levels'), $level_title, $level_desc);
            } else :
            {
                $q_str = sprintf("INSERT INTO %s (level_name, level_desc, level_image) VALUES ('%s', '%s', '%s')", $xoopsDB->prefix('arms_articals_levels'), $level_title, $level_desc, $level_image);
            }
        endif;

        if ($xoopsDB->query($q_str)) :
            {
                redirect_header('index.php?w=levels', 1, sprintf(_MM_ARMS_ADD_LEVEL, $level_title));
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    }
// ========== //
// Edit Level //
// ========== //
elseif ('editlevel' == $what) :
    {
        if (isset($_GET['idx'])) :
            {
                $idx = (int)$_GET['idx'];
            } else :
            {
                print_and_die(_ME_ARMS_PARAMS);
            }
        endif;

        $level_title = validate_text_field($_POST['level_title'], _MD_ARMS_WORD_TITLE);
        $level_desc = validate_text_field($_POST['level_desc'], _MD_ARMS_DESC);

        // Level image can be empty...
        if (isset($_POST['level_image']) and ('- - - - -' != $_POST['level_image'])) :
            {
                $level_image = (string)$_POST['level_image'];
            } else :
            {
                $level_image = '';
            }
        endif;

        // Lets get old section data...
        $q_str = 'SELECT level_name, level_desc, level_image FROM ' . $xoopsDB->prefix('arms_articals_levels') . ' WHERE level_id=' . $idx;
        if ($result = $xoopsDB->query($q_str)) :
            {
                $row = $xoopsDB->fetchArray($result);
                if (($row['level_name'] == $level_title) and ($row['level_desc'] == $level_desc) and ($row['level_image'] == $level_image)) :
                    {
                        print_and_die(_ME_ARMS_NO_CHANGE);
                    }
                endif;

                // New level name (remember that it needs to unique)???
                if ($row['level_name'] != $level_title) :
                    {
                        $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals_levels') . " WHERE level_name='" . $level_title . "'";
                        if ($result = $xoopsDB->query($q_str)) :
                            {
                                if ($xoopsDB->getRowsNum($result) > 0) :
                                    {
                                        print_and_die(sprintf(_ME_ARMS_LEVEL_EXISTS, $level_title));
                                    }
                                endif;
                            } else :
                            {
                                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                            }
                        endif;
                    }
                endif;
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets update data...
        if ('' == $level_image) :
            {
                $q_str = sprintf("UPDATE %s SET level_name = '%s', level_desc='%s', level_image='' WHERE level_id=%s", $xoopsDB->prefix('arms_articals_levels'), $level_title, $level_desc, $idx);
            } else :
            {
                $q_str = sprintf("UPDATE %s SET level_name = '%s', level_desc='%s', level_image='%s' WHERE level_id=%s", $xoopsDB->prefix('arms_articals_levels'), $level_title, $level_desc, $level_image, $idx);
            }
        endif;
        if ($xoopsDB->query($q_str)) :
            {
                redirect_header('index.php?w=levels', 1, sprintf(_MM_ARMS_LEVEL_UPDATED, $row['level_name']));
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    }
// ============ //
// Delete Level //
// ============ //
elseif ('deletelevel' == $what) :
    {
        if (isset($_POST['confirm']) and ('confirmed' == (string)$_POST['confirm'])) :
            {
                $confirmed = true;
            } else :
            {
                $confirmed = false;
            }
        endif;

        if (!$confirmed) :
            {
                print_and_die(_ME_ARMS_NOT_CONFIRMED);
            }
        endif;

        // Collect section index...
        if (isset($_GET['idx'])) :
            {
                $idx = (int)$_GET['idx'];
            } else :
            {
                print_and_die(_ME_ARMS_PARAMS);
            }
        endif;

        $move_to = validate_text_field($_POST['move_to'], _MD_ARMS_MOVE_ARTICALS);

        // Lets delete level
        $q_str = 'DELETE FROM ' . $xoopsDB->prefix('arms_articals_levels') . ' WHERE level_id=' . $idx;
        if (!$xoopsDB->query($q_str)) :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lest move or delete articals...
        if (_MD_ARMS_OP_DELETE == $move_to) :
            {
                // Lets delete articals...
                $q_str = 'DELETE FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE level_id=' . $idx;
                if (!$xoopsDB->query($q_str)) :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            } else :
            {
                // Where do we need to move articals???
                $q_str = 'SELECT level_id FROM ' . $xoopsDB->prefix('arms_articals_levels') . " WHERE level_name='" . $move_to . "'";
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        $row = $xoopsDB->fetchArray($result);
                        $move_to_idx = $row['level_id'];
                    } else :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;

                // Now update data...
                $q_str = 'UPDATE ' . $xoopsDB->prefix('arms_articals') . ' SET level_id=' . $move_to_idx . ' WHERE level_id=' . $idx;
                if (!$xoopsDB->query($q_str)) :
                    {
                        print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            }
        endif;

        // Lets tell people what have we done...
        redirect_header('index.php?w=levels', 1, _MM_ARMS_LEVEL_DELETED);
    }
// ============= //
// Add Moderator //
// ============= //
elseif ('addmod' == $what) :
    {
        $mod_user = validate_text_field($_POST['mod_user'], _MD_ARMS_USERNAME);
        $mod_section = validate_text_field($_POST['mod_section'], _MD_ARMS_SECTION);

        // Lets get user id
        $q_str = 'SELECT uid FROM ' . $xoopsDB->prefix('users') . " WHERE uname='" . $mod_user . "'";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        print_and_die(_ME_ARMS_USER_DOESNT_EXISTS);
                    }
                endif;
                $row = $xoopsDB->fetchArray($result);
                $mod_user_id = $row['uid'];
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets get section
        $q_str = 'SELECT sec_id FROM ' . $xoopsDB->prefix('arms_sections') . " WHERE sec_title='" . $mod_section . "'";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        print_and_die(_MI_ARMS_NO_SECTIONS);
                    }
                endif;
                $row = $xoopsDB->fetchArray($result);
                $mod_section_id = $row['sec_id'];
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Is user already moderator of selected section???
        $q_str = 'SELECT uid FROM ' . $xoopsDB->prefix('arms_moderators') . " WHERE uid=$mod_user_id AND sec_id=$mod_section_id";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        print_and_die(_ME_ARMS_ALREADY_MODERATOR);
                    }
                endif;
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets insert data...
        $q_str = sprintf('INSERT INTO %s (uid, sec_id) VALUES (%s, %s)', $xoopsDB->prefix('arms_moderators'), $mod_user_id, $mod_section_id);
        if ($xoopsDB->query($q_str)) :
            {
                redirect_header('index.php?w=oderators', 1, sprintf(_MM_MOD_ADDED, $mod_user, $mod_section));
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    }
// ================ //
// Delete moderator //
// ================ //
elseif ('deletemod' == $what) :
    {
        // Collect user id...
        if (isset($_GET['uid'])) :
            {
                $arms_uid = (int)$_GET['uid'];
            } else :
            {
                print_and_die(_ME_ARMS_PARAMS);
            }
        endif;

        // Collect section id...
        if (isset($_GET['secid'])) :
            {
                $arms_secid = (int)$_GET['secid'];
            } else :
            {
                print_and_die(_ME_ARMS_PARAMS);
            }
        endif;

        //
        // I copied this query output and PHPMyAdmin didn`t repored any errors...
        // I realy don`t know what is wrong with this!
        //

        // lets delete data...
        $q_str = 'DELETE FROM ' . $xoopsDB->prefix('arms_moderators') . " WHERE uid=$arms_uid AND sec_id=$arms_secid";
        if ($xoopsDB->query($q_str)) :
            {
                redirect_header('index.php?w=oderators', 1, _MM_MOD_DELETED);
            } else :
            {
                print_and_die(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    } elseif ('update' == $what) :
    {
        $arms_ver = validate_text_field($_POST['update_ver']);

        // Get File...
        require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/admin/update.php';
        for ($i = 0, $iMax = count($arms_update); $i < $iMax; $i++) :
            {
                if ($arms_update[$i]['title'] == $arms_ver) :
                    {
                        $sql_file = $arms_update[$i]['sql_file'];
                        break;
                    }
                endif;
            }
        endfor;

        $sql_file = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/sql/' . $sql_file;
        $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/admin/index.php';

        // Update DD
        if ($xoopsDB->queryFromFile($sql_file)) :
            {
                arms_error(_MM_ARMS_UPDATE, $arms_redirect);
            } else :
            {
                $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/admin/index.php?w=update';
                arms_error(_ME_ARMS_UPDATE_FAILED, $arms_redirect);
            }
        endif;
    }
endif;
