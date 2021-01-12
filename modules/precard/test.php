<?php	
    
	include("include/common.php");
	if (!loginCheck('User'))exit;
	global $action, $id, $cur_page, $lang, $conn, $config;
        echo '<link href="style.css" rel="stylesheet" type="text/css">';
        echo '<script type="text/javascript" src="functions.js"></script>';
        $sql = "
        SELECT * FROM card_prepaid
        ";
        
        //CardAdd
        echo '<div id="frameAddCard" style="display:none;">
                <div>Card Number: <input type="text" name="cardNumber"></input></div>
                <div>Charge: <input type="text" name="cardMax"></input></div>
                <div><input type="button" value="Add"></input> <input type="button" value="Cancel" onclick="FrontFrameClose()"></input></div>
            </div>';
        
        echo '<div id="divMenu"><table><tbody>
                <tr>
                    <td><input type="button" value="Create Card" onClick="javascript:FrontFrame($(\'#frameAddCard\').html(),1,\'250px\');"></input></td>
                </tr>
            </tbody></table></div>';
        //echo "<!--SQL: $sql-->";
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $recordSet = $conn->Execute($sql);
        if ($recordSet === false) log_error($sql);
        $num_rows = $recordSet->RecordCount();

        // build the string to select a certain number of listings per page
        $config[listings_per_page] = 1000;
        $limit_str = $cur_page * $config[listings_per_page];


        $recordSet = $conn->SelectLimit($sql, $config[listings_per_page], $limit_str );
        if ($recordSet === false) 
        {
                log_error($sql);
        }
        $count = 0;
        echo '<div id="divMain">
                <table class="tableList">
                    <tr class="tableHeader">
                        <td>Card ID</td>
                        <td>Balance Current</td>
                        <td>Balance Max</td>
                        <td>Date Create</td>
                    </tr>';
        while (!$recordSet->EOF)
        {
            $ID =           $recordSet->fields["Id"];
            $balanceCurrent=$recordSet->fields["BalanceCurrent"];
            $balanceMax=    $recordSet->fields["BalanceMax"];
            $dateCreate=    $recordSet->fields["DateCreate"];
            // alternate the colors
            if ($count == 0) $count = $count +1;
            else $count = 0;

            echo '<tr class="tableRow">';
            echo "<td>$ID</td>";
            echo "<td>$balanceCurrent</td>";
            echo "<td>$balanceMax</td>";
            echo "<td>$dateCreate</td>";
            echo "</tr>";
            $recordSet->MoveNext();
        } // end while
        
        echo '</table></div>';
	include("$config[template_path]/admin_bottom.html");
	$conn->Close(); // close the db connection
?>