<?php
 //       error_reporting(E_ALL);
//ini_set('display_errors', '1');
require_once('config.php');         
require_once "/usr/share/pear/Mail.php";
$dbHost = "localhost";        //Location Of Database usually its localhost
    $dbUser = "myworld";            //Database User Name
    $dbPass = "eituv7";            //Database Password
    $dbDatabase = "myworld_paper_ballot";    //Database Name
    
    $db = mysql_connect($dbHost,$dbUser,$dbPass)or die("Error connecting to database.");
    //Connect to the databasse
    mysql_select_db($dbDatabase, $db)or die("Couldn't select the database.");
	
// Database connection                                   
$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
$mysqli->real_connect($config['db_host'],$config['db_user'],$config['db_password'],$config['db_name']); 
                      
// Get all parameters provided by the javascript
$id = $mysqli->real_escape_string(strip_tags($_POST['id']));
//echo $id;
$newid = str_replace("volunteers_", "", $id);
//echo $newid;

$sql = "SELECT `volunteer_id` FROM `volunteers` WHERE `idscouts`='$newid' LIMIT 1";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
		$partnerid = $row['volunteer_id'];	}
		
$sql = "SELECT `email` FROM `volunteers` WHERE `idscouts`='$newid' LIMIT 1";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
		$email = $row['email'];	}
		
$sql = "SELECT `password` FROM `volunteers` WHERE `idscouts`='$newid' LIMIT 1";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
		$password = $row['password'];	}
		
$sql = "SELECT `organization` FROM `volunteers` WHERE `idscouts`='$newid' LIMIT 1";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
		$name = $row['organization'];	}

// This very generic. So this script can be used to update several tables.
$return=false;
//if ( $stmt = $mysqli->prepare("DELETE from volunteers WHERE idscouts=".$newid."")) {
	//$stmt->bind_param("si",$newid);
	//$return = $stmt->execute();
	//$stmt->close();
	
//}             
$mysqli->close();        

$from = "MyWorld Partner System <support@myworld2015.org>";
		 $to = "$name <$email>";
		 $subject="MyWorld Partner System - MyWorld Partner Password Reminder";
		 $body="<span style='font-size: 14px;'>
			$name,<br><br>
			A password reminder has been requested for your Partner ID<br>
			<br>
			Your login information is:<br>
			<br>
			<b>MY World Partner ID: $partnerid<br>
			Password: $password</b><br><br>
			This is your customized URL: http://myworld2015.org/?partner=$partnerid<br><br>
			Use this URL if you would like a record made of your mobilization efforts. We will be regularly reporting back to partners on the number of voters that have used their custom link, giving you a clear measurement of the effectiveness of your mobilization efforts.<br><br>
			MyWorld Ballot Partner System<br><br>
			If you have any questions or problems accessing your account please email the MyWorld Partner focal point (support@myworld2015.org).
			</span>";

		 
		 $host = "ssl://smtp.gmail.com";
		 $port = "465";
		 $username = "info@worldwewant2015.org";
		 $password = "TWr0cks6";
		 $contenttype = "text/html; charset=iso-8859-1"; 
		 $mimeversion = "1.0"; 
		 $headers = array ('From' => $from,
		   'To' => $to,
		   'Subject' => $subject,
		   'Content-type' => $contenttype,
		   'MIME-version' => $mimeversion);
		 $smtp = Mail::factory('smtp',
		   array ('host' => $host,
			 'port' => $port,
			 'auth' => true,
			 'username' => $username,
			 'password' => $password));
		 
		 $mail = $smtp->send($to, $headers, $body);
		 
		 if (PEAR::isError($mail)) {
		   echo("<p>" . $mail->getMessage() . "</p>");
		  } else {
		   //echo("<p>Message successfully sent!</p>");
		  }

echo $partnerid;
//echo $return ? "ok" : "error";

      
