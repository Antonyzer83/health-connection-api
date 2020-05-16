<?php
require_once 'model.php';
require_once 'response.php';
require_once 'platform.php';

// Use Reponse
$response = new Response();

// Check 'action' GET parameter and 'identifiant' and 'password' POST parameters
if (isset($_GET['action'], $_POST['identifiant'], $_POST['password'])) {

    // Get parameters
    $action = $_GET['action'];
    $identifiant = $_POST['identifiant'];
    $password = $_POST['password'];

    // Use model
    $model = new Model();

    // Register or Login choices
    if ($action === "register" && isset($_POST['conf_password'])) {
        $conf_password = $_POST['conf_password'];

        // Check similar passwords
        if ($password === $conf_password) {
            // Hash current password before insert
            $password = password_hash($password, PASSWORD_DEFAULT);

            $result = $model->register($identifiant, $password);

            // Check insert success in database
            if ($result) {
                // Use platform
                $platform = new Platform();
                $result = $platform->registerPlatform($identifiant, $password);

                // Check register success to IBM platform
                if ($result)
                    $response->sendResponse('Success registry');
                else
                    $response->sendResponse('Registry failed');
            } else {
                $response->sendResponse('Registry failed');
            }
        } else {
            $response->sendResponse('Registry failed');
        }
    } elseif ($action === "login") {
        $user = $model->login($identifiant);

        // Check existing user
        if ($user = $user->fetch(PDO::FETCH_ASSOC)) {
            // Get hashed password
            $hashed_password = $user['password'];

            // Check password entry and hashed password
            if (password_verify($password, $hashed_password)) {
                $response->sendResponse('Success login');
            } else {
                $response->sendResponse('Login failed');
            }
        } else {
            $response->sendResponse('Login failed');
        }
    } else {
        $response->sendResponse('No action');
    }
} else {
    $response->sendResponse('No action');
}