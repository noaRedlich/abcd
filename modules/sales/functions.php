<?php
    $simple=true;//Remove upper menu
    include_once("include/common.php");
    
    if (!loginCheck('User'))exit;
    global $action, $id, $cur_page, $lang, $conn, $config;
    
    
    if(isset($_POST['UseFunc'])){
        ob_clean();//Clear all echo
        if($_POST['UseFunc']=='CardAdd'){//Add card function call
            CardAdd($_POST['ID'],$_POST['BalanceMax'],$_POST['Paid']);
        }
        else if($_POST['UseFunc']=='CardAddFrame'){//Add card Frame function call
            echo CardAddFrame();
        }
        else if($_POST['UseFunc']=='CardGet'){// Get/Search cards function call
            if($_POST['ID']!=null) $filter['ID']=$_POST['ID'];
            if($_POST['BalanceCurrent']!=null) $filter['BalanceCurrent']=$_POST['BalanceCurrent'];
            if($_POST['DateCreate']!=null) $filter['DateCreate']=$_POST['DateCreate'];
            echo CardGet($filter);
        }
        else if($_POST['UseFunc']=='CardInfo'){
            echo CardInfo($_POST['ID']);
        }
        else if($_POST['UseFunc']=='CardCharge'){
            echo CardCharge($_POST['ID'],$_POST['Charge'],$_POST['Paid']);
        }
        $conn->Close(); // close the db connection
    }
    
    
    
    function CardAdd($ID,$charge,$paid){
        global $conn;
        $ID=(int)$ID;
        $charge=(float)$charge;
        $paid=(float)$paid;
        $sql = " INSERT INTO card_prepaid (`ID`, `DateCreate`) VALUES ($ID,NOW()) ";
        $recordSet = $conn->Execute($sql);
        if ($recordSet === false) echo log_error($sql);
        CardCharge($ID,$charge,$paid);
    }
    
    function CardAddFrame(){
        $langXML= LanguageLoad();
        return '    <table style="margin: auto;">
                    <tr><td>'.$langXML->cardId.':</td><td><input id="CardAddID" type="text" name="ID" value=""></td></tr>
                    <tr><td>'.$langXML->charge.':</td><td><input id="CardAddBalanceMax" type="text" name="BalanceMax" value="0.00" 
                    onkeyup="$(\'#Paid\').val($(this).val())" onfocus="InputReset(\'0.00\')"></td></tr>
                    <tr><td>'.$langXML->paid.':</td><td><input id="Paid" type="text" name="Paid" value="0.00" onfocus="InputReset(\'0.00\')"><br/><br/></td></tr>
                    </table>
                    <div><input type="button" value="'.$langXML->add.'" onclick="CardAdd()"> <input type="button" value="'.$langXML->cancel.'" onclick="FrontFrameClose()"></div>';
    }
    
    function MenuTop(){
        
    }
    
    function SalesGet($filters=null){
        global $conn;
        $langXML= LanguageLoad();
        $sql_filter='';
        foreach($filters as $k=>$v){
            switch($k){
                case 'ID':
                    $sql_filter.=' AND ID='.((int)$v);
                    break;
                case 'BalanceCurrent':
                    $sql_filter.=' AND BalanceCurrent='.((float)$v);
                    break;
                case 'DateCreate':
                    $sql_filter.=' AND DateCreate='.(mysql_real_escape_string($v));
                    break;
            }
        }
        if($sql_filter!='')$sql_filter='WHERE '.substr($sql_filter, 4);
        $sql = "
        SELECT * FROM sales $sql_filter ORDER BY DateCreate DESC
        ";
        $recordSet = $conn->Execute($sql);
        if ($recordSet === false) log_error($sql);
        $num_rows = $recordSet->RecordCount();
        
        $result= '<table class="tableList">
                    <tr class="tableHeader">
                        <td width="50%"></td>
                        <td>'.$langXML->cardId.'</td>
                        <td>'.$langXML->balanceCurrent.'</td>
                        <td>'.$langXML->dateCreate.'</td>
                        <td width="50%"></td>
                    </tr>';
        $rowColor=0;
        while (!$recordSet->EOF)
        {
            $ID =           $recordSet->fields["ID"];
            $balanceCurrent= $recordSet->fields["BalanceCurrent"];
            $dateCreate=    $recordSet->fields["DateCreate"];
            $rowColor++;
            $rowColor%=2;
            $result.= '<tr class="tableRow'.$rowColor.'" onclick="CardInfo('.$ID.')">';
            $result.= "<td width=\"50%\"></td>";
            $result.= "<td>$ID</td>";
            $result.= '<td>'.money_format('%i',$balanceCurrent).'</td>';
            $result.= "<td>$dateCreate</td>";
            $result.= "<td width=\"50%\"></td>";
            $result.= "</tr>";
            $recordSet->MoveNext();
        }
        return $result;
    }
    
    function CardInfo($ID){
        global $conn;
        $langXML= LanguageLoad();
        $ID=(int)$ID;
        $sql="SELECT * FROM card_prepaid WHERE ID=$ID";
        $recordSet = $conn->Execute($sql);
        $compPos[0]="Comp";
        $compPos[1]="Pos";
        if ($recordSet === false) log_error($sql);
        while (!$recordSet->EOF){
            $sqlHistory="SELECT * FROM card_prepaid_history WHERE ID=$ID ORDER BY `DATE` DESC";
            $recordSetHistory =$conn->Execute($sqlHistory);
            $history='<div style="overflow-y: scroll;height: 150px; border: solid 1px #999; direction:ltr;">
                <table class="tableList"><tr class="tableHeader"><td width="50%"></td><td>'.$langXML->charge.'</td><td>'.$langXML->paid.'</td>
                    <td>'.$langXML->date.'</td><td>'.$langXML->platform.'</td><td width="50%"></td></tr>';
            $rowColor=0;
            while (!$recordSetHistory->EOF){
                $rowColor++;
                $rowColor%=2;
                $sign='';
                if($recordSetHistory->fields['Charge']>=0) {$chargeColor="color:green"; $sign='+';}
                else $chargeColor="color:red";
                $history.= '<tr class="tableRow'.$rowColor.'"><td width="50%"></td>
                                <td style="'.$chargeColor.'">'.$sign.$recordSetHistory->fields['Charge'].'</td>
                                <td style="'.$chargeColor.'">'.$sign.$recordSetHistory->fields['Paid'].'</td>
                                <td>'.$recordSetHistory->fields['Date'].'</td>
                                <td>'.$compPos[$recordSetHistory->fields['Platform']].'</td><td width="50%"></td></tr>';
                $recordSetHistory->MoveNext();
            }
            $history.='</table></div>';
            $style="style=\"display:inline-block;width:150px;font-weight: bold; font-size: 14px;\"";
            $result='<table>
                        <tr><td>ID:</td><td style="color:red" id="ID">'.$recordSet->fields["ID"].'</td><td rowspan=3>'.$history.'</td></tr>
                        <tr><td>'.$langXML->balanceCurrent.':</td><td id="BalanceCurrent">'.$recordSet->fields["BalanceCurrent"].'</td></tr>
                        <tr><td>'.$langXML->dateCreate.':</td><td>'.$recordSet->fields["DateCreate"].'</td></tr>
                    </table><br/>
                    <div id="ChargeRow"><input type="button" value="'.$langXML->charge.'" onclick="CardChargeButton()"/></div>
                    <div id="ChargeRow2" style="display:none"><input class="inputText1" id="Charge" type="text" value="0.00" onkeyup="$(\'#Paid\').val($(this).val())" onfocus="InputReset(\'0.00\')"/>
                    <input type="button" value="Charge" onclick="CardCharge()"/><input class="inputText1" id="Paid" type="text" value="0.00" onfocus="InputReset(\'0.00\')"/></div>
                    <div id="ChargeRowInfo" style="display:none"><div '.$style.'>'.$langXML->charge.'</div><div style="display:inline-block;width:110px;"></div><div '.$style.'>'.$langXML->paid.'</div></div>';
            $recordSet->MoveNext();
        }
        return $result;
    }
    
    function CardCharge($ID,$charge,$paid){
        global $conn;
        $ID=(int)$ID;
        $charge=(float)$charge;
        $paid=(float)$paid;
        if($charge==0) return false;
        $sql="UPDATE card_prepaid SET BalanceCurrent=BalanceCurrent+$charge WHERE ID=$ID";
        $recordSet = $conn->Execute($sql);
        if ($recordSet === false) log_error($sql);
        $sql="INSERT INTO card_prepaid_history (ID, `Date`, Charge, Paid, Platform) VALUES($ID, NOW(), $charge, $paid, 0)";
        $recordSet = $conn->Execute($sql);
        if ($recordSet === false) log_error($sql);
    }
    
    function LanguageLoad(){
            //error_reporting(E_ALL);
            switch($_COOKIE['GO_LANGUAGE_NAME']){
            case 'Hebrew':
                $xml=simplexml_load_file("lang/eng.xml");
                return $xml;
                break;
            case 'English':
                $xml=simplexml_load_file("lang/eng.xml");
                return $xml;
                break;
            }
        }
    
?>
