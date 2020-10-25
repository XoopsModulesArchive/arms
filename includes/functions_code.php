<?php

// ========================================================================== //
// ArMS = Artical Menagment System                                            //
// ========================================================================== //
//                                                                            //
//   Unit info:                                                               //
//   ----------                                                               //
//   Version:       : 0.1                                                     //
//   Started        : 12:59:36 PM 9/13/2003                                   //
//   Started by     : Ilija Studen                                            //
//                    (mail: ilija_studen@yahoo.com)                          //
//                    (home: ilija.ionbee.net)                                //
//   Last update    : 12:59:45 PM 9/13/2003                                   //
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

function arms_text_area($textarea_id, $cols = 60, $rows = 15, $suffix = null, $tarea_text = '')
{
    // This function doesn`t prints data, it puts in one large string so you can

    // pass it as smarty variable... Like I said: I`m template junky :)

    $return = '';

    $hiddentext = isset($suffix) ? 'xoopsHiddenText' . trim($suffix) : 'xoopsHiddenText';

    //Hack for url, email ...., the anchor is for having a link on [_More...]

    $return .= "<a name='moresmiley'></a><img src='"
               . XOOPS_URL
               . "/images/url.gif' alt='url' onmouseover='style.cursor=\"hand\"' onclick='xoopsCodeUrl(\"$textarea_id\");'>&nbsp;<img src='"
               . XOOPS_URL
               . "/images/email.gif' alt='email' onmouseover='style.cursor=\"hand\"' onclick='xoopsCodeEmail(\"$textarea_id\");'>&nbsp;<img src='"
               . XOOPS_URL
               . "/images/imgsrc.gif' alt='imgsrc' onmouseover='style.cursor=\"hand\"' onclick='xoopsCodeImg(\"$textarea_id\");'>&nbsp;<img src='"
               . XOOPS_URL
               . "/images/image.gif' alt='image' onmouseover='style.cursor=\"hand\"' onclick='openWithSelfMain(\""
               . XOOPS_URL
               . '/imagemanager.php?target='
               . $textarea_id
               . "\",\"imgmanager\",400,430);'>&nbsp;<img src='"
               . XOOPS_URL
               . "/images/code.gif' alt='code' onmouseover='style.cursor=\"hand\"' onclick='xoopsCodeCode(\"$textarea_id\");'>&nbsp;<img src='"
               . XOOPS_URL
               . "/images/quote.gif' alt='quote' onmouseover='style.cursor=\"hand\"' onclick='xoopsCodeQuote(\"$textarea_id\");'><br>\n";

    $sizearray = ['xx-small', 'x-small', 'small', 'medium', 'large', 'x-large', 'xx-large'];

    $return .= "<select id='" . $textarea_id . "Size' onchange='setVisible(\"xoopsHiddenText\");setElementSize(\"" . $hiddentext . "\",this.options[this.selectedIndex].value);'>\n";

    $return .= "<option value='SIZE'>" . _SIZE . "</option>\n";

    foreach ($sizearray as $size) {
        $return .= "<option value='$size'>$size</option>\n";
    }

    $return .= "</select>\n";

    $fontarray = ['Arial', 'Courier', 'Georgia', 'Helvetica', 'Impact', 'Verdana'];

    $return .= "<select id='" . $textarea_id . "Font' onchange='setVisible(\"xoopsHiddenText\");setElementFont(\"" . $hiddentext . "\",this.options[this.selectedIndex].value);'>\n";

    $return .= "<option value='FONT'>" . _FONT . "</option>\n";

    foreach ($fontarray as $font) {
        $return .= "<option value='$font'>$font</option>\n";
    }

    $return .= "</select>\n";

    $colorarray = ['00', '33', '66', '99', 'CC', 'FF'];

    $return .= "<select id='" . $textarea_id . "Color' onchange='setVisible(\"xoopsHiddenText\");setElementColor(\"" . $hiddentext . "\",this.options[this.selectedIndex].value);'>\n";

    $return .= "<option value='COLOR'>" . _COLOR . "</option>\n";

    foreach ($colorarray as $color1) {
        foreach ($colorarray as $color2) {
            foreach ($colorarray as $color3) {
                $return .= "<option value='" . $color1 . $color2 . $color3 . "' style='background-color:#" . $color1 . $color2 . $color3 . ';color:#' . $color1 . $color2 . $color3 . ";'>#" . $color1 . $color2 . $color3 . "</option>\n";
            }
        }
    }

    $return .= "</select><span id='" . $hiddentext . "'>" . _EXAMPLE . "</span>\n";

    $return .= "<br>\n";

    //Hack smilies move for bold, italic ...

    $return .= "<img src='"
               . XOOPS_URL
               . "/images/bold.gif' alt='bold' onmouseover='style.cursor=\"hand\"' onclick='setVisible(\""
               . $hiddentext
               . '");makeBold("'
               . $hiddentext
               . "\");'>&nbsp;<img src='"
               . XOOPS_URL
               . "/images/italic.gif' alt='italic' onmouseover='style.cursor=\"hand\"' onclick='setVisible(\""
               . $hiddentext
               . '");makeItalic("'
               . $hiddentext
               . "\");'>&nbsp;<img src='"
               . XOOPS_URL
               . "/images/underline.gif' alt='underline' onmouseover='style.cursor=\"hand\"' onclick='setVisible(\""
               . $hiddentext
               . '");makeUnderline("'
               . $hiddentext
               . "\");'>&nbsp;<img src='"
               . XOOPS_URL
               . "/images/linethrough.gif' alt='linethrough' onmouseover='style.cursor=\"hand\"' onclick='setVisible(\""
               . $hiddentext
               . '");makeLineThrough("'
               . $hiddentext
               . "\");'></a>&nbsp;<input type='text' id='"
               . $textarea_id
               . "Addtext' size='20'>&nbsp;<input type='button' onclick='xoopsCodeText(\"$textarea_id\", \""
               . $hiddentext
               . "\")' value='"
               . _ADD
               . "'><br><br>";

    $return .= "<textarea id='" . $textarea_id . "' name='" . $textarea_id . "' cols='$cols' rows='$rows'>" . $tarea_text . "</textarea><br>\n";

    return $return;
    //Fin du hack
}

function amrs_emotions($textarea_id)
{
    $return = '';

    $myts = MyTextSanitizer::getInstance();

    $smiles = $myts->getSmileys();

    if (empty($smileys)) {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        if ($result = $db->query('SELECT * FROM ' . $db->prefix('smiles') . ' WHERE display=1')) {
            while (false !== ($smiles = $db->fetchArray($result))) {
                //hack smilies move for the smilies !!

                $return .= "<img src='" . XOOPS_URL . '/uploads/' . htmlspecialchars($smiles['smile_url'], ENT_QUOTES | ENT_HTML5) . "' border='0' onmouseover='style.cursor=\"hand\"' alt='' onclick='xoopsCodeSmilie(\"" . $textarea_id . '", " ' . $smiles['code'] . " \");'>";

                //fin du hack
            }
        }
    } else {
        $count = count($smiles);

        for ($i = 0; $i < $count; $i++) {
            if (1 == $smiles[$i]['display']) {
                //hack bis

                $return .= "<img src='" . XOOPS_URL . '/uploads/' . htmlspecialchars($smiles['smile_url'], ENT_QUOTES | ENT_HTML5) . "' border='0' alt='' onclick='xoopsCodeSmilie(\"" . $textarea_id . '", " ' . $smiles[$i]['code'] . " \");' onmouseover='style.cursor=\"hand\"'>";

                //fin du hack
            }
        }
    }

    //hack for more

    $return .= "&nbsp;[<a href='#moresmiley' onmouseover='style.cursor=\"hand\"' alt='' onclick='openWithSelfMain(\"" . XOOPS_URL . '/misc.php?action=showpopups&amp;type=smilies&amp;target=' . $textarea_id . "\",\"smilies\",300,475);'>" . _MORE . '</a>]';

    return $return;
}  //fin du hack
