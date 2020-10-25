<?php

// ========================================================================== //
// ArMS = Artical Menagment System                                            //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Version:       : 0.1                                                     //
//   Started        : 10:49:02 AM 9/10/2003                                   //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 10:15:23 PM 9/28/2003                                   //
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

function next_field_value($db_table, $db_field)
{
    global $xoopsDB;

    $q_str = "SELECT $db_field FROM " . $xoopsDB->prefix($db_table) . " ORDER BY $db_field DESC";

    if (!($result = $xoopsDB->query($q_str))) :
        {
            arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
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

function is_orphened_sec($sec_id, $redirect = '')
{
    global $xoopsDB, $xoopsModule;

    // To make sure...

    $sec_id = (int)$sec_id;

    if ('' == $redirect) :
        {
            $redirect = XOOPS_URL;
        }

    endif;

    // Get cat id

    $q_str = 'SELECT cat_id FROM ' . $xoopsDB->prefix('arms_sections') . " WHERE sec_id=$sec_id";

    $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $redirect);

    if ($xoopsDB->getRowsNum($result) < 1) :
        {
            return true;
        }

    endif;

    $row = $xoopsDB->fetchArray($result);

    // Check if cat exists...

    $q_str = 'SELECT count(cat_id) AS count_cats FROM ' . $xoopsDB->prefix('arms_categories') . ' WHERE cat_id=' . $row['cat_id'];

    $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $redirect);

    $row = $xoopsDB->fetchArray($result);

    if ($row['count_cats'] > 0) :
        {
            return false;
        } else :
        {
            return true;
        }

    endif;
}

// Returns first row of query result...
function arms_fetch_first($q_str, $message, $redirect = '')
{
    global $xoopsDB;

    $f_row = false;

    if ($result = $xoopsDB->query($q_str)) :
        {
            if ($xoopsDB->getRowsNum($result) > 1) :
                {
                    arms_error($message, $redirect);
                }
            endif;

            $f_row = $xoopsDB->fetchArray($result);
        } else :
        {
            arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $redirect);
        }

    endif;

    return $f_row;
}

// Returns all rows of query result
function arms_fetch_all($q_str, $message, $redirect = '')
{
    global $xoopsDB;

    $return = false;

    if ($result = $xoopsDB->query($q_str)) :
        {
            if ($xoopsDB->getRowsNum($result) > 1) :
                {
                    arms_error($message, $redirect);
                }
            endif;

            $counter = 0;
            $return = [];
            while (false !== ($f_row = $xoopsDB->fetchArray($result))) {
                $return[$counter] = $row;

                $counter++;
            }
        } else :
        {
            arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $redirect);
        }

    endif;

    return $return;
}

function inc_field_value($db_table, $db_field, $index, $index_field)
{
    $return = false;

    global $xoopsDB;

    $q_str = "SELECT $db_field FROM " . $xoopsDB->prefix($db_table) . " WHERE $index_field=$index";

    if ($result = $xoopsDB->query($q_str)) :
        {
            if ($xoopsDB->getRowsNum($result) > 0) :
                {
                    $row = $xoopsDB->fetchArray($result);
                    $new_value = ++$row[$db_field];
                    $q_str = 'UPDATE ' . $xoopsDB->prefix($db_table) . " SET $db_field=$new_value WHERE $index_field=$index";
                    if ($xoopsDB->query($q_str)) :
                        {
                            $return = true;
                        }
                    endif;
                }
            endif;
        }

    endif;

    return $return;
}

// Get section moderator user names and uid-s in array...
function get_arms_moderators($section_id)
{
    global $xoopsDB;

    $q_str = 'SELECT uid FROM ' . $xoopsDB->prefix('arms_moderators') . ' WHERE sec_id=' . (int)$section_id;

    $result = $xoopsDB->query($q_str) || exit('Error');

    if ($xoopsDB->getRowsNum($result) > 0) :
        {
            $moderators = [];
            $counter = 0;
            while (false !== ($row = $xoopsDB->fetchArray($result))) {
                $q_str = 'SELECT uname FROM ' . $xoopsDB->prefix('users') . ' WHERE uid=' . $row['uid'];

                $res = $xoopsDB->query($q_str) || exit('Error');

                $userrow = $xoopsDB->fetchArray($res);

                $moderators[$counter]['uid'] = $row['uid'];

                $moderators[$counter]['username'] = $userrow['uname'];

                $counter++;
            }

            return $moderators;
        } else :
        {
            return [];
        }

    endif;
}

// Checks database to see is user section moderator...
function is_section_moderator($section_id, $uid)
{
    global $xoopsDB;

    $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_moderators') . ' WHERE sec_id=' . $section_id . ' AND uid=' . $uid;

    if ($result = $xoopsDB->query($q_str)) :
        {
            if ($xoopsDB->getRowsNum($result) > 0) :
                {
                    return true;
                } else :
                {
                    return false;
                }
            endif;
        } else :
        {
            return false;
        }

    endif;
}

// Provides simple interface to artical_operation function
function can_edit_art($uid, $art_id)
{
    return artical_operation($uid, $art_id, 'aut_edit');
}

// Provides simple interface to artical_operation function
function can_delete_art($uid, $art_id)
{
    return artical_operation($uid, $art_id, 'aut_delete');
}

// Checks database and config to see what user can do with artical
function artical_operation($uid, $art_id, $operation = 'aut_edit')
{
    global $xoopsDB;

    global $xoopsModuleConfig;

    $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_id=' . $art_id;

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

    // Author???

    if ($xoopsModuleConfig($operation)) :
        {
            if ($art_row['uid'] == $uid) :
                {
                    return true;
                }
            endif;
        }

    endif;

    // Moderator???

    if (is_section_moderator($art_row['sec_id'], $uid)) :
        {
            return true;
        } else :
        {
            return false;
        }

    endif;
}
