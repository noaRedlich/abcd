<?php	
	include("include/common.php");
        include("functions.php");
	if (!loginCheck('User'))exit;
	global $action, $id, $cur_page, $lang, $conn, $config;
        
        $langXML= LanguageLoad();
        echo '<link href="style.css" rel="stylesheet" type="text/css">';
        echo '<script type="text/javascript" src="functions.js"></script>';
        echo '<div id="divMenuTop"><input type="button" value="'.$langXML->createCard.'" onClick="CardAddFrame()"></input></div>';
        echo '<div id="divMainContent"><div id="divMenu"><table><tbody>
                <tr style="height:25px;background-color: #6B92D8;">
                    <td></td><td></td>
                </tr>
                <tr>
                    <td>'.$langXML->cardId.':</td><td><input type="text" id="ID"></input></td>
                </tr>
                <tr>
                    <td>'.$langXML->balance.':</td><td><input type="text" id="BalanceCurrent"></input></td>
                </tr>
                <tr>
                    <td><input type="button" value="'.$langXML->reset.'" onClick="SearchReset()"></input></td><td>
                        <input type="button" value="'.$langXML->search.'" onClick="CardSearch()"></input></td>
                </tr>
            </tbody></table></div>';
        
        echo '<div id="divMain">';
        echo CardGet();
        echo '</table></div></div>
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
        
        function SelectorCreate($id,$min,$max,$empty=false){
            $result="<select id=\"$id\">";
            if($empty==true)$result.="<option value=\"\"></option>";
            for($min;$min<=$max;$min++){
                $result.="<option value=\"$min\">$min</option>";
            }
            $result.='</select>';
            return $result;
        }
?>