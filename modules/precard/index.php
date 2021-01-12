<?php
include("include/common.php");
include("functions.php");
if (!loginCheck('User'))
    exit;
global $action,$id,$cur_page,$lang,$conn,$config;
include("$config[template_path]/admin_top.html");
//$langXML = LanguageLoad();
//echo '<link rel="stylesheet" href="http://777.mlaitech.info/themes/blue/style.css" type="text/css">';
echo '<script type="text/javascript" src="functions.js"></script>';
echo '<div id="divMenuTop"><input type="button" value="' . $lang['create_card'] . '" onClick="CardAddFrame()"></div>';
echo '<div id="divContent"><div id="divMainContent"><div id="divMenu"><table><tbody>
                <tr style="height:33px;background-color: #00A7EB;">
                    <td></td><td></td>
                </tr>
                <tr>
                    <td>' . $lang['cardid'] . ':</td><td><input type="text" id="ID"></td>
                </tr>
                <tr>
                    <td>' . $lang['balance'] . ':</td><td><input type="text" id="BalanceCurrent"></td>
                </tr>
                 <tr>
                    <td>שם בעל הכרטיס:</td><td><input type="text" id="UserName_p"></input></td>
                </tr>
                <!--sk 18/02/2016-->
                <tr>
                    <td><input type="button" value="' . $lang['reset'] . '" onClick="SearchReset()"></td><td>
                        <input type="button" value="' . $lang['find'] . '" onClick="CardSearch()"></td>
                </tr>
            </tbody></table></div>';

echo '<div id="divMain">';
echo CardGet();
echo '</table></div></div></div>
            <div id="popupFrame">
                <table>
                    <tr>
                        <td colspan="3"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td id="popupContent"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                    </tr>
                </table>
            </div>';
include("$config[template_path]/admin_bottom.html");
$conn->Close(); // close the db connection

function SelectorCreate($id,$min,$max,$empty = false) {
    $result = "<select id=\"$id\">";
    if ($empty == true)
        $result .= "<option value=\"\"></option>";
    for ($min; $min <= $max; $min++) {
        $result .= "<option value=\"$min\">$min</option>";
    }
    $result .= '</select>';

    return $result;
}

?>
<style>
	#divHeader{
		display: none!important;
	}
	#divMenuTop{
		background: transparent;
	}
	#divContent {
	    direction: rtl;
	}
</style>