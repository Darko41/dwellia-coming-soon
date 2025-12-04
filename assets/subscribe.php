<?php

// ============================================
// CONFIGURATION
// ============================================
$emailTo = 'noreply@iterials.com'; // CHANGE THIS!
$siteName = 'Dwellia';
// ============================================

// Start output buffering to catch any stray output
ob_start();

// Simple email validation function
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Set JSON header FIRST
header('Content-Type: application/json; charset=utf-8');

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['valid' => 0, 'message' => 'Method not allowed']);
    exit;
}

// Check if email exists
if (!isset($_POST['email']) || empty($_POST['email'])) {
    echo json_encode(['valid' => 0, 'message' => 'Unesite Vašu email adresu.']);
    exit;
}

$subscriber_email = trim($_POST['email']);

// Validate email
if (!isValidEmail($subscriber_email)) {
    echo json_encode(['valid' => 0, 'message' => 'Unesite validnu email adresu.']);
    exit;
}

// Prepare email
$subject = '[DWELLIA-FORM] Nova prijava: ' . $subscriber_email;
$body = "NOVA PRIJAVA ZA DWELLIA PLATFORMU\n";
$body .= "========================================\n\n";
$body .= "📧 Email: " . $subscriber_email . "\n";
$body .= "📅 Datum: " . date('d.m.Y H:i:s') . "\n";
$body .= "🌐 IP: " . $_SERVER['REMOTE_ADDR'] . "\n\n";
$body .= "========================================\n";
$body .= "Ovo je automatska poruka sa Dwellia Coming Soon sajta.\n";

$headers = "From: Dwellia Website <noreply@yourdomain.com>\r\n";
$headers .= "Reply-To: " . $subscriber_email . "\r\n";
$headers .= "X-Dwellia-Form: subscription\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Try to send email
try {
    $mailSent = mail($emailTo, $subject, $body, $headers);

    if ($mailSent) {
        echo json_encode([
            'valid' => 1,
            'message' => 'Hvala Vam na interesovanju! Obavestićemo Vas kada budemo spremni.'
        ]);
    } else {
        echo json_encode([
            'valid' => 0,
            'message' => 'Došlo je do greške pri slanju. Pokušajte ponovo.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'valid' => 0,
        'message' => 'Greška: ' . $e->getMessage()
    ]);
}

// Clear any output buffer
ob_end_flush();
exit;

?>