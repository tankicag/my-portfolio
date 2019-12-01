<?php 

	require("PHPMailer/PHPMailer.php");
	require("PHPMailer/SMTP.php");

	if($_SERVER["REQUEST_METHOD"] == "POST") :

		// Sender Data
		$name = trim(strip_tags($_POST["name"]));
		$email = filter_var(trim(strip_tags($_POST["email"])), FILTER_SANITIZE_EMAIL);
		$subject = trim(strip_tags($_POST["subject"]));
		$message = trim(htmlentities($_POST["message"]));

		if(empty($name) || empty($email) || empty($subject) || empty($message)) :
			// Set a 400 (bad request) response code and exit
			http_response_code(400);
			echo "Please complete the form and try again.";
			exit;
		else : 
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) : 
				// Set a 400 (bad request) response code and exit
				http_response_code(400);
				echo "Invalid email format.";
				exit;
			endif;
		endif;


		$mail = new PHPMailer\PHPMailer\PHPMailer; // Make instance PHPMailer

		// SERVER SETTINGS
		$mail->isSMTP(); // Set mailer to use SMTP           
		$mail->Host = 'smtp1.example.com'; // Set the hostname of the mail server     
	    $mail->SMTPAuth = true; // Enable SMTP authentication                
	    $mail->Username = 'user@example.com'; // SMTP username
	    $mail->Password = 'secret'; // SMTP password         
	    $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted         
	    $mail->Port = 587; // TCP port to connect to                    


	   	// RECIPIENTS
	    $mail->setFrom($email, $name); // Set who the message is to be sent from
	    $mail->addAddress('user@example.com', 'Your Name'); // Set who the message is to be sent to
	    $mail->addReplyTo($email, $name); //Set an alternative reply-to address
	    

	    // CONTENT
	    $mail->isHTML(true); // Set email format to HTML
	    $mail->Subject = $subject; // This is the subject

	    // This is the HTML message body
	    $mail->Body = "<b>Name</b>: $name<br><b>Email</b>: $email<br><br><b>Message</b>: $message"; 

	    // This is the body in plain text for non-HTML mail clients
	    $mail->AltBody = "Name: $name\nEmail: $email\n\nMessage: $message";


	    // Send the message, check for errors
	    if (!$mail->send()) :
	    	// Set a 500 (internal server error) response code.
			http_response_code(500); // Failed
		else : 
			// Set a 200 (okay) response code
			http_response_code(200);
			echo "Thank You! Your message has been sent."; // Success
		endif;

	else :
		// Not a POST request, set a 403 (forbidden) response code
		http_response_code(403);
	endif;

?>