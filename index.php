<?PHP 

include('cookie.php');

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/main.dwt.php" codeOutsideHTMLIsLocked="false" --><!-- TemplateBegin template="/Templates/main.dwt.php" codeOutsideHTMLIsLocked="false" -->

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- InstanceBeginEditable name="doctitle" -->



<title>Fairchild Industrial Products</title>



<?PHP include('featured-products/loader.php');

echo $featured_products_header;

?>



<!-- InstanceEndEditable -->

<link href="/fairchild.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">

function ClearInput(value, id){ // This calls our function ClearInput, and the two variables we will need for it to function the original value and the id.

var input = document.getElementById(id); // Gets the input field based on its id.



if(value == input.value){ // If the default value is equal to the current value.

input.value = ''; // Empty It.

}else{ // Else the value is not equal to the current input field value.

input.value = input.value; // Leave it the same.

} // End Else.

} // Close Function.

</script>

<!-- InstanceBeginEditable name="head" -->



<style type="text/css">



<!--



.style1 {



  font-size: 12px



}



-->



</style>



<!-- InstanceEndEditable --><!-- InstanceParam name="Leftnav" type="boolean" value="false" --><!-- InstanceParam name="right-details" type="boolean" value="false" --><!-- InstanceParam name="New_Products" type="boolean" value="false" -->

</head>



<body<?PHP if(isset($errors) && count($errors)){ echo ' onload="loadErrors()"';} else if (isset($NMG_FORM)) {echo ' onload="checkCountryLoad()"'; }?>>



<div id="wrapper">

	<div id="header"><form action="/search/search-part.php" method="get"><div id="search">

	 <input type="text" name="query" value="Search by Part # or Keyword" id="query" onclick="ClearInput('Search by Part # or Keyword', this.id);" style="width:180px;" />

	&nbsp;<input type="submit" value="Go" /></div>  <input type="hidden" name="search" value="1" /></form>

	<?PHP include($_SERVER['DOCUMENT_ROOT'].'/chat-image.php');?>

    </div>

  <div id="navcontainer">

<ul id="navlist" style="margin-bottom: 0">

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

  <tr valign="top">    </tr>

</table>





<table width="940" border="0" cellspacing="0" cellpadding="0">

  <tr valign="top">

   

   

   

   

    <td>

      

      

      

      

      <!-- InstanceBeginEditable name="content" -->



      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="home-buckets">



        <tr valign="top">



          <td width="33%"><a href="/category.php?cid=1"><div class="bucket-title" align="center">Pressure Regulators</div></a>

    	      <div class="bucket-text"><div class="bucket-image"><a href="/category.php?cid=1"><img src="images/RegulatorsPic.jpg" width="159" height="176" /></a></div>



    	      Fairchild's wide selection satisfies your instrument or industrial control applications.&nbsp; Fairchild pneumatic regulators are available as: precision, back pressure, miniature, filter service, stainless steel, motorized, low pressure and specialty.</div>

              <div class="bucket-link"><a href="/distributors.php"><img src="images/find-distributor.jpg" alt="Find A Distributor"/></a></div>          </td>

<td width="33%"><a href="/category.php?cid=2"><div class="bucket-title-new" align="center">New Products</div></a>

<?PHP echo $featured_products;?>



</td>

          <td width="33%"><a href="/category.php?cid=2"><div class="bucket-title" align="center">Transducers</div></a>

          <div class="bucket-text"><div class="bucket-image"><a href="/category.php?cid=2"><img src="images/TransducersPic.jpg" width="158" height="183" /></a></div>

          Fairchild's fast response, high flow, compact I/P, E/P, D/P &amp; P/I electro-pneumatic transducers have extensive combinations of inputs and outputs, carry standard FM, CSA, ATEX or IECEx agency approvals, and maintain accuracies of 0.25% or 0.5%.</div>

          <div class="bucket-link"><a href="/distributors.php"><img src="images/find-distributor.jpg" alt="Find A Distributor"/></a></div></td>

          

          </tr></table>

      <div id="slogan"><img src="/images/fairchild_slogan.jpg" alt="Two Year Warranty - One to Two Week Deliveries on Standard Products - Customized Products."/></div>



      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="home-buckets">



        <tr valign="top">

<td width="33%"><a href="/subcat/3/Pneumatic_Volume_Boosters.php"><div class="bucket-title" align="center">Volume Boosters</div></a>



    	      <div class="bucket-text"><div class="bucket-image"><a href="/subcat/3/Pneumatic_Volume_Boosters.php"><img src="images/BoostersPic.jpg" width="163" height="183" /></a></div>

    	      Fairchild's pneumatic volume boosters feature high flow capacity and fast response with Cv ratios from 1 up to 18, with flow capacities of 45 SCFM, 76.5 m3/hr up to 1800 SCFM, 3058 m3/hr.&nbsp;</div>

    	      <div class="bucket-link"><a href="/distributors.php"><img src="images/find-distributor.jpg" alt="Find A Distributor"/></a></div></td>



 <td><a href="/category.php?cid=4"><div class="bucket-title" align="center">Relays</div></a>



    	      <div class="bucket-text"><div class="bucket-image"><a href="/category.php?cid=4"><img src="images/RelaysPic.jpg" width="170" height="183" /></a></div>

    	      Fairchild's extensive line of pneumatic relays serves a multitude of control applications with: positive biasing, positive and negative biasing, infinitely adjustable ratio, reversing, averaging and computing, high and low pressure selectors, and high and low pressure limiting relays.</div>

    	      <div class="bucket-link"><a href="/distributors.php"><img src="images/find-distributor.jpg" alt="Find A Distributor"/></a></div></td>



          <td><a href="/category.php?cid=5"><div class="bucket-title" align="center">Filters and Accessories</div></a>



    	      <div class="bucket-text"><div class="bucket-image"><a href="/category.php?cid=5"><img src="images/FilterPic.jpg" width="170" height="182" /></a></div>

    	        <p style="margin-top: 0; margin-bottom: 0;">Fairchild accessories complement our complete line of control products.&nbsp; Transducer manifold and rack mounting kits, pressure gages,  panel loading stations, and filters offer high quality and  precision.</p>

    	        </div>

    	      <div class="bucket-link"><a href="/distributors.php"><img src="images/find-distributor.jpg" alt="Find A Distributor"/></a></div></td>



  </tr>



        </table>  



      <table width="100%" height="357" border="0" cellpadding="0" cellspacing="0" class="home-buckets">



         <tr valign="top">



           <td width="33%"><a href="featured-products.php">



             <div class="bucket-title" align="center">New Products</div></a>



    	      <div class="bucket-text-trio"><a href="featured-products.php"><img src="images/home-new.jpg" /></a><br />



M17 vacuum Regulator, M72 High Performance Mini-regulator, M4100 Low Pressure Regulator, T1750 High Performance I/P Transducer, T6100 Lock in Position I/P Transducer, New P/I Pressure Transmitters</div>    </td>



         <td width="33%"><a href="/specon-power-transmission.php">



             <div class="bucket-title" align="center">Power Transmission</div></a>



    	      <div class="bucket-text-trio"><a href="/specon-power-transmission.php"><img src="images/home-specon.jpg" /></a><br />



SPECON manufactures PIV chain drive transmissions, planetary gearboxes and can repair or provide replacement units for SPECON&reg;, CUBIC&reg; or even other manufacturers’ products, such as those made by Rexnord&reg; or Link Belt&reg;</div>    </td>



      <td width="33%"><a href="press.php">



            <div class="bucket-title" align="center">Fairchild Global Locations</div></a>



          <div class="bucket-text-trio"><div align="center">

            <p style="margin-top: 0; margin-bottom: 0;">

            	<a href="fairchild-india.php"><img src="images/news-india.jpg" alt="Fairchild India" height="78" with="75" border="0" /></a>&nbsp; 

                <a href="fairchild-china.php"><img src="images/news-china.jpg" alt="Fairchild China" height="78" width="75" border="0" /></a>&nbsp;

                <a href="fairchild-mexico.php"><img src="images/news-mexico.jpg" alt="Fairchild Mexico" height="78" width="75" border="0" /></a>

                </p>

            <p style="margin-top: 0;">

            	<a href="fairchild-brasil.php"><img src="images/news-brasil.jpg" alt="Fairchild Brasil" width="78" height="75" border="0" /></a>&nbsp;

            	<a href="fairchild-australia.php"><img src="images/news-australia.jpg" alt="Fairchild Australia" width="78" height="75" border="0" /></a>

            	</p>

            <p style="margin-bottom: 0;">&nbsp;</p>

          </div>



            <p style="margin-top: 0; margin-bottom: 0;"><a href="/pdf/csaseal.pdf" class="style1">New CSA Secondary Seal Approval</a><br />



  &nbsp;<br /><a href="/pdf/IECEx Approval sheets 7800.TXI7800.pdf" class="style1">NEW IECEx APPROVALS</a>



  <br />            



          </div></td>



         </tr></table> 



      <!-- InstanceEndEditable -->        </td>

       

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

