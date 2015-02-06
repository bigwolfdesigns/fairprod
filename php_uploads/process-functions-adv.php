<?PHP
// SCOTTS FORM SOLUTIONS - version 1.2

function isDigits($element) {
  return !preg_match ("/[^0-9]/", $element);
}

function checkMailCode($code) {
	$country = 'US';
  $code = preg_replace("/[\s|-]/", "", $code);
  $length = strlen ($code);

  switch (strtoupper ($country)) {
    case 'US':
      if (($length <> 5) && ($length <> 9)) {
        return FALSE;
      }
      return isDigits($code);
    case 'CA':
      if ($length <> 6) {
        return FALSE;
      }
      return preg_match ("/([A-z][0-9]){3}/", $code);
  }
}

function checkEmail($email) {
  $pattern = "/^[A-z0-9\._-]+"
         . "@"
         . "[A-z0-9][A-z0-9-]*"
         . "(\.[A-z0-9_-]+)*"
         . "\.([A-z]{2,6})$/";
  return preg_match ($pattern, $email);
}



function crm($s, $n, $p, $ci, $st, $z, $e, $ip, $r, $pgs, $f, $co, $m){
$crmstring = 'site='.$s.'&n='.$n.'&p='.$p.'&ci='.$ci.'&st='.$st.'&z='.$z.'&e='.$e.'&ip='.$ip.'&r='.$r.'&pgs='.$pgs.'&f='.$f.'&co='.$co.'&m='.$m;
$crmstring = XORE($crmstring, 'aQeRoswlGsjpowMakISBJhF1XjQVUcEsGTP0Sdn69L');
$crm = '<img src="http://ts.networksmarketinggroup.com/images/crm.php?site='.$crmstring.'" />';
	return $crm;
}

function XOREncryption($InputString, $KeyPhrase){
    $KeyPhraseLength = strlen($KeyPhrase);
    for ($i = 0; $i < strlen($InputString); $i++){
        $rPos = $i % $KeyPhraseLength;
        $r = ord($InputString[$i]) ^ ord($KeyPhrase[$rPos]);
        $InputString[$i] = chr($r);
    }
    return $InputString;
}
 
function XORE($InputString, $KeyPhrase){
    $InputString = XOREncryption($InputString, $KeyPhrase);
    $InputString = base64_encode($InputString);
    return $InputString;
}


// Captcha array
$captcharray = array();
$captcharray[sha1('a.jpg')] = 'fh1tx9';
$captcharray[sha1('b.jpg')] = 'gs9x3h';
$captcharray[sha1('c.jpg')] = 'wyzta4';
$captcharray[sha1('d.jpg')] = 'jqm8pw';
$captcharray[sha1('e.jpg')] = 'utbn7x';
$captcharray[sha1('f.jpg')] = 's5ma3v';
$captcharray[sha1('g.jpg')] = 'gjl2w';
$captcharray[sha1('h.jpg')] = '88ipv6';
$captcharray[sha1('i.jpg')] = 'rty2zk';
$captcharray[sha1('j.jpg')] = 'l5ppf2';
$captcharray[sha1('k.jpg')] = 'aghxyw';
$captcharray[sha1('l.jpg')] = 'hj8rt';
$captcharray[sha1('m.jpg')] = 'km5izt';
$captcharray[sha1('n.jpg')] = 'y5rdn';
$captcharray[sha1('o.jpg')] = 'xt8wx';


function usDistEmails($zip) {
	$query = "SELECT d.email FROM zip_to_dis z JOIN distributors d ON z.dis = d.id WHERE z.zip='$zip'";
	//echo $query;	
	$result = mysql_query ($query); // Run the query.
	$emails = array();
	if (mysql_num_rows($result) != 0) {
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$emails[] = $row['email'];
		}
		return $emails;
	} else {
		return false;
	}
}

function caDistEmails($state)
{
	$query = "SELECT d.email FROM locations l JOIN distributors d ON l.distributor_id = d.id  WHERE l.state='$state'";	
	$result = mysql_query($query);
	$emails = array();
	if (mysql_num_rows($result))
    {
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
        {
		    $emails[] = $row['email'];
		}
		return $emails;
	}
    else
    {
		return false;
	}
}

// used in form-process-adv.php per client request for customer email
function getDistContact($distEmail)
	{
	// reconnect back to original DB (sic)
	//$dbh = mysql_connect ("localhost","fairprod_produsr","qv3Vpv7mKpK1") or die ('Error connecting back to original database: ' . mysql_error());
	//mysql_select_db("fairprod_prods");

	$query = "SELECT company_name,website FROM distributors WHERE email ='" . $distEmail . "'";
	$result = mysql_query ($query); // Run the query.
	if (mysql_num_rows($result) != 0) 
		{
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
			{
			$distContact = $row['company_name'] . "<br />\nhttp://" . $row['website'];
			}
		return $distContact;
		}
	//mysql_close($dbh);
	}
function getDistID($distEmail)
	{
	// reconnect back to original DB (sic)
	//$dbh = mysql_connect ("localhost","fairprod_produsr","qv3Vpv7mKpK1") or die ('Error connecting back to original database: ' . mysql_error());
	//mysql_select_db("fairprod_prods");

	$query = "SELECT id FROM distributors WHERE email ='$distEmail'";
	$result = mysql_query ($query); // Run the query.
	if (mysql_num_rows($result) != 0) 
		{
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
			{
			$distContact = $row['id'];
			}
		return $distContact;
		}
	//mysql_close($dbh);
	}






function getEmailArray($dist_id){
	$query = "SELECT email FROM `d_users` WHERE id='$dist_id'";
	$result = mysql_query ($query); // Run the query.
	//echo $query.'<br />';
	if($result) {
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { $email[] = $row['email'];}
		return $email;
	} else {
		return FALSE;
	}

}





///////////////////////////////////////////
// deprecated, FINALLY

/*
function add_to_dist_tool($dist,$data) 
	{
	// connect to dist DB for now
	$dbh=mysql_connect ("localhost","fairdist_dist","xeF4I8iTzzTv") or die ('Error connecting to external database: ' . mysql_error());
	mysql_select_db ("fairdist_dist");

	$query = "INSERT INTO `d_contacts` (
	`dist_id`,
	`crm_id`,
	`v_email`,
	`v_name`,
	`v_message`,
	`v_phone`,
	`v_city`,
	`v_state`,
	`v_zip`,
	`v_product`,
	`v_quantity`,
	`v_use`,
	`v_submitted`
	) VALUES (
	'" . $dist['id'] . "',
	'" . $dist['crm_id'] . "',
	'" . $data['email'] . "',
	'" . $data['name'] . "',
	'" . $data['message'] . "', 
	'" . $data['phone'] . "',
	'" . $data['city'] . "',
	'" . $data['state'] . "',
	'" . $data['zip'] . "', 
	'" . $data['product'] . "',
	'" . $data['quantity'] . "',
	'" . $data['product_use'] . "',
	NOW()
	)";

	$result = mysql_query($query);
	mysql_close($dbh);

	// reconnect back to original DB
	//$dbh=mysql_connect ("localhost","fairprod_produsr","qv3Vpv7mKpK1") or die ('Error connecting back to original database: ' . mysql_error());
	//mysql_select_db ("fairprod_prods");
	}


function insertUSorCArecord($data) {
	$dbh=mysql_connect ("localhost","fairprod_produsr","qv3Vpv7mKpK1") or die ('I cannot connect to the database because: ' . mysql_error());
	mysql_select_db ("fairprod_prods");
	if ($data['country'] == 'Canada') { 
		$query = "SELECT distributor_id as theid FROM locations WHERE state='".$data['state']."'";	
	} 
	if ($data['country'] == 'United States of America') { 
		$query = "SELECT dis as theid FROM zip_to_dis WHERE zip='".$data['zip']."'";	
	} 
	$disty = array();
	$result = mysql_query ($query); // Run the query.
	if (mysql_num_rows($result) != 0) {
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$disty[] = $row['theid'];
		}
	}	
	
	$dbh=mysql_connect ("localhost","fairdist_dist", "xeF4I8iTzzTv") or die ('I cannot connect to the database because: ' . mysql_error());
	mysql_select_db ("fairdist_dist");
	foreach ($disty as $dist_id) {
	
	$query = "INSERT INTO `d_contacts` (`dist_id`,`crm_id`,`sales_id`,`v_email`, `v_name`, `v_message`,`v_phone`, `v_company`,`v_city`, `v_state`, `v_zip`,`v_country`, `v_product`, `v_quantity`, `v_use`, `v_submitted`) VALUES ('".$dist_id."', '".$data['crm_id']."', '".$data['manager_id']."','".$data['email']."', '".$data['name']."', '".$data['message']."', 
				'{$data['phone']}',
				'{$data['company']}',
				'{$data['city']}',
				'{$data['state']}',
				'{$data['zip']}',
				'{$data['country']}', 
				'{$data['product']}',
				'{$data['quantity']}',
				'{$data['product_use']}',
				NOW())";
	$result = mysql_query ($query); // Run the query.
	//echo $query.'<br />';
	//if($result) {echo 'Successfull<br />';} else {echo 'failed<br />';}
	}
	mysql_close($dbh);
	// Reconnect to original DB
	$dbh=mysql_connect ("localhost","fairprod_produsr","qv3Vpv7mKpK1") or die ('I cannot connect to the database because: ' . mysql_error());
	mysql_select_db ("fairprod_prods");
}




function insertUSorCArecordID($data, $theid) {
	$dbh=mysql_connect ("localhost","fairdist_dist", "xeF4I8iTzzTv") or die ('I cannot connect to the database because: ' . mysql_error());
	mysql_select_db ("fairdist_dist");
	$query = "INSERT INTO `d_contacts` (`dist_id`,`crm_id`,`sales_id`,`v_email`, `v_name`, `v_message`,`v_phone`, `v_company`,`v_city`, `v_state`, `v_zip`,`v_country`, `v_product`, `v_quantity`, `v_use`, `v_submitted`) VALUES ('". $theid."', '".$data['crm_id']."', '".$data['manager_id']."','".$data['email']."', '".$data['name']."', '".$data['message']."', 
				'{$data['phone']}',
				'{$data['company']}',
				'{$data['city']}',
				'{$data['state']}',
				'{$data['zip']}',
				'{$data['country']}', 
				'{$data['product']}',
				'{$data['quantity']}',
				'{$data['product_use']}',
				NOW())";
	$result = mysql_query ($query); // Run the query.
	//echo $query.'<br />';
	//if($result) {echo 'Successfull<br />';} else {echo 'failed<br />';}
	
	mysql_close($dbh);
	// Reconnect to original DB
	$dbh=mysql_connect ("localhost","fairprod_produsr","qv3Vpv7mKpK1") or die ('I cannot connect to the database because: ' . mysql_error());
	mysql_select_db ("fairprod_prods");
}


*/




?>
