<?php
$source = 'C:\Users\asusx\.gemini\antigravity\brain\f7b79b5d-4a81-4124-b25d-fb1c619acac7\quiz_system_logo_1775377406557.png';
$destinationDir = __DIR__ . '/images';
$destination = $destinationDir . '/logo.png';

if (!is_dir($destinationDir)) {
    mkdir($destinationDir, 0777, true);
}

if (file_exists($source)) {
    if (copy($source, $destination)) {
        echo "Logo copied to $destination successfully.";
    } else {
        echo "Failed to copy logo.";
    }
} else {
    echo "Source file does not exist: $source";
}
