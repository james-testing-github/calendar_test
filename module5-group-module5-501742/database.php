<?php

$mysqli = new mysqli('localhost', 'calendar_admin', 'calendar', 'calendar');

                    /// Handles any login errors. 
                    if ($mysqli->connect_errno) {
                        printf("Connection Failed: %s\n", $mysqli->connect_error);
                        exit;
                    }