<?php
header('Content-Type: application/json');

echo json_encode([
    "request_method" => $_SERVER['REQUEST_METHOD'],
    "post_data" => $_POST,
    "get_data" => $_GET
]);