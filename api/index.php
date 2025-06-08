<?php

$source = __DIR__ . '/../database/database.sqlite';
$target = '/tmp/database.sqlite';

if (file_exists($source) && !file_exists($target)) {
    copy($source, $target);
    chmod($target, 0666);
}
if (!file_exists('/tmp/cache')) {
    mkdir('/tmp/cache', 0777, true);
}
if (!file_exists('/tmp/sessions')) {
    mkdir('/tmp/sessions', 0777, true);
}
// Forward Vercel requests to normal index.php
require __DIR__ . '/../public/index.php';