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

    $purchase_query = "SELECT A1.PONumber, A1.PODate, A2.supplier_name, A3.shipment_name, A4.term_name, A5.payment_name, A6.origin_name, A1.POShippingMarks, A1.PORemarks, A7.PO_Status_Name, A8.PO_Type_Name, A1.InsertBy
    FROM purchaseOrder A1
    LEFT JOIN supplier A2 ON A1.POSupplier = A2.supplier_id
    LEFT JOIN shipment A3 ON A1.POShipment = A3.shipment_id
    LEFT JOIN Term A4 ON A1.POTerm = A4.term_id
    LEFT JOIN payment A5 ON A1.POPayment = A5.payment_id
    LEFT JOIN origin A6 ON A1.POOrigin = A6.origin_id
    LEFT JOIN purchaseStatus A7 ON A1.POStatus = A7.PO_Status_ID
    LEFT JOIN purchaseType A8 ON A1.POType = A8.PO_Type_ID
    WHERE A1.POType = '705da1d4-d157-11ee-8'
    ORDER BY A1.PONumber DESC;";
    $purchase_result = mysqli_query($connect, $purchase_query);

    $purchase_array = array();
    while($purchase_row = mysqli_fetch_array($purchase_result)){
        array_push(
            $purchase_array,
            array(
                'PONumber' => $purchase_row['PONumber'],
                'PODate' => $purchase_row['PODate'],
                'supplier_name' => $purchase_row['supplier_name'],
                'shipment_name' => $purchase_row['shipment_name'],
                'term_name' => $purchase_row['term_name'],
                'payment_name' => $purchase_row['payment_name'],
                'origin_name' => $purchase_row['origin_name'],
                'POShippingMarks' => $purchase_row['POShippingMarks'],
                'PORemarks' => $purchase_row['PORemarks'],
                'PO_Status_Name' => $purchase_row['PO_Status_Name'],
                'PO_Type_Name' => $purchase_row['PO_Type_Name'],
                'InsertBy' => $purchase_row['InsertBy']
            )
        );
    }

    if($purchase_array){
        echo json_encode(
            array(
                'StatusCode' => 200,
                'Status' => 'Success',
                'Data' => $purchase_array
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