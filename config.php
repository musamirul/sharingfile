<?php
define('db_server','localhost');
define('db_user','root');
define('db_pass','');
define('db_name','sharefolder');

$con = mysqli_connect(db_server,db_user,db_pass,db_name);
//check connection
if($con->connect_error){
	echo "failed to connect to MYSQL";
}

?>