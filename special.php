<?php
	date_default_timezone_set('');
	include("dompdf/dompdf_config.inc.php");
	$msg=0;
	if (@$_POST['submit'] == 'submit'){
	
		$handle = fopen("special.txt", "r");
	if ($handle) {
		while (($line = fgets($handle)) !== false) {
			$stringArray[] = trim($line);
		}
		
			fclose($handle);
		} else {
			// error opening the file.
		} 
	$stringArray = array_values(array_filter($stringArray));	
	
	
	$myfile = fopen("countspecial.txt", "r") or die("Unable to open file!");
	$countvalue = fread($myfile,filesize("countspecial.txt"));
	fclose($myfile);	
	$code = $stringArray[$countvalue];

	
	if ($countvalue < count($stringArray)-1){
		$countvalue =$countvalue+1;
	} else {
		$countvalue;
	}


	$myfile = fopen("countspecial.txt", "w") or die("Unable to open file!");
	$txt = $countvalue;
	fwrite($myfile, $txt);
	fclose($myfile);
	
	
	$myfile = fopen("template/special.html", "r") or die("Unable to open file!");
	$bio_file_content1 = fread($myfile,filesize("template/special.html"));
	fclose($myfile);
	$bio_file_content1 = str_replace("images",str_replace("special.php","",$_SERVER['HTTP_REFERER'])."template/images/",$bio_file_content1);	
	
        $bio_file_content1 = str_replace("##code",$code,$bio_file_content1);	
	$bio_file_content1 = str_replace("##price",'8.45',$bio_file_content1);	
	$bio_file_content1 = str_replace("##Date", date("d.m.y", strtotime('3 weekdays')),$bio_file_content1);
     
	$bio_file_content1 = str_replace("##Trackingnumber",$_POST['trackingnumber'],$bio_file_content1);



$bio_file_content1 = str_replace("##Size",$_POST['size'],$bio_file_content1);



$bio_file_content1 = str_replace("##Weight",$_POST['weight'],$bio_file_content1);


$bio_file_content1 = str_replace("##Postfrom",$_POST['postfrom'],$bio_file_content1);
$bio_file_content1 = str_replace("##Postto",$_POST['postto'],$bio_file_content1);




		
	if ($_POST['name'] != ''){
		$bio_file_content1 = str_replace("##name",$_POST['name'],$bio_file_content1);	
	} else {
		$bio_file_content1 = str_replace("##name<br />", '', $bio_file_content1);	
	}
	
	if ($_POST['line1'] != ''){
		$bio_file_content1 = str_replace("##lstline",$_POST['line1'],$bio_file_content1);	
	} else {
		$bio_file_content1 = str_replace("##lstline<br />", '', $bio_file_content1);	
	}
	
	if ($_POST['line2'] != ''){
		$bio_file_content1 = str_replace("##2ndline",$_POST['line2'],$bio_file_content1);	
	} else {
		$bio_file_content1 = str_replace("##2ndline<br />", '', $bio_file_content1);	
	}
	
	if ($_POST['city'] != ''){
		$bio_file_content1 = str_replace("##city",$_POST['city'],$bio_file_content1);	
	} else {
		$bio_file_content1 = str_replace("##city<br />", '', $bio_file_content1);	
	}
	
	if ($_POST['country'] != ''){
		$bio_file_content1 = str_replace("##Country",$_POST['country'],$bio_file_content1);	
	} else {
		$bio_file_content1 = str_replace("##Country<br />", '', $bio_file_content1);	
	}
	
	if ($_POST['postcode'] != ''){
		$bio_file_content1 = str_replace("##postcode",$_POST['postcode'],$bio_file_content1);	
	} else {
		$bio_file_content1 = str_replace("##postcode<br />", '', $bio_file_content1);	
	}
	
	/*echo $bio_file_content1;
		exit;*/
	
		$dompdf = new DOMPDF();
		$dompdf->load_html($bio_file_content1);
		$customPaper = array(0,0,1130,735);
		$dompdf->set_paper($customPaper);
		$dompdf->render();
		//$dompdf->stream($code."_pdf.pdf");
		$pdf = $dompdf->output();

  // You can now write $pdf to disk, store it in a database or stream it
  // to the client.

   file_put_contents($code."sp_pdf.pdf", $pdf);
		$msg=1;
		//echo "<h1>".$code."_pdf.pdf successfull Created. <a href='".$code."_pdf.pdf'>Download<a> or <a href='print.php?pdf=".$code."_pdf.pdf' target='_blank'>Print</a> or <a href='pdf.php'>Go Back</a></h1>"; 
		//exit;
		
		
	}	
	

	
	
	
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Royal Mail Online Postage Form - Special Delivery</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="script/bootstrap.min.css">
  <script src="script/jquery.min.js"></script>
  <script src="script/bootstrap.min.js"></script>
  <script>
  function validate(){
  	if ($.trim($("#name").val()) == ''){
		$("#name").focus();
		return false;
	}
	if ($.trim($("#line1").val()) == ''){
		$("#line1").focus();
		return false;
	}
	if ($.trim($("#city").val()) == ''){
		$("#city").focus();
		return false;
	}
	if ($.trim($("#postcode").val()) == ''){
		$("#postcode").focus();
		return false;
	}
	
  	return true;
  }
  </script>
</head>
<body>
<div class="container">
<?php if ($msg == 0) { ?>
  <h2>Royal Mail Online Postage Form - 8.70 Special Delivery</h2>
  <form role="form" method="post" onSubmit="return validate()">
    <div class="form-group">
      <label for="email">Name:</label>
      <input type="text" class="form-control" id="name"  name="name" placeholder="Enter Name" required>
    </div>
    <div class="form-group">
      <label for="pwd">Address1:</label>
      <input type="text" class="form-control" id="line1" name="line1" placeholder="Enter Address1" required>
    </div>
	<div class="form-group">
      <label for="pwd">Address2:</label>
      <input type="text" class="form-control" id="line2" name="line2" placeholder="Enter Address2">
    </div>
	<div class="form-group">
      <label for="pwd">City:</label>
      <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" required>
    </div>
	<div class="form-group">
      <label for="pwd">County:</label>
      <input type="text" class="form-control" id="country" name="country" placeholder="Enter County">
    </div>
	<div class="form-group">
      <label for="pwd">Post code:</label>
      <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Enter Post code" required>
    </div>
<div class="form-group">
      <label for="pwd">Tracking Number:</label>
      <input type="text" class="form-control" id="trackingnumber" name="trackingnumber" placeholder="Enter Tracking Number" required>
    </div>
    



  <div class="form-group">
      <label for="pwd">What's the size of your item?</label> 
      <input type="text" class="form-control" id="size" name="size" placeholder="Enter Size" required>
    </div>





  <div class="form-group">
      <label for="pwd">Weight:</label> 
      <input type="text" class="form-control" id="weight" name="weight" placeholder="Enter Weight" required>
    </div>




 <div class="form-group">
      <label for="pwd">Post From:</label> 
      <input type="text" class="form-control" id="postfrom" name="postfrom" placeholder="Enter Three Letter Code" required>
    </div>
 <div class="form-group">
      <label for="pwd">Post To:</label> 
      <input type="text" class="form-control" id="postto" name="postto" placeholder="Enter Three Letter Code" required>
    </div>




	
    <button type="submit" name="submit" value="submit" class="btn btn-default">Submit</button>
  </form>


 <?php } ?>
  <?php if ($msg == 1) { ?>
  <p style="height:200px">&nbsp;</p>	
  		<div style="border:solid 5px #999999; padding:20px;">
  		
  		<h4>Thank you for entering your Address Details. You can either download as PDF or Print the details.</h4>
		<p><a href='<?php echo $code."sp_pdf.pdf"; ?>' target="_blank">Click here to download</a> or <a href='print.php?pdf=<?php echo $code."sp_pdf.pdf"; ?>' target='_blank'>print</a> as PDF</a> or <a href='special.php'>PRINT more postage</a></p>
		</div>
  <?php } ?>
</div>

</body>
</html>
