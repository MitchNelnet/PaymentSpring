<?php

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

/*error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");*/
?>

<form id="email_test" action="" method="post" novalidate="novalidate">
    <div class="form-label">
        <label for="name">Name</label>
    </div>
    <div class="form-controls">
        <input name="name" id="name" type="text">
    </div>
    <div class="form-label">
    	<label for="email_address">Email Address</label>
    </div>
    <div class="form-controls">
    	<input name="email_address" id="email_address" type="email">
    </div>
    <div class="form-controls">
   	    <input type="submit" value="Sign Up" id="submit-button" />
    </div>
</form>
<div>
  1 = Welcome to PaymentSpring<br />
  customer_template_1.html
<br /><br />
  2 = Knock, Knock . . . (it's something good, promise)<br />
  customer_template_2.html
<br /><br />
  3 = One thing before last call.<br />
  customer_template_3.html
<br /><br />
  6 = A few must-knows before activation<br />
  customer_template_6.html
<br /><br />
  7 = News from PaymentSpring<br />
  customer_template_nurture.html
<br /><br />
  8 = plug in to a payment solution that just works<br />
  customer_template_addons.html
<br /><br />
  9 = accept more donation solutions<br />
  customer_template_checkout.html
<br /><br />
  10 = PCI DSS Knowledge is Power<br />
  email-template/customer_template_monthly.html
<br /><br />
  11 = Accounting, Payments & Partner-Driven Development<br />
  monthly_september.html'
<br /><br />
  12 = Get the Inside Scoop on Tokenization<br />
  monthly_october2017.html
<br /><br />
  13 = PaymentSpring Dashboard Just Got Better<br />
  dashboard_email.html
<br /><br />
  14 = PaymentSpring Dashboard Just Got Better<br />
  dashboard_email_fs_clients.html
<br /><br />
  15 = Power Up with Partner Tools = Nov monthly<br />
  monthly_november2017.html


</div>

<?php

$send_email = true;
$select_email = $_GET['email'];

if (strlen($select_email) < 1) {
	$send_email = false;
	echo "please specify the email number in the URL.";
}

/* Customer Email */
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$to = $_POST['email_address'];

	if ($select_email == "2") {
		$subject = 'Knock, Knock . . . (it\'s something good, promise)';
		$message = file_get_contents('email-template/customer_template_2.html');
		//$message = str_replace("[UserEmail]",$_POST["email_address"],$message);
	} else if ($select_email == "3") {
		$subject = 'One thing before last call.';
		$message = file_get_contents('email-template/customer_template_3.html');
		//$message = str_replace("[UserEmail]",$_POST["email_address"],$message);
	} else if ($select_email == "6") {
		$subject = 'A few must-knows before activation';
		$message = file_get_contents('email-template/customer_template_6.html');
		//$message = str_replace("[UserEmail]",$_POST["email_address"],$message);
	} else if ($select_email == "1") {
		$subject = 'Welcome to PaymentSpring';
		$message = file_get_contents('email-template/customer_template_1.html');
		$message = str_replace("[UserEmail]",$_POST["email_address"],$message);
	}  else if ($select_email == "7") {
		$subject = 'News from PaymentSpring';
		$message = file_get_contents('email-template/customer_template_nurture.html');
		$message = str_replace("[UserEmail]",$_POST["email_address"],$message);
	} else if ($select_email == "8") {
    $subject = 'plug in to a payment solution that just works';
    $message = file_get_contents('email-template/customer_template_addons.html');
    $message = str_replace("[UserEmail]",$_POST["email_address"],$message);
  } else if ($select_email == "9") {
    $subject = 'accept more donation solutions';
    $message = file_get_contents('email-template/customer_template_checkout.html');
    $message = str_replace("[UserEmail]",$_POST["email_address"],$message);
  } else if ($select_email == "10") {
    $subject = 'PCI DSS Knowledge is Power';
    $message = file_get_contents('email-template/customer_template_monthly.html');
    $message = str_replace("[UserEmail]",$_POST["email_address"],$message);
  }
  else if ($select_email == "11") {
    $subject = 'Accounting, Payments & Partner-Driven Development';
    $message = file_get_contents('email-template/monthly_september.html');
    $message = str_replace("[UserEmail]",$_POST["email_address"],$message);
  }
	else if ($select_email == "12") {
    $subject = 'Get the Inside Scoop on Tokenization';
    $message = file_get_contents('email-template/monthly_october2017.html');
    $message = str_replace("[UserEmail]",$_POST["email_address"],$message);
  }
  else if ($select_email == "13") {
    $subject = 'PaymentSpring Dashboard Just Got Better';
    $message = file_get_contents('email-template/dashboard_email.html');
    $message = str_replace("[UserEmail]",$_POST["email_address"],$message);
  }
  else if ($select_email == "14") {
    $subject = 'PaymentSpring Dashboard Just Got Better';
    $message = file_get_contents('email-template/dashboard_email_fs_clients.html');
    $message = str_replace("[UserEmail]",$_POST["email_address"],$message);
  }
  else if ($select_email == "15") {
    $subject = 'Power Up with Partner Tools';
    $message = file_get_contents('email-template/monthly_november2017.html');
    $message = str_replace("[UserEmail]",$_POST["email_address"],$message);
  }
  else if ($select_email == "16") {
    $subject = 'Payments to Clients = Revenue to You';
    $message = file_get_contents('email-template/web_developer_email.html');
    $message = str_replace("[UserEmail]",$_POST["email_address"],$message);
  }
  else if ($select_email == "17") {
    $subject = 'Pardot Test';
    $message = file_get_contents('email-template/sp_email.html');
    $message = str_replace("[UserEmail]",$_POST["email_address"],$message);
  }



	if ($send_email) {
		$headers = "From: Team PaymentSpring <sales@paymentspring.com>\r\n";
		//$headers .= "CC: matia.ward@firespring.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$headers .= "X-Mailer: PHP/" . phpversion();

		if (wp_mail($to,$subject,$message,$headers)) {
			echo "email sent to " . $_POST['email_address'];
		} else {
			echo "email NOT sent. Ask Matia why!";
		}
		/* End Customer Email */
	}
}

?>
