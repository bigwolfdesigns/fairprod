<?PHP
//---------------------------------------------------------------------------
// NMG COMPLETE WEBSITE SOLUTIONS - v 2.01
// Networks Marketing Group - Written by Scott Andersen
// Copyright 2010
//---------------------------------------------------------------------------

// ##################################
// PRIMARY EMAIL ADDRESS
// ##################################
$to = 'cs@fairchildproducts.com';  // verified with client
//$to = 'scott@networksmg.com';

// ##################################
// ADDITIONAL EMAIL ADDRESSES
// ##################################

$toarray = array('');
//$toarray = array('sandersen9@gmail.com', 'sandersen@driveconvertmeasure.com');


// ##################################
// SOLUTION INFORMATION
// ##################################
$CRM = TRUE; // Does client have CRM solution?
$EMAILCUST = TRUE; // Return Email for Customer?


// ##################################
// COMPANY & PATH INFORMATION
// ##################################
$COMPANYNAME = 'Fairchild Products';
$emailroot = 'fairchildproducts.com';
$REPLYTO = 'cs@fairchildproducts.com';  // This sets the from address in emails
$SCRIPTDIR = '';
//'/kunden/homepages/15/d189944076/htdocs/appmet/php_uploads/';
$SITE = $emailroot; // Change if needed
$COMPANYURL = 'http://www.fairchildproducts.com/';  // MAKE SURE TO USE TRAILING SLASH FOR FILE UPLOAD SITES


// ##################################
// FILE UPLOAD PATH (if using file attach functions)
// ##################################
$file_path = "/php_uploads/uploads/";


// ##################################
// NON-STANDARD FORM FIELDS 
// Add each one based on form name established on form
// Default fields are name, zip, email and phone - list any others here
// ##################################
$OTHERFIELDS['Contact Us'] = array('company', 'message', 'country','product','quantity','product_use','state');
$OTHERFIELDS['Request a Sample'] = array('company', 'message', 'country', 'address1','address2','state');
$OTHERFIELDS['Request A Quote'] = array('company', 'message', 'address1', 'address2','country', 'state', 'fax','product','quantity','product_use');
$OTHERFIELDS['Self Serve Request A Quote'] = array('company', 'message', 'address1', 'address2','country', 'state', 'fax','product','quantity','price','product_use');
// ##################################
// REQUIRED FORM FIELDS 
// Add each one to be required on all forms
// ##################################
$NMG_REQUIRED[] = array('name','You Must Enter Your Name');
$NMG_REQUIRED[] = array('company','You Must Enter Your Company Name');
$NMG_REQUIRED[] = array('country','You Must Enter Your Country');

$NMG_REQUIRED[] = array('phone','You Must Enter Your Phone Number');
$NMG_REQUIRED[] = array('city','You Must Enter Your City');
//$NMG_REQUIRED[] = array('state','You Must Enter Your State');
$NMG_REQUIRED[] = array('email','You Must Enter Your Email Address');
$NMG_REQUIRED[] = array('product_use','Please Select a Product Use');

// CONTACT US FORM
$SUBJECT['Contact Us'] = 'Thank you for contacting '.$COMPANYNAME;
$CUSTBODY['Contact Us'] = 'Thank you for contacting '.$COMPANYNAME.'.  This is an acknowledgement that we 
have received your e-mail and will respond to your inquiry as soon as possible. 
<p>------------------------------------------------------------------------<br />Note: 
			This is an auto-reply message to the form that you have just submitted.  A customer service representative will be contacting you shortly.<p><a href="'.$COMPANYURL.'">'.$COMPANYURL.'</a></p>';
			
//RFQ Form
$SUBJECT['Request a Quote'] = 'Thank you for requesting a quote from '.$COMPANYNAME;
$CUSTBODY['Request a Quote'] = 'Thank you for requesting a quote from '.$COMPANYNAME.'.  This is an acknowledgement that we 
have received your e-mail and will respond to your inquiry as soon as possible. 
<p>------------------------------------------------------------------------<br />Note: 
			This is an auto-reply message to the form that you have just submitted.  A customer service representative will be contacting you shortly.<p><a href="'.$COMPANYURL.'">'.$COMPANYURL.'</a></p>';

?>