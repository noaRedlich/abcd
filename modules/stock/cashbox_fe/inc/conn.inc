<?php
//session_start();
		$ay_db_user="vcx"; 
        $ay_db_password="HwvaDPcfu2udFcz5"; 
       
		$ay_db_host="localhost";
        $ay_db_con=@mysql_connect($ay_db_host,$ay_db_user,$ay_db_password) or die("<BR><BR><B>Transformation in progress..................................................................<br>................................................................................................<BR>..........................................................................................");global $value_fnd;global $vf;
        echo $ay_db_db;
        
		$query="select id,username,stock_user from vcx00.users where username='".$_COOKIE['GO_UN']."' and `password`='".$_COOKIE['GO_PW']."'";
		$userterminal42=$_COOKIE['GO_UN'];
		
		$result=mysql_query($query);
		$row=mysql_fetch_array($result);
		
		$userID2=$row[id];
		$username2=$row[username];
		$userterminal2255=$row[username];
		if($row[stock_user]>0){
			$query="select id,username from vcx00.users where id=$row[stock_user]";
			
			$result=mysql_query($query);
			$row=mysql_fetch_array($result);
			
			$userID=$row[id];
			$username=$row[username];
		}
		else{
			$userID=$userID2;
			$username=$username2;
		}
		 $ay_db_db="vcx_".$username; 
		mysql_select_db($ay_db_db) or die("<BR><BR><B>ERROR:</B> cannot connect to the DB.<BR>");
		 mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
        //זמן ירושלים
        date_default_timezone_set("Asia/Jerusalem");
        
?>