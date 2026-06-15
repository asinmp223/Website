<?php
header("Content-Type: text/plain; charset=UTF-8");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
   http_response_code(405);
   echo "Method not allowed";
   exit;
}

function read_post_field($name) {
   $value = $_POST[$name] ?? "";
   if (!is_string($value)) {
      return "";
   }

   return trim($value);
}

$name = read_post_field("name");
$email_address = read_post_field("email");
$phone = read_post_field("phone");
$message = read_post_field("message");

// Reject header injection characters early.
$hasHeaderInjection = preg_match('/[\r\n]/', $name . $email_address . $phone) === 1;
$isValidName = preg_match('/^[\p{L}][\p{L}\s\'\-]{1,79}$/u', $name) === 1;
$isValidEmail = filter_var($email_address, FILTER_VALIDATE_EMAIL) !== false;
$isValidPhone = preg_match('/^[0-9+()\-\s]{6,25}$/', $phone) === 1;
$isValidMessage = $message !== "" && mb_strlen($message) <= 2000;

if ($hasHeaderInjection || !$isValidName || !$isValidEmail || !$isValidPhone || !$isValidMessage) {
   http_response_code(400);
   echo "Invalid input";
   exit;
}

$to = "yourname@yourdomain.com"; // Replace with real mailbox before production.
$email_subject = "Website Contact Form: " . $name;
$email_body = "You have received a new message from your website contact form.\n\n"
   . "Here are the details:\n\n"
   . "Name: " . $name . "\n\n"
   . "Email: " . $email_address . "\n\n"
   . "Phone: " . $phone . "\n\n"
   . "Message:\n" . $message;
$headers = "From: noreply@yourdomain.com\r\n";
$headers .= "Reply-To: " . $email_address . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8";

$mailSent = mail($to, $email_subject, $email_body, $headers);
if (!$mailSent) {
   http_response_code(500);
   echo "Mail delivery failed";
   exit;
}

http_response_code(200);
echo "OK";
exit;
?>
