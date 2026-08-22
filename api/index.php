<?php

// Vercel Serverless Entrypoint for CodeIgniter 4
// Ensure writable directory exists in /tmp for serverless environment
$writablePath = '/tmp/ci4_writable';
if (!is_dir($writablePath)) {
    @mkdir($writablePath, 0777, true);
    @mkdir($writablePath . '/cache', 0777, true);
    @mkdir($writablePath . '/session', 0777, true);
    @mkdir($writablePath . '/logs', 0777, true);
    @mkdir($writablePath . '/debugbar', 0777, true);
}

// Forward to public/index.php
require __DIR__ . '/../public/index.php';
