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
    $payment_id = $_POST['payment_id'];

    $query = "DELETE FROM payment WHERE payment_id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('s', $payment_id);
    $result = $stmt->execute();

    if($result){
        http_response_code(200);
        echo json_encode(
            array(
                'StatusCode' => 200,
                'Status' => 'Success',
                'Message' => 'Payment has been successfully deleted'
            )
        );
    } else {
        http_response_code(500);
        echo json_encode(
            array(
                'StatusCode' => 500,
                'Status' => 'Error',
                'Message' => 'Error: Payment cannot be deleted, there may be a mistake in the query'
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