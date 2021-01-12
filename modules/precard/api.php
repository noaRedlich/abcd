<?php
    
    $db_name = "vcx00";
    $db_name_stock = "vcx_weberp";
    
    if(isset($_GET['Com'])){
        DBConnect();
        switch(strtolower($_GET['Com'])){
            case 'getbalance':
                echo GetBalance($_GET['TerminalID'], $_GET['CardID']);
                break;
            case 'pay':
                echo Pay($_GET['TerminalID'], $_GET['CardID'], $_GET['Pay']);
                break;
            case 'charge':
                echo Charge($_GET['TerminalID'], $_GET['CardID'], $_GET['Charge'], $_GET['Pay']);
                break;
//            case 'cardadd':
//                echo CardAdd($_GET['TerminalID'], $_GET['CardID'],$_GET['Charge'],$_GET['Pay']);
//                break;
        }
    }
    mysql_close();
    
    
    
    function DBConnect(){
        $db_host='localhost';
        $db_name = "vcx_weberp";
        $db_user = "vcx";
        $db_password = "HwvaDPcfu2udFcz5";
        mysql_connect($db_host, $db_user, $db_password);
    }
    
    function GetBalance($stockID, $cardID){
        $db_name_stock = "vcx_weberp";
        $stockID =  mysql_real_escape_string($stockID);
        $cardID =  mysql_real_escape_string($cardID);
        mysql_select_db($db_name_stock);
        
        //Getting username
        $result=mysql_query('SELECT ud.UserName FROM listingsStocks ls LEFT JOIN userdata ud ON ls.user_ID=ud.office_user_id WHERE ls.TerminalID='.$stockID);
        $row=mysql_fetch_array($result);
        if($row==null) return "<Prepaid><Error>Bad StockID</Error></Prepaid>";
        
        $username='vcx_'.$row['UserName'];
        mysql_select_db($username);
        $result=mysql_query('SELECT * FROM card_prepaid WHERE ID="'.$cardID.'"');
        $row=mysql_fetch_array($result);
        if($row==null) return "<Prepaid><Error>Bad CardID</Error></Prepaid>";
        
        /*<?xml version=\"1.0\" encoding=\"UTF-8\" ?>*/
        return "<Prepaid><Terminal>$stockID</Terminal><CardNum>$cardID</CardNum><Balance>{$row['BalanceCurrent']}</Balance></Prepaid>";
    }
    
    function Pay($stockID, $cardID, $pay){
        $db_name_stock = "vcx_weberp";
        $stockID =  mysql_real_escape_string($stockID);
        $cardID =  mysql_real_escape_string($cardID);
        $pay=abs((float)$pay);
        mysql_select_db($db_name_stock);
        
        //Getting username
        $result=mysql_query('SELECT ud.UserName FROM listingsStocks ls LEFT JOIN userdata ud ON ls.user_ID=ud.office_user_id WHERE ls.TerminalID='.$stockID);
        $row=mysql_fetch_array($result);
        if($row==null) return "<Prepaid><Error>Bad StockID</Error></Prepaid>";
        
        $username='vcx_'.$row['UserName'];
        mysql_select_db($username);
        $result=mysql_query("SELECT BalanceCurrent FROM card_prepaid WHERE ID='".$cardID."'");
        $row=mysql_fetch_array($result);
        if($row==null) return "<Prepaid><Error>Bad CardID</Error></Prepaid>";
        $balanceLeft=$row['BalanceCurrent']-$pay;
        if($balanceLeft<0) return "<Prepaid><Error>Not enough balance: ".abs($balanceLeft)."</Error></Prepaid>";
        /*<?xml version=\"1.0\" encoding=\"UTF-8\" ?>*/
        mysql_query('UPDATE card_prepaid SET BalanceCurrent='.$balanceLeft.' WHERE ID="'.$cardID.'"');
        mysql_query("INSERT INTO card_prepaid_history (ID, `Date`, Charge, Paid, Platform) VALUES('$cardID', NOW(), -$pay, -$pay, 1)");
        return "<Prepaid><Terminal>$stockID</Terminal><CardNum>$cardID</CardNum><Balance>".abs($balanceLeft)."</Balance></Prepaid>";
    }
    
    function Charge($stockID, $cardID, $charge, $paid){
        $db_name_stock = "vcx_weberp";
        $stockID =  mysql_real_escape_string($stockID);
        $cardID =  mysql_real_escape_string($cardID);
        $charge=abs((float)$charge);
        $paid=abs((float)$paid);
        mysql_select_db($db_name_stock);
        
        //Getting username
        $result=mysql_query('SELECT ud.UserName FROM listingsStocks ls LEFT JOIN userdata ud ON ls.user_ID=ud.office_user_id WHERE ls.TerminalID='.$stockID);
        $row=mysql_fetch_array($result);
        if($row==null) return "<Prepaid><Error>Bad StockID</Error></Prepaid>";
        $username='vcx_'.$row['UserName'];
        mysql_select_db($username);
        
        
        $result=mysql_query('SELECT BalanceCurrent FROM card_prepaid WHERE ID="'.$cardID.'"');
        $row=mysql_fetch_array($result);
        //if($row==null) return "<Prepaid><Error>Bad CardID</Error></Prepaid>";
        if($row==null) {
            $row['BalanceCurrent']=0;
            mysql_query(" INSERT INTO card_prepaid (`ID`, `DateCreate`) VALUES ('$cardID',NOW()) "); //If card not exists. Create it.
        }
        $balanceLeft=$row['BalanceCurrent']+$charge;
        /*<?xml version=\"1.0\" encoding=\"UTF-8\" ?>*/
        mysql_query('UPDATE card_prepaid SET BalanceCurrent='.$balanceLeft.' WHERE ID="'.$cardID.'"');
        mysql_query("INSERT INTO card_prepaid_history (ID, `Date`, Charge, Paid, Platform) VALUES('$cardID', NOW(), $charge, $paid, 1)");
        return "<Prepaid><Terminal>$stockID</Terminal><CardNum>$cardID</CardNum><Balance>".abs($balanceLeft)."</Balance></Prepaid>";
    }
    
//    function CardAdd($stockID, $ID, $charge, $paid){
//        $db_name_stock = "vcx_weberp";
//        $stockID =  mysql_real_escape_string($stockID);
//        $ID=(int)$ID;
//        $charge=(float)$charge;
//        $paid=(float)$paid;
//        mysql_select_db($db_name_stock);
//        //Getting username
//        $result=mysql_query('SELECT ud.UserName FROM listingsStocks ls LEFT JOIN userdata ud ON ls.user_ID=ud.office_user_id WHERE ls.TerminalID='.$stockID);
//        $row=mysql_fetch_array($result);
//        if($row==null) return "<Prepaid><Error>Bad StockID</Error></Prepaid>";
//        $username='vcx_'.$row['UserName'];
//        mysql_select_db($username);
//        
//        $result=  mysql_query("SELECT ID FROM card_prepaid WHERE ID=$ID");
//        $row=  mysql_fetch_array($result);
//        if ($row['ID']!=null)return "<Prepaid><Error>Wrong CardID</Error></Prepaid>";
//        mysql_query(" INSERT INTO card_prepaid (`ID`, `DateCreate`) VALUES ($ID,NOW()) ");
//        return Charge($stockID, $ID, $charge, $paid);
//    }
?>
