<?php
$file = __DIR__ . '/../bootstrap/cache/routes-v7.php';
if (file_exists($file)) {
    unlink($file);
    echo "Deleted routes cache.";
} else {
    echo "Routes cache does not exist.";
}
