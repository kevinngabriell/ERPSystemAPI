<?php
//Header access is required
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

//Display error message
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//Connection access
require_once('../../connection/connection.php');

//Checking call API method
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $uom_name = $_POST['uom_name'];

    $query = "INSERT INTO unitOfMeasure (uomID, uomName) VALUES (UUID(), '$uom_name');";

    if(mysqli_query($connect, $query)){
        http_response_code(200);
        echo json_encode(
            array(
                "StatusCode" => 200,
                'Status' => 'Success',
                "message" => "Success: Data inserted successfully"
            )
        );
    } else {
        http_response_code(500);
        echo json_encode(
            array(
                "StatusCode" => 500,
                'Status' => 'Error',
                "message" => "Error: Unable to insert data - " . mysqli_error($connect)
            )
        );
    }

} else {
    http_response_code(404);
    echo json_encode(
        array(
            "StatusCode" => 404,
            'Status' => 'Error',
            "message" => "Error: Invalid method. Only GET requests are allowed."
        )
    );
}