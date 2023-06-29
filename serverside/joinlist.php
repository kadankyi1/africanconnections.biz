<?php
//var_dump($_POST);
if (
  $_SERVER["REQUEST_METHOD"] == "POST" 
  && isset($_POST["wtf"]) && empty($_POST["wtf"])
  && isset($_POST["joineremail"]) && !empty($_POST["joineremail"])
  && isset($_POST["g-recaptcha-response"]) && !empty($_POST["g-recaptcha-response"])){
  session_start();
  $captchaResponse = $_POST["g-recaptcha-response"];
  
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $url = 'https://www.google.com/recaptcha/api/siteverify';
  $data = array(
      'secret' => "6LcqVDYmAAAAAGoWewmCPnccXWLjmPraRUdXYlIU",
      'response' => $captchaResponse,
  );

  // use key 'http' even if you send the request to https://...
  $options = array(
      'http' => array(
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'POST',
          'content' => http_build_query($data)
      )
  );

  $context  = stream_context_create($options);

  $result = file_get_contents($url, false, $context);
  $result = json_decode($result);

  if($result->success && $_SERVER['SERVER_NAME'] == $result->hostname) {
    $joineremail = $_POST["joineremail"];
    
    $servername = "localhost";
    $username = "african1_aclistu";
    $password = "Sk1n!sK1n.~";
    $dbname = "african1_aclist";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    } else {
      $dbinput = filter_var($joineremail, FILTER_SANITIZE_EMAIL);

      $sql = "INSERT INTO subscribers (subscriber_email) VALUES ('$dbinput')";
  
      if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
  
      $conn->close();
    }


    $to = "info@africanconnections-usa.com";
    //$to = "annodankyikwaku@gmail.com";
    $subject = "SOMEONE NEW JOINED MAILING LIST FOR AC USA WEBSITE";
    $message = "\n\n EMAIL: $joineremail";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= "From: <$to>";
    //$headers .= 'Cc: myboss@example.com' . "\r\n";
    mail($to,$subject,$message,$headers);

    echo "\n\n EMAIL SENT";
  }

}