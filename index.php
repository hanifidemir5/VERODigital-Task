<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<body>
<?php
// Step 1: Authentication Request (POST)
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.baubuddy.de/index.php/login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        "username" => "365",
        "password" => "1"
    ]),
    CURLOPT_HTTPHEADER => [
        "Authorization: Basic QVBJX0V4cGxvcmVyOjEyMzQ1NmlzQUxhbWVQYXNz",
        "Content-Type: application/json"
    ],
]);

$auth_response = curl_exec($curl);
$auth_err = curl_error($curl);
curl_close($curl);

if ($auth_err) {
    echo "Authentication cURL Error #:" . $auth_err;
    exit;
}

// Decode the authentication response to get the access token
$auth_data = json_decode($auth_response, true);

if (isset($auth_data["oauth"]["access_token"])) {
    $token = $auth_data["oauth"]["access_token"];
} else {
    echo "Failed to retrieve access token.";
    exit;
}

// Step 2: Data Request (GET) using the token
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.baubuddy.de/dev/index.php/v1/tasks/select",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $token,
        "Content-Type: application/json"
    ],
]);

$data_response = curl_exec($curl);
$data_err = curl_error($curl);
curl_close($curl);
?>

<?php foreach (json_decode($data_response, true) as $data): ?>
    <h1>
        <?php echo htmlspecialchars($data['task']); // Use htmlspecialchars to prevent XSS ?>
    </h1>
<?php endforeach; ?>
</body>
</html>