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


function add_to_dist_tool($dist,$data) {
$hostname="localhost";
$username="fairdist_dist";
$password="xeF4I8iTzzTv";
$dbname="fairdist_dist";
// connect to the database
$dbh=mysql_connect ($hostname,$username, $password) or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("$dbname");

$query = "INSERT INTO `d_contacts` (`dist_id`,`crm_id`, `v_email`, `v_name`, `v_message`,`v_phone`, `v_city`, `v_state`, `v_zip`, `v_product`, `v_quantity`, `v_use`, `v_submitted`) VALUES ('".$dist['id']."', '".$dist['crm_id']."', '".$data['email']."', '".$data['name']."', '".$data['message']."', 
			'{$data['phone']}',
			'{$data['city']}',
			'{$data['state']}',
			'{$data['zip']}', 
			'{$data['product']}',
			'{$data['quantity']}',
			'{$data['product_use']}',
			NOW())";
$result = mysql_query ($query); // Run the query.
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




?>