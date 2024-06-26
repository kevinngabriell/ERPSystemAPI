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
    $supplier = $_GET['supplier'];

    $query = "SELECT A1.invoiceNumber, A1.invoiceDate, SUM(A2.Quantity * A2.UnitPrice) AS total, A3.supplier_name, A5.currency_name
    FROM purchaseInvoice A1
    LEFT JOIN PurchaseInvoiceItem A2 ON A1.PONumber = A2.PONumber
    LEFT JOIN supplier A3 ON A1.supplier = A3.supplier_id
    LEFT JOIN purchaseOrder A4 ON A1.PONumber = A4.PONumber
    LEFT JOIN currency A5 ON A3.supplier_currency = A5.currency_id
    WHERE A1.supplier = '$supplier' AND A4.POStatus != '4481ccb7-2ef5-11ef-9'
    GROUP BY A1.invoiceNumber, A1.invoiceDate, A3.supplier_name
    ORDER BY A1.insertDt DESC;";

    $result = mysqli_query($connect, $query);

    $array = array();
    while($row = mysqli_fetch_array($result)){
        array_push(
            $array,
            array(
                'invoiceNumber' => $row['invoiceNumber'],
                'invoiceDate' => $row['invoiceDate'],
                'total' => $row['total'],
                'supplier_name' => $row['supplier_name'],
                'currency_name' => $row['currency_name'],
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