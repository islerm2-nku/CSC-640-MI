<?php
header('Content-Type: application/json');
echo json_encode([
    'FILES' => $_FILES,
    'POST' => $_POST,
    'SERVER' => [
        'CONTENT_TYPE' => $_SERVER['CONTENT_TYPE'] ?? null,
        'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'] ?? null,
    ],
], JSON_PRETTY_PRINT);