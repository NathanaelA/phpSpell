<?php
define('IN_PHPBB', true);

//include ('spell_config.php');
$phpbb_root_path = "../";
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);

if( !$userdata['session_logged_in'] )
{
    header("Location: " . append_sid("../login.$phpEx", true));
}

$Template_Style_Sheet = "../templates/". substr($theme['head_stylesheet'], 0, strpos($theme['head_stylesheet'], "."))."/".$theme['head_stylesheet'];

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n<html>\n<head>\n<title>phpSpell</title>";
echo "<link rel=stylesheet href='$Template_Style_Sheet' type='text/css'>\n";
echo "<link rel=stylesheet href='spelling-op.css' type='text/css'>
     <script language=\"javascript\" src=\"spelling.js\"></script>
     <script language=\"javascript\"><!--
       function Can_Start_Spellchecker()
       {
         if (op6 || op7) setTimeout('Start_Spellchecker();', 500);
         else Start_Spellchecker();
       }
     --></script>
     </head>";


echo "<body bgcolor=\"".$theme['body_background']."\" text=\"".$theme['body_text']."\" link=\"".$theme['body_link']."\" vlink=\"".$theme['body_vlink']."\" topmargin=0 leftmargin=0 marginheight=0 marginwidth=0 class=\"bodyclass\" onload=\"Can_Start_Spellchecker();\">";

reset ($theme);
//while (list ($key, $val) = each ($theme)) {
//    echo "$key => $val<br />\n";
//}
?>
<!--
        'T_BODY_BACKGROUND' => $theme['body_background'],
        'T_BODY_ALINK' => '#'.$theme['body_alink'],
        'T_BODY_HLINK' => '#'.$theme['body_hlink'],
        'T_TR_COLOR1' => '#'.$theme['tr_color1'],
        'T_TR_COLOR2' => '#'.$theme['tr_color2'],
        'T_TR_COLOR3' => '#'.$theme['tr_color3'],
        'T_TR_CLASS1' => $theme['tr_class1'],
        'T_TR_CLASS2' => $theme['tr_class2'],
        'T_TR_CLASS3' => $theme['tr_class3'],
        'T_TH_COLOR1' => '#'.$theme['th_color1'],
        'T_TH_COLOR2' => '#'.$theme['th_color2'],
        'T_TH_COLOR3' => '#'.$theme['th_color3'],
        'T_TH_CLASS1' => $theme['th_class1'],
        'T_TH_CLASS2' => $theme['th_class2'],
        'T_TH_CLASS3' => $theme['th_class3'],
        'T_TD_COLOR1' => '#'.$theme['td_color1'],
        'T_TD_COLOR2' => '#'.$theme['td_color2'],
        'T_TD_COLOR3' => '#'.$theme['td_color3'],
        'T_TD_CLASS1' => $theme['td_class1'],
        'T_TD_CLASS2' => $theme['td_class2'],
        'T_TD_CLASS3' => $theme['td_class3'],
        'T_FONTFACE1' => $theme['fontface1'],
        'T_FONTFACE2' => $theme['fontface2'],
        'T_FONTFACE3' => $theme['fontface3'],
        'T_FONTSIZE1' => $theme['fontsize1'],
        'T_FONTSIZE2' => $theme['fontsize2'],
        'T_FONTSIZE3' => $theme['fontsize3'],
        'T_FONTCOLOR1' => '#'.$theme['fontcolor1'],
        'T_FONTCOLOR2' => '#'.$theme['fontcolor2'],
        'T_FONTCOLOR3' => '#'.$theme['fontcolor3'],
        'T_SPAN_CLASS1' => $theme['span_class1'],
        'T_SPAN_CLASS2' => $theme['span_class2'],
        'T_SPAN_CLASS3' => $theme['span_class3'], -->




<table width="550" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr ><td <?php if ($theme['body_background'] != "") echo " background=\"../".$theme['body_background']."\""; ?>>
    <table border=0 cellspacing=1 cellpadding=0 width=550 class=forumline>
     <th colspan=2>
        phpSpell<br>
     </th>
     <TR><td colspan="2">
       <table width="550" border="0" cellspacing="0" cellpadding="0" align="center">
         <TR>
            <td><iframe src="about:blank" scrolling="no" name="ispellheader" id="ispellheader" width="100%" height=16 marginwidth="0" marginheight="0" frameborder="0"></iframe></td>
         </tr>
       </table>
     </td></tr>
     <tr>
            <td width=400"><iframe src="about:blank" name="ispellcheck" id="ispellcheck" width="400" height="300" marginwidth="0" marginheight="0" frameborder="0" bgcolor=#ffffff></iframe></td>
            <td rowspan=2 width=150><iframe src="about:blank" name="ioptions" id="ioptions" width="150" height="100%" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" bgcolor="white"></iframe></td>
     </tr>
     <tr class="catleft"><td><span class="copyright">&nbsp;<a href="http://www.master-technology.com/demos/spell" target="_blank">(c)Copyright 2002-2005, Team phpSpell.</a></span></td></tr>
    </table>
  </td></tr></table>
</form>
</body>
</html>

