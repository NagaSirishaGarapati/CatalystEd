<?php
//The PHP script should include these command line options (directives):
//• --file [csv file name] – this is the name of the CSV to be parsed
//• --create_table – this will cause the MySQL users table to be built (and no further 
//• action will be taken)
//• --dry_run – this will be used with the --file directive in case we want to run the script but not 
//insert into the DB. All other functions will be executed, but the database won't be altered
//• -u – MySQL username
//• -p – MySQL password
//• -h – MySQL host
//• --help – which will output the above list of directives with details.

//$mysqlConn;

//Open Database connection
function OpenDBConnection($username, $password, $host)
{
	$mysqlConn = new mysqli($host, $username, $password);
	if ($mysqlConn->connect_error) {
		die('Connect Error (' . $mysqlConn->connect_errno . ') ' . $mysqlConn->connect_error);
	}
	else
	{
		return $mysqlConn;
	}
	echo '<p>Connection OK '. $mysqlConn->host_info.'</p>';
	echo '<p>Server '.$mysqlConn->server_info.'</p>';
	echo '<p>Initial charset: '.$mysqlConn->character_set_name().'</p>';
}

//Close Database Connection
function CloseDBConnection($mysqlConn){
	$mysqlConn->close();
}
