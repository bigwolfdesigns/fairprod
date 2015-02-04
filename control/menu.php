<?php
// User lands here after loggin in
require_once('settings.php');

session_name('YourVisitID');
session_start(); // Start the session.
// If no session value is present, redirect the user.
if(!isset($_SESSION['agent']) OR ( $_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']))){

	// Start defining the URL.
	$url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
	// Check for a trailing slash.
	if((substr($url, -1) == '/') OR ( substr($url, -1) == '\\')){
		$url = substr($url, 0, -1); // Chop off the slash.
	}
	$url .= '/index.php'; // Add the page.
	header("Location: $url");
	exit(); // Quit the script.
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?PHP echo $companyname; ?> Control Panel</title>
		<link href="/control/control.css" rel="stylesheet" type="text/css" />
		<script type="text/JavaScript">
			<!--
			function displaymessage()
			{
			alert(' You do not have this feature installed.\n Contact Networks Marketing Group to Purchase. \n Click OK to continue.');
			return false;
			}
			//-->
		</script>

	</head>

	<body>

		<div id="wrapper">
			<div id="header"><strong><?PHP echo $companyname; ?> Control Panel</strong><br /><span style="font-size:10px"><?PHP echo $con_SOFTNAME.' Version: '.$con_VERSION; ?></span><div id="mainlink"><a href="menu.php">Main Menu</a>&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp;<a href="logout.php">Logout</a></div></div>
			<div id="controlmenu">
				<?PHP
				switch($_SESSION['user_level']){
					case 'god':
						if(true){
							echo '<div class="button"><a href="crm-plus/index.php"><img src="images/crm-plus.jpg"></a></div>';
						}
						if($con_CRM){
							echo '<div class="button"><a href="view-crm.php"><img src="images/view-crm.jpg"></a></div>';
						}
						if($con_CRM){
							echo '<div class="button"><a href="export-crm.php"><img src="images/export-crm.jpg"></a></div>';
						}
						if($con_CRM){
							echo '<div class="button"><a href="export-email.php"><img src="images/export-email.jpg"></a></div>';
						}
						if($con_CRM){
							echo '<div class="button"><a href="google.php"><img src="images/google.jpg"></a></div>';
						}
						if($con_AUTORESPONSE){
							echo '<div class="button"><a href="autoresponse.php"><img src="images/autoresponse.jpg"></a></div>';
						}
						if($con_DISTRIBUTOR){
							echo '<div class="button"><a href="distributors.php"><img src="images/distributor.jpg"></a></div>';
							echo '<div class="button"><a href="authorized-distributors-list.php"><img src="images/authorized-distributor-domains.jpg"></a></div>';
							echo '<div class="button"><a href="distributors-pricing-view.php"><img src="images/distributor-pricing-block.jpg"></a></div>';
						}
						if(true){
							echo '<div class="button"><a href="keyword_analysis/index.php"><img src="images/keyword-analysis.jpg"></a></div>';
						}
						if(true){
							echo '<div class="button"><a href="featured-products/index.php"><img src="images/featured-products.jpg"></a></div>';
						}

						if($con_FAQ){
							echo '<div class="button"><a href="faq.php"><img src="images/ifaq.jpg"></a></div>';
						}
						if($con_FAQ){
							echo '<div class="button"><a href="faq_stats.php"><img src="images/ifaq-stats.jpg"></a></div>';
						}

						if($con_CATALOG){
							echo '<div class="button"><a href="'.$con_CATALOG_LINK.'"><img src="images/edit-catalog.jpg"></a></div>';
						}
						if($con_USEREDIT){
							echo '<div class="button"><a href="add-user.php"><img src="images/add-user.jpg"></a></div>
								<div class="button"><a href="view-users.php"><img src="images/view-users.jpg"></a></div>';
						}
						if($con_EMAIL_CAMPAIGN){
							echo '<div class="button"><a href="/control/campaigns/email-campaigns-list.php"><img src="images/email-campaigns.jpg"></a></div>';
						}

						if($con_tracking){
							echo '<div class="button"><a href="http://www.google.com/analytics/"><img src="images/tracking.jpg"></a></div>';
						}
						if($con_CRM){
							echo '<div class="button"><a href="campaigns/email-campaigns-list.php"><img src="images/export-crm.jpg">email campaigns list</a></div>';
						}
						echo '<div class="button"><a href="search-engine-ping/ping.php?d='.$con_WEBSITEURL.'"><img src="images/sitemap-ping.jpg"></a></div>';

						break;


					case 'admin':
						if($con_CRMPLUS){
							echo '<div class="button"><a href="crm-plus/index.php"><img src="images/crm-plus.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/crm-plus-disabled.jpg" onclick="displaymessage()"/></div>';
						}
						if($con_CRM){
							echo '<div class="button"><a href="view-crm.php"><img src="images/view-crm.jpg"></a></div>';
							echo '<div class="button"><a href="export-crm.php"><img src="images/export-crm.jpg"></a></div>';
							echo '<div class="button"><a href="export-email.php"><img src="images/export-email.jpg"></a></div>';
							echo '<div class="button"><a href="google.php"><img src="images/google.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/view-crm-disabled.jpg" onclick="displaymessage()"/></div>';
							echo '<div class="button"><img src="images/export-crm-disabled.jpg" onclick="displaymessage()"/></div>';
							echo '<div class="button"><img src="images/export-email-disabled.jpg" onclick="displaymessage()"/></div>';
							echo '<div class="button"><img src="images/google-disabled.jpg" onclick="displaymessage()"/></div>';
						}

						if(true){

							echo '<div class="button"><a href="/extranet/menu.php"><img src="images/lead-manager-viewer.jpg"></a></div>';
						}
						if($con_CONVERSION_REPORTS){
							echo '<div class="button"><a href="conversion-stats/index.php"><img src="images/conversion-reports.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/conversion-reports-disabled.jpg" onclick="displaymessage()"/></div>';
						}

						if($con_AUTORESPONSE){
							echo '<div class="button"><a href="autoresponse.php"><img src="images/autoresponse.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/autoresponse-disabled.jpg" onclick="displaymessage()"></div>';
						}
						if($con_DELAYAUTORESPONSE){
							echo '<div class="button"><a href="delayautoresponse.php"><img src="images/autoresponse2.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/autoresponse2-disabled.jpg" onclick="displaymessage()"/></div>';
						}
						if($con_DISTRIBUTOR){
							echo '<div class="button"><a href="distributors.php"><img src="images/distributor.jpg"></a></div>';
							// MOD: rkehret@camna.com  2/14/2014
							// uncomment the authorized distributor list and distributor pricing block widgets / pages.
							echo '<div class="button"><a href="authorized-distributors-list.php"><img src="images/authorized-distributor-domains.jpg"></a></div>';
							echo '<div class="button"><a href="distributors-pricing-view.php"><img src="images/distributor-pricing-block.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/distributor-disabled.jpg" onclick="displaymessage()"/></div>';
						}
						if($con_EMAIL_CAMPAIGN){
							echo '<div class="button"><a href="/control/campaigns/email-campaigns-list.php"><img src="images/email-campaigns.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/email-campaigns-disabled.jpg" onclick="displaymessage()"/></div>';
						}
						if($con_FAQ){
							echo '<div class="button"><a href="faq.php"><img src="images/ifaq.jpg"></a></div>';
							echo '<div class="button"><a href="faq_stats.php"><img src="images/ifaq-stats.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/ifaq-disabled.jpg" onclick="displaymessage()"/></div>';
							echo '<div class="button"><img src="images/ifaq-stats-disabled.jpg" onclick="displaymessage()"/></div>';
						}
						if($con_FEATURED_PRODUCTS){
							echo '<div class="button"><a href="featured-products/index.php"><img src="images/featured-products.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/featured-products-disabled.jpg" onclick="displaymessage()"/></div>';
						}
						if($con_PROJECT_MANAGER){
							echo '<div class="button"><a href="project-manager/project-manager-clients.php"><img src="images/project-manager.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/project-manager-disabled.jpg" onclick="displaymessage()"/></div>';
						}

						if($con_LINK_CHECKER){
							echo '<div class="button"><a href="link-checker/auto-link-checker.php"><img src="images/auto-link-checker.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/auto-link-checker-disabled.jpg" onclick="displaymessage()"/></div>';
						}
						if($con_QR_CODES){
							echo '<div class="button"><a href="qr-codes/index.php"><img src="images/qr-codes.jpg"></a></div>';
						}

						if($con_DOCMANAGER){
							echo '<div class="button"><a href="documents/index.php"><img src="images/document-manager.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/project-manager-disabled.jpg" onclick="displaymessage()"/></div>';
						}


						// MOD: rkehret@camna.com. remove till we build new
						// MOD: cnasal@camna.com 05/19/2014 Putting back
						//if (false) {echo '<div class="button"><a href="'.$con_CATALOG_LINK.'"><img src="images/edit-catalog.jpg"></a></div>';
						if($con_CATALOG){
							echo '<div class="button"><a href="'.$con_CATALOG_LINK.'"><img src="images/edit-catalog.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/edit-catalog-disabled.jpg" onclick="displaymessage()"/></div>';
						}
						if($con_USEREDIT){
							echo '<div class="button"><a href="add-user.php"><img src="images/add-user.jpg"></a></div>
				<div class="button"><a href="view-users.php"><img src="images/view-users.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/add-user-disabled.jpg" onclick="displaymessage()"/></div>';
							echo '<div class="button"><img src="images/view-users-disabled.jpg" onclick="displaymessage()"/></a></div>';
						}
						if($con_tracking){
							echo '<div class="button"><a href="http://www.google.com/analytics/"><img src="images/tracking.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/tracking-disabled.jpg" onclick="displaymessage()"/></div>';
						}
						if($con_PINGENGINES){
							echo '<div class="button"><a href="search-engine-ping/ping.php?d='.$con_WEBSITEURL.'"><img src="images/sitemap-ping.jpg"></a></div>';
						}else{
							echo '<div class="button"><img src="images/sitemap-ping-disabled.jpg" onclick="displaymessage()"/></div>';
						}
						if($con_KEYWORDANALYSIS){
							echo '<div class="button"><a href="keyword_analysis/index.php"><img src="images/keyword-analysis.jpg"></a></div>';
						}
						break;



					case 'basic':
						if($con_CRM){
							echo '<div class="button"><a href="view-crm.php"><img src="images/view-crm.jpg"></a></div>';
						}
						if($con_CRM){
							echo '<div class="button"><a href="google.php"><img src="images/google.jpg"></a></div>';
						}
						if($con_FAQ){
							echo '<div class="button"><a href="faq.php"><img src="images/ifaq.jpg"></a></div>';
						}
						if($con_CATALOG){
							echo '<div class="button"><a href="'.$con_CATALOG_LINK.'"><img src="images/edit-catalog.jpg"></a></div>';
						}
						if($con_tracking){
							echo '<div class="button"><a href="http://www.google.com/analytics/"><img src="images/tracking.jpg"></a></div>';
						}
						break;
				}
				?>
			</div>
			<div id="footer">
<?PHP echo $warning; ?>
				<br />
				NMG Control Panel - &copy; Networks Marketing Group <?PHP echo date('Y'); ?>
			</div>
		</div>

	</body></html>
