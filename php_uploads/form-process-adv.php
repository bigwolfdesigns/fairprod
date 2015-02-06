<?php

	// client setting grabs db connection and other settings
	$client = "fairprod";

	// various functions and settings
	include_once('connect.php'); // site-wide db
	include_once('process-functions-adv.php');
	include_once('process-settings.php');

	// include our needed master control settings (using ordinal.php)
	$WEBSITEURL = "http://networksmarketinggroup.com";
	$PANELNAME = "Fairchild";
	$COMPANYNAME = "Fairchild Industrial Products";
	$CLIENTLOGO = "images/clients/fairchild.png";
	$CLIENTEMAIL = "info@fairchildproducts.com";
	$CLIENTSITE = "http://fairchildproducts.com";
	$INCLIENTPANEL = "http://fairchildproducts.com/control/test/";
	$EXCLIENTPANEL = "http://dist-fairchildproducts.com/";
	$LOCALCODE = "fairprod";
	$QUOTEDIR = "/quotes/";

////////////////////


if (empty($_POST['captcha'])) 
	{
	$errors[] = 'You must answer the Human Verifier to continue.';
	}
else 
	{
	$captchaentered = strtolower($_POST['captcha']);
	$tokenrecieved = $_POST['token'];
	}

// main conditional
if (empty($errors)) // $errors and success $msg picked up by parent script(s)
	{
	// shows that at least something has been entered in the captcha box
	if ($captcharray[$tokenrecieved] === $captchaentered) 
		{
		$data = array();
		$data['form_name'] = addslashes(htmlspecialchars($_POST['form']));
		$data['ip_address'] = $_SERVER['REMOTE_ADDR'];
		$data['site'] = $SITE;		
		if (isset($_POST['price'])) 
			{
			$_POST['price'] = base64_decode($_POST['price']);
			$data['price'] = base64_decode($_POST['price']);
			}

		// catch a few errors
		foreach ($NMG_REQUIRED as $A)
			{
			$field = $A[0];
			$warning = $A[1];
			if (!empty($_POST[$field])) {$data[$field] = addslashes(htmlspecialchars($_POST[$field]));} 
			else 
				{
				$errors[] = $warning;
				$errorfields[] = $field;
				}
			}	
	
		$data['zipcode'] = addslashes(htmlspecialchars($_POST['zip']));

		// insert the other fields into the data array
		foreach ($OTHERFIELDS[$data['form_name']] as $k => $v) 
			{
			if (isset($_POST[$v]) && (!empty($_POST[$v]))) 
				{
				$data[$v] = addslashes(htmlspecialchars($_POST[$v]));
				$columnname = addslashes(htmlspecialchars($_POST[$v]));
				} 
			}
		
		// lead status
		if ($data['form_name'] == "Self Serve Request A Quote") $lstat = "Quoted";
		else $lstat = "Open";
		
		if ($data['country'] == 'Canada' && strlen($data['state']) != 2) {$errors[] = 'Please use the two letter abreviation for your state';}

		if (!empty($_SESSION['visits'])) {$data['number_pages_viewed'] = escape_data($_SESSION['visits']);}
		if (!empty($_SESSION['orig_referer'])) {$data['original_referer'] = escape_data($_SESSION['orig_referer']);}
		if (!empty($_SESSION['pages'])) {$data['list_of_pages_viewed'] = escape_data($_SESSION['pages']);}


		///////////////////////////////////////////////////
		// start the convoluted lead insertion process
		
		// us logic
		if ($data['country'] == 'United States of America') 
			{ 
			// duplicated in /distributors.php for us search
			switch($data['state'])
				{
				case "ME":
				case "NH":
				case "VT":
				case "MA":
				case "CT":
				case "RI":
				case "NY":
				case "NJ":
				case "DE":
				case "PA":
				case "MD":
				case "DC":
					//$repeml = 'phenderson@fairchildproducts.com';
					$repeml = 'mike.weaver@rotork.com';
					//$repid = '9175';
					$repid = '100182';
					break;
					
				case "VA":
				case "TN":
				case "NC":
				case "SC":
				case "GA":
				case "FL":
				case "AL":
				case "MS":
				case "WV":
				case "KY":
				case "OH":
					$repeml = 'dewey.kiger@rotork.com';
					$repid = '100202';
					break;

				case "AR":
				case "LA":
				case "TX":
				case "OK":
					//$repeml = 'mdormire@fairchildproducts.com';
					$repeml = 'mike.dormire@rotork.com';
					//$repid = '90001';
					$repid = '100203';
					break;

				case "MT":
				case "WY":
				case "CO":
				case "NM":
				case "AZ":
				case "UT":
				case "ID":
				case "WA":
				case "OR":
				case "NV":
				case "CA":
				case "AK":
					//$repeml = 'mdormire@fairchildproducts.com';
					//$repeml = 'paul.gant@rotork.com';
					//$repid = '100167';
					$repeml = 'doug.clark@rotork.com';
					$repid = '100217';					
					break;
					
				case "MN":
				case "IA":
				case "MO":
				case "IL":
				case "IN":
				case "WI":
				case "MI":
				case "KS":
				case "NE":
				case "ND":
				case "SD":
					//$repeml = 'fperez@fairchildproducts.com';
					$repeml = 'fernando.perez@rotork.com';
					//$repid = '90004';
					$repid = '100206';
					break;
				}
			
			// manufacturer replacement orders go to rep and multiple distributors
			if ($data['product_use'] == 'MRO') $distributoremails = usDistEmails($data['zipcode']);

			// sales rep for later use
			$data['assigned_to'] = $repid;
			} 


		// canada logic
		elseif ($data['country'] == 'Canada') 
			{
			switch($data['state'])
				{
				case "QC":
				case "ON":
				case "NB":
				case "PE":
				case "NS":
				case "NL":
					//$repeml = 'phenderson@fairchildproducts.com';
					$repeml = 'mike.weaver@rotork.com';
					//$repid = '90000';
					$repid = '100182';
					break;
					
				case "MB":
				case "SK":
				case "AB":
				case "BC":
				case "YK":
				case "NT":
				case "NU":
					//$repeml = 'mdormire@fairchildproducts.com';
					$repeml = 'mike.dormire@rotork.com';
					//$repid = '90001';
					$repid = '100203';
					break;
				}
					
			// manufacturer replacement orders go to fc rep and multiple distributors
			if ($data['product_use'] == 'MRO') $distributoremails = caDistEmails($data['state']);

			// sales rep for later use
			$data['assigned_to'] = $repid;
			}


		// the rest of the known world
		else 
			{
			switch ($data['country'])
				{
				case 'Mexico':
					//$repeml = 'fperez@fairchildproducts.com';
					$repeml = 'fernando.perez@rotork.com';
					//$repid = '90004';
					$repid = '100206';
					break;

				case 'China':
				case 'Hong Kong';
				case 'Korea';
				case 'Taiwan';
					//$repeml = 'lzhang@fairchildproducts.com';
					$repeml = 'leo.zhang@rotork.com';
					//$repid = '90005';
					$repid = '100208';
					break;

				case 'Venezuala':
				case 'Columbia':
				case 'Trinidad & Tobago':
				case 'Brazil':
				case 'Peru':
				case 'Argentina':
				case 'Chile':
					//$repeml = 'aparra@fairchildproducts.com';
					$repeml = 'andre.parra@rotork.com';
					//$repid = '550';
					$repid = '100209';
					break;

				case 'India':
				case 'Egypt':
				case 'Jordan':
				case 'Saudi Arabia':
				case 'United Arab Emirates':
				case 'Israel':
				case 'Syria':
				case 'Kuwait':
				case 'Pakistan':
				case 'Singapore':
				case 'Indonesia':
				case 'Thailand':
				case 'Malaysia':
				case 'Philippines':
					//$repeml = 'ssethi@fairchildproducts.com';
					$repeml = 'sandeep.sethi@rotork.com';
					//$repid = '90002';
					$repid = '100210';
					break;
		
				default:// europe, australia, all others
					
					//$repeml = 'apaine@fairchildproducts.com';
					//$repid = '552';
					$repeml = 'brian.carpenter@rotork.com';
					//$repid = '100180';
					$repid = '554';
				}

			// sales rep for later use
			$data['assigned_to'] = $repid;
			}
	
		///////////////////////////////////////////////////
		// at last, insert this contact into the main table
		$inserti = mysql_query("INSERT INTO contact (name,phone,email,address1,address2,city,state,zipcode,message,form_submitted,ip,referer,page_path,pages,created,company,country,fax,product_use,product_type,product,quantity,product_value,assigned_to,status,price) VALUES ('" . $data['name'] . "','" . $data['phone'] . "','" . $data['email'] . "','" . $data['address1'] . "','" . $data['address2'] . "','" . $data['city'] . "','" . $data['state'] . "','" . $data['zipcode'] . "','" . $data['message'] . "','" . $data['form_name'] . "','" . $data['ip_address'] . "','" . $data['original_referer'] . "','" . $data['list_of_pages_viewed'] . "','" . $data['number_pages_viewed'] . "',NOW(),'" . $data['company'] . "','" . $data['country'] . "','" . $data['fax'] . "','" . $data['product_use'] . "','" . $data['product_type'] . "','" . $data['product'] . "','" . $data['quantity'] . "','" . $data['product_value'] . "','" . $data['assigned_to'] . "','" . $lstat . "','" . $data['price'] . "')");
		if ($inserti)
			{
			// id used in full quote below, crm_id for external contact reference
			$customerid = mysql_insert_id();
			$data['crm_id'] = $customerid;

			// send a message to the rep

			// version of the export email with no attachment for now and added subject/message
			$random_hash = md5(date('r', time()));
			$subject = $PANELNAME . ': ' . $data['form_name']." Form Submission";
			$headers = "From: " . $CLIENTEMAIL . "\r\nReply-To: " . $CLIENTEMAIL . "";
			$headers .= "\r\nBcc: Brian.Carpenter@rotork.com";
			$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";

			ob_start();
			?>
--PHP-mixed-<?php echo $random_hash; ?> 
Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $random_hash; ?>"


--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/plain; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

(please do not reply directly to this email)

Hello,
<?php echo $data['name']; ?> has submitted a request via our website.  To respond, please visit the control panel:
<?php echo $INCLIENTPANEL; ?>

- <?php echo $COMPANYNAME; ?>
<?php echo $CLIENTSITE; ?>

--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/html; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

<img src="<?echo $WEBSITEURL . '/master-control/' . $CLIENTLOGO; ?>" />
<p>(please do not reply directly to this email)</p>
<p>Hello,<br />
<?php echo $data['name']; ?> has submitted a request via our website.  To respond, please visit the control panel:<br />
<a href="<?php echo $INCLIENTPANEL; ?>"><?php echo $INCLIENTPANEL; ?></a></p>
<p><em>- <?php echo $COMPANYNAME; ?></em><br />
<a href="<?php echo $CLIENTSITE; ?>"><?php echo $CLIENTSITE; ?></a></p>

--PHP-alt-<?php echo $random_hash; ?>--

			<?php

			//copy current buffer contents into $message variable and delete current output buffer
			$message = ob_get_clean();
			//send the email
			$mail_sent = @mail($repeml, $subject, $message, $headers);


	
			
			///////////////////////////////////
			// section for distributed leads
			
			// if a specific dist was captured, email and save to dist db
			if (isset($_POST['dist']) && $_POST['dist'] != "")
				{
				$telldistsent = "";
				$dist = stripslashes($_POST['dist']);
				$getdist = mysql_query ("SELECT email FROM distributors WHERE id=" . $dist . "");
				if ($drow = mysql_fetch_array($getdist, MYSQL_ASSOC))
					{
					// if we have such a distributor, insert lead into secondary db for them
					$inserte = mysql_query("INSERT INTO contact_external (dist_id,crm_id,v_email,v_name,v_company,v_message,v_phone,address1,address2,v_city,v_state,v_zip,v_product,v_quantity,v_use,v_submitted,v_status) VALUES ('" . $dist . "', '" . $data['crm_id'] . "', '" . $data['email'] . "', '" . $data['name'] . "', '" . $data['company'] . "', '" . $data['message'] . "','" . $data['phone'] . "','" . $data['address1'] . "','" . $data['address2'] . "','" . $data['city'] . "','" . $data['state'] . "','" . $data['zip'] . "','" . $data['product'] . "','" . $data['quantity'] . "','" . $data['product_use'] . "',NOW(),'" . $lstat . "')");

					// sends message to relevant distributor in this loop
					if ($inserte)
						{
						
						// version of the export email with no attachment for now and added subject/message
						$random_hash = md5(date('r', time()));
						$subject = $PANELNAME . ': ' . $data['form_name']." Form Submission";
						$headers = "From: " . $CLIENTEMAIL . "\r\nReply-To: " . $CLIENTEMAIL . "";
						$headers .= "\r\nBcc: Brian.Carpenter@rotork.com";
						$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";

						ob_start();
						?>
--PHP-mixed-<?php echo $random_hash; ?> 
Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $random_hash; ?>"


--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/plain; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

(please do not reply directly to this email)

Hello,
<?php echo $data['name']; ?> has submitted a request via our website.  To respond, please visit the control panel:
<?php echo $EXCLIENTPANEL; ?>

- <?php echo $COMPANYNAME; ?>
<?php echo $CLIENTSITE; ?>

--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/html; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

<img src="<?echo $WEBSITEURL . '/master-control/' . $CLIENTLOGO; ?>" />
<p>(please do not reply directly to this email)</p>
<p>Hello,<br />
<?php echo $data['name']; ?> has submitted a request via our website.  To respond, please visit the control panel:<br />
<a href="<?php echo $EXCLIENTPANEL; ?>"><?php echo $EXCLIENTPANEL; ?></a></p>
<p><em>- <?php echo $COMPANYNAME; ?></em><br />
<a href="<?php echo $CLIENTSITE; ?>"><?php echo $CLIENTSITE; ?></a></p>

--PHP-alt-<?php echo $random_hash; ?>--

						<?php

						//copy current buffer contents into $message variable and delete current output buffer
						$message = ob_get_clean();
						//send the email
						$mail_sent = @mail($drow['email'], $subject, $message, $headers);
						}
					else
						{
						$errors[] = 'There was an error with our distributor database!';
						}
					}
				}
				
			// catch all other distributor and put lead into secondary db for them to fight over
			else
				{
				foreach ($distributoremails as $distEmail) 
					{
					$distid = getDistID($distEmail);
					$getdist = mysql_query ("SELECT email FROM distributors WHERE id='" . $distid . "'");
					while ($drow = mysql_fetch_array($getdist, MYSQL_ASSOC))
						{
						// for each distributor, insert lead into secondary db for them
						$inserte = mysql_query("INSERT INTO contact_external (dist_id,crm_id,v_email,v_name,v_company,v_message,v_phone,address1,address2,v_city,v_state,v_zip,v_product,v_quantity,v_use,v_submitted,v_status) VALUES ('" . $distid . "', '" . $data['crm_id'] . "', '" . $data['email'] . "', '" . $data['name'] . "', '" . $data['company'] . "', '" . $data['message'] . "','" . $data['phone'] . "','" . $data['address1'] . "','" . $data['address2'] . "','" . $data['city'] . "','" . $data['state'] . "','" . $data['zip'] . "','" . $data['product'] . "','" . $data['quantity'] . "','" . $data['product_use'] . "',NOW(),'" . $lstat . "')");

						if ($inserte)
							{

							// version of the export email with no attachment for now and added subject/message
							$random_hash = md5(date('r', time()));
							$subject = $PANELNAME . ': ' . $data['form_name']." Form Submission";
							$headers = "From: " . $CLIENTEMAIL . "\r\nReply-To: " . $CLIENTEMAIL . "";
							$headers .= "\r\nBcc: Brian.Carpenter@rotork.com";
							$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";

							ob_start();
							?>
--PHP-mixed-<?php echo $random_hash; ?> 
Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $random_hash; ?>"


--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/plain; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

(please do not reply directly to this email)

Hello,
<?php echo $data['name']; ?> has submitted a request via our website.  To respond, please visit the control panel:
<?php echo $EXCLIENTPANEL; ?>

- <?php echo $COMPANYNAME; ?>
<?php echo $CLIENTSITE; ?>

--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/html; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

<img src="<?echo $WEBSITEURL . '/master-control/' . $CLIENTLOGO; ?>" />
<p>(please do not reply directly to this email)</p>
<p>Hello,<br />
<?php echo $data['name']; ?> has submitted a request via our website.  To respond, please visit the control panel:<br />
<a href="<?php echo $EXCLIENTPANEL; ?>"><?php echo $EXCLIENTPANEL; ?></a></p>
<p><em>- <?php echo $COMPANYNAME; ?></em><br />
<a href="<?php echo $CLIENTSITE; ?>"><?php echo $CLIENTSITE; ?></a></p>

--PHP-alt-<?php echo $random_hash; ?>--

							<?php

							//copy current buffer contents into $message variable and delete current output buffer
							$message = ob_get_clean();
							//send the email
							$mail_sent = @mail($drow['email'], $subject, $message, $headers);
							}
						else
							{
							$errors[] = 'There was an error with our distributors database!';
							}
						}				
					}
				}


			///////////////////////////////////
			// section for fully quoted leads and all customer emails

			// send the customer back their message like a reply
			$custmsg = "";
			$fcustmsg = "";
			if ($data['message'] != "")
				{
				$custmsg =  "\n\n--- " . $data['name'] . " wrote ---\n" . $data['message'] . "\n\n";
				$fcustmsg =  "<p>--- " . $data['name'] . " wrote ---</p><p>" . $data['message'] . "</p>";
				}
			
			$moreinfo = 'Our standard delivery is 1-2 weeks (ARO) and we also have a QUICKSHIP program if you need a faster delivery.  For further assistance, please contact our Regional Manager for your area (' . $repeml . '), who has been notified about your quote.';
			$fmoreinfo = '<p>' . $moreinfo . '</p>';

			$distinfo = "";
			$fdistinfo = "";
			
			// a specific part number was captured and quoted
			if ($data['form_name'] == "Self Serve Request A Quote") 
				{
				// create a pdf quote and send separate mail to customer
				include_once('quote-generation.php');
				// for future storfront development - does this work?
				if (isset($_POST['dist']) && $_POST['dist'] == '409') {$quoteTemplate = 'quote-template-409.jpg';} 
				else { $quoteTemplate = false; }

				// using pdf solution
				CreateQuotePDF($customerid, $data['company'], $data['address1'], 
				$data['city'], $data['state'], $data['zipcode'], $data['name'], 
				$data['phone'], $data['email'], $data['quantity'], $data['product'], $data['price'],$quoteTemplate);
			
				if ($data['product_use'] == "MRO")
					{
					$distinfo = 'Our authorized distributor(s) in your area are:';
					$fdistinfo = '<p>Our authorized distributor(s) in your area are:</p><ul>';
					foreach ($distributoremails as $distEmail) 
						{
						$distContact = getDistContact($distEmail);
						$distinfo .= "\n- " . $distContact . " (" . $distEmail . ")";
						$fdistinfo .= "<li>" . $distContact . " (" . $distEmail . ")</li>";
						}
					$distinfo .= "\n\n";
					$fdistinfo .= "</ul>";
					}


				$filename = "quote_" . $customerid . ".pdf";

				// version of the export email with attachment added back
				$random_hash = md5(date('r', time()));
				$subject = 'Your Quote Request for ' . $data['product'];
				$headers = "From: " . $CLIENTEMAIL . "\r\nReply-To: " . $CLIENTEMAIL . "";
				$headers .= "\r\nBcc: Brian.Carpenter@rotork.com";
				$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";

				$attachment = chunk_split(base64_encode(file_get_contents("/home/" . $LOCALCODE . "/public_html" . $QUOTEDIR . $filename)));

				ob_start();
				?>
--PHP-mixed-<?php echo $random_hash; ?> 
Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $random_hash; ?>"


--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/plain; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

Hello,
Thank you for your quote!  The attached PDF should reflect your request.
<?php echo $moreinfo; ?><?php echo $distinfo; ?>
- <?php echo $COMPANYNAME; ?>
<?php echo $CLIENTSITE; ?>
<?php echo $custmsg; ?>

--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/html; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

<img src="<?echo $WEBSITEURL . '/master-control/' . $CLIENTLOGO; ?>" />
<p>Hello,<br />
<p>Thank you for your quote!  The attached PDF should reflect your request.</p>
<?php echo $fmoreinfo; ?><?php echo $fdistinfo; ?>
<p><em>- <?php echo $COMPANYNAME; ?></em><br />
<a href="<?php echo $CLIENTSITE; ?>"><?php echo $CLIENTSITE; ?></a></p>
<?php echo $fcustmsg; ?>

--PHP-alt-<?php echo $random_hash; ?>--

--PHP-mixed-<?php echo $random_hash; ?> 
Content-Type: application/pdf; name="<?php echo $filename; ?>"
Content-Transfer-Encoding: base64 
Content-Disposition: attachment

<?php echo $attachment; ?>
--PHP-mixed-<?php echo $random_hash; ?>--

				<?php

				//copy current buffer contents into $message variable and delete current output buffer
				$message = ob_get_clean();
				//send the email
				$mail_sent = @mail($data['email'], $subject, $message, $headers);

				$msg = '<h2>Thank You</h2><p>Your request has been submitted!</p>';
				} 

			// otherwise, this is not a self-service RFQ, so send to customer more generically
			else
				{
				// version of the export email with no attachment for now and added subject/message
				$random_hash = md5(date('r', time()));
				$subject = 'Thank you for contacting ' . $COMPANYNAME . '';
				$headers = "From: " . $CLIENTEMAIL . "\r\nReply-To: " . $CLIENTEMAIL . "";
				$headers .= "\r\nBcc: Brian.Carpenter@rotork.com";
				$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";

				$autores = mysql_query ("SELECT setting FROM control_settings WHERE setting_name='autoresponse'");
				if ($autorow = mysql_fetch_array($autores, MYSQL_ASSOC))
					{
					$custbody = $autorow['setting'];
					$fcustbody = '<p>' . $custbody . '</p>';
					}
				else
					{
					$custbody = 'Thank You for contacting ' . $COMPANYNAME . '.  This is an auto-response, but our representatives will respond to your inquiry shortly.';
					$fcustbody = '<p>' . $custbody . '</p>';
					}

				ob_start();
				?>
--PHP-mixed-<?php echo $random_hash; ?> 
Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $random_hash; ?>"


--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/plain; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

Hello,
<?php echo $custbody; ?>
<?php echo $moreinfo; ?><?php echo $distinfo; ?>
- <?php echo $COMPANYNAME; ?>
<?php echo $CLIENTSITE; ?>
<?php echo $custmsg; ?>
--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/html; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

<img src="<?echo $WEBSITEURL . '/master-control/' . $CLIENTLOGO; ?>" />
<p>Hello,<br />
<?php echo $fcustbody; ?>
<?php echo $fmoreinfo; ?><?php echo $fdistinfo; ?>
<p><em>- <?php echo $COMPANYNAME; ?></em><br />
<a href="<?php echo $CLIENTSITE; ?>"><?php echo $CLIENTSITE; ?></a></p>
<?php echo $fcustmsg; ?>

--PHP-alt-<?php echo $random_hash; ?>--

				<?php

				//copy current buffer contents into $message variable and delete current output buffer
				$message = ob_get_clean();
				//send the email
				$mail_sent = @mail($data['email'], $subject, $message, $headers);

				$msg = '<h2>Thank You</h2><p>Your request has been submitted!</p>';
				}
			}

		// main db insert conditional
		else
			{
			if (empty($errors)) $errors[] = 'There was an error with our database!';
			}
		}
 
	// captcha conditional
	else 
		{ 
		// captcha failed
		if (empty($errors)) $errors[] = 'Human Verification failed.  Are you Human?';
		}
	}

// errors conditional
else 
	{ // errors[] occurred 
	//echo 'You forgot to fill in some required fields, Please try again';
	}


?>
