<?php

	include('cookie.php');

	if (isset($_POST['token'])) 
		{
           //var_dump($_POST);die;
		include($_SERVER['DOCUMENT_ROOT'].'/php_uploads/form-process-new.php');
		}
	else
		{
		// select captcha
		include($_SERVER['DOCUMENT_ROOT'].'/php_uploads/connect.php');
		$aresult = mysql_query ("SELECT image FROM antispam ORDER BY RAND() LIMIT 1");
		while ($arow = mysql_fetch_array($aresult, MYSQL_ASSOC)) {$imageD = $arow['image'];}
		}

/*$query = mysql_query("SELECT * from users_external where user_level='manager' ORDER BY company");
$a = array();
while($r = mysql_fetch_assoc($query))
    $a[] = $r;
var_dump($a);die;*/

	$NMG_FORM = TRUE;

	// Specify Form Particulars
	$formtitle = 'Contact Us';
	$site = 'dev.fairchildproducts.com';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/main.dwt.php" codeOutsideHTMLIsLocked="false" --><!-- TemplateBegin template="/Templates/main.dwt.php" codeOutsideHTMLIsLocked="false" -->

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Contact Fairchild Industrial Products</title>
<!-- InstanceEndEditable -->
<link href="/fairchild.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable --><!-- InstanceParam name="Leftnav" type="boolean" value="true" --><!-- InstanceParam name="right-details" type="boolean" value="false" --><!-- InstanceParam name="New_Products" type="boolean" value="true" -->
</head>

<body>

<div id="wrapper">
	<div id="header">
	<form action="/search/search-part.php" method="get"><div id="search">
	<input type="text" name="query" value="Search by Part # or Keyword" id="query" onclick="ClearInput('Search by Part # or Keyword', this.id);" style="width:180px;" />
	&nbsp;<input type="submit" value="Go" /></div>  <input type="hidden" name="search" value="1" /></form>
	<?PHP include($_SERVER['DOCUMENT_ROOT'].'/chat-image.php');?>
    </div>
<div id="navcontainer">
<ul id="navlist">
<li id="active"><a href="/" >Home</a></li>
<li><a href="/featured-products.php">New Products</a></li>
<li><a href="/about-us.php">About Us</a></li>
<li><a href="/industries.php">Industries</a></li>
<li><a href="/documents.php">Documents</a></li>
<li><a href="/faq.php">FAQ</a></li>
<li><a href="/request-quote.php">Request Quote</a></li>
<li><a href="/press.php">Press Releases</a></li>
<li><a href="/careers.php">Careers</a></li>
<li><a href="/contact-us.php">Contact Us</a></li>
<li><a href="http://www.dist-fairchildproducts.com/dhq/login.asp">Distributor Login</a></li>
</ul>
</div>

<div id="content">	

<table width="940" border="0" cellspacing="0" cellpadding="0">
  <tr valign="top">
   <td width="200"><div id="leftcontainer">
      <ul id="leftlist">
        <li id="active"><a href="/category.php?cid=1" id="current">Pressure Regulators</a></li>
      <li><a href="/category.php?cid=2">Transducers</a></li>
      <li><a href="/subcat/3/Pneumatic_Volume_Boosters.php">Volume Boosters</a></li>
      <li><a href="/category.php?cid=4">Relays</a></li>
      <li><a href="/category.php?cid=5">Filters and Accessories</a></li>
      </ul>
    </div>
    <div style="margin-top:20px;margin-left:5px;"><a href="/distributors.php"><img src="/images/find-distributor-sm.jpg" alt="Find A Distributor"/></a></div>
    </td>
    <td>
      
      

<div class="newprods">Click Here to See Our <a href="/featured-products.php">New Products</a></div>
      <!-- InstanceBeginEditable name="content" -->

<?php

if(isset($errors))
	{
	echo '<div id="message-box">';
	foreach($errors as $error) { print "\n<p>" . $error . "</p>";}
	echo '</div>';
	}
if(isset($msg))
	{
	echo '<div id="message-box">' . $msg . '</div>';
	}
if (!isset($_POST['token']))
	{
?>

<h2>Your Information to Contact Us</h2>

<div id="siteform">

<form action="" method="post">
<input type="hidden" name="sent" value="yes"/>
<input type="hidden" name="site" value="<?PHP echo $site; ?>"/>
<input type="hidden" name="form" value="<?PHP echo $formtitle; ?>"/>

<table border="0" cellspacing="4" cellpadding="4">
	<tr>
	<td class="required" valign="top">Name</td>
	<td class="input" valign="top"><input name="name" value="" /></td>
	</tr>
 
	<tr>
	<td class="required" valign="top">Company</td>
	<td class="input" valign="top"><input name="company" value="" /></td>
	</tr>
 
	<tr>
	<td class="required" valign="top">Email</td>
	<td class="input" valign="top"><input name="email" value="" /></td>
	</tr>

	<tr>
	<td class="required" valign="top">Phone</td>
	<td class="input" valign="top"><input name="phone" value="" /></td>
	</tr>

	<tr>
	<td class="" valign="top">Fax</td>
	<td class="input" valign="top"><input name="fax" value="" /></td>
	</tr>

	<tr>
	<td class="" valign="top">Street Address</td>
	<td class="input" valign="top">
	<input name="address1" value=""><br />
	<input style="margin-top:5px;" name="address2" value="" />
	</td>
	</tr>

	<tr>
	<td class="required" valign="top">City</td>
	<td class="input" valign="top"><input name="city" value="" /></td>
	</tr>
 
	<tr>
	<td class="required" valign="top">State/Province</td>
	<td class="input" valign="top">
<?php
$states = array(array('NA','Outside US/Canada'), array('AL','Alabama'), array('AK','Alaska'), array('AB','Alberta, Canada'), array('AS','American Samoa'), array('AZ','Arizona'), array('AR','Arkansas'), array('BC','British Columbia, Canada'), array('CA','California'), array('CO','Colorado'), array('CT','Connecticut'), array('DE','Delaware'), array('FM','Federated States of Micronesia'), array('FL','Florida'), array('GA','Georgia'), array('GU','Guam'), array('HI','Hawaii'), array('ID','Idaho'), array('IL','Illinois'), array('IN','Indiana'), array('IA','Iowa'), array('KS','Kansas'), array('KY','Kentucky'), array('LA','Louisiana'), array('ME','Maine'), array('MB','Manitoba, Canada'), array('MH','Marshall Islands'), array('MD','Maryland'), array('MA','Massachusetts'), array('MI','Michigan'), array('MN','Minnesota'), array('MS','Mississippi'), array('MO','Missouri'), array('MT','Montana'), array('NE','Nebraska'), array('NV','Nevada'), array('NB','New Brunswick, Canada'), array('NH','New Hampshire'), array('NJ','New Jersey'), array('NM','New Mexico'), array('NY','New York'), array('NF','Newfoundland, Canada'), array('NC','North Carolina'), array('ND','North Dakota'), array('MP','Northern Mariana Islands'), array('NT','Northwest Territories, Canada'), array('NS','Nova Scotia, Canada'), array('OH','Ohio'), array('OK','Oklahoma'), array('ON','Ontario, Canada'), array('OR','Oregon'), array('PW','Palau'), array('PA','Pennsylvania'), array('PE','Prince Edward Island, Canada'), array('PR','Puerto Rico'), array('QC','Quebec, Canada'), array('RI','Rhode Island'), array('SK','Saskatchewan, Canada'), array('SC','South Carolina'), array('SD','South Dakota'), array('TN','Tennessee'), array('TX','Texas'), array('UT','Utah'), array('VT','Vermont'), array('VI','Virgin Islands'), array('VA','Virginia'), array('WA','Washington'), array('DC','Washington, DC'), array('WV','West Virginia'), array('WI','Wisconsin'), array('WY','Wyoming'), array('YT','Yukon, Canada'));
?>
   	<select size="1" name="state">
	<option value="" selected="selected">please select</option>
<?php foreach($states as $state):?>
    <option value="<?php echo $state[0]; ?>"><?php echo $state[1]; ?></option>
<?php endforeach; ?>
	</td>
	</tr>

	<tr>
	<td class="required" valign="top">Country</td>
	<td class="input" valign="top">
<?php

$countries = array();
$query = mysql_query("select * from lead group by country");
while($row = mysql_fetch_assoc($query))
    $countries[] = $row['country'];




?>


   	<select size="1" name="country">
    <option value="" selected="selected">please select</option>
<?php foreach($countries as $country):?>
    <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
<?php endforeach; ?>
   	</select>
    </td>
   	</tr>
  
	<tr>
	<td class="required" valign="top">Zip/Postal Code</td>
	<td class="input" valign="top"><input name="zip" value="" /></td>
	</tr>
</table>



<h2>Product Information</h2>

<table border="0" cellspacing="4" cellpadding="4">

	<tr>
	<td class="" valign="top">Which Product?</td>
	<td class="input" valign="top"><input type="text" name="product" id="product" value="" /></td>
	</tr>

	<tr>
	<td class="" valign="top">How Many?</td>
	<td class="input" valign="top"><input type="text" name="quantity" id="quantity" value="" /></td>
	</tr>

	<tr>
	<td class="required" valign="top">Product Use?</td>
	<td class="input" valign="top">
	<select name="product_use">
	<option value="">Please Select</option>
	<option value="MRO">Replace Similar Unit In Our Facility</option>
	<option value="CAP PROJ">New Company Improvement Project</option>
	<option value="OEM">Component Used In The Products We Sell</option>
	</select></td>
	</tr>
 
	<tr>
	<td colspan="2" valign="top"><br />
	<em>detail your request...</em><br />

	<textarea name="message" rows="10" cols="60"></textarea>
	</td>

	<tr>
	<td class="input" valign="top">
	<span class="required">Check Code >><br />
	<input type="text" name="captcha" size="20" maxlength="10" /><input type="hidden" name="submit" value="<?php echo $imageD; ?>" /></td>
	<td valign="top">
	<img src="/captcha/<?php echo $imageD; ?>" alt="captcha image" style="border:2px solid #333333;" />
	</td>
	</tr>

	<tr>
	<td colspan="2" style="padding-top:20px;border-top:2px dotted #ebebeb;">
	<input type="hidden" name="token" value="token" />
	<input type="hidden" name="stamp" value="<?PHP echo md5(uniqid(rand(), true));?>" />
	<button type="submit"><img src="/images/send-button.png" /></button>
	</td>
	</tr>

</table>

</form>

</div>

<?php 

	}
	
?>



	<table width="100%" border="0" cellspacing="5" cellpadding="3">

	<tr>



	<td valign="top" width="220">
	<h1 class="style2" style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;">Fairchild Management</h1>



	<div><strong style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;">Alan G.  Paine </strong> <a href="mailto:alan.paine@rotork.com"><img src="/images/mailto.jpg" /></a></div>



        <div>Managing Director</div>



	<div>&nbsp;</div>




        <div><strong style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;">David C.  Velten </strong><a href="mailto:david.velten@rotork.com"><img src="/images/mailto.jpg" /></a></div>



        <div>Director, Finance</div>


	<div>&nbsp;</div>





        <div><strong style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;">Claudio Borges</strong> <a href="mailto:claudio.borges@rotork.com"><img src="/images/mailto.jpg" /></a></div>



        <div>Director of Sales</div>



        <div>&nbsp;</div>







	<h1 class="style2" style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;">Application Engineers</h1>



   <p style="margin-top: 0;"><strong>David Baker</strong> <a href="mailto:david.baker@rotork.com"><img src="/images/mailto.jpg" /></a><br />







      Phone: (336) 659-3446  </p>



    <p style="margin-bottom: 0;"><strong>Bryan Buono</strong> <a href="mailto:bryan.buono@rotork.com"><img src="/images/mailto.jpg" /></a></p>



    <p style="margin-top: 0;">Phone: (336) 659-3454</p>



    <p style="margin-bottom: 0;">&nbsp;</p>

    </blockquote>

 <p><strong style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;">Customer Service:</strong> <a href="mailto:us-ws-cs@rotork.com"><img src="/images/mailto.jpg" /></a> (336) 659-3400</p>







<?PHP // Set feature so that functionality automatically is enabled on Sept 7th 2010.







if( strtotime("2010-09-07") < time() && NMG_ALLOW_CHAT ){







    // Your date is in the past







	?>







    <div align="center"><!-- http://www.LiveZilla.net Chat Button Link Code --><a href="javascript:void(window.open('http://www.fairchildproducts.com/chat/chat.php?intgroup=U2VydmljZQ==&amp;hg=P01hbmFnZW1lbnQ_&amp;reset=true','','width=590,height=600,left=0,top=0,resizable=yes,menubar=no,location=no,status=yes,scrollbars=yes'))"><img src="http://www.fairchildproducts.com/chat/image.php?id=02&amp;hg=P01hbmFnZW1lbnQ_" width="191" height="69" border="0" alt="LiveZilla Live Help" /></a>







       <noscript><div><a href="http://www.fairchildproducts.com/chat/chat.php?intgroup=U2VydmljZQ==&amp;hg=P01hbmFnZW1lbnQ_&amp;reset=true" target="_blank">Start Live Help Chat</a></div></noscript><!-- http://www.LiveZilla.net Chat Button Link Code --><!-- http://www.LiveZilla.net Tracking Code --><div id="livezilla_tracking" style="display:none"></div><script type="text/javascript">var script = document.createElement("script");script.type="text/javascript";var src = "http://www.fairchildproducts.com/chat/server.php?request=track&output=jcrpt&reset=true&nse="+Math.random();setTimeout("script.src=src;document.getElementById('livezilla_tracking').appendChild(script)",1);</script><!-- http://www.LiveZilla.net Tracking Code --></div>







    <?PHP







} 







?>







        <p><strong style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;">Human Resources: </strong><a href="mailto:beth.petree@rotork.com"><img src="/images/mailto.jpg" /></a> (336) 659-3400</p>







        <p style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;">General Information</p>







        <ul>







          <li><a href="http://www.fairchildproducts.com/pdf/supp_terms.pdf" target="_blank">Suppliers Terms and Conditions</a></li>







          <li><a href="http://www.fairchildproducts.com/pdf/cust_terms.pdf" target="_blank">Customers Terms and Conditions</a></li>







        </ul></td>





	<td valign="top">



	<div id="contact_right">



        <h1 class="style2">North America</h1>



        <blockquote style="margin-bottom: 0;">
	<p style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;"><strong>Mike Weaver</strong> <a href="mailto:mike.weaver@rotork.com"><img src="/images/mailto.jpg" /></a>
	<strong> Regional Sales Manager</strong><br />

	<strong class="style4">US:</strong></p>
<!-- ME, NH, VT, MA, CT, RI, NY, NJ, DE, PA, MD, D.C. -->
	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0; margin-bottom: 0;">ME, NH, VT, MA, CT, RI, NY, NJ, DE, PA, MD, D.C.</p>

	<p class="style4" style="border-bottom:1px solid #e8e8e8; margin-top: 0; margin-bottom: 0;"><strong>Canada:</strong></p>
<!-- QC, ON, NB, PE, NS, NL -->
	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0; margin-bottom: 0;">QC, ON, NB, PE, NS, NL</p>
	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">

	Cell: (336) 391-4347 </p>

	</blockquote>



	<blockquote style="margin-bottom: 0;">

	<p style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;"><strong>Fernando Perez</strong> <a href="mailto:fernando.perez@rotork.com"><img src="/images/mailto.jpg" /></a> 
	<strong>Regional Sales Manager</strong><br />

	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0; margin-bottom: 0;"><strong>US:</strong></p>
<!-- -->
	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0; margin-bottom: 0;">MN, IA, MO, IL, IN, WI, MI, KS, NE, ND, SD</p>

	<p align="left" class="style6" style="margin-top: 0; margin-bottom: 0;">Mexico:</p>

	<p align="left" style="margin-top: 0; margin-bottom: 0;">All States</p>

	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">

	Cell: 630-853-8068 </p>
	</blockquote>

		<blockquote style="margin-bottom: 0;">

	<p style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;"><strong>Jeff Englehardt</strong> <a href="mailto:jeff.englehardt@rotork.com"><img src="/images/mailto.jpg" /></a> 
	<strongRegional Sales Manager</strong><br />

	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0; margin-bottom: 0;"><strong>US:</strong></p>
<!-- OH, KY, WV, TN -->
	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">OH, KY, TN, AL, MS, WV</p>

	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">

	Cell: (931) 217-9376</p>

	</blockquote>


	<blockquote style="margin-bottom: 0;">

	<p style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;"><strong>Dewey Kiger</strong> <a href="mailto:dewey.kiger@rotork.com"><img src="/images/mailto.jpg" /></a> 
	<strong>National Sales Manager - East</strong><br />

	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0; margin-bottom: 0;"><strong>US:</strong></p>
<!-- VA, NC, SC, GA, FL, AL, MS -->
	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">VA, NC, SC, GA, FL</p>

	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">

	Cell: (336) 602-0451</p>

	</blockquote>
    
    <blockquote style="margin-bottom: 0;">

	<p style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;"><strong>Keith Phillips</strong> <a href="mailto:keith.phillips@rotork.com"><img src="/images/mailto.jpg" /></a> 
	<strong>Regional Sales Manager</strong><br />

	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0; margin-bottom: 0;"><strong>US:</strong></p>
<!-- AR, LA, TX, OK -->
	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">CO, NM, TX, OK, LA</p>

	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">

	Cell: (281) 728-7443</p>

	</blockquote>


	<blockquote style="margin-bottom: 0;">

	<p style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;"><strong>Michael Dormire</strong> <a href="mailto:mike.dormire@rotork.com"><img src="/images/mailto.jpg" /></a> 
	<strong>National Sales Manager - West</strong><br />

	<strong class="style4">US:</strong></p>
<!-- AR, LA, TX, OK, MT, WY, CO, NM, AZ, UT, ID, WA, OR, NV, CA, AK, HI -->
	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0; margin-bottom: 0;">CA, OR, WA, MT, WY, UT, ID, AZ, NV, AK</p>

	<p class="style4" style="border-bottom:1px solid #e8e8e8; margin-top: 0; margin-bottom: 0;">Canada:</p>

	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">MB, SK, AB, BC, YK, NT, NU</p>
	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">
    <p class="style4" style="border-bottom:1px solid #e8e8e8; margin-top: 0; margin-bottom: 0;">International:</p>

	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">MX</p>
	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">

	Cell: (214) 549-9742 <br />

	Fax:  (336) 768-6002 </p>

	</blockquote>



	<h1 class="style2">Eurasia</h1>

	<blockquote style="margin-top: 0;">
	<p style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;"><strong>Leo Zhang</strong> <a href="mailto:leo.zhang@rotork.com"><img src="/images/mailto.jpg" /></a> 
	<strong>North Asia General Manager</strong><br />
	<strong class="style4">North Asia:</strong></p>
	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0; margin-bottom: 0;">China (PRC), Hong Kong, Korea, Taiwan</p>
	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">
	Cell: 86-138-0802-7010</p>
	</blockquote>


	<blockquote style="margin-top: 0;">
	<p style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;"><strong>Claudio Borges</strong> <a href="mailto:claudio.borges@rotork.com"><img src="/images/mailto.jpg" /></a> 
	<strong>Director of Sales</strong><br />
	<strong class="style4">Europe:</strong></p>
	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0; margin-bottom: 0;">Austria, Belgium, Channel Islands , Denmark, Finland, France, Germany, Iceland, Ireland, Italy, Luxembourg, Malta, Netherlands, Norway, Portugal, Spain, Sweden, Switzerland, United Kingdom, Albania, Belarus, Bulgaria, Croatia , Czech Republic, Greece , Hungary, Latvia , Lithuania , Montenegro , Poland, Republic of Moldova, Romania, Serbia , Slovakia, Slovenia , Ukraine, Russian Federation, Australia, Japan</p>
	</blockquote>

	<h1 class="style2">Middle East &amp; Southeast Asia</h1>



	<blockquote>

	<p style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;"><strong>Sandeep Sethi</strong> <a href="mailto:sandeep.sethi@rotork.com"><img src="/images/mailto.jpg" /> </a>
	<strong>MESA General Manager</strong><br />

	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0; margin-bottom: 0;">India, Egypt, Jordan, Saudi Arabia, UAE, Israel, Syria, Kuwait, Pakistan, Singapore, Indonesia, Thailand, Malaysia, Phillipines</p>

	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">

	Office: 00-91-120-4318302</p>

	</blockquote>

	
	<h1 class="style2">South America</h1>
	<blockquote style="margin-bottom: 0;">
	<p style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;"><strong>Andre Parra</strong> <a href="mailto:andre.parra@rotork.com"><img src="/images/mailto.jpg" /></a> 
	<strong>Country Sales Manager</strong><br />
	<p style="margin-top: 0; margin-bottom: 0;">Venezuala, Columbia, Trinidad &amp; Tobago, Brazil, Peru, Argentina, Chile</p>
	<p style="margin-top: 0; margin-bottom: 0;">Cell: 55-11-2379-7797</p>
	<p style="border-bottom:1px solid #e8e8e8; margin-top: 0;">
	Office: 55-19-3115-1059</p>
	</blockquote>


	<h1 class="style2">Africa &amp; Countries Not Listed</h1>

	<blockquote style="margin-top: 0;">
	<p style="border-bottom:1px solid #e8e8e8; margin-bottom: 0;"><strong>Claudio Borges</strong> <a href="mailto:claudio.borges@rotork.com"><img src="/images/mailto.jpg" /></a> 
	<strong>Director of Sales</strong><br />
	</blockquote>

	<!--code storage
	
	11-99670-7718
	
	//-->


	</td>

	<!--  end contact table //-->

	</tr>

</table>





      <!-- InstanceEndEditable -->

     </td>
  </tr>

</table>

</div>

   <div id="footer">

  <div class="footerlinks">

  <a href="/" >Home</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="/featured-products.php">New Products</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="/about-us.php">About Us</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="/industries.php">Industries</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="/documents.php">Documents</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="/faq.php">FAQ</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="/request-quote.php">Request a Quote</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="/press.php">Press Releases</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="/contact-us.php">Contact Us</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="http://www.dist-fairchildproducts.com/dhq/login.asp">Distributor Login</a>  </div>



  

  

    Fairchild Industrial Products Company &bull; 3920 West Point Blvd. &bull; Winston-Salem, NC 27103<br />

tel: 336-659-3400 &bull; fax: 336-659-9323 &bull; sales@fairchildproducts.com<br />

<a href="http://www.networksmarketinggroup.com">Website Design</a> by NMG

</div>

</div>



<!-- InstanceBeginEditable name="tracking" -->



<!-- Google Analytics Code START -->



<script type="text/javascript">



  var _gaq = _gaq || [];

  _gaq.push(['_setAccount', 'UA-27953475-1']);

  if (typeof formViewed != 'undefined') {

  	_gaq.push(['_setCustomVar',1,'Form Viewed','Yes',2]);

	}

  if (typeof formSubmitted != 'undefined') {

  	_gaq.push(['_setCustomVar',2,'Form Submitted','Yes',2]);

	}

  _gaq.push(['_trackPageview']);



  (function() {

    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;

    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';

    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);

  })();



</script>

<!-- Google Analytics Code END -->



<!-- InstanceEndEditable -->



</body>

<!-- TemplateEnd --><!-- InstanceEnd --></html>
