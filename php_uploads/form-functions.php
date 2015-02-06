<?PHP
// SCOTTS FORM SOLUTIONS - version 1.2

function HeadingCreate($name) {
$field = '<tr><td colspan="2"><h2 class="form_heading">'.$name.'</h2></td></tr>';
return $field;

}


function textCreate($fieldname,$fieldlabel,$required) {
$field = '<tr><td class="formcol1"><label for="'.$fieldname.'">'.$fieldlabel;
if ($required) { $field .='*'; } else { $field .='&nbsp;'; }
$field .= '</label></td><td class="formcol2"><input type="text" class="formtext" name="'.$fieldname.'" id="'.$fieldname.'" value="';
if (isset($_POST[$fieldname]) && $_POST[$fieldname] != '') { $field .= $_POST[$fieldname];}

$field .='"/></td></tr>';
return $field;

}
function textAreaCreate($fieldname,$fieldlabel,$required) {
$field = '<tr><td class="formcol1"><label for="'.$fieldname.'">'.$fieldlabel;
if ($required) { $field .='*'; } else { $field .='&nbsp;'; }
$field .= '</label></td><td class="formcol2"><textarea class="formtextarea" name="'.$fieldname.'" id="'.$fieldname.'">';
if (isset($_POST[$fieldname]) && $_POST[$fieldname] != '') { $field .= $_POST[$fieldname];}

$field .='</textarea></td></tr>';
return $field;

}

function selectBoxCreate($fieldname,$fieldlabel,$fieldoptions,$required) {
$field = '<tr><td class="formcol1"><label for="'.$fieldname.'">'.$fieldlabel;
if ($required) { $field .='*'; } else { $field .='&nbsp;'; }
$field .= '</label></td><td class="formcol2"><select class="formtext" name="'.$fieldname.'" id="'.$fieldname.'">';
foreach($fieldoptions as $k => $v ) {
$field .= '<option value="'.$v.'"';
if (isset($_POST[$fieldname]) && $_POST[$fieldname] == $v) { $field .= ' selected="selected" '; } 
$field .='>'.$v.'</option>';
}
$field .='</select></td></tr>';
return $field;

}


function checkBoxCreate($fieldname,$fieldlabel,$fieldoptions,$required) {
$field = '<tr><td class="formcol1"><label for="'.$fieldname.'">'.$fieldlabel;
if ($required) { $field .='*'; } else { $field .='&nbsp;'; }
$field .= '</label></td><td class="formcol2">';
//if (isset($_POST[$fieldname]) && $_POST[$fieldname] != '') { $field .= $_POST[$fieldname];}
//foreach($GLOBALS[$selectbox1options] as $k => $v ) {
foreach($fieldoptions as $k => $v ) {
$field .= '<input type="checkbox" class="formcheckbox" name="'.$fieldname.'_'.$v.'" id="'.$fieldname.'"/>'.$v.'<br />';
}
$field .='</td></tr>';
return $field;

}

function radioCreate($fieldname,$fieldlabel,$fieldoptions,$required) {
$field = '<tr><td class="formcol1"><label for="'.$fieldname.'">'.$fieldlabel;
if ($required) { $field .='*'; } else { $field .='&nbsp;'; }
$field .= '</label></td><td class="formcol2">';
//if (isset($_POST[$fieldname]) && $_POST[$fieldname] != '') { $field .= $_POST[$fieldname];}
foreach($fieldoptions as $k => $v ) {
$field .= '<input type="radio" class="formradio" name="'.$fieldname.'" id="'.$fieldname.'" value="'.$v.'"/>'.$v.'<br />';
}
$field .='</td></tr>';
return $field;

}


function countrySelectBox($fieldname,$fieldlabel,$required) {
	$fieldoptions = array();
	
	$hostname="localhost";
	$username="networks_nmgZips";
	$password="yWLs6tw2k1Ca";
	$dbname="networks_nmgZips";
	$dbh=mysql_connect ($hostname,$username, $password) or die ('I cannot connect to the database because: ' . mysql_error());
	mysql_select_db ("$dbname");
	$fieldoptions[] = "Please Select";
	$fieldoptions[] = "United States of America";

	$queryY = "SELECT country, iso, zip FROM zip_countries ORDER BY country";
//echo $queryY;			
	$resultY = mysql_query ($queryY); // Run the query.
	while ($rowY = mysql_fetch_array($resultY, MYSQL_ASSOC)) {
	$fieldoptions[] = $rowY['country'];
	}

$field = '<tr><td class="formcol1"><label for="'.$fieldname.'">'.$fieldlabel;
if ($required) { $field .='*'; } else { $field .='&nbsp;'; }
$field .= '</label></td><td class="formcol2"><select class="formtext" name="'.$fieldname.'" id="'.$fieldname.'" onchange="checkCountry();">';
foreach($fieldoptions as $v ) {
if ($v == 'Please Select') { $field .= '<option value=""'; } else { $field .= '<option value="'.$v.'"'; }
if (isset($_POST[$fieldname])) {
		if ($_POST[$fieldname] == $v) { $field .= ' selected="selected" '; }
	 } else {
	 	//if ($v == 'United States of America') {$field .= ' selected="selected" ';}
	 }
$field .='>'.$v.'</option>';
}
$field .='</select></td></tr><tr><td>Zip / Postal Code</td><td><input type="text" class="formtext" name="zip" id="zip" style="display:none;" onblur="updateCityState();" value="';
if (isset($_POST['zip']) && $_POST['zip'] != '') { $field .= $_POST['zip'];}

$field .='"><span id="zip_info">Not Applicable</span></td></tr>';
$field .='<tr><td>City*</td><td><input type="text" class="formtext" name="city" id="city"  value="';
if (isset($_POST['city']) && $_POST['city'] != '') { $field .= $_POST['city'];}

$field .='"></td></tr>';
$field .='<tr><td>State or Province*</td><td><input type="text" class="formtext" name="state" id="state" value="';
if (isset($_POST['state']) && $_POST['state'] != '') { $field .= $_POST['state'];}

$field .='"></td></tr>';
return $field;

}

function captcha() {
$captchablock = array('a.jpg','b.jpg','c.jpg','d.jpg','e.jpg','f.jpg','g.jpg','h.jpg','i.jpg','j.jpg','k.jpg','l.jpg','m.jpg','n.jpg','o.jpg' );
$rand_keys = array_rand($captchablock, 1);
$captchaimage = $captchablock[$rand_keys];
$field = '<tr><td>&nbsp;</td><td><img src="/captcha/'.$captchaimage.'" style="border:2px solid black;"/></td></tr><tr><td class="formcol1"><label for="captcha">Please enter the letters in the box above</td><td class="formcol2"><input type="text" name="captcha" class="formtext" id="captcha"/></label><input type="hidden" name="token" value="'.sha1($captchaimage).'"/></td></tr>';

$field .='<tr><td>&nbsp;</td><td><input type="submit" name="submit" value="Submit"/></td></tr>';
return $field;

}

function submitForm() {
$field ='<tr><td>&nbsp;</td><td><input type="submit" name="submit" value="Submit"/></td></tr>';
return $field;

}

function fileUpload($fieldname,$fieldlabel,$required,$maxfilesize) {
$megs = $maxfilesize / 1000000;
$field = '<tr><td class="formcol1"><label for="'.$fieldname.'">'.$fieldlabel;
if ($required) { $field .='*'; } else { $field .='&nbsp;'; }
$field .= '</label></td><td class="formcol2"><input type="file" class="formfile" name="'.$fieldname.'" id="'.$fieldname.'" /><input type="hidden" name="MAX_FILE_SIZE" value="'.$maxfilesize.'" /><input type="hidden" name="attachment" value="TRUE" /><br />Maximum File Size: '.$megs.' mb</td></tr>';

return $field;

}


?>