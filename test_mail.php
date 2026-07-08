<?php
require __DIR__ . '/config/mail.php';

var_dump(sendMail(
    'email_pribadi@gmail.com',
    'TES OTP',
    'OTP TEST 123456'
));
