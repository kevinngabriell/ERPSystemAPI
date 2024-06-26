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
    $customer_id = $_GET['customer_id'];

    $query = "SELECT A1.invoiceNumber, A1.invoiceDate, SUM(A2.productQuantity * A2.unitPrice + A2.tax) AS total, A3.company_name
                FROM salesInvoice A1
                LEFT JOIN salesInvoiceItem A2 ON A1.invoiceNumber = A2.SONumber
                LEFT JOIN customer A3 ON A1.customerID = A3.company_id
                LEFT JOIN salesOrder A4 ON A1.invoiceNumber = A4.SONumber
                WHERE A1.customerID = '$customer_id' AND A4.SOStatus != '5cf82c94-2ef5-11ef-9'
                GROUP BY A1.invoiceNumber, A1.invoiceDate, A3.company_name
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
                'company_name' => $row['company_name'],
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