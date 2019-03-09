<?php
//Email to send enrolment too
define("EMAIL", "enrolmentoffice@localhost");
//SMTP authenticated address to send emails from
define("FROM", "webadmin@localhost");

$auto_reply = FALSE;

$first_nameErr = $last_nameErr = $addressErr = $cityErr = $postcodeErr = $phoneErr = $emailErr = $ew_idErr = $ew_regErr = $reg_typeErr = $courseErr = $ewrb_completeErr = "";
$first_name = $last_name = $address = $city = $postcode = $phone = $email = $ew_id = $ew_reg = $reg_type = $course = $ewrb_complete = "";
$errMsg = $succMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if(isset($_POST['submit']) && !empty($_POST['submit'])):
      if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])):
          //your site secret key, 'keep it secret, keep it safe
          $secret = 'KeyGoesHere'; //Change me
          //get verify response data
          $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
          $responseData = json_decode($verifyResponse);
          if($responseData->success):

              //Pre sanitize all inputs
              $_POST['first_name'] = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
              $_POST['last_name'] = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
              $_POST['address'] = filter_var($_POST['address'],  FILTER_SANITIZE_STRING);
              $_POST['city'] = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
              $_POST['postcode'] = filter_var($_POST['postcode'], FILTER_SANITIZE_NUMBER_INT);
              $_POST['phone'] = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
              $_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
              $_POST['ew_id'] = filter_var($_POST['ew_id'], FILTER_SANITIZE_STRING);
              $_POST['ew_reg'] = filter_var($_POST['ew_reg'], FILTER_SANITIZE_STRING);
              $_POST['reg_type'] = filter_var($_POST['reg_type'], FILTER_SANITIZE_STRING);
              $_POST['course'] = !empty($_POST['course'])?filter_var($_POST['course'], FILTER_SANITIZE_STRING):'';
              $_POST['ewrb_complete'] = !empty($_POST['ewrb_complete'])?filter_var($_POST['ewrb_complete'], FILTER_SANITIZE_STRING):'';

              //Save completed fields and test against regex. Load error message if not.
              if(!empty($_POST['first_name'])){
                $first_name = test_input($_POST["first_name"]);
                if (!preg_match("/^[a-zA-Z ]*$/",$first_name)) {
                  $first_nameErr = "Only letters and white space allowed";
                }
              } else {
                $first_name = '';
                $first_nameErr = "Name is required";
              }

              if(!empty($_POST['last_name'])){
                $last_name = test_input($_POST["last_name"]);
                if (!preg_match("/^[a-zA-Z ]*$/",$last_name)) {
                  $last_nameErr = "Only letters and white space allowed";
                }
              } else {
                $last_name = '';
                $last_nameErr = "Name is required";
              }

              if(!empty($_POST['address'])){
                $address = test_input($_POST["address"]);
                if (!preg_match("/^[,a-zA-Z0-9 ]*$/",$address)) {
                  $addressErr = "Only letters and numbers allowed";
                }
              } else {
                $address = '';
                $addressErr = "Postal address is required";
              }
              if(!empty($_POST['city'])){
                $city = test_input($_POST["city"]);
                if (!preg_match("/^[a-zA-Z ]*$/",$city)) {
                  $cityErr = "Only letters and white space allowed";
                }
              } else {
                $city = '';
                $cityErr = "City is required";
              }
              if(!empty($_POST['postcode'])){
                $postcode = test_input($_POST["postcode"]);
                if (!preg_match("/^[0-9]*$/",$postcode)) {
                  $postcodeErr = "Only numbers allowed";
                }
              } else {
                $postcode = '';
                $postcodeErr = "Postcode is required";
              }
              if(!empty($_POST['phone'])){
                $phone = test_input($_POST["phone"]);
                if (!preg_match("/^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/",$phone)) {
                  $phoneErr = "Only numbers allowed";
                }
              } else {
                $phone = '';
                $phoneErr = "Please enter a contact number";
              }

              if(!empty($_POST['email'])){
                $email = test_input($_POST["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                  $emailErr = "Invalid email format";
                }
              } else {
                  $emailErr = "Email is required";
              }

              if(!empty($_POST['ew_id'])){
                $ew_id = test_input($_POST["ew_id"]);
                if (!preg_match("/^[a-zA-Z0-9 ]*$/",$ew_id)) {
                  $ew_idErr = "Only letters and numbers allowed";
                }
              } else {
                $ew_id = '';
                $ew_idErr = 'Please enter your EW ID number from the <a href="https://ewrb.ewr.govt.nz/publicregister/search.aspx" rel="noopener noreferrer" target="_blank">electrical workers register</a> ';
              }

              if(!empty($_POST['ew_reg'])){
                $ew_reg = test_input($_POST["ew_reg"]);
                if (!preg_match("/^[a-zA-Z0-9 \-*]*$/",$ew_reg)) {
                  $ew_regErr = "Registration number invalid";
                }
              } else {
                $ew_reg = '';
                $ew_regErr = 'Please enter your registration number from the <a  href="https://ewrb.ewr.govt.nz/publicregister/search.aspx" rel="noopener noreferrer" target="_blank">electrical workers register</a> ';
              }

              $reg_type = !empty($_POST['reg_type'])?$_POST['reg_type']:'';

              $course = !empty($_POST['course'])?$_POST['course']:'';
              if(!empty($_POST['course'])){
                $course = test_input($_POST['course']);
              } else {
                $course = '';
                $courseErr = "Please select a course type";
              }

              $ewrb_complete = !empty($_POST['ewrb_complete'])?$_POST['ewrb_complete']:'';
              
              if($first_nameErr == null && $last_nameErr == null && $addressErr == null && $cityErr == null && $postcodeErr == null && $phoneErr == null && $emailErr == null && $ew_idErr == null && $ew_regErr == null && $reg_typeErr == null && $courseErr == null):
    						
                $subject = "New enrolment from: " . $first_name;
                $htmlContent = "
                    <h1>Enrolment details</h1>
                    <p><b>first_name: </b>".$first_name."</p>
                    <p><b>last_name: </b>".$last_name."</p>
                    <p><b>address: </b>".$address."</p>
                    <p><b>city: </b>".$city."</p>
                    <p><b>postcode: </b>".$postcode."</p>
                    <p><b>phone: </b>".$phone."</p>
                    <p><b>email: </b>".$email."</p>
                    <p><b>ew_id: </b>".$ew_id."</p>
                    <p><b>ew_reg: </b>".$ew_reg."</p>
                    <p><b>reg_type: </b>".$reg_type."</p>
                    <p><b>course: </b>".$course."</p>
                    <p><b>e-learning status: </b>".$ewrb_complete."</p>
                ";

                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                // More headers
                $headers .= "From: Vidtech Services <". FROM .">\r\n";
                //send email
                @mail(EMAIL,$subject,$htmlContent,$headers);
                

                $succMsg = 'Your contact request have submitted successfully.';
              else:
                $errMsg = 'Please fill in all the fields';
              endif;
          else:
              $errMsg = 'Robot verification failed, please try again.';
          endif;
      else:
          $errMsg = 'Please click on the reCAPTCHA box.';
      endif;
  else:
      $errMsg = '';
      $succMsg = '';
  endif;
}

if($succMsg != null):
    header("Location: /contact/success/");
endif;

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
