<?php
$path = '.env';
$content = file_get_contents($path);

$replacements = [
    'MAIL_MAILER=log' => 'MAIL_MAILER=smtp',
    'MAIL_PORT=2525' => 'MAIL_PORT=1025',
    'MAIL_FROM_ADDRESS="hello@example.com"' => 'MAIL_FROM_ADDRESS="admin@servicepoint.local"',
    'MAIL_FROM_NAME="${APP_NAME}"' => 'MAIL_FROM_NAME="ServicePoint"',
];

foreach ($replacements as $key => $value) {
    if (strpos($content, $key) !== false) {
        $content = str_replace($key, $value, $content);
        echo "Replaced $key\n";
    } else {
        echo "Could not find $key\n";
    }
}

file_put_contents($path, $content);
echo "Done.";
