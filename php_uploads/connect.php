<?PHP

// SCOTTS FORM SOLUTIONS - version 1.2
//Connect To Database
$hostname	 = "localhost";
$username	 = "fairprod_produsr";
$password	 = "qv3Vpv7mKpK1";
$dbname		 = "fairprod_prods";
// connect to the database
$dbh		 = mysql_connect($hostname, $username, $password) or die('I cannot connect to the database because: '.mysql_error());
mysql_select_db($dbname);


// Create a function for escaping the data.
if(!function_exists('escape_data')){
	function escape_data($data){
		if(ini_get('magic_quotes_gpc')){
			$data = stripslashes($data);
		}
		if(function_exists('mysql_real_escape_string')){
			global $dbh; // Need the connection.
			$data = mysql_real_escape_string(trim($data), $dbh);
		}else{
			$data = mysql_escape_string(trim($data));
		}
		return $data;
	}
}
if(!function_exists('underscore')){
	function underscore($co){
		$search	 = array(' ', '/');
		$replace = array("_", '_');
		$result	 = str_replace($search, $replace, $co);
		return $result;
	}
}
if(!function_exists('dunderscore')){
	function dunderscore($co){
		$search	 = array('_');
		$replace = array(" ");
		$result	 = str_replace($search, $replace, $co);
		return $result;
	}
}
if(!function_exists('removeslash')){
	function removeslash($co){
		$search	 = array("\\");
		$replace = array("");
		$result	 = str_replace($search, $replace, $co);
		return $result;
	}
}
if(!function_exists('cleanEmailName')){
	function cleanEmailName($co){
		$search	 = array('_', '.', '-');
		$replace = array(" ", " ", " ");
		$result	 = str_replace($search, $replace, $co);
		return $result;
	}
}
function swapName($name, $co){
	$search = array(" [NAME]", "[NAME] ", "[NAME]");
	if($name == ''){
		$replace = array('', '', '');
	}else{
		$replace = array(' '.$name, $name.' ', $name);
	}
	$result = str_replace($search, $replace, $co);
	return $result;
}
function fixfeat($co){
	$search	 = array('<ul>Features<li>', '<ul>Benefits<li>', 'Panel Loading Station Details:');
	$replace = array('<h2>Features</h2><ul><li>', '<h2>Benefits</h2><ul><li>', '<h2>Panel Loading Station Details</h2<ul>');
	$result	 = str_replace($search, $replace, $co);
	return $result;
}
function pullImages($images){
	$imagearray	 = explode(',', $images);
	$result		 = '';
	foreach($imagearray as $V){
		$result .= '<img src="/images/thumbsm/'.$V.'">';
	}
	return $result;
}
function imagename($co){
	$firstimage	 = explode('<>', $co);
	$co			 = $firstimage[0];
	$search		 = array(' ', '%20', '.bmp', '.BMP', '.gif', '.GIF', '[', ']');
	$replace	 = array("_", '_', '.jpg', '.jpg', '.jpg', '.jpg', '-', '-');
	$result		 = str_replace($search, $replace, $co);
	return $result;
}
function offsetToPagePos($offset){
	if($offset == 0){
		return '';
	}
	if($offset <= 10){
		return 'Page 1, Position '.$offset;
	}
	if($offset <= 20){
		return 'Page 2, Position '.($offset - 10);
	}
	if($offset <= 30){
		return 'Page 3, Position '.($offset - 20);
	}
	if($offset <= 40){
		return 'Page 4, Position '.($offset - 30);
	}
	if($offset <= 50){
		return 'Page 5, Position '.($offset - 40);
	}
	if($offset <= 60){
		return 'Page 6, Position '.($offset - 50);
	}
	if($offset <= 70){
		return 'Page 7, Position '.($offset - 60);
	}
	if($offset <= 80){
		return 'Page 8, Position '.($offset - 70);
	}
	if($offset <= 90){
		return 'Page 9, Position '.($offset - 80);
	}
	if($offset <= 100){
		return 'Page 10, Position '.($offset - 90);
	}
}
/**
  Validate an email address.
  Provide email address (raw input)
  Returns true if the email address has the email
  address format and the domain exists.
 */
function validEmail($email){
	$isValid = true;
	$atIndex = strrpos($email, "@");
	if(is_bool($atIndex) && !$atIndex){
		$isValid = false;
	}else{
		$domain		 = substr($email, $atIndex + 1);
		$local		 = substr($email, 0, $atIndex);
		$localLen	 = strlen($local);
		$domainLen	 = strlen($domain);
		if($localLen < 1 || $localLen > 64){
			// local part length exceeded
			$isValid = false;
		}else if($domainLen < 1 || $domainLen > 255){
			// domain part length exceeded
			$isValid = false;
		}else if($local[0] == '.' || $local[$localLen - 1] == '.'){
			// local part starts or ends with '.'
			$isValid = false;
		}else if(preg_match('/\\.\\./', $local)){
			// local part has two consecutive dots
			$isValid = false;
		}else if(!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)){
			// character not valid in domain part
			$isValid = false;
		}else if(preg_match('/\\.\\./', $domain)){
			// domain part has two consecutive dots
			$isValid = false;
		}else if
		(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local))){
			// character not valid in local part unless 
			// local part is quoted
			if(!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local))){
				$isValid = false;
			}
		}
		if($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))){
			// domain not found in DNS
			$isValid = false;
		}
	}
	return $isValid;
}
function get_all_values($qry){
	$result = mysql_query($qry);
	return mysql_fetch_assoc($result);
}
