<?PHP

include('process-functions.php');

if (empty($_POST['captcha'])) {
		$errors[] = 'You must answer the Human Verifier to continue.';
	} else {
		$captchaentered = strtolower($_POST['captcha']);
		$tokenrecieved = $_POST['token'];
	}
if (empty($errors)) {
	// shows that at least something has been entered in the captch box
	if ($captcharray[$tokenrecieved] === $captchaentered) {
			// The Captcha was entered correctly - Continue Validating the Form
	
include('process-settings.php');
	
$data = array();
$data['form_name'] = addslashes(htmlspecialchars($_POST['form']));
$data['ip_address'] = $_SERVER['REMOTE_ADDR'];
$data['site'] = $SITE;		

// Check to see if there should be a file uploaded with the form.
if (isset($_POST['attachment']) && ($_POST['attachment'] == TRUE))
	{
	
	$blacklist = array(".php", ".phtml", ".php3", ".php4", '.htaccess');
	foreach ($blacklist as $item) {
		if(preg_match("/$item\$/i", $_FILES['uploadedfile']['name'])) {
		$errors[] = "We do not allow uploading of that type of file!";
		}
	}
	if (empty($errors)) {
	

		$target_path = $file_path . basename( $_FILES['uploadedfile']['name']); 

		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
			$filelink = 'To download file customer uploaded, 
			<a href="'.$COMPANYURL.'uploads/'. basename( $_FILES['uploadedfile']['name']).'">Click Here</a>';
			chmod($target_path, 0644); 
			$fileuploaded = TRUE;
			} else{
			$errors[] = "There was an error uploading the file, please try again!";
			}
	
	}
	

	} else {$filelink = '';$target_path='';}
	// BEGIN FORM VALIDATION  -------------------------------------------------------------------------------
	// Check required Fields	
	foreach ($NMG_REQUIRED as $A) {
	$field = $A[0];
	$warning = $A[1];
		if (!empty($_POST[$field])) {
		$data[$field] = addslashes(htmlspecialchars($_POST[$field]));
		} else {
		$errors[] = $warning;
		$errorfields[] = $field;
		}
	}	
	
	$data['zipcode'] = addslashes(htmlspecialchars($_POST['zip']));

	// insert the other fields into the data array
	$inquery = '';
	$valquery = '';
	foreach ($OTHERFIELDS[$data['form_name']] as $k => $v) {
		
		
		if (isset($_POST[$v]) && (!empty($_POST[$v]))) {
			$data[$v] = addslashes(htmlspecialchars($_POST[$v]));
			$columnname = addslashes(htmlspecialchars($_POST[$v]));
			if ($inquery == '') { $inquery = '`'.$v.'`, ';} else { $inquery .= '`'.$v.'`, '; }
			if ($valquery == '') { $valquery = '\''.$columnname.'\', ';} else { $valquery .= '\''.$columnname.'\', ';; }
		} 
		
	}


	
	if (empty($errors)) { // If everything's OK.
		//if the customer has the CRM solution add that data
		if ($CRM) {
			// Process information into CRM db
			include_once($SCRIPTDIR.'connect.php');

			if (!empty($_SESSION['visits'])) {
				$data['number_pages_viewed'] = escape_data($_SESSION['visits']);
			} 
			if (!empty($_SESSION['orig_referer'])) {
				$data['original_referer'] = escape_data($_SESSION['orig_referer']);
			} 
			if (!empty($_SESSION['pages'])) {
				$data['list_of_pages_viewed'] = escape_data($_SESSION['pages']);
			} 
			include_once('regiondata.php');
			if ($data['country'] == 'United States of America') { $data['SRegion'] = $regionUS[$data['state']];}
			elseif ($data['country'] == 'Canada') { $data['SRegion'] = $regionCA[$data['state']];}
			elseif ($data['country'] == 'Mexico') { $data['SRegion'] = 'Fernando';}
			else {$data['SRegion']='';}
			
			
			
			// Check for previous entry...
			foreach ($data as $K =>$V) {
				if ($V!='' && $K!='number_pages_viewed' && $K!='original_referer' && $K!='created' && $K!='list_of_pages_viewed' && $K!='ip_address' && $K!='id' && $K!='captcha' && $K!='token' && $K!='stamp' && $K!='form_name' && $K!='site' ) {
					if (isset($dupecheck)) { $dupecheck .= ' AND `'.$K.'`=\''.escape_data($V).'\''; } else { $dupecheck = '`'.$K.'`=\''.escape_data($V).'\''; }
				}
			}
			
			$query = "SELECT * FROM `contact` WHERE $dupecheck";
			$result = mysql_query ($query);
			if (mysql_num_rows($result)>=1) { $nodupedetected = false; } else { $nodupedetected = true; }
			if (!$result) {echo $query;}
			
			
			
			
			
			
			$query = "INSERT INTO `contact` 
			(`name`, `phone`,  `city`,`zipcode`, `email`,`SRegion`,
			 $inquery 
			 `referer`, `page_path`, `pages`, `created`, `form_submitted`, `ip`) 
			VALUES (
			'{$data['name']}',
			'{$data['phone']}',
			'{$data['city']}',
			
			'{$data['zipcode']}',
			'{$data['email']}',
			'{$data['SRegion']}',
			$valquery

			'{$data['original_referer']}',
			'{$data['list_of_pages_viewed']}',
			'{$data['number_pages_viewed']}',
			NOW(),
			'{$data['form_name']}',
			'{$data['ip_address']}')";
		
			//for testing purposes, view the query by uncommenting below
			//echo $query;
			if ($nodupedetected) {
			//echo 'insert';
			$result = mysql_query ($query); // Run the query.
					if (mysql_affected_rows() != 1) { // A record was added.
					$msg = '<p><font color="red">Error Proccessing Request for Quote</font></p>'; // Public message.
					echo mysql_error() . '<br /><br />Query: ' . $query; // Debugging message.
					}
			
			
				//build link for email
				$subid = mysql_insert_id();	
				$url = 'http://' . $_SERVER['HTTP_HOST'] . '/control/';	
				$data['crm_id'] = $subid;
		
			}
		
		
			$subject = $data['form_name']." Form Submission";
			$body = "<html><body><p>".$data['form_name']." Form Submission";
			
			$body .= "<ul>";
				foreach ($data as $k=>$v) {
						$body .= "\t<li>".ucwords(dunderscore($k))." = ". stripslashes($v) ."</li>\n";
				}
			$body .= "</ul>";
			if ($CRM) {
				$body .= "<p>To View Online, <a href=".$url.">Click Here</a>\n\n";
				}
			if (isset($fileuploaded)) {
				$body .= '<p>'.$filelink.'</p>';
				}
			$body .="</body></html>";
			
			
			$headers =  "From: $REPLYTO\n";
			$headers .= "Bcc: Brian.Carpenter@rotork.com\n".
			$headers .= "MIME-Version: 1.0\n".
						"Content-type: text/html; charset=iso-8859-1";
			
			
			// section for distributor emails
			if (isset($_POST['dist']) && $_POST['dist'] !='') {
			//Connect To Database
$hostname="localhost";
$username="fairprod_produsr";
$password="qv3Vpv7mKpK1";
$dbname="fairprod_prods";
// connect to the database
$dbh=mysql_connect ($hostname,$username, $password) or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("$dbname");
				$dist = stripslashes($_POST['dist']);
				$query2 = "SELECT * FROM distributors WHERE id=$dist";		
				$result2 = mysql_query ($query2); // Run the query.
					while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					add_to_dist_tool($row2,$data);
					$mailsuccess2 = mail($row2['email'], 'DIST- '.$subject, $body, $headers);
					$telldistsent = 'Form submission also sent to '.$row2['company_name'].' at '.$row2['email'];
					}	
			}
			$body .= $telldistsent;
			if($data['form_name'] == 'INT TEST') { $to='scott@networksmg.com'; }
			
			if ($data['form_name'] == 'Self Serve Request A Quote') {
			
				include_once('quote-generation.php');
				CreateQuotePDF($subid, $data['company'], $data['address1'], $data['city'], $data['state'], $data['zipcode'], $data['name'], $data['email'], $data['quantity'], $data['product'], $data['price']);
			require_once('phpmailer/class.phpmailer.php');
	$quoteemailbody ='<p>Please find attached the quote that you generated on the Fairchild Products website for '.$data['quantity'].' of our '.$data['product'].'.  This quote is valid for the next 30 days.';
			$mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
	
				try {
  //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
  //$mail->SMTPAuth   = true;                  // enable SMTP authentication
  $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
  $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
  $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
  $mail->Username   = "cs@fairchildproducts.com";  // GMAIL username
  $mail->Password   = "fair777";            // GMAIL password
  $mail->AddReplyTo('cs@fairchildproducts.com', 'Fairchild Products');
  $mail->AddAddress($data['email'], $data['name']);
  $mail->AddAddress('cs@fairchildproducts.com', 'Fairchild Products');
  //$mail->AddAddress('scott@networksmg.com', 'Fairchild Products');
  $mail->SetFrom('cs@fairchildproducts.com', 'Fairchild Products');
  $mail->Subject = 'Your Quote Request for '.$data['product'];
  $mail->MsgHTML($quoteemailbody);
  $mail->AddAttachment($_SERVER['DOCUMENT_ROOT'].'/quotes/quote_'.$subid.'.pdf');
  $mail->Send();
  //echo "Message Sent OK to $email<br />";
 	//setConfirmationSent($lesson_id);
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
			$mailsuccess=true;	
			
			} else {
				$mailsuccess = mail($to, $subject, $body, $headers);
			}
			
			
			
			if ($mailsuccess) { // An email was sent.
						$msg = '<h2>Thank You</h2><p>Your request has been submitted!</p>';
						include('process-lead-extranet.php');
						if ($EMAILCUST) {
						$hostname="localhost";
						$username="fairprod_produsr";
						$password="qv3Vpv7mKpK1";
						$dbname="fairprod_prods";
						// connect to the database
						$dbh=mysql_connect ($hostname,$username, $password) or die ('I cannot connect to the database because: ' . mysql_error());
						mysql_select_db ("$dbname");
						$custsubject = 'Thank you for contacting Fairchild Industrial Products Company';
						$query2 = "SELECT setting FROM control_settings WHERE setting_name='autoresponse'";				
						$result2 = mysql_query ($query2); // Run the query.
						while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
						$custbody = $row2['setting']; 
						}
						$headers =  "From: $REPLYTO\n";
						$headers .= "Bcc: Brian.Carpenter@rotork.com\n".
						mail($data['email'], $custsubject, $custbody, $headers);
						}
						
						if (count($toarray) > 0) {
						// if there are multiple Addresses to send the submission to send those emails
							foreach($toarray as $toemail) {
							mail($toemail, $subject, $body, $headers);
							}
						
						}
						
					} else { // Insert failed.
						$msg = '<p><font color="red">Error Proccessing '.$data['form_name'].' Form </font></p>'; // Public message.
					}
		} else {
			$msg = '<h2>Thank You</h2><p>Your request has been submitted!*</p>';	
		}
			
	} else { // Error occurred. 
		$error = '<p><font color="red">You forgot to fill in some required fields, Please try again</font></p>';
	}

} else { 
	// The Human Verifier Failed, Display error message
	$errors[] = 'The Human Verification failed.  Are you Human?'; // Public message.
	}
	
} // end if empty errors for captcha
?>
