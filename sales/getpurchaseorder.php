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
require_once('../connection/connection.php');

//Checking call API method
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $query = "SELECT PONumber, PODate, POSupplier, POOrigin
    FROM purchaseOrder 
    ORDER BY PONumber ASC;";

    $result = mysqli_query($connect, $query);
    $po_array = array();
    while($row = mysqli_fetch_array($result)){
        array_push(
            $po_array,
            array(
                'PONumber' => $row['PONumber'],
                'PODate' => $row['PODate'],
                'POSupplier' => $row['POSupplier'],
                'POOrigin' => $row['POOrigin']
            )
        );
    }

    if($po_array){
        echo json_encode(
            array(
                'StatusCode' => 200,
                'Status' => 'Success',
                'Data' => $po_array
            )
        );
    } else {
        http_response_code(400);
        echo json_encode(
            array(
                'StatusCode' => 400,
                'Status' => 'Error Bad Request, Result not found !'
            )
        );
    }

} else {
    http_response_code(404);
    echo json_encode(
        array(
            "StatusCode" => 404,
            'Status' => 'Error',
            "message" => "Error: Invalid method. Only POST requests are allowed."
        )
    );
}