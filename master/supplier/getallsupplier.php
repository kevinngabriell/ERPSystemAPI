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
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $company = $_GET['company'];

    $supplier_query = "SELECT A1.supplier_id, A1.supplier_name, A1.supplier_phone, A2.origin_name FROM supplier A1 JOIN origin A2 ON A2.origin_id = A1.supplier_origin WHERE A1.company = '$company' ORDER BY A1.supplier_name ASC;";
    $supplier_result = mysqli_query($connect, $supplier_query);

    $supplier_array = array();
    while($supplier_row = mysqli_fetch_array($supplier_result)){
        array_push(
            $supplier_array,
            array(
                'supplier_id' => $supplier_row['supplier_id'],
                'supplier_name' => $supplier_row['supplier_name'],
                'supplier_phone' => $supplier_row['supplier_phone'],
                'origin_name' => $supplier_row['origin_name']
            )
        );
    }

    if($supplier_array){
        echo json_encode(
            array(
                'StatusCode' => 200,
                'Status' => 'Success',
                'Data' => $supplier_array
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

?>