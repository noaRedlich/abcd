<?php
    $simple=true;//Remove upper menu
    header('Content-Type: text/html; charset=UTF-8');
    include_once("include/common.php");
    
    if (!loginCheck('User'))exit;
    global $action, $id, $cur_page, $lang, $conn, $config;
    
    
    if(isset($_REQUEST['UseFunc'])){
        ob_clean();//Clear all echo
        if($_POST['UseFunc']=='CardAdd'){//Add card function call
            CardAdd($_POST['ID'],$_POST['BalanceMax'],$_POST['Paid'],$_POST['user_name']);
        }
        else if($_POST['UseFunc']=='CardAddFrame'){//Add card Frame function call
            echo CardAddFrame();
        }
        else if($_POST['UseFunc']=='CardGet'){// Get/Search cards function call
            if($_POST['ID']!=null){
                 $filter['ID']=$_POST['ID']; 
                if(";".substr($filter['ID'],1,-1)."?" == $filter['ID']){
                    $filter['ID'] = substr($filter['ID'],1,-1);
                }
            }
            
            if($_POST['BalanceCurrent']!=null) $filter['BalanceCurrent']=$_POST['BalanceCurrent'];
            if($_POST['DateCreate']!=null) $filter['DateCreate']=$_POST['DateCreate'];
			if($_POST['UserNameP']!=null) $filter['UserNameP']=$_POST['UserNameP'];/*sk 18/02/2016*/
			//echo $_POST['ID'];
			
            echo CardGet($filter);
        }
        else if($_POST['UseFunc']=='CardInfo'){
            echo CardInfo($_POST['ID']);
        }
        else if($_POST['UseFunc']=='CardCharge'){
            echo CardCharge($_POST['ID'],$_POST['Charge'],$_POST['Paid']);
        }
		 else if($_POST['UseFunc']=='CardZero'){
            echo CardZero($_POST['ID'],$_POST['yitra']);
        }
        else if($_REQUEST['UseFunc']=='CardRemove'){        	
            echo CardRemove($_REQUEST['ID']);
        }
		else if($_REQUEST['UseFunc']=='CardUpdateUser'){        	
            echo CardUpdateUser($_REQUEST['ID'],$_REQUEST['user']);
        }
        $conn->Close(); // close the db connection
    }
    
    
    
    function CardAdd($ID,$charge,$paid,$user_name1){
        global $conn;
      //  $ID=(int)$ID;
      
        $charge=(float)$charge;
        $paid=(float)$paid;
		$user_name1 = str_replace("'", "\'", $user_name1);
        $sql = " INSERT INTO card_prepaid (`ID`, `DateCreate`,`username`) VALUES ('$ID',NOW(),'$user_name1' )";
		
      
		
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
                    <tr><td>שם בעל הכרטיס:</td><td><input id="user_name" type="text" name="user_name" value="" ><br/><br/></td></tr>
                    </table>
                    <div><input type="button" value="'.$langXML->add.'" onclick="CardAdd()"> <input type="button" value="'.$langXML->cancel.'" onclick="FrontFrameClose()"></div>';
    }
    
    function MenuTop(){
        
    }
    //set query for searc precard
    function CardGet($filters=null){
        global $conn;
        $langXML= LanguageLoad();
        $sql_filter='';
        foreach($filters as $k=>$v){
            switch($k){
                case 'ID':
                    $sql_filter.=" AND ID='".$v."'";
                    break;
                case 'BalanceCurrent':
                    $sql_filter.=' AND BalanceCurrent='.((float)$v);
                    break;
                case 'DateCreate':
                    $sql_filter.=' AND DateCreate='.(mysql_real_escape_string($v));
                    break;
				case 'UserNameP':
                    $sql_filter.=' AND username="'.$v.'"';
                    break;
            }
        }
        if($sql_filter!='')$sql_filter='WHERE '.substr($sql_filter, 4);
        $sql = "
        SELECT * FROM card_prepaid $sql_filter ORDER BY DateCreate DESC
        ";
        $recordSet = $conn->Execute($sql);
        if ($recordSet === false) log_error($sql);
        $num_rows = $recordSet->RecordCount();
        
        $result= '<table class="tableList">
                    <tr class="tableHeader">
                        <td width="25%">שם בעל הכרטיס</td>
                        <td>'.$langXML->cardId.'</td>
                        <td>'.$langXML->balanceCurrent.'</td>
                        <td>'.$langXML->dateCreate.'</td>
                        <td>'.$langXML->Controls.'</td>
                        <td width="25%"></td>
                    </tr>';
        $rowColor=0;
        while (!$recordSet->EOF)
        {
            $ID =           $recordSet->fields["ID"];
			$ss="'";
			$ss.=$ID."'";
            $balanceCurrent= $recordSet->fields["BalanceCurrent"];
            $dateCreate=    $recordSet->fields["DateCreate"];
			$user_name=  $recordSet->fields["username"];
            $rowColor++;
            $rowColor%=2;
            $result.= '<tr class="tableRow'.$rowColor.'" >';
            $result.= "<td =\"25%\" onclick=\"CardInfo(".$ss.")\">$user_name</td>";
            $result.= "<td onclick=\"CardInfo(".$ss.")\">$ID</td>";
            $result.= '<td onclick="CardInfo('.$ss.')">'.money_format('%i',$balanceCurrent).'</td>';
            $result.= "<td onclick=\"CardInfo(".$ss.")\">$dateCreate</td>";
            $result.= "<td><p class=\"butX\" onclick=\"CardRemove('$ID','$langXML->RemoveConfirm');\">X</p></td>";
            $result.= "<td onclick=\"CardInfo(".$ss.")\" width=\"25%\"></td>";
            $result.= "</tr>";
            $recordSet->MoveNext();
        }
        return $result;
    }
    
    function CardInfo($ID){
        global $conn;
        $langXML= LanguageLoad();
        //$ID=(int)$ID;
        $sql="SELECT * FROM card_prepaid WHERE ID='$ID'";
        $recordSet = $conn->Execute($sql);
        $compPos[0]="Comp";
        $compPos[1]="POS";
        if ($recordSet === false) log_error($sql);
        while (!$recordSet->EOF){
            $sqlHistory="SELECT * FROM card_prepaid_history WHERE ID='$ID' ORDER BY `DATE` DESC";
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
                        <tr><td>שם בעל הכרטיס:</td><td>'.$recordSet->fields["username"].'</td></tr>
                    </table><br/>
                    <div id="ChargeRow"><input type="button" value="'.$langXML->charge.'" onclick="CardChargeButton()"/><input type="button" value="אפס  יתרה" style="margin-right: 20px;" onclick="CardZeroButton()"/><input type="button" value="עדכון בעל הכרטיס" style="margin-right: 20px;" onclick="CardUpdateUser()"/><input type="text" id="card_user" name="card_user" style="display:none"/>
                    <input type="button" id="update_button" value="עדכן" onclick="UpdateUserNameCard()" style="display:none"/>
                    </div>
                    <div id="ChargeRow2" style="display:none"><input class="inputText1" id="Charge" type="text" value="0.00" onkeyup="$(\'#Paid\').val($(this).val())" onfocus="InputReset(\'0.00\')"/>
                    <input type="button" value="Charge" onclick="CardCharge()"/><input class="inputText1" id="Paid" type="text" value="0.00" onfocus="InputReset(\'0.00\')"/></div>
                    <div id="ChargeRowInfo" style="display:none"><div '.$style.'>'.$langXML->charge.'</div><div style="display:inline-block;width:110px;"></div><div '.$style.'>'.$langXML->paid.'</div></div>';
            $recordSet->MoveNext();
        }
        return $result;
    }
    
    function CardCharge($ID,$charge,$paid){
        global $conn;
        //$ID=(int)$ID;
        $charge=abs((float)$charge);
        $paid=abs((float)$paid);
        if($charge==0) return false;
        $sql="UPDATE card_prepaid SET BalanceCurrent=BalanceCurrent+$charge WHERE ID='$ID'";
        $recordSet = $conn->Execute($sql);
        if ($recordSet === false) log_error($sql);
        $sql="INSERT INTO card_prepaid_history (ID, `Date`, Charge, Paid, Platform) VALUES('$ID', NOW(), $charge, $paid, 0)";
        $recordSet = $conn->Execute($sql);
        if ($recordSet === false) log_error($sql);
    }
	function CardZero($ID,$yitra){
        global $conn;
        //$ID=(int)$ID;
        $yitra=abs((float)$yitra);
        if($yitra==0) return false;
        $sql="UPDATE card_prepaid SET BalanceCurrent=0 WHERE ID='$ID'";
        $recordSet = $conn->Execute($sql);		
        if ($recordSet === false) log_error($sql);
		$yitra=-$yitra;
        $sql="INSERT INTO card_prepaid_history (ID, `Date`, Charge, Paid, Platform) VALUES('$ID', NOW(), $yitra, $yitra, 0)";
        $recordSet = $conn->Execute($sql);
        if ($recordSet === false) log_error($sql);
    }
    
    function CardRemove($id){
        global $conn;
       // $id=(int)$id;
  // $sql="  alter table card_prepaid22 drop column `html`";
	  
   /*$sql="ALTER TABLE card_prepaid_history DROP FOREIGN KEY `card_prepaid_history_ibfk_1`;
  			delete from card_prepaid where ID = '$id';
  			SET FOREIGN_KEY_CHECKS = 0;
  			ALTER TABLE `card_prepaid_history` ADD CONSTRAINT `card_prepaid_history_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `card_prepaid` (`ID`);";*/
       $sql = " DELETE FROM card_prepaid WHERE ID like '$id'";
	
	//echo $sql;die();
        $recordSet = $conn->Execute($sql);
        if ($recordSet === false){
        	echo 'dddddddd';
        	echo log_error($sql);
        
        } 
    }
	
	function CardUpdateUser($id,$user){
		 global $conn;
		$sql="update card_prepaid set username='$user' where ID='$id'";
		 $recordSet = $conn->Execute($sql);
        if ($recordSet === false){
        	echo 'dddddddd';
        	echo log_error($sql);
        
        } 
	}
    
    function LanguageLoad(){
            //error_reporting(E_ALL);
            switch($_COOKIE['GO_LANGUAGE_NAME']){
            case 'Hebrew':
                $xml=simplexml_load_file("lang/heb.xml");
                return $xml;
                break;
            case 'English':
                $xml=simplexml_load_file("lang/eng.xml");
                return $xml;
                break;
            }
        }
    
?>
