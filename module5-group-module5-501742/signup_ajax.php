<?php
    

    header("Content-Type: application/json");



    require 'database.php';
    //Because you are posting the data via fetch(), php has to retrieve it elsewhere.
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);   


    $email_input = $json_obj['email'];

    $retrieveemail = $mysqli->prepare("select user_id from users where email = '" . $email_input. "'");
                    if (!$retrieveemail) {
                        echo json_encode(array(
                            "status" => "Query failed!",                        
                            ));
                            
                        // printf("Query Prep Faileed: %s\n", $mysqli->error);
                        exit;
                    } else {

                        /// If select command is valid, execute it and bind its results 
                        $retrieveemail->execute();
                        $retrieveemail->bind_result($id);
                        $retrieveemail->fetch();

                        /// Check if any matches to the email were found
                        if (isset($id)) {

                            /// Email is in use! Alert user.
                            echo json_encode(array(
                                "status" => "Email already in use!",                        
                                ));
                        } else {

                            /// Email is not in use! Retrieve entered name, email, and password
                            $firstname = $json_obj['firstname'];
                            $lastname =  $json_obj['lastname'];
                            $email =  $json_obj['email'];
                            $password = password_hash($json_obj['password'], PASSWORD_DEFAULT);

                            /// Prepare insertion
                            $adduser = $mysqli->prepare('insert into users (email, first_name, last_name, hashed_password) values (?, ?, ?, ?)');
                            if (!$adduser) {
                                printf("Query Prep Failedd: %s\n", $mysqli->error);
                                exit;
                            }

                            /// Bind email, name, and password, execute, and close. 
                            $adduser->bind_param('ssss', $email, $firstname, $lastname, $password);
                            $adduser->execute();
                            $adduser->close();

                            /// Let user know they can now login.
                            echo json_encode(array(
                                "status" => "Success! Now you can login",                        
                                ));
                        }
                    }
                
            // } else {

            //     /// A required field has been left blank! Alert the user. 
            //     echo json_encode(array(
            //         "status" => "Please enter an email and a password",                        
            //         ));
            //     }
    
    

?>