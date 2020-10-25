<?php

// ========================================================================== //
// ArMS = Article Management System                                           //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Version:       : 0.1                                                     //
//   Started        : 9:56:07 AM 9/10/2003                                    //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 10:18:39 PM 9/11/2003                                   //
//   Last update by : Ilija Studen                                            //
//                                                                            //
//   Change log:                                                              //
//   -----------                                                              //
//   - some grammatic errors fixed                                            //
//     (5:30:14 PM 10/17/2003 by SNIPER)                                      //
//                                                                            //
// ========================================================================== //
// (c) 2003. by Ilija Studen                                                  //
//              (mail: ilija_studen@yahoo.com)                                //
//              (home: ilija.ionbee.net)                                      //
// ========================================================================== //

define('_MI_ARMS_NAME', 'ArMS');
define('_MI_ARMS_DESC', 'ArMS is advanced article (tutorial, online documentation etc.) management system for XOOPS 2.');

// Main menu (MM MainMenu)
define('_MM_ARMS_MMENU_ADD', 'Add Article');
define('_MM_ARMS_MMENU_MY', 'My Articles');
define('_MM_ARMS_MMENU_NEWEST', 'Newest');
define('_MM_ARMS_MMENU_TOPRATED', 'Top Rated');
define('_MM_ARMS_MMENU_MOSTPOPULAR', 'Most Popular');

// Admin menu (MA MenuAdmin)
define('_MA_ARMS_ADMENU_ARTICALS', 'Articles');                    // ArMS 0.3
define('_MA_ARMS_ADMENU_CATS', 'Categories');                  // ArMS 0.3
define('_MA_ARMS_ADMENU_SECTIONS', 'Sections');
define('_MA_ARMS_ADMENU_LEVELS', 'Levels');
define('_MA_ARMS_ADMENU_MODERATORS', 'Moderators');
define('_MA_ARMS_ADMENU_CODE', 'ArMS Code');
define('_MA_ARMS_ADMENU_VOTELOG', 'View votelog');
define('_MA_ARMS_ADMENU_UPDATE', 'Update');                      // ArMS 0.3
define('_MA_ARMS_ADMENU_WAITING', 'Waiting contents');            // ArMS 0.3

// For config (MCT MConfigTitle, MConfigDescription)
define('_MCT_ARMS_MOD_ACTIVATE', 'Permissions - Moderator can activate articles');
define('_MCD_ARMS_MOD_ACTIVATE', 'Select YES if you want to allow moderators to activate articles.');

define('_MCT_ARMS_AUT_EDIT', 'Permissions - Author can edit his articles');
define('_MCD_ARMS_AUT_EDIT', 'Select YES if you want to allow author to edit his articles.');

define('_MCT_ARMS_AUT_DELETE', 'Permissions - Author can delete his articles');
define('_MCD_ARMS_AUT_DELETE', 'Select YES if you want to allow author to delete his articles.<br><span style="color:red"><b>Recommended: No</b></span>.');

define('_MCT_ARMS_RATING', 'Rating - Enabled'); // ArMS 0.4
define(
    '_MCD_ARMS_RATING',
    'SELECT YES IF you want TO ENABLE article rating. Only logged USER can rate article.<br><span style="color:red"><b>WARNING/NOTE: </b></span>IF you DISABLE this OPTION, please REMOVE Top Rated OPTION FROM ArMS menu (COMMENT LINES 79 AND 80 IN xoops_version.php AND UPDATE ArMS FROM XOOPS Modules PAGE).'
); // ArMS 0.4

define('_MCT_ARMS_RATING_FORMED', 'Rating - Show only formed rates'); // ArMS 0.4
define('_MCD_ARMS_RATING_FORMED', 'Select YES if you want to skip showing Not Rated message. If user is logged in there will be a link (Rate this article), if not whole row will be skiped.'); // ArMS 0.4

define('_MCT_ARMS_PERMISSIONS', 'Extra permissions - Enabled');
define('_MCD_ARMS_PERMISSIONS', 'Select YES if you want to let authors to assign extra permissions to other users (allow them to create coauthors).');

define('_MCT_ARMS_MAX_PERMS', 'Extra Permissions - Max permissions per article'); // ArMS 0.4
define('_MCD_ARMS_MAX_PERMS', 'Set max number of permissions that author can assign to other user. This option works only when extra permissions are enabled.<br><span style="color:red"><b>0 = no limit</b></span>'); // ArMS 0.4

define('_MCT_ARMS_CROSS', 'Crossposting - Enabled');
define('_MCD_ARMS_CROSS', 'Select YES if you want to enable crossposting (articles can be visible in more then one section).');

define('_MCT_ARMS_MAX_CROSS', 'Crossposting - Max sections'); // ArMS 0.4
define('_MCD_ARMS_MAX_CROSS', 'Set max number of sections in witch artical can be posted (crossposted). This option works only if crossposting is enabled.<br><span style="color:red"><b>0 = no limit</b></span>'); // ArMS 0.4

define('_MCT_ARMS_MIN_ART_TITLE_LENGTH', 'Posting - Minimal article title length');
define('_MCD_ARMS_MIN_ART_TITLE_LENGTH', 'Set minimal article title length in characters');

define('_MCT_ARMS_MIN_ART_DESC_LENGTH', 'Posting - Minimal article description length');
define('_MCD_ARMS_MIN_ART_DESC_LENGTH', 'Set minimal article description length in characters');

define('_MCT_ARMS_MIN_PAGE_TITLE_LEN', 'Posting - Minimal page title length');
define('_MCD_ARMS_MIN_PAGE_TITLE_LEN', 'Set minimal page title length in characters');

define('_MCT_ARMS_MIN_PAGE_DESC_LENGTH', 'Posting - Minimal page description length');
define('_MCD_ARMS_MIN_PAGE_DESC_LENGTH', 'Set minimal page description length in characters');

define('_MCT_ARMS_MIN_PAGE_TEXT_LENGTH', 'Posting - Minimal page text length');
define('_MCD_ARMS_MIN_PAGE_TEXT_LENGTH', 'Set minimal page text length in characters');

define('_MCT_ARMS_ART_PER_PAGE', 'Display - Articles per page');
define('_MCD_ARMS_ART_PER_PAGE', 'Set number of articles that will be displayed on section page');

define('_MCT_ARMS_EMPTY_SECS', 'Display - Show Empty Categories/Sections');
define('_MCD_ARMS_EMPTY_SECS', 'If this option is set to No ArMS will count articles for every category/section and if there is no articles that category/section will be hidden. This option is crossposting safe.');

// Blocks (MBlockDescription)
define('_MBT_ARMS_SEC_JT', 'ArMS Section (jumpbox)'); // ArMS 0.3
define('_MBT_ARMS_SEC_DTLD_SIDE', 'ArMS Sections (medium)'); // ArMS 0.3
define('_MBT_ARMS_SEC_DTLD_CENTER', 'ArMS Sections (full)'); // ArMS 0.3
define('_MBT_ARMS_ART_NEW', 'New (recent) articles'); // ArMS 0.3
define('_MBT_ARMS_ART_POP', 'Popular articles'); // ArMS 0.3
define('_MBT_ARMS_ART_TOP', 'Top rated articles'); // ArMS 0.3
