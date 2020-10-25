<?php

// ========================================================================== //
// ArMS = Artical Menagment System                                            //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Version:       : 0.1                                                     //
//   Started        : 8:05:06 PM 9/12/2003                                    //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 8:05:13 PM 9/12/2003                                    //
//   Last update by : Ilija Studen                                            //
//                                                                            //
//   Change log:                                                              //
//   -----------                                                              //
//   - add artical subroutine changed (uip fix)                               //
//   - edit artical data subroutine changed (uip and cross posting fix)       //
//   - rate artical subroutine changed (uip fix)                              //
//   - add page subroutine changed (uip fix)                                  //
//   - reorder pages subroutine added                                         //
//   - edit page subroutine changed (uip fix)                                 //
//   - add, delete permission, clear, update permissions subroutine added     //
//                                                                            //
// ========================================================================== //
// (c) 2003. by Ilija Studen                                                  //
//              (mail: ilija_studen@yahoo.com)                                //
//              (home: ilija.ionbee.net)                                      //
// ========================================================================== //

function validate_text_field($var, $min_length, $fld_name = '', $redirect = '')
{
    if (isset($var) and ('' != (string)$var)) :
        {
            $value = (string)$var;
            if (mb_strlen($value) < $min_length) :
                {
                    arms_error(sprintf(_ME_ARMS_TEXT_VALID, $fld_name), $redirect);
                }
            endif;

            return $value;
        } else :
        {
            arms_error(sprintf(_ME_ARMS_TEXT_VALID, $fld_name), $redirect);
        }

    endif;
}

// This function updates page_order of selected artical to go from 1 to X.
// Call it when you think (or know) that thera are gaps or duplicats in page
// ovder of artical...
function fix_page_order($art_id, $redirect = '')
{
    global $xoopsDB;

    $q_str = 'SELECT page_id, page_order FROM ' . $xoopsDB->prefix('arms_pages') . ' WHERE art_id=' . $art_id . ' ORDER BY page_order';

    if ($result = $xoopsDB->query($q_str)) :
        {
            $pages = [];
            $counter = 1;
            while (false !== ($row = $xoopsDB->fetchArray($result))) {
                $pages[$counter]['page_id'] = $row['page_id'];

                $pages[$counter]['page_order'] = $counter;

                $counter++;
            }

            // Update order...
            for ($i = 1, $iMax = count($pages); $i <= $iMax; $i++) {
                $q_str = sprintf(
                    'UPDATE %s SET page_order = %s WHERE page_id = %s',
                    $xoopsDB->prefix('arms_pages'),
                    $pages[$i]['page_order'],
                    $pages[$i]['page_id']
                );

                if (!$xoopsDB->query($q_str)) :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $redirect);
                    }

                endif;
            }
        } else :
        {
            arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $redirect);
        }

    endif;
}

require __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';

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
// Add Artical //
// =========== //
if ('addart' == $what) :
    {
        $arms_redirect = XOOPS_URL . '/modules/arms/forms.php?w=addart';

        $art_title = validate_text_field($_POST['art_title'], $xoopsModuleConfig['min_art_title_length'], _MD_ARMS_WORD_TITLE, $arms_redirect);
        $art_desc = validate_text_field($_POST['art_desc'], $xoopsModuleConfig['min_art_desc_length'], _MD_ARMS_DESC, $arms_redirect);
        $art_section = validate_text_field($_POST['art_section'], 0, _MD_ARMS_SECTION);
        $art_level = validate_text_field($_POST['art_level'], 0, _MD_ARMS_LEVEL);

        // Lets get section id...
        $q_str = 'SELECT sec_id FROM ' . $xoopsDB->prefix('arms_sections') . " WHERE sec_title='" . $art_section . "'";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result)) :
                    {
                        $row = $xoopsDB->fetchArray($result);
                        $art_section_id = $row['sec_id'];
                    } else :
                    {
                        arms_error(_ME_ARMS_SECTION_DOESNT_EXISTS);
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets get level id...
        $q_str = 'SELECT level_id FROM ' . $xoopsDB->prefix('arms_articals_levels') . " WHERE level_name='" . $art_level . "'";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        $row = $xoopsDB->fetchArray($result);
                        $art_level_id = $row['level_id'];
                    } else :
                    {
                        arms_error(_ME_ARMS_LEVEL_DOESNT_EXISTS);
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Lets find index of our artical (needed for redirection...)
        $art_id = next_field_value('arms_articals', 'art_id');

        // Sanitize data...
        $my_ts = MyTextSanitizer::getInstance();
        $art_title = $my_ts->censorString($art_title);
        $art_title = $my_ts->addSlashes($art_title);
        $art_desc = $my_ts->censorString($art_desc);
        $art_desc = $my_ts->addSlashes($art_desc);

        // Now insert data...
        $q_str = sprintf(
            "INSERT INTO %s (art_id, sec_id, level_id, uid, art_title, art_desc, art_posttime, uip) VALUES (%s, %s, %s, %s, '%s', '%s', %s, '%s')",
            $xoopsDB->prefix('arms_articals'),
            $art_id,
            $art_section_id,
            $art_level_id,
            $xoopsUser->uid(),
            $art_title,
            $art_desc,
            time(),
            $_SERVER['REMOTE_ADDR']
        );
        if ($xoopsDB->query($q_str)) :
            {
                redirect_header(XOOPS_URL . '/modules/arms/forms.php?w=editart&idx=' . $art_id, 1, _MM_ARMS_ARTICAL_ADDED);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
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

        $arms_redirect = XOOPS_URL . '/modules/arms/forms.php?w=editart&idx=' . $idx;

        $art_title = validate_text_field($_POST['art_title'], $xoopsModuleConfig['min_art_title_length'], _MD_ARMS_WORD_TITLE, $arms_redirect);
        $art_desc = validate_text_field($_POST['art_desc'], $xoopsModuleConfig['min_art_desc_length'], _MD_ARMS_DESC, $arms_redirect);
        $art_section = validate_text_field($_POST['art_section'], 0, _MD_ARMS_SECTION, $arms_redirect);
        $art_level = validate_text_field($_POST['art_level'], 0, _MD_ARMS_LEVEL, $arms_redirect);

        // We could add here a code block to see was there any changes or not...
        // I decided not to to increase script performance...

        // Lets get section id...
        $q_str = 'SELECT sec_id FROM ' . $xoopsDB->prefix('arms_sections') . " WHERE sec_title='" . $art_section . "'";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result)) :
                    {
                        $row = $xoopsDB->fetchArray($result);
                        $art_section_id = $row['sec_id'];
                    } else :
                    {
                        arms_error(_ME_ARMS_SECTION_DOESNT_EXISTS, $arms_redirect);
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;

        // Lets get level id...
        $q_str = 'SELECT level_id FROM ' . $xoopsDB->prefix('arms_articals_levels') . " WHERE level_name='" . $art_level . "'";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        $row = $xoopsDB->fetchArray($result);
                        $art_level_id = $row['level_id'];
                    } else :
                    {
                        arms_error(_ME_ARMS_LEVEL_DOESNT_EXISTS, $arms_redirect);
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;

        // Increment updatecount??? Seems like a good idea :)
        $update_count = next_field_value('arms_articals', 'art_updatecount');

        // Sanitize data...
        $my_ts = MyTextSanitizer::getInstance();
        $art_title = $my_ts->censorString($art_title);
        $art_title = $my_ts->addSlashes($art_title);
        $art_desc = $my_ts->censorString($art_desc);
        $art_desc = $my_ts->addSlashes($art_desc);

        // Now insert data...
        $q_str = sprintf(
            "UPDATE %s SET sec_id=%s, level_id=%s, art_title='%s', art_desc='%s', art_updatecount=%s, art_lastupdate=%s, art_lastupdateby=%s, art_lastupdatebyip = '%s' WHERE art_id=%s",
            $xoopsDB->prefix('arms_articals'),
            $art_section_id,
            $art_level_id,
            $art_title,
            $art_desc,
            $update_count,
            time(),
            $xoopsUser->uid(),
            $_SERVER['REMOTE_ADDR'],
            $idx
        );
        if (!$xoopsDB->query($q_str)) :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Delete crossposting for this section...
        $q_str = sprintf(
            'DELETE FROM %s WHERE sec_id = %s AND art_id = %s',
            $xoopsDB->prefix('arms_cross_section'),
            $art_section_id,
            $idx
        );
        $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
        arms_error(_MM_ARMS_ARTICAL_EDITED, $arms_redirect);
    }
// ============== //
// Delete Artical //
// ============== //
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

        // Is action confirmed???
        $confirmed = false;
        if (isset($_POST['arms_confirm']) and ('confirmed' == (string)$_POST['arms_confirm'])) :
            {
                $confirmed = true;
            }
        endif;

        if ($confirmed) :
            {
                // Delete pages
                $q_str = 'DELETE FROM ' . $xoopsDB->prefix('arms_pages') . ' WHERE art_id=' . $idx;
                if (!$xoopsDB->query($q_str)) :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
                // Delete artical
                $q_str = 'DELETE FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_id=' . $idx;
                if (!$xoopsDB->query($q_str)) :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;

                xoops_comment_delete($xoopsModule->getVar('mid'), $idx);

                arms_error(_MM_ARTICAL_DELETE);
            } else :
            {
                arms_error(_ME_ARMS_NOT_CONFIRMED);
            }
        endif;
    }
// ================ //
// Activate article //
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

        // Get section id (for moderators)
        $q_str = 'SELECT sec_id FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_id=' . $idx;
        $art_row = arms_fetch_first($q_str, _ME_ARMS_SECTION_DOESNT_EXISTS);

        // Check levels and permissions...
        if (is_object($xoopsUser)) :
            {
                $arms_is_admin = $xoopsUser->isAdmin($xoopsModule->mid());
                $arms_is_moderator = is_section_moderator($idx, $xoopsUser->uid());
            } else :
            {
                arms_error(_ME_ARMS_REG_ONLY);
            }
        endif;

        if (!($arms_is_admin or ($arms_is_moderator and $xoopsModuleConfig['mod_activate']))) :
            {
                arms_error(_ME_ARMS_NO_PERMISSION);
            }
        endif;

        // Is action confirmed???
        $confirmed = false;
        if (isset($_POST['arms_confirm']) and ('confirmed' == (string)$_POST['arms_confirm'])) :
            {
                $confirmed = true;
            }
        endif;

        if ($confirmed) :
            {
                // Lets activate artical
                $q_str = sprintf('UPDATE %s SET art_activated=1 WHERE art_id=%s', $xoopsDB->prefix('arms_articals'), $idx);
                if ($xoopsDB->query($q_str)) :
                    {
                        arms_error(_MM_ARTICAL_ACTIVATED);
                    } else :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            } else :
            {
                arms_error(_ME_ARMS_NOT_CONFIRMED);
            }
        endif;
    }
// ================== //
// Deactivate article //
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

        // Get section id (for moderators)
        $q_str = 'SELECT sec_id FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_id=' . $idx;
        $art_row = arms_fetch_first($q_str, _ME_ARMS_SECTION_DOESNT_EXISTS);

        // Check levels and permissions...
        if (is_object($xoopsUser)) :
            {
                $arms_is_admin = $xoopsUser->isAdmin($xoopsModule->mid());
                $arms_is_moderator = is_section_moderator($idx, $xoopsUser->uid());
            } else :
            {
                arms_error(_ME_ARMS_REG_ONLY);
            }
        endif;

        if (!($arms_is_admin or ($arms_is_moderator and $xoopsModuleConfig['mod_activate']))) :
            {
                arms_error(_ME_ARMS_NO_PERMISSION);
            }
        endif;

        // Is action confirmed???
        $confirmed = false;
        if (isset($_POST['arms_confirm']) and ('confirmed' == (string)$_POST['arms_confirm'])) :
            {
                $confirmed = true;
            }
        endif;

        if ($confirmed) :
            {
                // Lets activate artical
                $q_str = sprintf('UPDATE %s SET art_activated=0 WHERE art_id=%s', $xoopsDB->prefix('arms_articals'), $idx);
                if ($xoopsDB->query($q_str)) :
                    {
                        arms_error(_MM_ARTICAL_DEACTIVATED);
                    } else :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            } else :
            {
                arms_error(_ME_ARMS_NOT_CONFIRMED);
            }
        endif;
    }
// ============ //
// Rate Article //
// ============ //
elseif ('rateart' == $what) :
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

        // Page handling...
        $arms_on_page = 1;
        if (isset($_GET['page'])) :
            {
                $arms_on_page = (int)$_GET['page'];
            }
        endif;

        $arms_redirect = XOOPS_URL . "/modules/arms/view.php?w=art&idx=$idx&page=$arms_on_page";

        // Is user already voted???
        $q_str = 'SELECT vote_id FROM ' . $xoopsDB->prefix('arms_votelog') . " WHERE art_id=$idx AND uid=" . $xoopsUser->uid();
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        arms_error(_ME_ARMS_ALREADY_RATED, $arms_redirect);
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;

        if (isset($_POST['rate_art'])) :
            {
                $art_rate = (string)$_POST['rate_art'];
            } else :
            {
                arms_error(_ME_ARMS_PARAMS, $arms_redirect);
            }
        endif;

        if ('rate5' == $art_rate) :
            {
                $art_rate = 5;
            } elseif ('rate4' == $art_rate) :
            {
                $art_rate = 4;
            } elseif ('rate3' == $art_rate) :
            {
                $art_rate = 3;
            } elseif ('rate2' == $art_rate) :
            {
                $art_rate = 2;
            } else :
            {
                $art_rate = 1;
            }
        endif;

        // Lets get some data...
        $q_str = 'SELECT art_ratecount, art_ratetotal FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_id=' . $idx;
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_ARTICAL_DOESNT_EXISTS, $arms_redirect);
                    }
                endif;

                $rate_row = $xoopsDB->fetchArray($result);
                $rate_row['art_ratecount'] = ++$rate_row['art_ratecount'];
                $rate_row['art_ratetotal'] += $art_rate;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;

        // Lets update data...
        $q_str = sprintf(
            'UPDATE %s SET art_ratecount=%s, art_ratetotal=%s WHERE art_id=%s',
            $xoopsDB->prefix('arms_articals'),
            $rate_row['art_ratecount'],
            $rate_row['art_ratetotal'],
            $idx
        );
        if (!$xoopsDB->query($q_str)) :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;

        // Lets insert some data to votelog...
        $q_str = sprintf(
            "INSERT INTO %s (vote_time, art_id, uid, uip) VALUES (%s, %s, %s, '%s')",
            $xoopsDB->prefix('arms_votelog'),
            time(),
            $idx,
            $xoopsUser->uid(),
            $_SERVER['REMOTE_ADDR']
        );
        if ($xoopsDB->query($q_str)) :
            {
                redirect_header($arms_redirect, 1, _MM_RATE_SUBMITED);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;
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

        // Where to redirect when data is invalid???
        $arms_redirect = XOOPS_URL . '/modules/arms/forms.php?w=addpage&idx=' . $idx;

        // Text fields...
        $page_title = validate_text_field($_POST['page_title'], $xoopsModuleConfig['min_page_title_length'], _MD_ARMS_WORD_TITLE, $arms_redirect);
        $page_level = validate_text_field($_POST['page_level'], 1, _MD_ARMS_LEVEL, $arms_redirect);
        $page_desc = validate_text_field($_POST['page_desc'], $xoopsModuleConfig['min_page_desc_length'], _MD_ARMS_DESC, $arms_redirect);
        $page_text = validate_text_field($_POST['page_text'], $xoopsModuleConfig['min_page_text_length'], _MD_ARMS_WORD_TEXT, $arms_redirect);
        $page_order = validate_text_field($_POST['page_order'], 1, _MD_ARMS_ORDER, $arms_redirect);

        if (!is_numeric($page_order)) :
            {
                $q_str = 'SELECT page_order FROM ' . $xoopsDB->prefix('arms_pages') . ' WHERE art_id=' . $idx . ' ORDER BY page_order DESC LIMIT 0, 1';
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        if ($xoopsDB->getRowsNum($result) < 1) :
                            {
                                $page_order = 1;
                            } else :
                            {
                                $tmp_row = $xoopsDB->fetchArray($result);
                                $page_order = ++$tmp_row['page_order'];
                            }
                        endif;
                    } else :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            } else :
            {
                $page_order = (int)$page_order;
            }
        endif;

        // Options
        $allow_html = 0;
        if (isset($_POST['allow_html']) and ('checked' == (string)$_POST['allow_html'])) :
            {
                $allow_html = 1;
            }
        endif;
        $allow_emotions = 0;
        if (isset($_POST['allow_emotions']) and ('checked' == (string)$_POST['allow_emotions'])) :
            {
                $allow_emotions = 1;
            }
        endif;
        $allow_bbcode = 0;
        if (isset($_POST['allow_bbcode']) and ('checked' == (string)$_POST['allow_bbcode'])) :
            {
                $allow_bbcode = 1;
            }
        endif;

        // Lets get level id...
        $q_str = 'SELECT level_id FROM ' . $xoopsDB->prefix('arms_articals_levels') . " WHERE level_name='" . $page_level . "'";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        $row = $xoopsDB->fetchArray($result);
                        $page_level_id = $row['level_id'];
                    } else :
                    {
                        arms_error(_ME_ARMS_LEVEL_DOESNT_EXISTS);
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Sanitize form data...
        $my_ts = MyTextSanitizer::getInstance();
        $page_title = $my_ts->censorString($page_title);
        $page_title = $my_ts->addSlashes($page_title);
        $page_desc = $my_ts->censorString($page_desc);
        $page_desc = $my_ts->addSlashes($page_desc);
        $page_text = $my_ts->censorString($page_text);
        $page_text = $my_ts->addSlashes($page_text);

        // Lets insert data...
        $arms_redirect = XOOPS_URL . '/modules/arms/forms.php?w=editart&idx=' . $idx;
        $q_str = sprintf(
            "INSERT INTO %s (page_order, art_id, level_id, uid, page_title, page_desc, page_text, page_allow_html, page_allow_emotions, page_allow_bbcode, page_posttime, uip) VALUES (%s, %s, %s, %s, '%s', '%s', '%s', %s, %s, %s, %s, '%s')",
            $xoopsDB->prefix('arms_pages'),
            $page_order,
            $idx,
            $page_level_id,
            $xoopsUser->uid(),
            $page_title,
            $page_desc,
            $page_text,
            $allow_html,
            $allow_emotions,
            $allow_bbcode,
            time(),
            $_SERVER['REMOTE_ADDR']
        );
        if ($xoopsDB->query($q_str)) :
            {
                redirect_header($arms_redirect, 1, sprintf(_MM_ARMS_PAGE_ADDED, $page_title));
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    }
// ============= //
// Reorder Pages //
// ============= //
elseif ('reorderpages' == $what) :
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

        $arms_redirect = XOOPS_URL . '/modules/arms/forms.php?w=editart&idx=' . $idx;

        $confirmed = false;
        if (isset($_POST['arms_confirm']) and ('confirmed' == (string)$_POST['arms_confirm'])) :
            {
                $confirmed = true;
            }
        endif;

        if (!$confirmed) :
            {
                arms_error(_ME_ARMS_NOT_CONFIRMED, $arms_redirect);
            }
        endif;

        // Let ids...
        $q_str = 'SELECT page_id FROM ' . $xoopsDB->prefix('arms_pages') . " WHERE art_id=$idx ORDER BY page_order";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_NO_PAGES_IN_ART, $arms_redirect);
                    }
                endif;

                while (false !== ($row = $xoopsDB->fetchArray($result))) :
                    {
                        $fld = 'pageorder' . $row['page_id'];
                        if (isset($_POST[$fld]) and is_numeric($_POST[$fld])) :
                            {
                                $new_order = (int)$_POST[$fld];
                            } else :
                            {
                                $new_order = 0;
                            }
                        endif;

                        $q_str = sprintf(
                            "UPDATE %s SET page_order=%s, page_updatecount = page_updatecount + 1, page_lastupdate = %s, page_lastupdateby = %s, page_lastupdatebyip = '%s' WHERE page_id=%s",
                            $xoopsDB->prefix('arms_pages'),
                            $new_order,
                            time(),
                            $xoopsUser->uid(),
                            $_SERVER['REMOTE_ADDR'],
                            $row['page_id']
                        );
                        if (!$xoopsDB->query($q_str)) :
                            {
                                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
                            }
                        endif;
                    }
                endwhile;
                arms_error(_MM_ARMS_PAGES_REORDERED, $arms_redirect);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;
    }
// ========= //
// Edit page //
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

        // Where to redirect when data is invalid???
        $arms_redirect = XOOPS_URL . '/modules/arms/forms.php?w=editpage&idx=' . $idx;

        // Text fields...
        $page_title = validate_text_field($_POST['page_title'], $xoopsModuleConfig['min_page_title_length'], _MD_ARMS_WORD_TITLE, $arms_redirect);
        $page_level = validate_text_field($_POST['page_level'], 1, _MD_ARMS_LEVEL, $arms_redirect);
        $page_desc = validate_text_field($_POST['page_desc'], $xoopsModuleConfig['min_page_desc_length'], _MD_ARMS_DESC, $arms_redirect);
        $page_text = validate_text_field($_POST['page_text'], $xoopsModuleConfig['min_page_text_length'], _MD_ARMS_WORD_TEXT, $arms_redirect);

        $page_order = validate_text_field($_POST['page_order'], 1, _MD_ARMS_ORDER, $arms_redirect);

        if (!is_numeric($page_order)) :
            {
                // We`ll need artical ID to make this code block work!
                $q_str = 'SELECT art_id FROM ' . $xoopsDB->prefix('arms_pages') . ' WHERE page_id=' . $idx;
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        if ($xoopsDB->getRowsNum($result) < 1) :
                            {
                                arms_error(_ME_ARMS_PAGE_DOESNT_EXISTS);
                            }
                        endif;

                        $art_row = $xoopsDB->fetchArray($result);
                        $art_idx = $art_row['art_id'];
                    } else :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;

                $q_str = 'SELECT page_order FROM ' . $xoopsDB->prefix('arms_pages') . ' WHERE art_id=' . $art_idx . ' ORDER BY page_order DESC LIMIT 0, 1';
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        if ($xoopsDB->getRowsNum($result) < 1) :
                            {
                                $page_order = 1;
                            } else :
                            {
                                $tmp_row = $xoopsDB->fetchArray($result);
                                $page_order = ++$tmp_row['page_order'];
                            }
                        endif;
                    } else :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            } else :
            {
                $page_order = (int)$page_order;
            }
        endif;

        // Options
        $allow_html = 0;
        if (isset($_POST['allow_html']) and ('checked' == (string)$_POST['allow_html'])) :
            {
                $allow_html = 1;
            }
        endif;
        $allow_emotions = 0;
        if (isset($_POST['allow_emotions']) and ('checked' == (string)$_POST['allow_emotions'])) :
            {
                $allow_emotions = 1;
            }
        endif;
        $allow_bbcode = 0;
        if (isset($_POST['allow_bbcode']) and ('checked' == (string)$_POST['allow_bbcode'])) :
            {
                $allow_bbcode = 1;
            }
        endif;

        // Lets get level id...
        $q_str = 'SELECT level_id FROM ' . $xoopsDB->prefix('arms_articals_levels') . " WHERE level_name='" . $page_level . "'";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        $row = $xoopsDB->fetchArray($result);
                        $page_level_id = $row['level_id'];
                    } else :
                    {
                        arms_error(_ME_ARMS_LEVEL_DOESNT_EXISTS);
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        // Sanitize form data...
        $my_ts = MyTextSanitizer::getInstance();
        $page_title = $my_ts->censorString($page_title);
        $page_title = $my_ts->addSlashes($page_title);
        $page_desc = $my_ts->censorString($page_desc);
        $page_desc = $my_ts->addSlashes($page_desc);
        $page_text = $my_ts->censorString($page_text);
        $page_text = $my_ts->addSlashes($page_text);

        // Update count
        $update_count = next_field_value('arms_pages', 'page_updatecount');

        // Lets insert data...
        $q_str = sprintf(
            "UPDATE %s SET page_order=%s, level_id=%s, page_title='%s', page_desc='%s', page_text='%s', page_allow_html=%s, page_allow_emotions=%s, page_allow_bbcode=%s, page_updatecount=%s, page_lastupdate=%s, page_lastupdateby=%s, uip='%s' WHERE page_id=%s",
            $xoopsDB->prefix('arms_pages'),
            $page_order,
            $page_level_id,
            $page_title,
            $page_desc,
            $page_text,
            $allow_html,
            $allow_emotions,
            $allow_bbcode,
            $update_count,
            time(),
            $xoopsUser->uid(),
            $_SERVER['REMOTE_ADDR'],
            $idx
        );
        if ($xoopsDB->query($q_str)) :
            {
                $q_str = 'SELECT art_id FROM ' . $xoopsDB->prefix('arms_pages') . " WHERE page_id=$idx";
                if ($result = $xoopsDB->query($q_str)) :
                    {
                        if ($row = $xoopsDB->fetchArray($result)) :
                            {
                                $arms_redirect = XOOPS_URL . '/modules/arms/forms.php?w=editart&idx=' . $row['art_id'];
                            } else :
                            {
                                $arms_redirect = XOOPS_URL . '/modules/arms/index.php';
                            }
                        endif;
                    } else :
                    {
                        $arms_redirect = XOOPS_URL . '/modules/arms/index.php';
                    }
                endif;
                redirect_header($arms_redirect, 1, _MM_ARMS_PAGE_EDITED);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;
    }
// =========== //
// Delete page //
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

        // Is action confirmed???
        $confirmed = false;
        if (isset($_POST['arms_confirm']) and ('confirmed' == (string)$_POST['arms_confirm'])) :
            {
                $confirmed = true;
            }
        endif;

        // We need artical index for redirection
        $q_str = 'SELECT art_id FROM ' . $xoopsDB->prefix('arms_pages') . ' WHERE page_id=' . $idx;
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

        $arms_redirect = XOOPS_URL . '/modules/arms/forms.php?w=editart&idx=' . $page_row['art_id'];

        if ($confirmed) :
            {
                // Lets delete page!
                $q_str = 'DELETE FROM ' . $xoopsDB->prefix('arms_pages') . ' WHERE page_id=' . $idx;
                if ($xoopsDB->query($q_str)) :
                    {
                        redirect_header($arms_redirect, 1, _MM_ARMS_PAGE_DELETED);
                    } else :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            } else :
            {
                arms_error(_ME_ARMS_NOT_CONFIRMED, $arms_redirect);
            }
        endif;
    }
// ============== //
// Add Permission //
// ============== //
elseif ('addperm' == $what) :
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

        // Options ???
        if (isset($_POST['can_edit']) and ('checked' == $_POST['can_edit'])) :
            {
                $can_edit = 1;
            } else :
            {
                $can_edit = 0;
            }
        endif;
        if (isset($_POST['can_add']) and ('checked' == $_POST['can_add'])) :
            {
                $can_add = 1;
            } else :
            {
                $can_add = 0;
            }
        endif;
        if (isset($_POST['can_delete']) and ('checked' == $_POST['can_delete'])) :
            {
                $can_delete = 1;
            } else :
            {
                $can_delete = 0;
            }
        endif;
        if ((0 == $can_edit) and (0 == $can_add) and (0 == $can_delete)) :
            {
                arms_error(_ME_ARMS_NO_PERMS_SEL, $arms_redirect);
            }
        endif;

        $user_name = validate_text_field($_POST['user_name'], 1, _MD_ARMS_USERNAME, $arms_redirect);
        // Find uid...
        $q_str = 'SELECT uid FROM ' . $xoopsDB->prefix('users') . " WHERE uname = '$user_name'";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_USER_DOESNT_EXISTS, $arms_redirect);
                    }
                endif;
                $row = $xoopsDB->fetchArray($result);
                $user_id = $row['uid'];
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str, $arms_redirect));
            }
        endif;

        if ($xoopsUser->uid() == $user_id) :
            {
                arms_error(_ME_ARMS_ALREADY_AUTHOR, $arms_redirect);
            }
        endif;

        // User already have permission???
        $q_str = 'SELECT p_id FROM ' . $xoopsDB->prefix('arms_permissions') . " WHERE art_id = $idx AND uid = $user_id";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) > 0) :
                    {
                        arms_error(_ME_ARMS_USER_HAS_PERM, $arms_redirect);
                    }
                endif;
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;

        // Lets insert data...
        $q_str = sprintf(
            'INSERT INTO %s (art_id, uid, can_edit_pages, can_add_pages, can_delete_pages, added_by) VALUES (%s, %s, %s, %s, %s, %s)',
            $xoopsDB->prefix('arms_permissions'),
            $idx,
            $user_id,
            $can_edit,
            $can_add,
            $can_delete,
            $xoopsUser->uid()
        );
        if ($xoopsDB->query($q_str)) :
            {
                arms_error(sprintf(_MM_ARMS_PERM_ADDED, $user_name), $arms_redirect);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;
    }
// ================= //
// Delete Permission //
// ================= //
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

        // Is action confirmed???
        $confirmed = false;
        if (isset($_POST['arms_confirm']) and ('confirmed' == (string)$_POST['arms_confirm'])) :
            {
                $confirmed = true;
            }
        endif;

        $q_str = 'SELECT art_id FROM ' . $xoopsDB->prefix('arms_permissions') . " WHERE p_id=$idx";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_NO_PERM_IN_DB);
                    }
                endif;
                $row = $xoopsDB->fetchArray($result);
                $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/forms.php?w=editart&idx=' . $row['art_id'];
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
            }
        endif;

        if ($confirmed) :
            {
                // Lets delete it!
                $q_str = 'DELETE FROM ' . $xoopsDB->prefix('arms_permissions') . " WHERE p_id = $idx";
                if ($xoopsDB->query($q_str)) :
                    {
                        arms_error(_MM_ARMS_PERM_DELETED, $arms_redirect);
                    } else :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            } else :
            {
                arms_error(_ME_ARMS_NOT_CONFIRMED, $arms_redirect);
            }
        endif;
    }
// ================= //
// Clear Permissions //
// ================= //
elseif ('clearperms' == $what) :
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

        // Is action confirmed???
        $confirmed = false;
        if (isset($_POST['arms_confirm']) and ('confirmed' == (string)$_POST['arms_confirm'])) :
            {
                $confirmed = true;
            }
        endif;

        $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/forms.php?w=editart&idx=$idx";

        if ($confirmed) :
            {
                // Lets delete permissions...
                $q_str = 'DELETE FROM ' . $xoopsDB->prefix('arms_permissions') . " WHERE art_id=$idx";
                if ($xoopsDB->query($q_str)) :
                    {
                        arms_error(_MM_ARMS_PERMS_CLEARED, $arms_redirect);
                    } else :
                    {
                        arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                    }
                endif;
            } else :
            {
                arms_error(_ME_ARMS_NOT_CONFIRMED, $arms_redirect);
            }
        endif;
    }
// ================== //
// Update Permissions //
// ================== //
elseif ('updateperms' == $what) :
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

        $arms_redirect = XOOPS_URL . '/modules/arms/forms.php?w=editart&idx=' . $idx;

        $confirmed = false;
        if (isset($_POST['arms_confirm']) and ('confirmed' == (string)$_POST['arms_confirm'])) :
            {
                $confirmed = true;
            }
        endif;

        if (!$confirmed) :
            {
                arms_error(_ME_ARMS_NOT_CONFIRMED, $arms_redirect);
            }
        endif;

        // Get permissions
        $q_str = 'SELECT p_id FROM ' . $xoopsDB->prefix('arms_permissions') . " WHERE art_id=$idx";
        if ($result = $xoopsDB->query($q_str)) :
            {
                if ($xoopsDB->getRowsNum($result) < 1) :
                    {
                        arms_error(_ME_ARMS_NO_PERM_IN_ART, $arms_redirect);
                    }
                endif;

                while (false !== ($row = $xoopsDB->fetchArray($result))) :
                    {
                        $edit_fld = 'can_edit' . $row['p_id'];
                        $add_fld = 'can_add' . $row['p_id'];
                        $del_fld = 'can_delete' . $row['p_id'];
                        if (isset($_POST[$edit_fld]) and ('checked' == $_POST[$edit_fld])) :
                            {
                                $can_edit = 1;
                            } else :
                            {
                                $can_edit = 0;
                            }
                        endif;
                        if (isset($_POST[$add_fld]) and ('checked' == $_POST[$add_fld])) :
                            {
                                $can_add = 1;
                            } else :
                            {
                                $can_add = 0;
                            }
                        endif;
                        if (isset($_POST[$del_fld]) and ('checked' == $_POST[$del_fld])) :
                            {
                                $can_delete = 1;
                            } else :
                            {
                                $can_delete = 0;
                            }
                        endif;

                        $q_str = sprintf(
                            'UPDATE %s SET can_edit_pages = %s, can_add_pages = %s, can_delete_pages = %s WHERE p_id=%s',
                            $xoopsDB->prefix('arms_permissions'),
                            $can_edit,
                            $can_add,
                            $can_delete,
                            $row['p_id']
                        );
                        if (!$xoopsDB->query($q_str)) :
                            {
                                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
                            }
                        endif;
                    }
                endwhile;
                arms_error(_MM_ARMS_PERMS_UPDATED, $arms_redirect);
            } else :
            {
                arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endif;
    }
// ========================= //
// Update Cross Posting Data //
// ========================= //
elseif ('updatecross' == $what) :
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

        $arms_redirect = XOOPS_URL . '/modules/arms/forms.php?w=editart&idx=' . $idx;

        $confirmed = false;
        if (isset($_POST['arms_confirm']) and ('confirmed' == (string)$_POST['arms_confirm'])) :
            {
                $confirmed = true;
            }
        endif;

        if (!$confirmed) :
            {
                arms_error(_ME_ARMS_NOT_CONFIRMED, $arms_redirect);
            }
        endif;

        // Lets get art section
        $q_str = 'SELECT sec_id FROM ' . $xoopsDB->prefix('arms_articals') . " WHERE art_id=$idx";
        $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
        if ($xoopsDB->getRowsNum($result) < 1) :
            {
                arms_error(_MI_ARMS_NO_SECTIONS, $arms_redirect);
            }
        endif;
        $row = $xoopsDB->fetchArray($result);
        $sec_id = $row['sec_id'];

        // Lets get sections...
        $q_str = 'SELECT sec_id FROM ' . $xoopsDB->prefix('arms_sections') . " WHERE sec_id <> $sec_id";
        $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
        if ($xoopsDB->getRowsNum($result) < 1) :
            {
                arms_error(_ME_ARMS_NO_SECS_FOR_CROSS, $arms_redirect);
            }
        endif;

        $counter = 0;
        $arms_cross = [];
        while (false !== ($row = $xoopsDB->fetchArray($result))) :
            {
                $fld = 'cross_sec' . $row['sec_id'];
                if (isset($_POST[$fld]) and ('checked' == $_POST[$fld])) :
                    {
                        $arms_cross[$counter] = $row['sec_id'];
                        $counter++;
                    }
                endif;
            }
        endwhile;

        if (($xoopsModuleConfig['max_cross'] > 0) and (count($arms_cross) > $xoopsModuleConfig['max_cross'])) :
            {
                arms_error(sprintf(_ME_ARMS_MAX_CROSS, $xoopsModuleConfig['max_cross']), $arms_redirect);
            }
        endif;

        // Lets clear crossposting data for this artical...
        $q_str = sprintf('DELETE FROM %s WHERE art_id=%s', $xoopsDB->prefix('arms_cross_section'), $idx);
        $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);

        for ($i = 0, $iMax = count($arms_cross); $i < $iMax; $i++) :
            {
                $q_str = sprintf(
                    'INSERT INTO %s (art_id, sec_id) VALUES (%s, %s)',
                    $xoopsDB->prefix('arms_cross_section'),
                    $idx,
                    $arms_cross[$i]
                );
                $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
            }
        endfor;
        arms_error(_MM_ARMS_CROSS_UPDATED, $arms_redirect);
    }
// =================== //
// Change OnHold Stamp //
// =================== //
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

        $arms_redirect = XOOPS_URL . '/modules/arms/forms.php?w=editart&idx=' . $idx;

        $confirmed = false;
        if (isset($_POST['arms_confirm']) and ('confirmed' == (string)$_POST['arms_confirm'])) :
            {
                $confirmed = true;
            }
        endif;

        if (!$confirmed) :
            {
                arms_error(_ME_ARMS_NOT_CONFIRMED, $arms_redirect);
            }
        endif;

        // Current value
        $q_str = 'SELECT art_onhold FROM ' . $xoopsDB->prefix('arms_articals') . " WHERE art_id=$idx";
        $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
        if ($xoopsDB->getRowsNum($result) < 1) :
            {
                arms_error(_ME_ARMS_ARTICAL_DOESNT_EXISTS, $art_redirect);
            }
        endif;
        $row = $xoopsDB->fetchArray($result);
        if (0 == $row['art_onhold']) :
            {
                $new_value = 1;
            } else :
            {
                $new_value = 0;
            }
        endif;

        // Update data...
        $q_str = sprintf(
            'UPDATE %s SET art_onhold = %s WHERE art_id=%s',
            $xoopsDB->prefix('arms_articals'),
            $new_value,
            $idx
        );
        $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
        arms_error(_MM_ARMS_ONHOLD_CHANGED, $arms_redirect);
    }
// =============== //
// Jump To Section //
// =============== //
elseif ('jumptosec' == $what) :
    {
        // Get index...
        if (isset($_GET['idx'])) :
            {
                $idx = (int)$_GET['idx'];
            }
        endif;

        // Get on...
        if (isset($_GET['on'])) :
            {
                $on = (string)$_GET['on'];
            }
        endif;

        // Get page...
        if (isset($_GET['page'])) :
            {
                $page = (int)$_GET['page'];
            }
        endif;

        $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname();
        if (isset($idx) and isset($on) and ('sec' == $on)) :
            {
                $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/view.php?w=sec&idx=$idx";
            } elseif (isset($on) and isset($page) and ('art' == $on) and ('' == $page)) :
            {
                $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/view.php?w=art&idx=$idx";
            } elseif (isset($on) and isset($page) and ('art' == $on) and ('' != $page)) :
            {
                $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/view.php?w=art&idx=$idx&page=$page";
            }
        endif;

        $jt = validate_text_field($_POST['jump_to_sec'], 0, _MD_ARMS_JUMP_TO_SEC, $arms_redirect);
        $q_str = 'SELECT sec_id FROM ' . $xoopsDB->prefix('arms_sections') . " WHERE sec_title='$jt'";
        $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);
        if ($xoopsDB->getRowsNum($result) > 0) :
            {
                $row = $xoopsDB->fetchArray($result);
                $arms_redirect = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/view.php?w=sec&idx=' . $row['sec_id'];
                arms_error(_MM_ARMS_REDIRECTING, $arms_redirect);
            } else :
            {
                arms_error(_ME_ARMS_SECTION_DOESNT_EXISTS, $arms_redirect);
            }
        endif;
    }
endif;
