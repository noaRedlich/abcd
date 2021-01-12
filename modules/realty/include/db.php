<?php
	$db_type = $GO_CONFIG->db_type;
	// possible choices are: access, ado, db2, ado_access, vfp, fbsql, ibase
	// firebird, borland_ibase, informix, mssql, mysql, mysqlt, maxsql, oci8
	// oci8po, odbc, oracle, postgres, postgres7, sqlanywhere, sybase

	$db_user = $GO_CONFIG->db_user;		//database user
	$db_password = $GO_CONFIG->db_pass;		//database password
	$db_database = $GO_CONFIG->db_name;	//database definition file
	$db_server = $GO_CONFIG->db_host;		//database server -- usually localhost, but one never knows
?>