<?php
    

    header("Content-Type: application/json");



    require 'database.php';

    
    //Because you are posting the data via fetch(), php has to retrieve it elsewhere.
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);   

    //Variables can be accessed as such:
    $email_input = $json_obj['email'];
    $password_input = $json_obj['password'];

    $loginattempt = $mysqli->prepare('select COUNT(*), user_id, hashed_password FROM users WHERE email=?');

    // Bind the inputted email
    $loginattempt->bind_param('s', $email_input);
    $loginattempt->execute();

    // Bind the query results
    $loginattempt->bind_result($cnt, $user_id, $pwd_hash);
    $loginattempt->fetch();

    // Compare the submitted password to the actual password hash
    if ($cnt == 1 && password_verify($password_input, $pwd_hash)) {
        // Login succeeded; update user_id & token session and redirect to home
        ini_set( 'session.cookie_httponly', 1 );

        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

        //error_log($password_input);
        echo json_encode(array(
        "status" => "Login successful!",
        // 'user_id' => $user_id,

        ));
    exit;
    } else {
        // Login failed
        echo json_encode(array(
        'status' => "Login failed"
        ));
        exit;
    }
    

?>