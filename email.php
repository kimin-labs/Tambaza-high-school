
<?php
// email.php - simple, safer contact form handler
// - Accepts POST from the contact form
// - Sends an HTML email to kiminsalmin169@gmail.com
// - Redirects back to the form with success or error flag

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

// Helper: trim and null-coalesce
$raw_name    = isset($_POST['name'])    ? trim($_POST['name'])    : '';
$raw_email   = isset($_POST['email'])   ? trim($_POST['email'])   : '';
$raw_phone   = isset($_POST['phone'])   ? trim($_POST['phone'])   : '';
$raw_message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Basic required validation
$errors = [];
if ($raw_name === '')  $errors[] = 'Name is required';
if ($raw_email === '') $errors[] = 'Email is required';
if ($raw_message === '') $errors[] = 'Message is required';

// Validate email format
if ($raw_email !== '' && !filter_var($raw_email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email address';
}

// Prevent header injection: disallow CR or LF in header fields (name, email, phone)
$bad_chars_pattern = "/[\r\n]/";
foreach (['raw_name' => $raw_name, 'raw_email' => $raw_email, 'raw_phone' => $raw_phone] as $k => $v) {
    if (preg_match($bad_chars_pattern, $v)) {
        $errors[] = 'Invalid input detected';
        break;
    }
}

if (!empty($errors)) {
    // Redirect back with an error flag. You can expand to include messages in query if needed.
    header('Location: contact1.html?success=0');
    exit;
}

// Sanitize & prepare content
$name    = htmlspecialchars($raw_name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$email   = $raw_email; // keep raw for Reply-To after validation
$phone   = htmlspecialchars($raw_phone, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$message = nl2br(htmlspecialchars($raw_message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));

// Recipient
$to = 'kiminsalmin169@gmail.com';

// Subject
$subject = "Website Contact: " . ($name !== '' ? $name : 'New Contact');

// Build HTML email body
$body  = "<html><body>";
$body .= "<h2>New contact form submission</h2>";
$body .= "<p><strong>Name:</strong> {$name}</p>";
$body .= "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
if ($phone !== '') {
    $body .= "<p><strong>Phone:</strong> {$phone}</p>";
}
$body .= "<hr>";
$body .= "<p><strong>Message:</strong></p>";
$body .= "<div>{$message}</div>";
$body .= "</body></html>";

// Proper headers
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";

// Use a fixed From address on your domain to reduce spam issues.
// Replace no-reply@yourdomain.com with a valid sender from your hosting domain.
$headers .= "From: Website Contact <no-reply@yourdomain.com>\r\n";

// Set Reply-To to the user email (safe because we validated the email and blocked CR/LF)
$headers .= "Reply-To: {$email}\r\n";

// Optionally set Return-Path (some hosts ignore this in mail())
// $headers .= "Return-Path: no-reply@yourdomain.com\r\n";

// Send email
$sent = mail($to, $subject, $body, $headers);

// Log failure for debugging if mail() returned false
if (!$sent) {
    error_log("email.php: failed to send contact form email to {$to}. Data: name={$name}, email={$email}");
    header('Location: contact1.html?success=0');
    exit;
}

// Success: redirect back (you can show a success UI based on the query param)
header('Location: contact1.html?success=1');
exit;
?>