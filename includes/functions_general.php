<?php

// ========================================================================== //
// ArMS = Artical Menagment System                                            //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Version:       : 0.1                                                     //
//   Started        : 7:37:15 PM 9/9/2003                                     //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 12:06:23 PM 9/10/2003                                   //
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

// Forms arms_lang array witch is passed to smarty...
// Page index is supplied as string (easier to understand word than number)
function get_arms_lang($page_idx)
{
    // Common

    $return = [
        'anonym' => _MD_ARMS_ANONYMOUS,
'category' => _MD_ARMS_CATEGORY,
'categories' => _MD_ARMS_CATEGORIES,
'section' => _MD_ARMS_SECTION,
'sections' => _MD_ARMS_SECTIONS,
'artical' => _MD_ARMS_ARTICAL,
'articals' => _MD_ARMS_ARTICALS,
'lesson' => _MD_ARMS_PAGE,
'lessons' => _MD_ARMS_PAGES,
'comment' => _MD_ARMS_COMMENT,
'comments' => _MD_ARMS_COMMENTS,
    ];

    // For index page...

    if ('ARMS_PAGE_INDEX' == $page_idx) :
        {
            $return['title'] = _MD_ARMS_TITLE;
            $return['welcome'] = _MD_ARMS_WELCOME;
            $return['newest'] = _MD_ARMS_NEWEST;
            $return['toprated'] = _MD_ARMS_TOPRATED;
            $return['mostpopular'] = _MD_ARMS_MOSTPOPULAR;
            $return['desc'] = _MD_ARMS_DESC;
            $return['moderators'] = _MD_ARMS_MODERATORS;
            $return['add_section'] = _MD_ARMS_ADD_SECTION;
            $return['waiting'] = _MD_ARMS_WAITING;
            $return['admin'] = _MD_ARMS_ADMINISTRATION;
        } // Add artical form

    elseif ('ARMS_ADD_ARTICAL' == $page_idx) :
        {
            $return['section'] = _MD_ARMS_SECTION;
            $return['level'] = _MD_ARMS_LEVEL;
            $return['desc'] = _MD_ARMS_DESC;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['add_artical'] = _MD_ARMS_ADD_ARTICAL;
        }

    // Edit artical (diferent from edit_artical_data)

    elseif ('ARMS_EDIT_ARTICAL' == $page_idx) :
        {
            $return['section'] = _MD_ARMS_SECTION;
            $return['level'] = _MD_ARMS_LEVEL;
            $return['desc'] = _MD_ARMS_DESC;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['edit_artical'] = _MD_ARMS_EDIT_ARTICAL;
            $return['edit_artical_data'] = _MD_ARMS_EDIT_ARTICAL_DATA;
            $return['add_page'] = _MD_ARMS_ADD_PAGE;
            $return['edit'] = _MD_ARMS_EDIT;
            $return['delete'] = _MD_ARMS_DELETE;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['author'] = _MD_ARMS_AUTHOR;
            $return['pages_info'] = _MD_ARMS_PAGES_INFO;
            $return['permissions_info'] = _MD_ARMS_PERMISSIONS_INFO;
            $return['username'] = _MD_ARMS_USERNAME;
            $return['yes'] = _MD_ARMS_WORD_YES;
            $return['no'] = _MD_ARMS_WORD_NO;
            $return['permissions'] = _MD_ARMS_PERMISSIONS;
            $return['can_edit'] = _MD_ARMS_CAN_EDIT;
            $return['can_add'] = _MD_ARMS_CAN_ADD;
            $return['can_delete'] = _MD_ARMS_CAN_DELETE;
            $return['can_edit_full'] = _MD_ARMS_CAN_EDIT_FULL;
            $return['can_add_full'] = _MD_ARMS_CAN_ADD_FULL;
            $return['can_delete_full'] = _MD_ARMS_CAN_DELETE_FULL;
            $return['add_permission'] = _MD_ARMS_ADD_PERMISSION;
            $return['clear_permissions'] = _MD_ARMS_CLEAR_PERMS;
            $return['update_perms'] = _MD_ARMS_UPDATE_PERSM;
            $return['confirm'] = _MD_ARMS_CONFIRM;
            $return['order'] = _MD_ARMS_ORDER;
            $return['reorder_pages'] = _MD_ARMS_REORDER_PAGES;
            $return['artical_data'] = _MD_ARMS_ARTICAL_DATE;
            $return['cross_sections'] = _MD_ARMS_CROSS_SECTION;
            $return['update_cross'] = _MD_ARMS_UPDATE_CROSS;
            $return['cross_info'] = _MD_ARMS_CROSS_INFO;
            $return['crossed'] = _MD_ARMS_CROSSED;
            $return['onhold'] = _MD_ARMS_ONHOLD;
            $return['is_onhold'] = _MD_ARMS_IS_ONHOLD;
            $return['isnt_onhold'] = _MD_ARMS_ISNT_ONHOLD;
            $return['onhold_info'] = _MD_ARMS_ONHOLD_INFO;
            $return['change'] = _MD_ARMS_CHANGE;
            $return['restricted'] = _MD_ARMS_RESTRICTED;
            $return['perms_limit_info'] = _MI_ARMS_PERMS_LIMIT;
        }

    // Edit artical data...

    elseif ('ARMS_EDIT_ARTICAL_DATE' == $page_idx) :
        {
            $return['section'] = _MD_ARMS_SECTION;
            $return['level'] = _MD_ARMS_LEVEL;
            $return['desc'] = _MD_ARMS_DESC;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['edit_artical_data'] = _MD_ARMS_EDIT_ARTICAL_DATA;
        }

    // Add page

    elseif ('ARMS_ADD_PAGE' == $page_idx) :
        {
            $return['add_page'] = _MD_ARMS_ADD_PAGE;
            $return['desc'] = _MD_ARMS_DESC;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['level'] = _MD_ARMS_LEVEL;
            $return['text'] = _MD_ARMS_WORD_TEXT;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['allow_html'] = _MD_ARMS_ALLOW_HTML;
            $return['allow_emotions'] = _MD_ARMS_ALLOW_EMOTIONS;
            $return['allow_bbcode'] = _MD_ARMS_ALLOW_BBCODE;
            $return['options'] = _MD_ARMS_WORD_OPTIONS;
            $return['order'] = _MD_ARMS_ORDER;
        }

    // Edit page

    elseif ('ARMS_EDIT_PAGE' == $page_idx) :
        {
            $return['edit_page'] = _MD_ARMS_EDIT_PAGE;
            $return['desc'] = _MD_ARMS_DESC;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['level'] = _MD_ARMS_LEVEL;
            $return['text'] = _MD_ARMS_WORD_TEXT;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['allow_html'] = _MD_ARMS_ALLOW_HTML;
            $return['allow_emotions'] = _MD_ARMS_ALLOW_EMOTIONS;
            $return['allow_bbcode'] = _MD_ARMS_ALLOW_BBCODE;
            $return['options'] = _MD_ARMS_WORD_OPTIONS;
            $return['order'] = _MD_ARMS_ORDER;
        }

    // Confirm form...

    elseif ('ARMS_CONFIRM_DELETE_PAGE' == $page_idx) :
        {
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['confirm'] = _MD_ARMS_CONFIRM;
            $return['confirm_message'] = _MD_ARMS_DELETE_PAGE;
        } elseif ('ARMS_CONFIRM_DELETE_ART' == $page_idx) :
        {
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['confirm'] = _MD_ARMS_CONFIRM;
            $return['confirm_message'] = _MD_ARMS_CONFIRM_DELETE_ARTICAL;
        }

    // Activate artical confirmation

    elseif ('ARMS_CONFIRM_ACTIVATE_ART' == $page_idx) :
        {
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['confirm'] = _MD_ARMS_CONFIRM;
            $return['confirm_message'] = _MD_ARMS_CONFIRM_ACTIVATE_ARTICAL;
        }

    // Deactivate artical confirmation

    elseif ('ARMS_CONFIRM_DEACTIVATE_ART' == $page_idx) :
        {
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['confirm'] = _MD_ARMS_CONFIRM;
            $return['confirm_message'] = _MD_ARMS_CONFIRM_DEACTIVATE_ARTICAL;
        } elseif ('ARMS_CONFIRM_DELETE_PERM' == $page_idx) :
        {
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['confirm'] = _MD_ARMS_CONFIRM;
            $return['confirm_message'] = _MD_ARMS_CONFIRM_DELETE_PERM;
        } elseif ('ARMS_CONFIRM_CLEAR_PERMS' == $page_idx) :
        {
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['confirm'] = _MD_ARMS_CONFIRM;
            $return['confirm_message'] = _MD_ARMS_CONFIRM_CLEAR_PERMS;
        } elseif ('ARMS_CONFIRM_CHANGE_ONHOLD' == $page_idx) :
        {
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['confirm'] = _MD_ARMS_CONFIRM;
            $return['confirm_message'] = _MD_ARMS_CONFIRM_CHANGE_ONHOLD;
        }

    // View section

    elseif ('ARMS_VIEW_SECTION' == $page_idx) :
        {
            $return['desc'] = _MD_ARMS_DESC;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['level'] = _MD_ARMS_LEVEL;
            $return['rate'] = _MD_ARMS_RATE;
            $return['to_top'] = _MD_ARMS_TO_TOP;
            $return['author'] = _MD_ARMS_AUTHOR;
            $return['posttime'] = _MD_ARMS_POSTTIME;
            $return['updates'] = _MD_ARMS_UPDATES;
            $return['no_updates'] = _MD_ARMS_ART_NO_UPDATES;
            $return['details'] = _MD_ARMS_DETAILS;
            $return['rate_5'] = _MD_ARMS_RATE_5;
            $return['rate_4'] = _MD_ARMS_RATE_4;
            $return['rate_3'] = _MD_ARMS_RATE_3;
            $return['rate_2'] = _MD_ARMS_RATE_2;
            $return['rate_1'] = _MD_ARMS_RATE_1;
            $return['no_rate'] = _MD_ARMS_NO_RATE;
            $return['rate_art'] = _MD_ARMS_RATE_ART;
            $return['navigation'] = _MD_ARMS_NAVIGATION;
            $return['first'] = _MD_ARMS_FIRST;
            $return['last'] = _MD_ARMS_LAST;
            $return['previous'] = _MD_ARMS_PREVIOUS;
            $return['next'] = _MD_ARMS_NEXT;
            $return['options'] = _MD_ARMS_WORD_OPTIONS;
            $return['edit'] = _MD_ARMS_EDIT;
            $return['delete'] = _MD_ARMS_DELETE;
            $return['activate'] = _MD_ARMS_ACTIVATE;
            $return['deactivate'] = _MD_ARMS_DEACTIVATE;
            $return['votelog'] = _MD_ARMS_VOTELOG;
            $return['update_info'] = _MD_ARMS_UPDATE_INFO;
            $return['page_details'] = _MD_ARMS_PAGE_DETAILS;
            $return['ip'] = _MD_ARMS_IP;
            $return['jump_to_sec'] = _MD_ARMS_JUMP_TO_SEC;
            $return['go'] = _MD_ARMS_GO;
        }

    // View art

    elseif ('ARMS_VIEW_ART' == $page_idx) :
        {
            $return['desc'] = _MD_ARMS_DESC;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['level'] = _MD_ARMS_LEVEL;
            $return['rate'] = _MD_ARMS_RATE;
            $return['author'] = _MD_ARMS_AUTHOR;
            $return['posttime'] = _MD_ARMS_POSTTIME;
            $return['updates'] = _MD_ARMS_UPDATES;
            $return['no_updates'] = _MD_ARMS_ART_NO_UPDATES;
            $return['rate_art'] = _MD_ARMS_RATE_ART;
            $return['rate_5'] = _MD_ARMS_RATE_5;
            $return['rate_4'] = _MD_ARMS_RATE_4;
            $return['rate_3'] = _MD_ARMS_RATE_3;
            $return['rate_2'] = _MD_ARMS_RATE_2;
            $return['rate_1'] = _MD_ARMS_RATE_1;
            $return['no_rate'] = _MD_ARMS_NO_RATE;
            $return['add_art_data'] = _MD_ARMS_ADDITIONAL_ART_DATE;
            $return['first'] = _MD_ARMS_FIRST;
            $return['last'] = _MD_ARMS_LAST;
            $return['previous'] = _MD_ARMS_PREVIOUS;
            $return['next'] = _MD_ARMS_NEXT;
            $return['options'] = _MD_ARMS_WORD_OPTIONS;
            $return['edit'] = _MD_ARMS_EDIT;
            $return['delete'] = _MD_ARMS_DELETE;
            $return['next_page'] = _MD_ARMS_NEXT_PAGE;
            $return['prev_page'] = _MD_ARMS_PREV_PAGE;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['activate'] = _MD_ARMS_ACTIVATE;
            $return['deactivate'] = _MD_ARMS_DEACTIVATE;
            $return['jump_to_sec'] = _MD_ARMS_JUMP_TO_SEC;
            $return['go'] = _MD_ARMS_GO;
            $return['ip'] = _MD_ARMS_IP;
            $return['votelog'] = _MD_ARMS_VOTELOG;
        } elseif ('ARMS_VIEW_LEVEL' == $page_idx) :
        {
            $return['desc'] = _MD_ARMS_DESC;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['level'] = _MD_ARMS_LEVEL;
            $return['image'] = _MD_ARMS_IMAGE;
        } elseif ('ARMS_VIEW_MY' == $page_idx) :
        {
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['add_artical'] = _MD_ARMS_ADD_ARTICAL;
            $return['edit'] = _MD_ARMS_EDIT;
            $return['delete'] = _MD_ARMS_DELETE;
            $return['activated'] = _MD_ARMS_ACTIVATED;
            $return['not_activated'] = _MD_ARMS_NOT_ACTIVATED;
            $return['activate'] = _MD_ARMS_ACTIVATE;
            $return['deactivate'] = _MD_ARMS_DEACTIVATE;
            $return['my_articals'] = _MD_ARMS_MY_ARTICALS;
            $return['my_info'] = _MD_ARMS_MY_INFO;
            $return['with_perm'] = _MD_ARMS_WITH_PERMS;
            $return['with_perm_info'] = _MD_ARMS_WITH_PERM_INFO;
            $return['view'] = _MD_ARMS_VIEW;
        } elseif ('ARMS_VIEW_WAITING' == $page_idx) :
        {
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['add_artical'] = _MD_ARMS_ADD_ARTICAL;
            $return['edit'] = _MD_ARMS_EDIT;
            $return['delete'] = _MD_ARMS_DELETE;
            $return['activated'] = _MD_ARMS_ACTIVATED;
            $return['not_activated'] = _MD_ARMS_NOT_ACTIVATED;
            $return['activate'] = _MD_ARMS_ACTIVATE;
            $return['no_waiting'] = _MD_ARMS_NO_WAITING;
            $return['by'] = _MD_ARMS_BY;
        } elseif ('ARMS_VIEW_VOTELOG' == $page_idx) :
        {
            $return['rate_5'] = _MD_ARMS_RATE_5;
            $return['rate_4'] = _MD_ARMS_RATE_4;
            $return['rate_3'] = _MD_ARMS_RATE_3;
            $return['rate_2'] = _MD_ARMS_RATE_2;
            $return['rate_1'] = _MD_ARMS_RATE_1;
            $return['no_rate'] = _MD_ARMS_NO_RATE;
            $return['rate'] = _MD_ARMS_RATE;
            $return['username'] = _MD_ARMS_USERNAME;
            $return['votelog'] = _MD_ARMS_VOTELOG;
            $return['total_votes'] = _MD_ARMS_TOTAL_VOTES;
            $return['posttime'] = _MD_ARMS_POSTTIME;
            $return['ip'] = _MD_ARMS_IP;
        } elseif ('ARMS_NEWEST' == $page_idx) :
        {
            $return['rate_5'] = _MD_ARMS_RATE_5;
            $return['rate_4'] = _MD_ARMS_RATE_4;
            $return['rate_3'] = _MD_ARMS_RATE_3;
            $return['rate_2'] = _MD_ARMS_RATE_2;
            $return['rate_1'] = _MD_ARMS_RATE_1;
            $return['no_rate'] = _MD_ARMS_NO_RATE;
            $return['rate'] = _MD_ARMS_RATE;
            $return['ip'] = _MD_ARMS_IP;
            $return['page_title'] = _MF_ARMS_NEWEST;
            $return['page_info'] = _MF_ARMS_NEWEST_INFO;
            $return['by'] = _MD_ARMS_BY;
            $return['level'] = _MD_ARMS_LEVEL;
            $return['edit'] = _MD_ARMS_EDIT;
            $return['delete'] = _MD_ARMS_DELETE;
        } elseif ('ARMS_POPULAR' == $page_idx) :
        {
            $return['rate_5'] = _MD_ARMS_RATE_5;
            $return['rate_4'] = _MD_ARMS_RATE_4;
            $return['rate_3'] = _MD_ARMS_RATE_3;
            $return['rate_2'] = _MD_ARMS_RATE_2;
            $return['rate_1'] = _MD_ARMS_RATE_1;
            $return['no_rate'] = _MD_ARMS_NO_RATE;
            $return['rate'] = _MD_ARMS_RATE;
            $return['ip'] = _MD_ARMS_IP;
            $return['page_title'] = _MF_ARMS_POPULAR;
            $return['page_info'] = _MF_ARMS_POPULAR_INFO;
            $return['by'] = _MD_ARMS_BY;
            $return['level'] = _MD_ARMS_LEVEL;
            $return['edit'] = _MD_ARMS_EDIT;
            $return['delete'] = _MD_ARMS_DELETE;
        } elseif ('ARMS_TOP_RATED' == $page_idx) :
        {
            $return['rate_5'] = _MD_ARMS_RATE_5;
            $return['rate_4'] = _MD_ARMS_RATE_4;
            $return['rate_3'] = _MD_ARMS_RATE_3;
            $return['rate_2'] = _MD_ARMS_RATE_2;
            $return['rate_1'] = _MD_ARMS_RATE_1;
            $return['no_rate'] = _MD_ARMS_NO_RATE;
            $return['rate'] = _MD_ARMS_RATE;
            $return['ip'] = _MD_ARMS_IP;
            $return['page_title'] = _MF_ARMS_TOPRATED;
            $return['page_info'] = _MF_ARMS_TOPRATED_INFO;
            $return['by'] = _MD_ARMS_BY;
            $return['level'] = _MD_ARMS_LEVEL;
            $return['edit'] = _MD_ARMS_EDIT;
            $return['delete'] = _MD_ARMS_DELETE;
        }

    // ===== //

    // ADMIN //

    // ===== //

    elseif ('ARMS_ADMIN_CATEGORIES_MAIN' == $page_idx) :
        {
            $return['your_site'] = _MD_ARMS_YOUR_SITE;
            $return['xoops_admin'] = _MD_ARMS_XOOPS_ADMIN;
            $return['arms_admin'] = _MD_ARMS_ARMS_ADMIN;
            $return['cats_main'] = _MD_ARMS_ADMIN_CATEGORIES;
            $return['add_category'] = _MD_ARMS_ADD_CATEGORY;
            $return['edit'] = _MD_ARMS_EDIT;
            $return['delete'] = _MD_ARMS_DELETE;
            $return['desc'] = _MD_ARMS_DESC;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['order'] = _MD_ARMS_ORDER;
            $return['order_info'] = _MD_ORDER_FLD_INFO;
        }

    // For admin sections page

    elseif ('ARMS_ADMIN_DELETE_CAT' == $page_idx) :
        {
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['confirm'] = _MD_ARMS_CONFIRM;
            $return['confirm_message'] = _MD_ARMS_CONFIRM_DELETE_CATEGORY;
        } elseif ('ARMS_ADMIN_EDIT_CAT' == $page_idx) :
        {
            $return['your_site'] = _MD_ARMS_YOUR_SITE;
            $return['xoops_admin'] = _MD_ARMS_XOOPS_ADMIN;
            $return['arms_admin'] = _MD_ARMS_ARMS_ADMIN;
            $return['edit_cat'] = _MD_ARMS_EDIT_CATEGORY;
            $return['desc'] = _MD_ARMS_DESC;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['order'] = _MD_ARMS_ORDER;
        } elseif ('ARMS_ADMIN_SECTIONS_MAIN' == $page_idx) :
        {
            $return['your_site'] = _MD_ARMS_YOUR_SITE;
            $return['xoops_admin'] = _MD_ARMS_XOOPS_ADMIN;
            $return['arms_admin'] = _MD_ARMS_ARMS_ADMIN;
            $return['sections_main'] = _MD_ARMS_ADMIN_SECTIONS;
            $return['add_section'] = _MD_ARMS_ADD_SECTION;
            $return['edit'] = _MD_ARMS_EDIT;
            $return['delete'] = _MD_ARMS_DELETE;
            $return['move_up'] = _MD_ARMS_MOVE_UP;
            $return['move_down'] = _MD_ARMS_MOVE_DOWN;
            $return['desc'] = _MD_ARMS_DESC;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['sec_image'] = _MD_ARMS_SECTION_IMAGE;
            $return['img_info'] = _MD_SEC_IMG_FLD_INFO;
            $return['order'] = _MD_ARMS_ORDER;
            $return['order_info'] = _MD_ORDER_FLD_INFO;
            $return['orphened'] = _MD_ARMS_ORPHENED;
        }

    // Edit section page

    elseif ('ARMS_ADMIN_EDIT_SECTION' == $page_idx) :
        {
            $return['your_site'] = _MD_ARMS_YOUR_SITE;
            $return['xoops_admin'] = _MD_ARMS_XOOPS_ADMIN;
            $return['arms_admin'] = _MD_ARMS_ARMS_ADMIN;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['desc'] = _MD_ARMS_DESC;
            $return['edit_section'] = _MD_ARMS_EDIT_SECTION;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['sec_image'] = _MD_ARMS_SECTION_IMAGE;
            $return['img_info'] = _MD_SEC_IMG_FLD_INFO;
            $return['order'] = _MD_ARMS_ORDER;
        }

    // Delete section page

    elseif ('ARMS_ADMIN_DELETE_SECTION' == $page_idx) :
        {
            $return['your_site'] = _MD_ARMS_YOUR_SITE;
            $return['xoops_admin'] = _MD_ARMS_XOOPS_ADMIN;
            $return['arms_admin'] = _MD_ARMS_ARMS_ADMIN;
            $return['delete_section'] = _MD_ARMS_DELETE_SECTION;
            $return['move_articals'] = _MD_ARMS_MOVE_ARTICALS;
            $return['move_info'] = _MD_ARMS_MOVE_ART_ON_SEC_DEL;
            $return['confirm'] = _MD_ARMS_CONFIRM;
            $return['op_delete'] = _MD_ARMS_OP_DELETE;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
        } elseif ('ARMS_ADMIN_ARTICALS_MAIN' == $page_idx) :
        {
            $return['your_site'] = _MD_ARMS_YOUR_SITE;
            $return['xoops_admin'] = _MD_ARMS_XOOPS_ADMIN;
            $return['arms_admin'] = _MD_ARMS_ARMS_ADMIN;
            $return['arts_main'] = _MD_ARMS_ARTICALS_MAIN;
            $return['display'] = _MD_ARMS_DISPLAY;
            $return['display_info'] = _MD_ARMS_DISPLAY_MSG;
            $return['go'] = _MD_ARMS_GO;
            $return['opt_art_title'] = _MD_ARMS_OPT_ART_NAME;
            $return['opt_sec_name'] = _MD_ARMS_OPT_SEC_NAME;
            $return['opt_cat_name'] = _MD_ARMS_OPT_CAT_NAME;
            $return['opt_author'] = _MD_ARMS_OPT_AUTHOR_NAME;
            $return['where_part'] = _MD_ARMS_OPT_WHERE_PART;
            $return['stats'] = _MD_ARMS_STATS;
            $return['total_arts'] = _DI_ARMS_TOTAL_ARTS;
            $return['waiting'] = _DI_ARMS_WAITING;
            $return['waiting_page'] = _DI_ARMS_WAITING_PAGE;
        } elseif ('ARMS_ADMIN_DISPLAY_ARTS' == $page_idx) :
        {
            $return['your_site'] = _MD_ARMS_YOUR_SITE;
            $return['xoops_admin'] = _MD_ARMS_XOOPS_ADMIN;
            $return['arms_admin'] = _MD_ARMS_ARMS_ADMIN;
            $return['display_arts'] = _MD_ARMS_DISPLAY_ARTS;
            $return['edit'] = _MD_ARMS_EDIT;
            $return['delete'] = _MD_ARMS_DELETE;
            $return['onhold'] = _MD_ARMS_ONHOLD;
            $return['activated'] = _MD_ARMS_ACTIVATED;
            $return['not_activated'] = _MD_ARMS_NOT_ACTIVATED;
            $return['activate'] = _MD_ARMS_ACTIVATE;
            $return['deactivate'] = _MD_ARMS_DEACTIVATE;
            $return['display_info'] = _MD_ARMS_DISPLAY_MSG;
            $return['go'] = _MD_ARMS_GO;
            $return['opt_art_title'] = _MD_ARMS_OPT_ART_NAME;
            $return['opt_sec_name'] = _MD_ARMS_OPT_SEC_NAME;
            $return['opt_cat_name'] = _MD_ARMS_OPT_CAT_NAME;
            $return['opt_author'] = _MD_ARMS_OPT_AUTHOR_NAME;
            $return['where_part'] = _MD_ARMS_OPT_WHERE_PART;
        }

    // Levels main page

    elseif ('ARMS_ADMIN_LEVELS_MAIN' == $page_idx) :
        {
            $return['your_site'] = _MD_ARMS_YOUR_SITE;
            $return['xoops_admin'] = _MD_ARMS_XOOPS_ADMIN;
            $return['arms_admin'] = _MD_ARMS_ARMS_ADMIN;
            $return['levels_main'] = _MD_ARMS_ADMIN_LEVELS;
            $return['level'] = _MD_ARMS_LEVEL;
            $return['levels'] = _MD_ARMS_LEVELS;
            $return['edit'] = _MD_ARMS_EDIT;
            $return['delete'] = _MD_ARMS_DELETE;
            $return['add_level'] = _MD_ARMS_ADD_LEVEL;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['desc'] = _MD_ARMS_DESC;
            $return['image'] = _MD_ARMS_IMAGE;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['bytes'] = _MD_ARMS_BYTES;
        }

    // Edit level page...

    elseif ('ARMS_ADMIN_EDIT_LEVEL' == $page_idx) :
        {
            $return['your_site'] = _MD_ARMS_YOUR_SITE;
            $return['xoops_admin'] = _MD_ARMS_XOOPS_ADMIN;
            $return['arms_admin'] = _MD_ARMS_ARMS_ADMIN;
            $return['edit_level'] = _MD_ARMS_EDIT_LEVEL;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['desc'] = _MD_ARMS_DESC;
            $return['image'] = _MD_ARMS_IMAGE;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
        }

    // Delete level page...

    elseif ('ARMS_ADMIN_DELETE_LEVEL' == $page_idx) :
        {
            $return['your_site'] = _MD_ARMS_YOUR_SITE;
            $return['xoops_admin'] = _MD_ARMS_XOOPS_ADMIN;
            $return['arms_admin'] = _MD_ARMS_ARMS_ADMIN;
            $return['delete_level'] = _MD_ARMS_DELETE_LEVEL;
            $return['change_level'] = _MD_ARMS_CHANGE_LEVEL;
            $return['level'] = _MD_ARMS_LEVEL;
            $return['title'] = _MD_ARMS_WORD_TITLE;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['confirm'] = _MD_ARMS_CONFIRM;
            $return['op_delete'] = _MD_ARMS_OP_DELETE;
        }

    // Admin moderators main page

    elseif ('ARMS_ADMIN_MODERATORS_MAIN' == $page_idx) :
        {
            $return['your_site'] = _MD_ARMS_YOUR_SITE;
            $return['xoops_admin'] = _MD_ARMS_XOOPS_ADMIN;
            $return['arms_admin'] = _MD_ARMS_ARMS_ADMIN;
            $return['mods_main'] = _MD_ARMS_ADMIN_MODS;
            $return['submit'] = _MD_ARMS_SUBMIT;
            $return['reset'] = _MD_ARMS_RESET;
            $return['moderator'] = _MD_ARMS_MODERATOR;
            $return['delete'] = _MD_ARMS_DELETE;
            $return['username'] = _MD_ARMS_USERNAME;
            $return['add_mod'] = _MD_ARMS_ADD_MODERATOR;
        }

    // ====== //

    // Update //

    // ====== //

    elseif ('ARMS_ADMIN_UPDATE' == $page_idx) :
        {
            $return['your_site'] = _MD_ARMS_YOUR_SITE;
            $return['xoops_admin'] = _MD_ARMS_XOOPS_ADMIN;
            $return['arms_admin'] = _MD_ARMS_ARMS_ADMIN;
            $return['update'] = _DI_ARMS_UPDATE;
            $return['update_info'] = _MI_ARMS_UPDATE_INFO;
            $return['go'] = _MD_ARMS_GO;
        }

    endif;

    return $return;
}

function arms_error($arms_message, $arms_redirect = '')
{
    if ('' == $arms_redirect) :
        {
            redirect_header(XOOPS_URL . '/modules/arms/index.php', 1, $arms_message);
        } else :
        {
            redirect_header($arms_redirect, 1, $arms_message);
        }

    endif;

    die();
}

function arms_get_rate($rate_total, $rate_count)
{
    if ($rate_count < 2) :
        {
            $rate = 0;
        } else :
        {
            $rate = round($rate_total / ($rate_count - 1));
        }

    endif;

    return $rate;
}

// Image upload handling routines
// - build_random_name
// - randomize file name
// - validate_image
// - build_image_name

function build_random_name($ext)
{
    $img_name = 'img';

    for ($i = 1; $i <= 10; $i++) {
        $img_name .= random_int(0, 9);
    }

    return $img_name . $ext;
}

function randomize_file_name($ext)
{
    $ext = (string)$ext;

    if ('' == $ext) :
        {
            return '';
        }

    endif;

    // Podesava inicijalnu vrednost i ulazi u while koji obezbedjuje da ne dodje

    // do kolizije imena fajlova...

    $img_name = build_random_name($ext);

    while (file_exists($img_name)) {
        $img_name = build_random_name($ext);
    }

    return $img_name;
}

function validate_image($img, $props)
{
    define('GIF_TYPE_INDEX', 1);

    define('JPG_TYPE_INDEX', 2);

    define('PNG_TYPE_INDEX', 3);

    // Velicina

    if ($img['size'] > $props['max_size']) :
        {
            return false;
        }

    endif;

    $img_location = str_replace('\\', '/', $img['tmp_name']);

    $img_size = getimagesize($img_location);

    // Type (just GIF, JPEG i PNG)

    if ((GIF_TYPE_INDEX != $img_size[2]) && (JPG_TYPE_INDEX != $img_size[2]) && (PNG_TYPE_INDEX != $img_size[2])) :
        {
            return false;
        }

    endif;

    // width

    if ($img_size[0] > $props['max_width']) :
        {
            return false;
        }

    endif;

    // height

    if ($img_size[1] > $props['max_height']) :
        {
            return false;
        }

    endif;

    // Returns file type... For BUILD_IMAGE_NAME function...

    return $img_size[2];
}

function build_image_name($type)
{
    define('GIF_TYPE_INDEX', 1);

    define('JPG_TYPE_INDEX', 2);

    define('PNG_TYPE_INDEX', 3);

    define('DEFAULT_GIF_EXT', '.gif');

    define('DEFAULT_JPG_EXT', '.jpg');

    define('DEFAULT_PNG_EXT', '.png');

    if (GIF_TYPE_INDEX == $type) :
        {
            return randomize_file_name(DEFAULT_GIF_EXT);
        } elseif (JPG_TYPE_INDEX == $type) :
        {
            return randomize_file_name(DEFAULT_JPG_EXT);
        } elseif (JPG_TYPE_INDEX == $type) :
        {
            return randomize_file_name(DEFAULT_PNG_EXT);
        } else :
        {
            return '';
        }

    endif;
}

// Key function of crossposting system...
function arms_get_articals($sec_id, $just_count = false, $limit_start = -1, $limit_count = -1, $arms_redirect = '', $order_by = '')
{
    global $xoopsDB;

    $limit_str = ''; // Don`t display that warning again! :0)

    if (($limit_start >= 0) and ($limit_count >= 0)) :
        {
            $limit_str = " LIMIT $limit_start, $limit_count";
        } else :
        {
            $limit_str = '';
        }

    endif;

    if ('' == $order_by) :
        {
            $order_by = 'ORDER BY art_posttime DESC';
        } else :
        {
            $order_by = ' ' . (string)$order_by;
        }

    endif;

    // Get articals count... Real R`n`R
    $in = ''; // Just to stop warning display
    $q_str = 'SELECT DISTINCT art_id FROM ' . $xoopsDB->prefix('arms_cross_section') . ' WHERE sec_id=' . $sec_id;

    $cross_result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);

    if ($xoopsDB->getRowsNum($cross_result) > 0) :
        {
            $in = '(';
            $is_first = true;
            while (false !== ($cross_row = $xoopsDB->fetchArray($cross_result))) :
                {
                    if ($is_first) :
                        {
                            $is_first = false;
                        } else :
                        {
                            $in .= ',';
                        }
                    endif;
                    $in .= $cross_row['art_id'];
                }
            endwhile;
            $in .= ')';
        }

    endif;

    if ('' == $in) :
        {
            $q_str = 'SELECT DISTINCT art_id FROM ' . $xoopsDB->prefix('arms_articals') . " WHERE sec_id=$sec_id AND (art_activated = 1 AND art_onhold = 0)";
        } else :
        {
            $q_str = sprintf(
                'SELECT DISTINCT art_id FROM %s WHERE (sec_id = %s OR art_id IN %s) AND (art_activated = 1 AND art_onhold = 0)',
                $xoopsDB->prefix('arms_articals'),
                $sec_id,
                $in
            );
        }

    endif;

    $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);

    if ($just_count) :
        {
            return $xoopsDB->getRowsNum($result);
        }

    endif;

    $in = ''; // Reset $in

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

    // Lets get artical data...

    if ('' == $in) :
        {
            return false;
        } else :
        {
            $q_str = 'SELECT * FROM ' . $xoopsDB->prefix('arms_articals') . " WHERE (art_id IN $in) AND (art_activated = 1 AND art_onhold = 0) $order_by $limit_str";
        }

    endif;

    // print $q_str; die();

    $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);

    return $result;
}

function arms_init_perms()
{
    global $xoopsModuleConfig;

    $ret['can_view_perms'] = $xoopsModuleConfig['aut_permissions'];

    $ret['can_view_cross'] = $xoopsModuleConfig['aut_cross'];

    $ret['can_edit_art'] = false;

    $ret['can_delete_art'] = false;

    $ret['can_activate_art'] = false;

    $ret['can_view_uip'] = false;

    $ret['can_view_onhold'] = false;

    $ret['can_edit_page'] = false;

    $ret['can_add_page'] = false;

    $ret['can_delete_page'] = false;

    return $ret;
}

function arms_get_artical_perms($art_id, $uid, $arms_redirect = '')
{
    global $xoopsDB;

    global $xoopsModuleConfig;

    global $xoopsUser;

    $ret = arms_init_perms();

    if (!is_object($xoopsUser)) :
        {
            $ret['can_view_perms'] = false;
            $ret['can_view_cross'] = false;

            return $ret;
        }

    endif;

    if ($xoopsUser->isAdmin()) :
        {
            $ret['can_edit_art'] = true;
            $ret['can_delete_art'] = true;
            $ret['can_activate_art'] = true;
            $ret['can_view_uip'] = true;
            $ret['can_view_onhold'] = true;
            $ret['can_edit_page'] = true;
            $ret['can_add_page'] = true;
            $ret['can_delete_page'] = true;

            return $ret;
        }

    endif;

    // Get art data...

    $q_str = 'SELECT sec_id, uid FROM ' . $xoopsDB->prefix('arms_articals') . " WHERE art_id = $art_id";

    $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);

    if ($xoopsDB->getRowsNum($result) < 1) :
        {
            $ret['can_view_perms'] = false;
            $ret['can_view_cross'] = false;

            return $ret;
        }

    endif;

    $row = $xoopsDB->fetchArray($result);

    // Moderator???

    if (is_section_moderator($row['sec_id'], $uid)) :
        {
            $ret['can_edit_art'] = true;
            $ret['can_delete_art'] = true;
            $ret['can_activate_art'] = $xoopsModuleConfig['mod_activate'];
            $ret['can_view_uip'] = true;
            $ret['can_view_onhold'] = true;
            $ret['can_edit_page'] = true;
            $ret['can_add_page'] = true;
            $ret['can_delete_page'] = true;

            return $ret;
        }

    endif;

    // Is author???

    if ($row['uid'] == $uid) :
        {
            $ret['can_edit_art'] = $xoopsModuleConfig['aut_edit'];
            $ret['can_delete_art'] = $xoopsModuleConfig['aut_delete'];
            $ret['can_activate_art'] = false;
            $ret['can_view_uip'] = false;
            $ret['can_view_onhold'] = true;
            $ret['can_edit_page'] = true;
            $ret['can_add_page'] = true;
            $ret['can_delete_page'] = true;

            return $ret;
        }

    endif;

    // Is coauthor???

    $q_str = 'SELECT can_edit_pages, can_add_pages, can_delete_pages FROM ' . $xoopsDB->prefix('arms_permissions') . " WHERE art_id = $art_id AND uid = $uid";

    $result = $xoopsDB->query($q_str) or arms_error(sprintf(_ME_ARMS_SQL_ERROR, $q_str), $arms_redirect);

    if ($xoopsDB->getRowsNum($result) > 0) :
        {
            $row = $xoopsDB->fetchArray($result);
            $ret['can_edit_art'] = false;
            $ret['can_delete_art'] = false;
            $ret['can_activate_art'] = false;
            $ret['can_view_uip'] = false;
            $ret['can_view_onhold'] = false;
            $ret['can_edit_page'] = $row['can_edit_pages'];
            $ret['can_add_page'] = $row['can_add_pages'];
            $ret['can_delete_page'] = $row['can_delete_pages'];
            $ret['can_view_perms'] = false;
            $ret['can_view_cross'] = false;

            return $ret;
        }

    endif;

    // No permissions...

    $ret = arms_init_perms();

    $ret['can_view_perms'] = false;

    $ret['can_view_cross'] = false;

    return $ret;
}
