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

    $query = "SELECT A2.supplier_name, A1.invoiceNumber, A1.invoiceDate, A1.InsertDt, A1.PONumber, A4.PO_Status_Name
    FROM purchaseInvoice A1
    LEFT JOIN supplier A2 ON A1.supplier = A2.supplier_id
    LEFT JOIN purchaseOrder A3 ON A1.PONumber = A3.PONumber
    LEFT JOIN purchaseStatus A4 ON A3.POStatus = A4.PO_Status_ID
    ORDER BY A1.InsertDt DESC;";

    $result = mysqli_query($connect, $query); 
    $array = array();
    while($row = mysqli_fetch_array($result)){
        array_push(
            $array,
            array(
                'supplier_name' => $row['supplier_name'],
                'invoiceDate' => $row['invoiceDate'],
                'invoiceNumber' => $row['invoiceNumber'],
                'PONumber' => $row['PONumber'],
                'PO_Status_Name' => $row['PO_Status_Name']
            )
        );
    }

} else {
    http_response_code(404);
    echo json_encode(
        array(
            "StatusCode" => 404,
            'Status' => 'Error',
            "message" => "Error: API not found"
        )
    );
}