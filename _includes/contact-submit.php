<?php
//Email to send contact too
define("EMAIL", "contact@localhost");
//SMTP authenticated address to send emails from
define("FROM", "webadmin@localhost");

$auto_reply = FALSE;

$nameErr = $messageErr = $emailErr = "";
$name = $message = $email = "";
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
              $_POST['name'] = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
              $_POST['message'] = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
              $_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);


              //Save completed fields and test against regex. Load error message if not.
              if(!empty($_POST['name'])){
                $name = test_input($_POST["name"]);
                if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
                  $nameErr = "Only letters and white space allowed";
                }
              } else {
                $name = '';
                $nameErr = "Name is required";
              }

              if(!empty($_POST['message'])){
                $message = test_input($_POST["message"]);
                if (!preg_match("/^[a-zA-Z0-9 .?&':,-]*$/",$message)) {
                  $messageErr = "Only letters, numbers and white space allowed";
                }
              } else {
                $message = '';
                $messageErr = "Please complete your message";
              }

              if(!empty($_POST['email'])){
                $email = test_input($_POST["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                  $emailErr = "Invalid email format";
                }
              } else {
                  $emailErr = "Email is required";
              }

              if($nameErr == null && $messageErr == null && $emailErr == null):

                $subject = "Website query: " . $name;
                $htmlContent = "
                    <h1>Query details</h1>
                    <p><b>name: </b>".$name."</p>
                    <p><b>email: </b>".$email."</p>
                    <p><b>message: </b>".$message."</p>

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
