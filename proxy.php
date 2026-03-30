<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Méthode non autorisée"]);
    exit();
}

define('HF_API_KEY', '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'); // va sur [huggingface.co](https://huggingface.co/settings/tokens)

$body = file_get_contents("php://input");

$ch = curl_init("[api-inference.huggingface.co](https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct)");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $body,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . HF_API_KEY,
        "Content-Type: application/json"
    ]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($httpCode);
echo $response;
