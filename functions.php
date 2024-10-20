<?php
// namespace Icinga\Blog;

// use Cron\CronExpression;
// use DateTime;
// use React\EventLoop\Loop;

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
    $jsonFilePath = 'data/tasks.json'; // Path to the JSON file

    // Fetch new data from the API
    $data = getAPIData();

    $jsonFilePath = 'data/tasks.json';

    file_put_contents($jsonFilePath, json_encode($data, JSON_PRETTY_PRINT));
    return $data; // Return the newly fetched data
}

// Schedule the fetchAndSaveData function to run every hour
// function schedule(callable $task, CronExpression $cron): void {
//     $now = new DateTime();
//     if ($cron->isDue($now)) {
//         Loop::futureTick($task);
//     }

//     $schedule = function () use (&$schedule, $task, $cron) {
//         $task();
//         $now = new DateTime();
//         $nextDue = $cron->getNextRunDate($now);
//         Loop::addTimer($nextDue->getTimestamp() - $now->getTimestamp(), $schedule);
//     };

//     $nextDue = $cron->getNextRunDate($now);
//     Loop::addTimer($nextDue->getTimestamp() - $now->getTimestamp(), $schedule);
// }

// // Schedule the fetchAndSaveData function to run every hour
// $cron = new CronExpression('0 * * * *'); // Run at the start of every hour
// $task = function () {
//     $data = fetchAndSaveData();
//     echo date('Y-m-d H:i:s') . ": Fetched and saved data.\n";
// };

// // Schedule the task
// schedule($task, $cron);

// // Start the event loop
// Loop::run();
