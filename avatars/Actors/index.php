<?php

// Try to handle it with the upper level index.php.  (it should know what to do.)
if (file_exists(__DIR__ . '/index.php')) {
    include __DIR__ . '/index.php';
} else {
    exit;
}
