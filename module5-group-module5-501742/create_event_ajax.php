<?php
header("Content-Type: application/json");

require 'database.php';
session_start();

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

//Gets variables from json, sessino
$user_id = $_SESSION['user_id'];
$event_title = $json_obj['title'];
$event_date = $json_obj['date'];
$event_time = $json_obj['time'];

//inserts into table
$stmt = $mysqli->prepare("INSERT into events (user_id, event_title, event_date, event_time) values (?, ?, ?, ?)");
if(!$stmt){
	echo json_encode(array(
        "success" => "Query failed!"
    ));
}
    //binds param
    $stmt->bind_param('isss', $user_id, $event_title, $event_date, $event_time);
    $stmt->execute();
    $stmt->close();
    
    //json
    echo json_encode(array(
        "status" => "Event created!",
        "user_id" => $user_id,
        "event_title" => $event_title,
        "event_date" => $event_date,
        "event_time" => $event_time
    ));



?>