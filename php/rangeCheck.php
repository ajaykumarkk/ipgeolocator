<?php
$servername = "localhost";
$username = "root";
$password = "root123";
$dbname = "ip_geo";
$t="";
$t1="";
$ip = $_GET["ip"];
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT *
FROM iprange
WHERE (INET_ATON('$ip') BETWEEN INET_ATON(START) AND INET_ATON(END));";
$result = $conn->query($sql);


$json = file_get_contents('https://ipinfo.io/'.$ip.'/json');
$obj = json_decode($json,true);

$str="";
if ($result->num_rows > 0) {
    // output data of each row
    $row = $result->fetch_assoc();
		$t=$row["CountryCode"];
		if(count($obj) < 3){$t1 = $t;}
		//print_r($obj);
		if(strcmp($t,$t1)!=0 )
		{
			$t=$obj["country"];
		}
		
		if(strcmp($t,'ZZ')==0)
		{
			echo "Private IP or IP unallocated";
			exit(0);
		}
		$sql = "SELECT *
		FROM country
		WHERE iso = '$t'";
		$result = $conn->query($sql);
		$row1 = $result->fetch_assoc();
		if(count($obj) < 3)
		{
			$str = $row1["nicename"]."#@"; 
		}
		else
		{
			 $str=$row1["nicename"]."#@".$obj["city"]."#@".$obj["region"]."#@".$obj["org"]."#@".$obj["loc"]."#@";
		}
		echo $str;
		
    
} else {
    echo "0 results";
}

$conn->close();
?> 
