<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Honeypot check — bots fill this, humans leave it empty
if (!empty($_POST['hp_field'])) {
    // Silently succeed to fool the bot
    echo json_encode(['success' => true, 'message' => 'Thank you for your message.']);
    exit;
}

// Sanitise inputs
$name    = trim(strip_tags($_POST['your-name']    ?? ''));
$email   = trim(strip_tags($_POST['your-email']   ?? ''));
$message = trim(strip_tags($_POST['your-message'] ?? ''));

// Validate
$errors = [];

if ($name === '') {
    $errors[] = 'Name is required.';
} elseif (mb_strlen($name) > 100) {
    $errors[] = 'Name is too long.';
}

if ($email === '') {
    $errors[] = 'Email address is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

if ($message === '') {
    $errors[] = 'Message is required.';
} elseif (mb_strlen($message) > 5000) {
    $errors[] = 'Message is too long.';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// Build the email
$to      = 'info@chambers-inc.co.uk';
$subject = 'Website enquiry from ' . $name;

$body  = "You have received a new enquiry via the Chambers Inc website.\n\n";
$body .= "Name:    {$name}\n";
$body .= "Email:   {$email}\n";
$body .= "Message:\n{$message}\n";

// Headers — use the sender's address as Reply-To, not From (avoids SPF/DKIM rejection)
$headers  = "From: Chambers Inc Website <noreply@chambers-inc.co.uk>\r\n";
$headers .= "Reply-To: {$name} <{$email}>\r\n";
$headers .= "X-Mailer: PHP/" . PHP_VERSION . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($to, $subject, $body, $headers)) {
    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent. We\'ll be in touch within 24–48 hours.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Sorry, there was a problem sending your message. Please try again or call us directly.']);
}
