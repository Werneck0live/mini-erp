<?php

return [
    'smtp_host' => getenv('SMTP_HOST') ?: 'sandbox.smtp.mailtrap.io',
    'smtp_port' => getenv('SMTP_PORT') ?: 587,
    'smtp_charset' => getenv('SMTP_CHARTSET') ?: 'UTF-8',
    'smtp_username' => getenv('SMTP_USER') ?: 'f5300501c790c0',
    'smtp_password' => getenv('SMTP_PASS') ?: '7787f68eab3ec7',
    'smtp_secure' => getenv('SMTP_SECURE') ?: '', // tls ou ''
    'from_email' => getenv('SMTP_FROM_EMAIL') ?: '4c7f8dbf34-021a3a+user1@inbox.mailtrap.io',
    'from_name' => getenv('SMTP_FROM_NAME') ?: '',
];

?>