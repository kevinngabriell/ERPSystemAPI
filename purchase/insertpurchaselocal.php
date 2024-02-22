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
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $purchase_order_number = $_POST['purchase_order_number'];
    $purchase_order_date = $_POST['purchase_order_date'];
    $purchase_order_supplier = $_POST['purchase_order_supplier'];
    $purchase_order_shipment = $_POST['purchase_order_shipment'];
    $purchase_order_payment = $_POST['purchase_order_payment'];
    $purchase_order_origin = $_POST['purchase_order_origin'];
    $purchase_order_status = $_POST['purchase_order_status'];
    $purchase_order_type = $_POST['purchase_order_type'];
    $purchase_order_currency = $_POST['purchase_order_currency'];
    $insert_by = $_POST['insert_by'];
    $currentDateTime = new DateTime();
    $indonesiaTimeZone = new DateTimeZone('Asia/Jakarta');
    $currentDateTime->setTimezone($indonesiaTimeZone);
    $currentDateTimeString = $currentDateTime->format("Y-m-d H:i:s");

    $insert_purchase_local_query = "INSERT IGNORE INTO purchaseOrder (PONumber, PODate, POSupplier, POShipment, POPayment, POOrigin, POStatus , POType, POCurrency, InsertBy, InsertDt) VALUES ('$purchase_order_number','$purchase_order_date', '$purchase_order_supplier', '$purchase_order_shipment', '$purchase_order_payment', '$purchase_order_origin', '$purchase_order_status', '$purchase_order_type', '$purchase_order_currency', '$insert_by', '$currentDateTimeString');";
    
    if(mysqli_query($connect, $insert_purchase_local_query)){
        
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
                "message" => "Error: Unable to insert data to purchaseOrder table - " . mysqli_error($connect)
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
?>