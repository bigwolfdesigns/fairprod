<?php

// client setting grabs db connection and other settings
$client			 = "fairprod";
// various functions and settings
include_once('connect.php'); // site-wide db
include_once('process-functions-adv.php');
include_once('process-settings.php');
// include our needed master control settings (using ordinal.php)
$WEBSITEURL		 = "http://networksmarketinggroup.com";
$PANELNAME		 = "Fairchild";
$COMPANYNAME	 = "Fairchild Industrial Products";
$CLIENTLOGO		 = "images/clients/fairchild.png";
$CLIENTEMAIL	 = "info@fairchildproducts.com";
$CLIENTSITE		 = "http://fairchildproducts.com";
$INCLIENTPANEL	 = "http://fairchildproducts.com/control/test/";
$EXCLIENTPANEL	 = "http://dist-fairchildproducts.com/";
$LOCALCODE		 = "fairprod";
$QUOTEDIR		 = "/quotes/";
require_once dirname(__FILE__).'/phpmailer/fair_prod_mailer.php';
$mailer			 = new fair_prod_mailer();
////////////////////
if(empty($_POST['captcha'])){
	$errors[] = 'You must answer the Human Verifier to continue.';
}else{
	$captchaentered	 = strtolower($_POST['captcha']);
	$tokenrecieved	 = $_POST['token'];
}
$its_me = (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 38)?true:false;
// main conditional
if(empty($errors)){ // $errors and success $msg picked up by parent script(s)
	// shows that at least something has been entered in the captcha box
	//if ($captcharray[$tokenrecieved] === $captchaentered) 
	$image	 = escape_data($_POST['submit']);
	// see if the captcha entered is correct
	$cresult = @mysql_query("SELECT id FROM antispam WHERE image='".$image."' AND checksum=SHA('".$captchaentered."')");
	if($crow	 = mysql_fetch_array($cresult, MYSQL_ASSOC)){
		if(empty($_POST['name'])) $errors[]			 = 'Sorry, please enter your name!';
		if(empty($_POST['company'])) $errors[]			 = 'Sorry, please enter a company!';
		if(empty($_POST['email'])) $errors[]			 = 'Sorry, please enter a valid email address!';
		if(empty($_POST['phone'])) $errors[]			 = 'Sorry, please enter a phone number!';
		if(empty($_POST['city'])) $errors[]			 = 'Sorry, please enter a city!';
		if(empty($_POST['state'])) $errors[]			 = 'Sorry, please enter a state!';
		if(empty($_POST['country'])) $errors[]			 = 'Sorry, please enter a country!';
		if(empty($_POST['zip'])) $errors[]			 = 'Sorry, please enter a zip/postal code!';
		if(empty($_POST['product_use'])) $errors[]			 = 'Sorry, please select a product use!';
		$data				 = array();
		$data['form_name']	 = addslashes(htmlspecialchars($_POST['form']));
		$data['ip_address']	 = $_SERVER['REMOTE_ADDR'];
		$data['site']		 = $SITE;
		if(isset($_POST['price'])){
			$_POST['price']	 = base64_decode($_POST['price']);
			$data['price']	 = base64_decode($_POST['price']);
		}
		// loop through fields
		foreach($NMG_REQUIRED as $A){
			$field	 = $A[0];
			$warning = $A[1];
			if(!empty($_POST[$field])){
				$data[$field] = addslashes(htmlspecialchars($_POST[$field]));
			}
			/* else 
			  {
			  $errors[] = $warning;
			  $errorfields[] = $field;
			  } */
		}
		$data['zipcode'] = addslashes(htmlspecialchars($_POST['zip']));
		// insert the other fields into the data array
		foreach($OTHERFIELDS[$data['form_name']] as $k => $v){
			if(isset($_POST[$v]) && (!empty($_POST[$v]))){
				$data[$v]	 = addslashes(htmlspecialchars($_POST[$v]));
				$columnname	 = addslashes(htmlspecialchars($_POST[$v]));
			}
		}
		// lead status
		if($data['form_name'] == "Self Serve Request A Quote"){
			$lstat = "Quoted";
		}else{
			$lstat = "Open";
		}

		if($data['country'] == 'Canada' && strlen($data['state']) != 2){
			$errors[] = 'Please use the two letter abreviation for your state';
		}
		if(!empty($_SESSION['visits'])){
			$data['number_pages_viewed'] = escape_data($_SESSION['visits']);
		}
		if(!empty($_SESSION['orig_referer'])){
			$data['original_referer'] = escape_data($_SESSION['orig_referer']);
		}
		if(!empty($_SESSION['pages'])){
			$data['list_of_pages_viewed'] = escape_data($_SESSION['pages']);
		}
		///////////////////////////////////////////////////
		// start the convoluted lead insertion process
		$country = $data['country'];
		$state	 = $data['state'];
//		$query = mysql_query("select users.user_id as assigned_to, users.email as email, users_external.id as dist_id from lead join users on users.user_id = lead.l_id join users_external on users_external.email=users.email where (lead.country='$country' AND lead.state='$state') OR (lead.country='$country' AND lead.state='*') OR users.user_id=33 group by assigned_to");
		$query	 = mysql_query("SELECT 
									`users`.`user_id` AS `assigned_to`,
									`users`.`email` AS `email`,
									`users_external`.`id` AS `dist_id`
								FROM `lead`
								LEFT JOIN `users` ON `users`.`user_id` = `lead`.`l_id`
								LEFT JOIN `users_external` ON `users_external`.`email` = `users`.`email`
								WHERE
									(
										`lead`.`country` = '$country' AND 
										`lead`.`state` = '$state'
									)
									OR 
									(
										`lead`.`country` = '$country' AND 
										`lead`.`state` = '*'
									)
									#OR
									#`users`.`user_id` = 33
									GROUP BY `assigned_to`,`email`
								");
		$dists	 = array();
		while($res	 = mysql_fetch_assoc($query)){
			$dists[] = $res;
		}
		if($data['country'] == 'United States of America'){
			if($data['product_use'] == 'MRO'){
				$distributoremails = usDistEmails($data['zipcode']);
			}
		}elseif($data['country'] == 'Canada'){
			if($data['product_use'] == 'MRO'){
				$distributoremails = caDistEmails($data['state']);
			}
		}
		$data['assigned_to'] = $dists[0]['assigned_to'];
		$repeml				 = $dists[0]['email'];
		///////////////////////////////////////////////////
		// at last, insert this contact into the main table
		if(empty($errors)){
				$inserti = mysql_query("INSERT INTO contact (name,phone,email,address1,address2,city,state,zipcode,message,form_submitted,ip,referer,page_path,pages,created,company,country,fax,product_use,product_type,product,quantity,product_value,assigned_to,status,price) VALUES ('".$data['name']."','".$data['phone']."','".$data['email']."','".$data['address1']."','".$data['address2']."','".$data['city']."','".$data['state']."','".$data['zipcode']."','".$data['message']."','".$data['form_name']."','".$data['ip_address']."','".$data['original_referer']."','".$data['list_of_pages_viewed']."','".$data['number_pages_viewed']."',NOW(),'".$data['company']."','".$data['country']."','".$data['fax']."','".$data['product_use']."','".$data['product_type']."','".$data['product']."','".$data['quantity']."','".$data['product_value']."','".$data['assigned_to']."','".$lstat."','".$data['price']."')");
			if($inserti){
				// id used in full quote below, crm_id for external contact reference
				$customerid		 = mysql_insert_id();
				$data['crm_id']	 = $customerid;
				// send a message to the rep
				// version of the export email with no attachment for now and added subject/message
				if(!$its_me){
					$subject	 = $PANELNAME.': '.$data['form_name']." Form Submission";
					$text_body	 = "(please do not reply directly to this email)\n";
					$text_body .= "Hello,\n";
					$text_body .= $data['name']." has submitted a request via our website.\n";
					$text_body .= $data['form_name']." Form Submission\n";
					foreach($data as $k => $v){
						if(!in_array($k, array('captcha', 'submit', 'token', 'stamp'))){
							if(!is_array($v)){
								$text_body .= "\t".ucwords(dunderscore($k))." = ".stripslashes($v)."\n";
							}
						}
					}
					$html_body = "<html><body>";
					$html_body .="<p>(please do not reply directly to this email)</p>";
					$html_body .="<p>Hello,</p><br />";
					$html_body .="<p>".$data['name']." has submitted a request via our website.</p><br />";
					$html_body .="<p>".$data['form_name']." Form Submission</p>";
					$html_body .="<ul>";
					foreach($data as $k => $v){
						if(!in_array($k, array('captcha', 'submit', 'token', 'stamp'))){
							if(!is_array($v)){
								$html_body .="<li>".ucwords(dunderscore($k))." = ".stripslashes($v)."</li>";
							}
						}
					}
					$html_body .="</ul>";
					$html_body .="<p><em>- $COMPANYNAME</em><br /><a href='$CLIENTSITE'>$CLIENTSITE</a></p>";
					$html_body .="</body></html>";
					//send the email
					$mailer->AddAddress($repeml);
					$mailer->Subject = $subject;
					$mailer->Body	 = $html_body;
					$mailer->AltBody = $text_body;
					$mailer->Send();
					$mailer->clear_all();
				}
				///////////////////////////////////
				// section for distributed leads
				// if a specific dist was captured, email and save to dist db
				if(!empty($dists)){
					foreach($dists as $dist){
						$distid		 = $dist['dist_id'];
						// for each distributor, insert lead into secondary db for them
						$inserte	 = mysql_query("INSERT INTO contact_external (dist_id,crm_id,v_email,v_name,v_company,v_message,v_phone,address1,address2,v_city,v_state,v_zip,v_product,v_quantity,v_use,v_submitted,v_status) VALUES ('".$distid."', '".$data['crm_id']."', '".$data['email']."', '".$data['name']."', '".$data['company']."', '".$data['message']."','".$data['phone']."','".$data['address1']."','".$data['address2']."','".$data['city']."','".$data['state']."','".$data['zip']."','".$data['product']."','".$data['quantity']."','".$data['product_use']."',NOW(),'".$lstat."')");
						$dcustomerid = mysql_insert_id();
						if($inserte){
							// version of the export email with no attachment for now and added subject/message
							$subject	 = $PANELNAME.': '.$data['form_name']." Form Submission";
							$text_body	 = "(please do not reply directly to this email)\n";
							$text_body .= "Hello,\n";
							$text_body .=$data['name']." has submitted a request via our website.\n";
							$text_body .=$data['form_name']." Form Submission\n";
							foreach($data as $k => $v){
								if(!in_array($k, array('captcha', 'submit', 'token', 'stamp'))){
									if(!is_array($v)){
										$text_body .="\t".ucwords(dunderscore($k))." = ".stripslashes($v)."\n";
									}
								}
							}
							$text_body .= "--- $COMPANYNAME $CLIENTSITE";
							$html_body = "<html><body>";
							$html_body .= "<p>(please do not reply directly to this email)</p>";
							$html_body .= "<p>Hello,</p><br />";
							$html_body .= "<p>".$data['name']." has submitted a request via our website.</p><br />";
							$html_body .= "<p>".$data['form_name']." Form Submission</p>";
							$html_body .= "<ul>";
							foreach($data as $k => $v){
								if(!in_array($k, array('captcha', 'submit', 'token', 'stamp'))){
									if(!is_array($v)){
										$html_body .= "<li>".ucwords(dunderscore($k))." = ".stripslashes($v)."</li>";
									}
								}
							}
							$html_body .= "</ul>";
							$html_body .= "<p><em>- $COMPANYNAME</em><br /><a href='$CLIENTSITE'>$CLIENTSITE</a></p>";
							$html_body .= "</body></html>";
							//send the email			
							$mailer->AddAddress($dist['email']);
							$mailer->Subject = $subject;
							$mailer->Body	 = $html_body;
							$mailer->AltBody = $text_body;
							$mailer->Send();
							$mailer->clear_all();
						}else{
							$errors[] = 'There was an error with our distributors database!';
						}
					}
				}
				if(!empty($_POST['dist'])){
					$telldistsent	 = "";
					$dist			 = stripslashes($_POST['dist']);
					$getdist		 = mysql_query("SELECT email FROM distributors WHERE id=".$dist."");
					if($drow			 = mysql_fetch_array($getdist, MYSQL_ASSOC)){
						// if we have such a distributor, insert lead into secondary db for them
						$inserte	 = mysql_query("INSERT INTO contact_external (dist_id,crm_id,v_email,v_name,v_company,v_message,v_phone,address1,address2,v_city,v_state,v_zip,v_product,v_quantity,v_use,v_submitted,v_status) VALUES ('".$dist."', '".$data['crm_id']."', '".$data['email']."', '".$data['name']."', '".$data['company']."', '".$data['message']."','".$data['phone']."','".$data['address1']."','".$data['address2']."','".$data['city']."','".$data['state']."','".$data['zip']."','".$data['product']."','".$data['quantity']."','".$data['product_use']."',NOW(),'".$lstat."')");
						$dcustomerid = mysql_insert_id();
						// sends message to relevant distributor in this loop
						if($inserte){
							// version of the export email with no attachment for now and added subject/message
							$subject	 = $PANELNAME.': '.$data['form_name']." Form Submission";
							$text_body	 = "(please do not reply directly to this email)\n";
							$text_body .= "Hello,\n";
							$text_body .= $data['name']." has submitted a request via our website.  To respond, please visit the control panel:\n";
							$text_body .= "$EXCLIENTPANEL?page=customers&view=$dcustomerid\n\n";
							$text_body .= "- $COMPANYNAME\n";
							$text_body .= "  $CLIENTSITE";

							$html_body		 = "<html><body>";
							$html_body .= "<img src='$WEBSITEURL/master-control/$CLIENTLOGO' />";
							$html_body .= "<p>(please do not reply directly to this email)</p>";
							$html_body .= "<p>Hello,<br />";
							$html_body .= $data['name']." has submitted a request via our website.  To respond, please visit the control panel:<br />";
							$html_body .= "<a href='$EXCLIENTPANEL?page=customers&view=$dcustomerid'>$EXCLIENTPANEL?page=customers&view=$dcustomerid</a></p>";
							$html_body .= "<p><em>- $COMPANYNAME</em><br />";
							$html_body .= "<a href='<?php echo $CLIENTSITE'>$CLIENTSITE</a></p>";
							$html_body .= "</body></html>";
							//send the email
							$mailer->AddAddress($drow['email']);
							$mailer->Subject = $subject;
							$mailer->Body	 = $html_body;
							$mailer->AltBody = $text_body;
							$mailer->Send();
							$mailer->clear_all();
						}else{
							$errors[] = 'There was an error with our distributor database!';
						}
					}
				}
				if(is_array($distributoremails) && count($distributoremails) > 0){
					// catch all other distributors and put lead into secondary db for them to fight over
					foreach($distributoremails as $distEmail){
						$distid = getDistID($distEmail);
//						if($its_me){
//							echo"<pre>";
//							var_dump($distid);
//							die();
//						}
						$getdist = mysql_query("SELECT email FROM distributors WHERE id='".$distid."'");
						if($drow	 = mysql_fetch_array($getdist, MYSQL_ASSOC)){
							// for each distributor, insert lead into secondary db for them
							$inserte	 = mysql_query("INSERT INTO contact_external (dist_id,crm_id,v_email,v_name,v_company,v_message,v_phone,address1,address2,v_city,v_state,v_zip,v_product,v_quantity,v_use,v_submitted,v_status) VALUES ('".$distid."', '".$data['crm_id']."', '".$data['email']."', '".$data['name']."', '".$data['company']."', '".$data['message']."','".$data['phone']."','".$data['address1']."','".$data['address2']."','".$data['city']."','".$data['state']."','".$data['zip']."','".$data['product']."','".$data['quantity']."','".$data['product_use']."',NOW(),'".$lstat."')");
							$dcustomerid = mysql_insert_id();
							if($inserte){
								// version of the export email with no attachment for now and added subject/message
								$subject	 = $PANELNAME.': '.$data['form_name']." Form Submission";
								ob_start();
								$text_body	 = "(please do not reply directly to this email)\n";
								$text_body .= "Hello,\n";
								$text_body .=$data['name']." has submitted a request via our website.\n";
								$text_body .=$data['form_name']." Form Submission\n";
								foreach($data as $k => $v){
									if(!in_array($k, array('captcha', 'submit', 'token', 'stamp'))){
										if(!is_array($v)){
											$text_body .="\t".ucwords(dunderscore($k))." = ".stripslashes($v)."\n";
										}
									}
								}
								$text_body .= "--- $COMPANYNAME $CLIENTSITE";
								$html_body = "<html><body>";
								$html_body .= "<p>(please do not reply directly to this email)</p>";
								$html_body .= "<p>Hello,</p><br />";
								$html_body .= "<p>".$data['name']." has submitted a request via our website.</p><br />";
								$html_body .= "<p>".$data['form_name']." Form Submission</p>";
								$html_body .= "<ul>";
								foreach($data as $k => $v){
									if(!in_array($k, array('captcha', 'submit', 'token', 'stamp'))){
										if(!is_array($v)){
											$html_body .= "<li>".ucwords(dunderscore($k))." = ".stripslashes($v)."</li>";
										}
									}
								}
								$html_body .= "</ul>";
								$html_body .= "<p><em>- $COMPANYNAME</em><br /><a href='$CLIENTSITE'>$CLIENTSITE</a></p>";
								$html_body .= "</body></html>";
								//send the email
								$mailer->AddAddress($drow['email']);
								$mailer->Subject = $subject;
								$mailer->Body	 = $html_body;
								$mailer->AltBody = $text_body;
								$mailer->Send();
								$mailer->clear_all();
							}else{
								$errors[] = 'There was an error with our distributors database!';
							}
						}
					}
				}
				///////////////////////////////////
				// section for fully quoted leads and all customer emails
				// send the customer back their message like a reply
				$custmsg	 = "";
				$fcustmsg	 = "";
				if($data['message'] != ""){
					$custmsg	 = "\n\n--- ".$data['name']." wrote ---\n".$data['message']."\n\n";
					$fcustmsg	 = "<p>--- ".$data['name']." wrote ---</p><p>".$data['message']."</p>";
				}

				$moreinfo	 = 'Our standard delivery is 1-2 weeks (ARO) and we also have a QUICKSHIP program if you need a faster delivery.  For further assistance, please contact our Regional Manager for your area ('.$repeml.'), who has been notified about your request.';
				$fmoreinfo	 = '<p>'.$moreinfo.'</p>';

				$distinfo	 = "";
				$fdistinfo	 = "";

				// a specific part number was captured and quoted
				if($data['form_name'] == "Self Serve Request A Quote"){
					// create a pdf quote and send separate mail to customer
					include_once('quote-generation.php');
					// for future storfront development - does this work?
					if(isset($_POST['dist']) && $_POST['dist'] == '409'){
						$quoteTemplate = 'quote-template-409.jpg';
					}else{
						$quoteTemplate = false;
					}

					// using pdf solution
					CreateQuotePDF($customerid, $data['company'], $data['address1'], $data['city'], $data['state'], $data['zipcode'], $data['name'], $data['phone'], $data['email'], $data['quantity'], $data['product'], $data['price'], $quoteTemplate);

					if($data['product_use'] == "MRO"){
						$distinfo	 = 'Our authorized distributor(s) in your area are:';
						$fdistinfo	 = '<p>Our authorized distributor(s) in your area are:</p><ul>';
						foreach($distributoremails as $distEmail){
							$distContact = getDistContact($distEmail);
							$distinfo .= "\n- ".$distContact." (".$distEmail.")";
							$fdistinfo .= "<li>".$distContact." (".$distEmail.")</li>";
						}
						$distinfo .= "\n\n";
						$fdistinfo .= "</ul>";
					}
					$filename	 = "quote_".$customerid.".pdf";
					// version of the export email with attachment added back
					$subject	 = 'Your Quote Request for '.$data['product'];
					$attachment	 = "/home/".$LOCALCODE."/public_html".$QUOTEDIR.$filename;
					$text_body	 = "Hello,\n";
					$text_body .= "Thank you for your quote!  The attached PDF should reflect your request.\n";
					$text_body .= "$moreinfo $distinfo\n";
					$text_body .= "- $COMPANYNAME\n";
					$text_body .= "$CLIENTSITE\n";
					$text_body .= "$custmsg";

					$html_body		 = "</html></body>";
					$html_body .= "<img src='$WEBSITEURL/master-control/$CLIENTLOGO'/>";
					$html_body .= "<p>Hello,<br />";
					$html_body .= "<p>Thank you for your quote!  The attached PDF should reflect your request.</p>";
					$html_body .= "$fmoreinfo $fdistinfo";
					$html_body .= "<p><em>- $COMPANYNAME</em><br />";
					$html_body .= "<a href='$CLIENTSITE'>$CLIENTSITE</a></p>";
					$html_body .= "$fcustmsg";
					//send the email
					$mailer->AddCC($repeml);
					$mailer->AddAttachment($attachment, $filename);
					$mailer->AddAddress($data['email']);
					$mailer->Subject = $subject;
					$mailer->Body	 = $html_body;
					$mailer->AltBody = $text_body;
					$mailer->Send();
					$mailer->clear_all();

					$msg = '<h2>Thank You</h2><p>Your request has been submitted!</p>';
				}else{
					// otherwise, this is not a self-service RFQ, so send to customer more generically
					// version of the export email with no attachment for now and added subject/message
					$subject = 'Thank you for contacting '.$COMPANYNAME.'';
					$autoset = "1";
					if($autoset == "1"){
						$custbody		 = 'Thank You for contacting '.'Fairchild Products'.'.  This is an auto-response, but our representatives will respond to your inquiry shortly.';
						$fcustbody		 = '<p>'.$custbody.'</p>';
						$text_body		 = $data['name'].",\n";
						$text_body .= "$custbody\n";
						$text_body .= "$moreinfo $distinfo\n";
						$text_body .= "- $COMPANYNAME\n";
						$text_body .= "$CLIENTSITE\n";
						$text_body .= "$custmsg\n";
						$html_body		 = "<html><body>";
						$html_body .= "<p>".$data['name'].",</p>";
						$html_body .= "$fcustbody";
						$html_body .= "$fmoreinfo $fdistinfo";
						$html_body .= "<p><em>- $COMPANYNAME</em><br />";
						$html_body .= "<a href='$CLIENTSITE'>$CLIENTSITE</a></p>";
						$html_body .= "$fcustmsg";
						$html_body .= "</body></html>";
						//copy current buffer contents into $message variable and delete current output buffer
						$message		 = ob_get_clean();
						//send the email
						$mailer->AddAddress($data['email']);
						$mailer->Subject = $subject;
						$mailer->Body	 = $html_body;
						$mailer->AltBody = $text_body;
						$mailer->Send();
						$mailer->clear_all();
						$msg			 = '<h2>Thank You</h2><p>Your request has been submitted!</p>';
					}
				}
			}
		}
		// main db insert conditional
		else{
			if(empty($errors)) $errors[] = 'There was an error with our database!';
		}
	}
	// captcha conditional
	else{
		// captcha failed
		if(empty($errors)) $errors[] = 'Human Verification failed.  Are you Human?';
	}
}
// errors conditional
else{ // errors[] occurred 
	//echo 'You forgot to fill in some required fields, Please try again';
}