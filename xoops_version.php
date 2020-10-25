<?php

// ========================================================================== //
// ArMS = Article Management System                                           //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Version:       : 0.1                                                     //
//   Started        : 7:53:42 PM 9/9/2003                                     //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 1:59:30 PM 9/28/2003                                    //
//   Last update by : Ilija Studen                                            //
//                                                                            //
//   Change log:                                                              //
//   -----------                                                              //
//   NOTE: If you are changing this file please add some notes here           //
//                                                                            //
// ========================================================================== //
// (c) 2003. by Ilija Studen                                                  //
//              (mail: ilija_studen@yahoo.com)                                //
//              (home: ilija.ionbee.net)                                      //
// ========================================================================== //

// =========== //
// Module Info //
// =========== //
$modversion['name'] = _MI_ARMS_NAME;
$modversion['version'] = 0.4;
$modversion['description'] = _MI_ARMS_DESC;
$modversion['credits'] = '<a href="mailto:ilija_studen@yahoo.com">Ilija Studen</a>';
$modversion['author'] = '<a href="mailto:ilija_studen@yahoo.com">Ilija Studen</a>';
$modversion['help'] = 'arms.html';
$modversion['license'] = 'General Public License - See LICENSE';
$modversion['official'] = 0;
$modversion['image'] = 'images/arms_logo.gif';
$modversion['dirname'] = 'arms';
$modversion['use_smarty'] = 1;

// ======== //
// SQL File //
// ======== //
$modversion['sqlfile']['mysql'] = 'sql/arms_mysql.sql';

// ========= //
// DB Tables //
// ========= //
$modversion['tables'][0] = 'arms_sections';
$modversion['tables'][1] = 'arms_articals_levels';
$modversion['tables'][2] = 'arms_articals';
$modversion['tables'][3] = 'arms_pages';
$modversion['tables'][4] = 'arms_votelog';
$modversion['tables'][5] = 'arms_moderators';
// ArMS 0.3 - New tables
$modversion['tables'][6] = 'arms_categories';
$modversion['tables'][7] = 'arms_permissions';
$modversion['tables'][8] = 'arms_cross_section';

// ============ //
// Admin things //
// ============ //
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// ============== //
// Menu - Submenu //
// ============== //
$modversion['hasMain'] = 1;
$modversion['sub'][1]['name'] = _MM_ARMS_MMENU_ADD;
$modversion['sub'][1]['url'] = 'forms.php?w=addart';
$modversion['sub'][2]['name'] = _MM_ARMS_MMENU_MY;
$modversion['sub'][2]['url'] = 'view.php?w=myart';
// ArMS 0.3 - New options...
$modversion['sub'][3]['name'] = _MM_ARMS_MMENU_NEWEST;
$modversion['sub'][3]['url'] = 'filter.php?w=newest';
$modversion['sub'][5]['name'] = _MM_ARMS_MMENU_TOPRATED; // If you disable rating you should comment
$modversion['sub'][5]['url'] = 'filter.php?w=toprated';  // these two lines...
$modversion['sub'][4]['name'] = _MM_ARMS_MMENU_MOSTPOPULAR;
$modversion['sub'][4]['url'] = 'filter.php?w=popular';

// ========= //
// Templates //
// ========= //
$modversion['templates'][1]['file'] = 'arms_index.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'arms_admin_sections_main.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'arms_admin_forms_delete_section.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'arms_admin_forms_edit_section.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'arms_admin_levels_main.html';
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = 'arms_admin_forms_edit_level.html';
$modversion['templates'][6]['description'] = '';
$modversion['templates'][7]['file'] = 'arms_admin_forms_delete_level.html';
$modversion['templates'][7]['description'] = '';
$modversion['templates'][8]['file'] = 'arms_forms_add_artical.html';
$modversion['templates'][8]['description'] = '';
$modversion['templates'][9]['file'] = 'arms_forms_edit_artical.html';
$modversion['templates'][9]['description'] = '';
$modversion['templates'][10]['file'] = 'arms_forms_add_page.html';
$modversion['templates'][10]['description'] = '';
$modversion['templates'][11]['file'] = 'arms_forms_edit_artical_data.html';
$modversion['templates'][11]['description'] = '';
$modversion['templates'][12]['file'] = 'arms_forms_edit_page.html';
$modversion['templates'][12]['description'] = '';
$modversion['templates'][13]['file'] = 'arms_forms_confirm.html';
$modversion['templates'][13]['description'] = '';
$modversion['templates'][14]['file'] = 'arms_view_section.html';
$modversion['templates'][14]['description'] = '';
$modversion['templates'][15]['file'] = 'arms_view_artical.html';
$modversion['templates'][15]['description'] = '';
$modversion['templates'][16]['file'] = 'arms_admin_moderators_main.html';
$modversion['templates'][16]['description'] = '';
$modversion['templates'][17]['file'] = 'arms_view_level.html';
$modversion['templates'][17]['description'] = '';
$modversion['templates'][18]['file'] = 'arms_view_my.html';
$modversion['templates'][18]['description'] = '';
$modversion['templates'][19]['file'] = 'arms_view_waiting.html';
$modversion['templates'][19]['description'] = '';
// ArMS 0.3 - New templates...
$modversion['templates'][20]['file'] = 'arms_admin_categories_main.html';
$modversion['templates'][20]['description'] = '';
$modversion['templates'][21]['file'] = 'arms_admin_forms_edit_cat.html';
$modversion['templates'][21]['description'] = '';
$modversion['templates'][22]['file'] = 'arms_admin_articals_main.html';
$modversion['templates'][22]['description'] = '';
$modversion['templates'][23]['file'] = 'arms_admin_display_articals.html';
$modversion['templates'][23]['description'] = '';
$modversion['templates'][24]['file'] = 'arms_view_votelog.html';
$modversion['templates'][24]['description'] = '';
$modversion['templates'][25]['file'] = 'arms_view_nptr.html';
$modversion['templates'][25]['description'] = '';
// ArMS 0.4 - New templates...
$modversion['templates'][26]['file'] = 'arms_admin_forms_update.html';
$modversion['templates'][26]['description'] = '';

// ====== //
// Blocks //
// ====== //
// added in ArMS 0.3

// Sections (in jumpbox)
$modversion['blocks'][1]['file'] = 'arms_secs.php';
$modversion['blocks'][1]['name'] = _MBT_ARMS_SEC_JT;
$modversion['blocks'][1]['description'] = 'Display section in jumpbox. Optimized for sideblock.';
$modversion['blocks'][1]['show_func'] = 'arms_show_jt';
$modversion['blocks'][1]['template'] = 'secs_jt.html';

// Sections (categories - list)
$modversion['blocks'][2]['file'] = 'arms_secs.php';
$modversion['blocks'][2]['name'] = _MBT_ARMS_SEC_DTLD_SIDE;
$modversion['blocks'][2]['description'] = 'Display categories and sections with details. Optimized for sideblock.';
$modversion['blocks'][2]['show_func'] = 'arms_show_dtld_side';
$modversion['blocks'][2]['template'] = 'secs_dtld_side.html';

// Sections (detailed table)
$modversion['blocks'][3]['file'] = 'arms_secs.php';
$modversion['blocks'][3]['name'] = _MBT_ARMS_SEC_DTLD_CENTER;
$modversion['blocks'][3]['description'] = 'Display categories and sections with details. Optimized for centerblock.';
$modversion['blocks'][3]['show_func'] = 'arms_show_dtld_center';
$modversion['blocks'][3]['template'] = 'secs_dtld_center.html';

// New articls
$modversion['blocks'][4]['file'] = 'arms_arts.php';
$modversion['blocks'][4]['name'] = _MBT_ARMS_ART_NEW;
$modversion['blocks'][4]['show_func'] = 'arts_show_new';
$modversion['blocks'][4]['template'] = 'arts_show.html';

// Popular articals
$modversion['blocks'][5]['file'] = 'arms_arts.php';
$modversion['blocks'][5]['name'] = _MBT_ARMS_ART_POP;
$modversion['blocks'][5]['show_func'] = 'arts_show_pop';
$modversion['blocks'][5]['template'] = 'arts_show.html';

// Top 10
$modversion['blocks'][6]['file'] = 'arms_arts.php';
$modversion['blocks'][6]['name'] = _MBT_ARMS_ART_TOP;
$modversion['blocks'][6]['show_func'] = 'arts_show_top';
$modversion['blocks'][6]['template'] = 'arts_show.html';

// ======= //
// Configs //
// ======= //
// Moderator can activate articals
$modversion['config'][1]['name'] = 'mod_activate';
$modversion['config'][1]['title'] = '_MCT_ARMS_MOD_ACTIVATE';
$modversion['config'][1]['description'] = '_MCD_ARMS_MOD_ACTIVATE';
$modversion['config'][1]['formtype'] = 'yesno';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 0;
// Author can edit articals
$modversion['config'][2]['name'] = 'aut_edit';
$modversion['config'][2]['title'] = '_MCT_ARMS_AUT_EDIT';
$modversion['config'][2]['description'] = '_MCD_ARMS_AUT_EDIT';
$modversion['config'][2]['formtype'] = 'yesno';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 1;
// Author can delete articals
$modversion['config'][3]['name'] = 'aut_delete';
$modversion['config'][3]['title'] = '_MCT_ARMS_AUT_DELETE';
$modversion['config'][3]['description'] = '_MCD_ARMS_AUT_DELETE';
$modversion['config'][3]['formtype'] = 'yesno';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 0;
// Rating enabled (ArMS 0.4)
$modversion['config'][4]['name'] = 'rating_enabled';
$modversion['config'][4]['title'] = '_MCT_ARMS_RATING';
$modversion['config'][4]['description'] = '_MCD_ARMS_RATING';
$modversion['config'][4]['formtype'] = 'yesno';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 1;
// Rating show formed (ArMS 0.4)
$modversion['config'][5]['name'] = 'rating_only_formed';
$modversion['config'][5]['title'] = '_MCT_ARMS_RATING_FORMED';
$modversion['config'][5]['description'] = '_MCD_ARMS_RATING_FORMED';
$modversion['config'][5]['formtype'] = 'yesno';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = 1;
// Extra permissions enabled (ArMS 0.3)
$modversion['config'][6]['name'] = 'aut_permissions';
$modversion['config'][6]['title'] = '_MCT_ARMS_PERMISSIONS';
$modversion['config'][6]['description'] = '_MCD_ARMS_PERMISSIONS';
$modversion['config'][6]['formtype'] = 'yesno';
$modversion['config'][6]['valuetype'] = 'int';
$modversion['config'][6]['default'] = 1;
// Max extra permissions (ArMS 0.4)
$modversion['config'][7]['name'] = 'max_permissions';
$modversion['config'][7]['title'] = '_MCT_ARMS_MAX_PERMS';
$modversion['config'][7]['description'] = '_MCD_ARMS_MAX_PERMS';
$modversion['config'][7]['formtype'] = 'textbox';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] = 5;
// Crossposting enabled (ArMS 0.3)
$modversion['config'][8]['name'] = 'aut_cross';
$modversion['config'][8]['title'] = '_MCT_ARMS_CROSS';
$modversion['config'][8]['description'] = '_MCD_ARMS_CROSS';
$modversion['config'][8]['formtype'] = 'yesno';
$modversion['config'][8]['valuetype'] = 'int';
$modversion['config'][8]['default'] = 1;
// Max number of sections for crossposting (ArMS 0.4)
$modversion['config'][9]['name'] = 'max_cross';
$modversion['config'][9]['title'] = '_MCT_ARMS_MAX_CROSS';
$modversion['config'][9]['description'] = '_MCD_ARMS_MAX_CROSS';
$modversion['config'][9]['formtype'] = 'textbox';
$modversion['config'][9]['valuetype'] = 'int';
$modversion['config'][9]['default'] = 2;
// Min artical title length
$modversion['config'][10]['name'] = 'min_art_title_length';
$modversion['config'][10]['title'] = '_MCT_ARMS_MIN_ART_TITLE_LENGTH';
$modversion['config'][10]['description'] = '_MCD_ARMS_MIN_ART_TITLE_LENGTH';
$modversion['config'][10]['formtype'] = 'textbox';
$modversion['config'][10]['valuetype'] = 'int';
$modversion['config'][10]['default'] = 5;
// Min artical description length
$modversion['config'][11]['name'] = 'min_art_desc_length';
$modversion['config'][11]['title'] = '_MCT_ARMS_MIN_ART_DESC_LENGTH';
$modversion['config'][11]['description'] = '_MCD_ARMS_MIN_ART_DESC_LENGTH';
$modversion['config'][11]['formtype'] = 'textbox';
$modversion['config'][11]['valuetype'] = 'int';
$modversion['config'][11]['default'] = 20;
// Min page title length
$modversion['config'][12]['name'] = 'min_page_title_length';
$modversion['config'][12]['title'] = '_MCT_ARMS_MIN_PAGE_TITLE_LEN';
$modversion['config'][12]['description'] = '_MCD_ARMS_MIN_PAGE_TITLE_LEN';
$modversion['config'][12]['formtype'] = 'textbox';
$modversion['config'][12]['valuetype'] = 'int';
$modversion['config'][12]['default'] = 5;
// Min page desc length
$modversion['config'][13]['name'] = 'min_page_desc_length';
$modversion['config'][13]['title'] = '_MCT_ARMS_MIN_PAGE_DESC_LENGTH';
$modversion['config'][13]['description'] = '_MCD_ARMS_MIN_PAGE_DESC_LENGTH';
$modversion['config'][13]['formtype'] = 'textbox';
$modversion['config'][13]['valuetype'] = 'int';
$modversion['config'][13]['default'] = 20;
// Min page text length
$modversion['config'][14]['name'] = 'min_page_text_length';
$modversion['config'][14]['title'] = '_MCT_ARMS_MIN_PAGE_TEXT_LENGTH';
$modversion['config'][14]['description'] = '_MCD_ARMS_MIN_PAGE_TEXT_LENGTH';
$modversion['config'][14]['formtype'] = 'textbox';
$modversion['config'][14]['valuetype'] = 'int';
$modversion['config'][14]['default'] = 20;
// Articals per page...
$modversion['config'][15]['name'] = 'art_per_page';
$modversion['config'][15]['title'] = '_MCT_ARMS_ART_PER_PAGE';
$modversion['config'][15]['description'] = '_MCD_ARMS_ART_PER_PAGE';
$modversion['config'][15]['formtype'] = 'textbox';
$modversion['config'][15]['valuetype'] = 'int';
$modversion['config'][15]['default'] = 5;
// Dispay empty sections/categories
$modversion['config'][16]['name'] = 'empty_sec';
$modversion['config'][16]['title'] = '_MCT_ARMS_EMPTY_SECS';
$modversion['config'][16]['description'] = '_MCD_ARMS_EMPTY_SECS';
$modversion['config'][16]['formtype'] = 'yesno';
$modversion['config'][16]['valuetype'] = 'int';
$modversion['config'][16]['default'] = 0;

// ====== //
// Search //
// ====== //
// search added in ArMS 0.3
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'includes/search.php';
$modversion['search']['func'] = 'arms_search';

// ======== //
// Comments //
// ======== //
// comments added in ArMS 0.3
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'view.php';
$modversion['comments']['itemName'] = 'idx';

// Notifications Will be added soon
$modversion['hasNotification'] = 0;
