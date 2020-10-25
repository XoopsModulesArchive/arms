<?php

// ========================================================================== //
// ArMS = Article Management System                                           //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Unit version   : 0.001                                                   //
//   Started        : 8:28:25 PM 9/10/2003                                    //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 11:10:34 AM 10/22/2003                                  //
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

function print_and_die($message)
{
    xoops_cp_header();

    echo $message;

    xoops_cp_footer();

    die();
}

// wfsections / admin / filemenager.php thnx
function list_files($dir)
{
    $files = [];

    $counter = 0;

    $dir = opendir($dir);

    while (false !== ($file = readdir($dir))) :
        {
            if ('.' != $file && '..' != $file) :
                {
                    $files[$counter]['file_name'] = $file;
                    $counter++;
                }
            endif;
        }

    endwhile;

    sort($files);

    closedir($dir);

    return $files;
}

function validate_text_field($var, $fld_name = '')
{
    if (isset($var) and ('' != (string)$var)) :
        {
            return (string)$var;
        } else :
        {
            print_and_die(sprintf(_ME_ARMS_TEXT_VALID, $fld_name));
        }

    endif;
}
