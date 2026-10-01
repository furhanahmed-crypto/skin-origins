<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Method not allowed.']);
    exit;
}

$name = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));

$errors = [];
if ($name === '' || strlen($name) < 2) {
    $errors[] = 'Please enter your full name.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
$digits = preg_replace('/\D+/', '', $phone) ?? '';
if ($phone === '' || strlen($digits) < 10) {
    $errors[] = 'Please enter a valid phone number.';
}

if ($errors !== []) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => implode(' ', $errors)]);
    exit;
}

$logDir = SO_ROOT . '/storage';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}

$entry = [
    'received_at' => date('c'),
    'source' => 'consult-modal',
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
];

@file_put_contents(
    $logDir . '/contact-submissions.log',
    json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL,
    FILE_APPEND | LOCK_EX
);

$subject = 'New consultation request (popup) — Skin Origins';
$body = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nSource: website popup\n";
$headers = 'From: ' . SO_SITE['email'] . "\r\n" . 'Reply-To: ' . $email . "\r\n";
@mail(SO_SITE['email'], $subject, $body, $headers);

echo json_encode([
    'ok' => true,
    'message' => 'Thank you. Our team will get back to you shortly.',
]);
