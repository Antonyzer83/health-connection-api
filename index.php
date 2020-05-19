<?php
require_once 'model.php';
require_once 'response.php';
require_once 'platform.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Headers: Accept");
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, origin, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

// Use Reponse
$response = new Response();

// Check 'action' GET parameter and 'identifiant' and 'password' POST parameters
if (isset($_GET['action'], $_SERVER['CONTENT_TYPE'])) {

    // Check POST Content Type as JSON
    $content_type = $_SERVER['CONTENT_TYPE'];
    if (stripos($content_type, 'application/json'))
        $response->badResponse('Bad Content Type');

    // Get JSON
    $body = file_get_contents("php://input");
    $object = json_decode($body, true);

    // Check JSON
    if (!is_array($object))
        $response->badResponse('Failed to decode JSON');

    // Get action parameter
    $action = $_GET['action'];

    // Use model
    $model = new Model();

    // Register or Login choices
    if ($action === "register" && isset($object['c_password'])) {

        // Check similar passwords
        if ($object['password'] === $object['c_password']) {
            // Hash current password before insert
            $new_password = password_hash($object['password'], PASSWORD_DEFAULT);

            // Check identifiant already exists in database
            $exists = $model->login($object['identifiant'], $object['role']);
            if ($exists->rowCount() === 0) {

                $result = $model->register($object['identifiant'], $new_password, $object['role']);

                // Check insert success in database
                if ($result) {
                    // Use platform
                    $platform = new Platform();
                    $result = $platform->registerPlatform($object['identifiant'], $object['password']);

                    // Check register success to IBM platform
                    if ($result)
                        $response->sendResponse('Success registry');
                    else
                        $response->badResponse('Registry failed at IBM');
                } else {
                    $response->badResponse('Registry failed at BDD');
                }
            } else {
                $response->badResponse('Identifiant already exists');
            }
        } else {
            $response->badResponse('Two different passwords');
        }
    } elseif ($action === "login") {
        $user = $model->login($object['identifiant'], $object['role']);

        // Check existing user
        if ($user = $user->fetch(PDO::FETCH_ASSOC)) {
            // Get hashed password
            $hashed_password = $user['password'];

            // Check password entry and hashed password
            if (password_verify($object['password'], $hashed_password)) {
                $response->sendResponse('Success login');
            } else {
                $response->badResponse('Login failed');
            }
        } else {
            $response->badResponse('Login failed');
        }
    } else {
        $response->badResponse('Bad action');
    }
} else {
    $response->badResponse('No action & no POST');
}