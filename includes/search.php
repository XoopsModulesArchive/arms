<?php

// ========================================================================== //
// ArMS = Artical Menagement System                                           //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Unit Version   : 0.001                                                   //
//   Started        : 2:42:55 PM 10/8/2003                                    //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 3:33:49 PM 10/8/2003                                    //
//   Last update by : Ilija Studen                                            //
//                                                                            //
//   Change log:                                                              //
//   -----------                                                              //
//   - first file version                                                     //
//                                                                            //
// ========================================================================== //
// (c) 2003. by Ilija Studen                                                  //
//              (mail: ilija_studen@yahoo.com)                                //
//              (home: ilija.ionbee.net)                                      //
// ========================================================================== //

require_once XOOPS_ROOT_PATH . '/modules/arms/includes/functions_general.php';
require_once XOOPS_ROOT_PATH . '/modules/arms/language/english/main.php';

function arms_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;

    $ret = [];

    if (0 != $userid) :
        {
            return $ret;
        }

    endif;

    // Search articals

    $q_str = 'SELECT art_id, art_title, art_posttime, uid FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_activated = 1 AND art_onhold = 0 ';

    $count = count($queryarray);

    if ($count > 0 and is_array($queryarray)) :
        {
            $q_str .= "AND ((art_title LIKE '%$queryarray[0]%' OR art_desc LIKE '%$queryarray[0]%')";
            for ($i = 1; $i < $count; $i++) {
                $q_str .= " $andor ";

                $q_str .= "(art_title LIKE '%$queryarray[$i]%' OR art_desc LIKE '%$queryarray[$i]%')";
            }
            $q_str .= ') ';
        }

    endif;

    $q_str .= 'ORDER BY art_posttime DESC';

    $counter = 0;

    if ($result = $xoopsDB->query($q_str, $limit, $offset)) :
        {
            while (false !== ($row = $xoopsDB->fetchArray($result))) :
                {
                    $ret[$counter]['image'] = 'images/artical.gif';
                    $ret[$counter]['link'] = 'view.php?w=art&idx=' . $row['art_id'];
                    $ret[$counter]['title'] = $row['art_title'];
                    $ret[$counter]['time'] = $row['art_posttime'];
                    $ret[$counter]['uid'] = $row['uid'];
                    $counter++;
                }
            endwhile;
        } else :
        {
            arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
        }

    endif;

    // Search pages

    $q_str = 'SELECT page_id, page_title, art_id, uid FROM ' . $xoopsDB->prefix('arms_pages') . ' WHERE ';

    if ($count > 0 and is_array($queryarray)) :
        {
            $q_str .= "((page_title LIKE '%$queryarray[0]%' OR page_desc LIKE '%$queryarray[0]%' OR page_text LIKE '%$queryarray[0]%')";
            for ($i = 1; $i < $count; $i++) {
                $q_str .= " $andor ";

                $q_str .= "(page_title LIKE '%$queryarray[$i]%' OR page_desc LIKE '%$queryarray[$i]%' OR page_text LIKE '%$queryarray[0]%')";
            }
            $q_str .= ') ';
        }

    endif;

    $q_str .= 'ORDER BY page_posttime DESC';

    if ($result = $xoopsDB->query($q_str, $limit, $offset)) :
        {
            while (false !== ($row = $xoopsDB->fetchArray($result))) :
                {
                    // Is artical activated???
                    $q_str = 'SELECT art_title, art_posttime FROM ' . $xoopsDB->prefix('arms_articals') . ' WHERE art_id=' . $row['art_id'] . ' AND art_activated = 1 AND art_onhold = 0';
                    if ($art_res = $xoopsDB->query($q_str)) :
                        {
                            if ($xoopsDB->getRowsNum($art_res) > 0) :
                                {
                                    $art_row = $xoopsDB->fetchArray($art_res);
                                    $ret[$counter]['image'] = 'images/page.gif';
                                    $ret[$counter]['link'] = 'view.php?w=art&idx=' . $row['art_id'];
                                    $ret[$counter]['title'] = $art_row['art_title'] . ' (' . _MD_ARMS_PAGE . ': ' . $row['page_title'] . ')';
                                    $ret[$counter]['time'] = $art_row['art_posttime'];
                                    $ret[$counter]['uid'] = $row['uid'];
                                    $counter++;
                                }
                            endif;
                        } else :
                        {
                            arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
                        }
                    endif;
                }
            endwhile;
        } else :
        {
            arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str));
        }

    endif;

    return $ret;
}
