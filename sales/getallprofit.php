<?php
// Header access is required
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Display error message
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Connection access
require_once('../connection/connection.php');

// Checking call API method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT A1.SalesNumber, A1.InsertDt, A2.company_name, A4.SO_Status_Name
    FROM salesProfit A1
    LEFT JOIN customer A2 ON A1.ProfitCustomer = A2.company_id
    LEFT JOIN salesOrder A3 ON A1.SalesNumber = A3.SONumber
    LEFT JOIN salesStatus A4 ON A4.SO_Status_ID = A3.SOStatus;";

    $result = mysqli_query($connect, $query);

    $array = array();
    while($row = mysqli_fetch_array($result)){
        array_push(
            $array,
            array(
                'SalesNumber' => $row['SalesNumber'],
                'InsertDt' => $row['InsertDt'],
                'company_name' => $row['company_name'],
                'SO_Status_Name' => $row['SO_Status_Name']
            )
        );
    }

    if($array){
        echo json_encode(
            array(
                'StatusCode' => 200,
                'Status' => 'Success',
                'Data' => $array
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
            "message" => "Error: Invalid method. Only GET requests are allowed."
        )
    );
}