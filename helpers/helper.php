<?php

function urlIs($value){
    return $_SERVER["REQUEST_URI"] == $value;
}

function getAuth(){
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

    $auth_data = json_decode($auth_response, true);

    // Decode the authentication response to get the access token
    if (isset($auth_data["oauth"]["access_token"])) {
    $token = $auth_data["oauth"]["access_token"];
    } else {
        echo "Failed to retrieve access token.";
        exit;
    }
    return $token;
}

function getAPIData(){

    $token = getAuth();
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

    $decoded_data = json_decode($data_response, true);
    return $decoded_data;
}

function fetchAndSaveData() {
    $jsonFilePath = 'public/src/data/tasks.json'; // Path to the JSON file
    
    // Log the JSON file path
    \Log::info('JSON file path:', ['path' => $jsonFilePath]);

    // Fetch new data from the API
    $data = getAPIData(); // Ensure this function is defined as well

    file_put_contents($jsonFilePath, json_encode($data, JSON_PRETTY_PRINT));
    return $data; // Return the newly fetched data
}
