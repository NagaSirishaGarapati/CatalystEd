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

/* opens Database connection */
function OpenDBConnection($username, $password, $host)
{
	$mysqlConn = new mysqli($host, $username, $password);
	if ($mysqlConn->connect_error) {
		die('Connect Error (' . $mysqlConn->connect_errno . ') ' . $mysqlConn->connect_error);
	} else {
		return $mysqlConn;
	}
	echo '<p>Connection OK ' . $mysqlConn->host_info . '</p>';
	echo '<p>Server ' . $mysqlConn->server_info . '</p>';
	echo '<p>Initial charset: ' . $mysqlConn->character_set_name() . '</p>';
}

/* closes Database Connection */
function CloseDBConnection($mysqlConn)
{
	$mysqlConn->close();
}

/*reads requested command line Options and return the options as an array */
function getRequestOpts()
{
	$shortOptions = "u::";	//optional value
	$shortOptions .= "p::";	//optional value
	$shortOptions .= "h::";	//optional value

	$longOptions = array(
		"file:",			//required value
		"create_table::",	//optional value
		"dry_run",			//no value
		"help"				//no value
	);

	$opts = getopt($shortOptions, $longOptions);
	return $opts;
}

/* creates database */
function createDatabase($db, $conn)
{
	$createDB = "CREATE DATABASE IF NOT EXISTS {$db}";
	if ($conn->query($createDB) === TRUE) {
		echo "Database 'CatalystEd' created successfully", PHP_EOL;
	} else {
		echo "Error creating database: " . $conn->error, PHP_EOL;
	}
}

/* creates table */
function createTable($table, $conn)
{
	$createUsersTb = "CREATE TABLE IF NOT EXISTS {$table} (
		id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(50) NOT NULL,
		surname VARCHAR(50) NOT NULL,
		email VARCHAR(100),
		UNIQUE KEY unique_email (email)
		)";
	if ($conn->query($createUsersTb) === TRUE) {
		echo "Table created successfully", PHP_EOL;
	} else {
		echo "Error creating table: " . $conn->error, PHP_EOL;
	}
}

/* Check the option is empty or null*/
function IsNullOrEmptyString($str){
    return ($str === null || trim($str) === '');
}


$options = getRequestOpts();
print_r($options);

if (!array_key_exists('file', $options)) {
	die("Please specify the file name to upload users using the command line option --file .");
} else {
	//Get MySQL UserName
	$mysqlUser 	= (array_key_exists('u', $options)) ? $options['u'] : 'root';
	//Get MySQL Password
	$mysqlPwd 	= (array_key_exists('p', $options)) ? $options['p'] : '';
	//Get MySQL Host
	$mysqlHost 	= (array_key_exists('h', $options)) ? $options['h'] : 'localhost';

	//Open MySQL Connection
	$mysqlConn = OpenDBConnection($mysqlUser, $mysqlPwd, $mysqlHost);

	//create database 'CatalystEd'
	createDatabase('CatalystEd', $mysqlConn);
	//select database 'CatalystEd'
	$mysqlConn->select_db('CatalystEd');

	//Default table name
	$tableName = 'Users';
	//command line option --create_table exists :
	if (array_key_exists('create_table', $options)) {
		//create table
		if(!IsNullOrEmptyString($options['create_table']))
		{
			$tableName = $options['create_table'];
		}
		createTable($tableName, $mysqlConn);
	}
	$fileName		= $options['file'];
	$fileContent	= fopen($fileName, "r");
	while (($row = fgetcsv($fileContent)) !== FALSE) {
		//print_r($row);
		//Before Inserting record, checking if the table exists or not
		if (!$mysqlConn->query("SELECT * FROM {$tableName}") === TRUE) {
			die("Table doesn't exists, please specify the command line option --create_table .");
		}
		//Inserting rows
		$insertQuery = $mysqlConn->prepare("INSERT INTO {$tableName} (name, surname, email) VALUES (?, ?, ?)");
		$name 		= ucfirst(trim($row[0]));
		$surname 	= ucfirst(trim($row[1]));
		$email 		= strtolower(trim($row[2]));
		if (!preg_match("/^([a-z\d\.-]+)@([a-z\d-]+)\.([a-z]{2,8})(\.[a-z]{2,8})?$/", $email)) {
			echo "Email : '" . $email . "' is not valid. So, this record with the mentioned email is not inserted to the table", PHP_EOL;
			continue;
		}

		if (!array_key_exists('dry_run', $options)) {
			$insertQuery->bind_param("sss", $name, $surname, $email);
			$insertQuery->execute();
		} else {
			echo "dry run";
		}
	}
	//close file
	fclose($fileContent);
	//Close MySQL Connection
	CloseDBConnection($mysqlConn);
}
