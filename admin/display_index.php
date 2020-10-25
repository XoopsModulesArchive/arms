<?php

// ========================================================================== //
// ArMS = Article Management System                                           //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Unit version   : 0.001                                                   //
//   Started        : 7:09:04 PM 10/7/2003                                    //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 11:08:49 AM 10/22/2003                                  //
//   Last update by : Ilija Studen                                            //
//                                                                            //
//   Change log:                                                              //
//   -----------                                                              //
//                                                                            //
// ========================================================================== //
// (c) 2003. by Ilija Studen                                                  //
//              (mail: ilija_studen@yahoo.com)                                //
//              (home: ilija.ionbee.net)                                      //
// ========================================================================== //

// This function added in ArMS 0.3
// I don`t like to mix PHP and HTML code... All other ArMS pages are using
// Smarty (even admin pages =] )!
function arms_display_index($input_arr)
{
    xoops_cp_header();

    print('<table class="outer" width="100%" border="0" cellspacing="1">');

    printf('<tr><th align="center" colspan="2">%s</th></tr>', _DI_ARMS_WELCOME);

    printf('<tr><td class="odd" align="center" colspan="2"><img src="%s" alt="%s"></td></tr>', $input_arr['arms_logo'], _DI_ARMS_LOGO_ALT);

    printf('<tr><th align="center" colspan="2">%s</th></tr>', _DI_ARMS_STATS);

    printf('<tr><td class="even">%s: <b>%s</b><br><a href="index.php?w=cats">%s</a></td></tr>', _DI_ARMS_TOTAL_CATS, $input_arr['total_cats'], _DI_ARMS_CATS_PAGE);

    printf('<tr><td class="odd">%s: <b>%s</b><br><a href="index.php?w=sections">%s</a></td></tr>', _DI_ARMS_TOTAL_SECS, $input_arr['total_secs'], _DI_ARMS_SECS_PAGE);

    printf('<tr><td class="even">%s: <b>%s</b><br><a href="index.php?w=arts">%s</a></td></tr>', _DI_ARMS_TOTAL_ARTS, $input_arr['total_arts'], _DI_ARMS_ARTS_PAGE);

    printf('<tr><td class="odd">%s: <b>%s</b><br><a href="../view.php?w=waiting&idx=all">%s</a></td></tr>', _DI_ARMS_WAITING, $input_arr['waiting'], _DI_ARMS_WAITING_PAGE);

    printf('<tr><th align="center">%s</th></tr>', _DI_ARMS_OTHER_PAGES);

    printf('<tr><td class="even" align="center"><a href="index.php?w=levels">%s</a> :: <a href="index.php?w=moderators">%s</a></td></tr>', _DI_ARMS_LEVEL_PAGE, _DI_ARMS_MOD_PAGE);

    printf('<tr><th align="center">%s</th></tr>', _DI_ARMS_UPDATE);

    printf('<tr><td class="even" align="center"><a href="index.php?w=update">%s</a></td></tr>', _DI_ARMS_CLICK_TO_UPDATE);

    print('<tr><td align="center" class="odd"><br>(c) 2003. by <a href="mailto:ilija_studen@yahoo.com?subject=ArMS">Ilija Studen</a></th></tr>');

    print('</table>');

    xoops_cp_footer();
}
