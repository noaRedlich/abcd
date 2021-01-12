<?php
require("functions.php");

if(isset($_GET['Com'])){
    DBConnect();
    switch(strtolower($_GET['Com'])){
        case 'enter':
            echo Enter($_GET['TerminalID'], $_GET['CardID'], $_GET['Date'], $_GET['Time']);
            break;
        case 'exit':
            echo Out($_GET['TerminalID'], $_GET['CardID'], $_GET['Date'], $_GET['Time']);
            break;
    }
}
mysql_close();

function DBConnect(){
    $db_host='localhost';
    $db_user = "vcx";
    $db_password = "HwvaDPcfu2udFcz5";
    mysql_connect($db_host, $db_user, $db_password);
}

// запрос: /modules/clock/api.php?Com=enter&TerminalID=6000010&CardID=10&Date=07092014&Time=0830
function Enter($TerminalID, $cardID, $date, $time)
{
    $message = array();
    $db_name_stock = "vcx_weberp";
    $TerminalID = mysql_real_escape_string($TerminalID);
    $num =  mysql_real_escape_string($cardID);
    $DateSQL=substr($date,4,4)."-".substr($date,2,2)."-".substr($date,0,2);  // 2014-09-07
    $newEnterTime=substr($time,0,2).":".substr($time,2,2); 					 // 08:30

    $message['Status'] = '';
    $message['TerminalID'] = $TerminalID;
    $message['CardID'] = $num;
    $message['Date'] = $DateSQL;
    $message['Time'] = $newEnterTime;

    // check Date
    if (checkdate(substr($date,2,2), substr($date,0,2), substr($date,4,4)) == false)
    {
        $message['Status'] = "Error: Bad Date";
        return json_encode($message);
    }
    // check Time
    if (substr($time,0,2)>23 || substr($time,2,2)>59)
    {
        $message['Status'] = "Error: Bad Time";
        return json_encode($message);
    }
    mysql_select_db($db_name_stock); // vcx_weberp

    //Getting username
    $result = mysql_query('SELECT ud.UserName, ls.user_ID FROM listingsStocks ls LEFT JOIN userdata ud ON ls.user_ID=ud.office_user_id WHERE ls.TerminalID='.$TerminalID);
    $row = mysql_fetch_array($result); // Array ( [0] => meytal [UserName] => meytal [1] => 573 [user_ID] => 573 )
    // check TerminalID
    if ($row == null)
    {
        $message['Status'] = "Error: Bad TerminalID";
        return json_encode($message);
    }
    $username_db='vcx_'.$row['UserName']; // vcx_meytal
    $userID = $row['user_ID'];
    mysql_select_db($username_db);

    // check CardID
    mysql_query("SET NAMES 'utf8'");
    $result = mysql_query("select ID as workerid, HourSalary from workers where user_ID = $userID and WorkerNum=$num and Status=1");
    $row = mysql_fetch_array($result);
    if ($row == null)
    {
        $message['Status'] = "Error: Bad CardID";
        return json_encode($message);
    }
    $workerid = $row['workerid'];
    $hSalary = $row['HourSalary'];

    // check enterence for worker:
    $result = mysql_query("select worker_num, day, entertime from attendance where day = '$DateSQL' and worker_num = '$num'");
    $row = mysql_fetch_array($result);
    if ($row == false)
    {
        $row = mysql_fetch_array(mysql_query("SELECT MAX(id) as id from attendance"));
        $id = $row['id']; // get max id
        $id++;

        $sql="INSERT INTO attendance (hour_salary, manual, id, terminal_id, worker_id, worker_num, day, entertime, exittime, recordclosednormally, istwodates, entermanual, enterclosedabnormally, exitmanual, exitclosedabnormally, user_id,timestamp,valid) VALUES ('$hSalary',1,$id,$TerminalID, '$workerid', $num, '$DateSQL', '$newEnterTime', '$newExitTime', 1, 0, 1, 0, 1, 0, $userID,unix_timestamp(),1)";
        $result = mysql_query($sql);
        if ($result)
        {
            $message['Status'] = "You entered successfully";
            return json_encode($message);
        }
    }
    else
    {
        $message['Status'] = "Error: You entered already today at ".$row['entertime'];
        return json_encode($message);
    }
}

// запрос: /modules/clock/api.php?Com=exit&TerminalID=6000010&CardID=10&Date=07092014&Time=0830
function Out($TerminalID, $cardID, $date, $time)
{
    $message = array();
    $db_name_stock = "vcx_weberp";
    $TerminalID = mysql_real_escape_string($TerminalID);
    $num =  mysql_real_escape_string($cardID);
    $DateSQL=substr($date,4,4)."-".substr($date,2,2)."-".substr($date,0,2);  // 2014-09-07
    $newExitTime=substr($time,0,2).":".substr($time,2,2); 					 // 08:30

    $message['Status'] = '';
    $message['TerminalID'] = $TerminalID;
    $message['CardID'] = $num;
    $message['Date'] = $DateSQL;
    $message['Time'] = $newExitTime;

    // check Date
    if (checkdate(substr($date,2,2), substr($date,0,2), substr($date,4,4)) == false)
    {
        $message['Status'] = "Error: Bad Date";
        return json_encode($message);
    }
    // check Time
    if (substr($time,0,2)>23 || substr($time,2,2)>59)
    {
        $message['Status'] = "Error: Bad Time";
        return json_encode($message);
    }
    mysql_select_db($db_name_stock); // vcx_weberp

    //Getting username
    $result = mysql_query('SELECT ud.UserName, ls.user_ID FROM listingsStocks ls LEFT JOIN userdata ud ON ls.user_ID=ud.office_user_id WHERE ls.TerminalID='.$TerminalID);
    $row = mysql_fetch_array($result); // Array ( [0] => meytal [UserName] => meytal [1] => 573 [user_ID] => 573 )
    // check TerminalID
    if ($row == null)
    {
        $message['Status'] = "Error: Bad TerminalID";
        return json_encode($message);
    }
    $username = $row['UserName'];
	$username_db = 'vcx_'.$row['UserName']; // vcx_meytal
    $userID = $row['user_ID'];
    mysql_select_db($username_db);

    // check CardID
    mysql_query("SET NAMES 'utf8'");
    $result = mysql_query("select ID as workerid, HourSalary from workers where user_ID = $userID and WorkerNum=$num and Status=1");
    $row = mysql_fetch_array($result);
    if ($row == null)
    {
        $message['Status'] = "Error: Bad CardID";
        return json_encode($message);
    }
    $workerid = $row['workerid'];
    $hSalary = $row['HourSalary'];	
	//calcSalary($num,$userID,$username,$DateSQL,$hSalary,$newExitTime);	
	// check enterence for worker:
    $result = mysql_query("select id, worker_num, day, entertime, exittime from attendance where day = '$DateSQL' and worker_num = '$num'");
    $row = mysql_fetch_array($result);
    if ($row['exittime'] == '00:00:00')
    {
		$result = calcSalary($num,$userID,$username,$DateSQL,$hSalary,$newExitTime);		
        if ($result)
        {
            $message['Status'] = "You exit successfully at ".$newExitTime;
            return json_encode($message);
        }
    }
    else
    {
        $message['Status'] = "Error: You exit already today at ".$row['exittime'];
        return json_encode($message);
    }
}