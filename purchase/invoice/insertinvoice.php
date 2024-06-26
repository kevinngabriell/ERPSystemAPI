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
    $purchase_order_number = $_POST['purchase_order_number'];
    $purchase_order_supplier = $_POST['purchase_order_supplier'];
    $invoice_number = $_POST['invoice_number'];
    $invoice_date = $_POST['invoice_date'];
    $ship_date = $_POST['ship_date'];
    $kurs = $_POST['kurs'];
    $term = $_POST['term'];
    $username = $_POST['username'];

    $insert_by = $_POST['insert_by'];
    $currentDateTime = new DateTime();
    $indonesiaTimeZone = new DateTimeZone('Asia/Jakarta');
    $currentDateTime->setTimezone($indonesiaTimeZone);
    $currentDateTimeString = $currentDateTime->format("Y-m-d H:i:s");

    $date_parts = explode(' ', $invoice_date);
    $date_string = $date_parts[1] . ' ' . $date_parts[2] . ' ' . $date_parts[3];
    $invoice_date_obj = DateTime::createFromFormat('M d Y', $date_string);
    $formatted_invoice_date = $invoice_date_obj->format("Y-m-d");

    $date_parts_ship = explode(' ', $ship_date);
    $date_string_ship = $date_parts_ship[1] . ' ' . $date_parts_ship[2] . ' ' . $date_parts_ship[3];
    $ship_date_obj_ship = DateTime::createFromFormat('M d Y', $date_string_ship);
    $formatted_ship_date = $ship_date_obj_ship->format("Y-m-d");

    $product_length = $_POST['product_length'];

    $insert_purchase_invoice_query = "INSERT INTO purchaseInvoice (PONumber, supplier, invoiceNumber, invoiceDate, shipDate, kurs , term, insertBy, insertDt) 
    VALUES ('$purchase_order_number', '$purchase_order_supplier', '$invoice_number', '$formatted_invoice_date', '$formatted_ship_date', '$kurs', '$term' , '$username' , '$currentDateTimeString');";

    $update_query = "UPDATE purchaseOrder SET POType = 'e4376c01-1438-11ef-9' WHERE PONumber = '$purchase_order_number';";

    if(mysqli_query($connect, $insert_purchase_receive_query) && mysqli_query($connect, $update_query)){
        
        for ($i = 1; $i <= $product_length; $i++) {
            $purchase_order_product_name = $_POST['purchase_order_product_name_' . $i];
            $purchase_order_product_quantity = $_POST['purchase_order_product_quantity_' . $i];
            $purchase_order_product_packaging_size = $_POST['purchase_order_product_packaging_size_' . $i];
            $purchase_order_product_unit_price = $_POST['purchase_order_product_unit_price_' . $i];
            
            // Change table name
            $insert_item_query = "INSERT INTO PurchaseInvoiceItem (PONumber, ProductName, Quantity, PackagingSize, UnitPrice, VAT, Total) 
            VALUES ('$purchase_order_number','$purchase_order_product_name', '$purchase_order_product_quantity', '$purchase_order_product_packaging_size', '$purchase_order_product_unit_price', 0, 0);";
            mysqli_query($connect, $insert_item_query);
        }

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