<?PHP
// ----------------------------------------------------------
// NMG Control Panel Settings
// ----------------------------------------------------------
$con_VERSION = '2.0';
$con_SOFTNAME = 'NMG Control Panel';
$con_ADFEED = 'http://www.networksmarketinggroup.com/crmfeed.xml';
$con_ADMINEMAIL = 'scott@networksmg.com';

// ----------------------------------------------------------
// SERVER & FILE Settings
// ----------------------------------------------------------
define('NMG_SERVER_DIRNAME','fairprod');
define('NMG_SERVER_ROOT','/home/'.NMG_SERVER_DIRNAME.'/public_html/');
define('NMG_SERVER_CONTROL','/home/'.NMG_SERVER_DIRNAME.'/public_html/control/');
define('NMG_SERVER_PHP_UPLOADS','/home/'.NMG_SERVER_DIRNAME.'/public_html/php_uploads/');

define('NMG_USER_ROOT','http://www.fairchildproducts.com/');
define('NMG_USER_CONTROL',NMG_USER_ROOT.'control/');
define('NMG_SITEMAP_URL', NMG_USER_ROOT."sitemap.xml");


// ----------------------------------------------------------
// COMPANY CONFIGURATION AREA
// ----------------------------------------------------------
$companyname = 'Fairchild Industrial Products';
$from = 'info@fairchildproducts.com'; // this is used for the FAQ among other things

// ----------------------------------------------------------
// MODULE CONFIGURATION AREA
// (warning module must be installed first to be enabled.)
// ----------------------------------------------------------
$con_CRM = TRUE;
$con_CRMPLUS = FALSE;
$con_FAQ = TRUE;
$con_tracking = TRUE; // if they have omniture, display link to login.
$con_USEREDIT = TRUE;
$con_CATALOG = TRUE;
//$con_CATALOG_LINK = '/cat-edit/catalog-list.php'; // if there is a catalog editor, put link here
$con_CATALOG_LINK = '/control/catalog/catalog/products/'; // if there is a catalog editor, put link here
$con_AUTORESPONSE = TRUE;
$con_DELAYAUTORESPONSE = TRUE;
$con_EMAIL_CAMPAIGN = FALSE;
$con_DISTRIBUTOR = TRUE;
$con_PINGENGINES = TRUE;
$con_KEYWORDANALYSIS = TRUE;
$con_FEATURED_PRODUCTS = TRUE;
$con_LINK_CHECKER = true;
$con_QR_CODES = true;
$con_DOCMANAGER = TRUE;
$con_PROJECT_MANAGER = true;
$con_CONVERSION_REPORTS = true;

// ----------------------------------------------------------
// CRM PLUS MODULE CONFIGURATION AREA
// (warning module must be installed & enabled.)
// ----------------------------------------------------------
if( ! defined("MAINLINK"))
{
	define("MAINLINK", 'name');  // this is the field that is used for links in the CRM.
}

// ----------------------------------------------------------
// EMAIL CAMPAIGN MODULE CONFIGURATION AREA
// (warning module must be installed & enabled.)
// ----------------------------------------------------------
$CONF_company_name = $companyname;
// link below should NOT include trailing slash
$CONF_weburlroot = 'http://www.fairchildproducts.com';
$CONF_imageloc = 'http://www.fairchildproducts.com/';
$CONF_replyto = 'info@fairchildproducts.com';
$CONF_from = 'fairchildproducts.com';
// ----------------------------------------------------------
// CRM PLUS MODULE CONFIGURATION AREA
// (warning module must be installed & enabled.)
// ----------------------------------------------------------
if( ! defined("MAINLINK"))
{
	define("MAINLINK", 'company');  // this is the field that is used for links in the CRM.
}

// ----------------------------------------------------------
// AUTOMATIC LINK CHECKER MODULE CONFIGURATION AREA
// (warning module must be installed & enabled.)
// ----------------------------------------------------------
define('WEBSITE_ROOT','http://www.fairchildproducts.com');  // DO NOT ADD TRAILING SLASH


// ----------------------------------------------------------
// QR CODE MODULE CONFIGURATION AREA
// (warning module must be installed & enabled.)
// ----------------------------------------------------------
define('TRACKINGURL',$CONF_weburlroot.'/campaign/qr-special.php?deal=');



// ----------------------------------------------------------
// SEARCH ENGINE PINGER v 1.0
// ----------------------------------------------------------
define('NMG_PINGER_SITEROOT', 'http://www.fairchildproducts.com/'); // Define site root with / at end.
define('NMG_PINGER_KEYWORD', 'Precision Pressure Regulators'); // Used for Ping-O-Matic

// ----------------------------------------------------------
// GENERIC WARNING FOR LOGIN & OTHER PAGES
// ----------------------------------------------------------
$warning = 'You have accessed a secure page. This webpage and any linked documents are intended only for the company that ownes this data and may contain information which is privileged, confidential and prohibited from disclosure or unauthorized use under applicable law.  If you are not authorized to access this page, you are hereby notified that any use, dissemination, or copying of this data is strictly prohibited by the owner. Your IP has been logged as: '.$_SERVER['REMOTE_ADDR'].'.  Unauthorized access will be procescuted.';

function fancyDate($date) {
	if ($date !='') {
	$out = date('M j, Y g:i:s A',strtotime( $date ));
	return $out;
	} else {
	return '';
	}
}

?>
