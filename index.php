<?php

// Check 'action' GET parameter and 'identifiant' and 'password' POST parameters
if (isset($_GET['action'], $_POST['identifiant'], $_POST['password'])) {

    // Get parameters
    $action = $_GET['action'];
    $identifiant = $_POST['identifiant'];
    $password = $_POST['password'];

    // Register or Login choices
    if ($action === "register") {
        
    } elseif ($action === "login") {

    }
}