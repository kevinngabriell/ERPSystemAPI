<?php
// Header access is required
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Display error message
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

// Connection access
require_once('../connection/connection.php');

// Checking call API method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $PONumber = $_GET['PONumber'];

    $purchase_query = "SELECT A1.PONumber, A1.PODate, A1.POSupplier, A2.supplier_pic_name, A1.POPayment, A1.POOrigin, A1.POStatus, A1.POType, A1.POShipment, A3.PO_Status_Name, A1.InsertDt, A1.POShippingMarks, A1.PORemarks, A4.term_name, A1.POTerm, A1.POCurrency
    FROM purchaseOrder A1
    JOIN supplier A2 ON A1.POSupplier = A2.supplier_id
    JOIN purchaseStatus A3 ON A1.POStatus = A3.PO_Status_ID
    JOIN term A4 ON A1.POTerm = A4.term_id
    WHERE A1.PONumber = '$PONumber';";

    $purchase_result = mysqli_query($connect, $purchase_query);
    $purchase_array = array();
    while($purchase_row = mysqli_fetch_array($purchase_result)){
        array_push(
            $purchase_array,
            array(
                'PONumber' => $purchase_row['PONumber'],
                'PODate' => $purchase_row['PODate'],
                'POSupplier' => $purchase_row['POSupplier'],
                'POShipment' => $purchase_row['POShipment'],
                'POSupplierPIC' => $purchase_row['supplier_pic_name'],
                'POPayment' => $purchase_row['POPayment'],
                'POOrigin' => $purchase_row['POOrigin'],
                'POStatus' => $purchase_row['POStatus'],
                'POStatusName' => $purchase_row['PO_Status_Name'],
                'POType' => $purchase_row['POType'],
                'POShippingMarks' => $purchase_row['POShippingMarks'],
                'PORemarks' => $purchase_row['PORemarks'],
                'term_name' => $purchase_row['term_name'],
                'POTerm' => $purchase_row['POTerm'],
                'POCurrency' => $purchase_row['POCurrency'],
                'InsertDt' => $purchase_row['InsertDt']
            )
        );
    }

    $purchase_item_query_one = "SELECT * FROM purchaseOrderItem WHERE PONumber = '$PONumber' ORDER BY POProductName LIMIT 1;";
    $purchase_item_result_one = mysqli_query($connect, $purchase_item_query_one);
    $purchase_array_one = array();

    if(mysqli_num_rows($purchase_item_result_one) > 0){
        while($purchase_item_row_one = mysqli_fetch_array($purchase_item_result_one)){
            array_push(
                $purchase_array_one,
                array(
                    'POProductNameOne' => $purchase_item_row_one['POProductName'],
                    'POQuantityOne' => $purchase_item_row_one['POQuantity'],
                    'POPackagingSizeOne' => $purchase_item_row_one['POPackagingSize'],
                    'POUnitPriceOne' => $purchase_item_row_one['POUnitPrice'],
                )
            );
        }
    } else {
        array_push(
            $purchase_array_one,
            array(
                'POProductNameOne' => NULL,
                'POQuantityOne' => NULL,
                'POPackagingSizeOne' => NULL,
                'POUnitPriceOne' => NULL,
            )
        );
    }

    $purchase_item_query_two = "SELECT * FROM purchaseOrderItem WHERE PONumber = '$PONumber' ORDER BY POProductName LIMIT 1 OFFSET 1;";
    $purchase_item_result_two = mysqli_query($connect, $purchase_item_query_two);
    $purchase_array_two = array();

    if(mysqli_num_rows($purchase_item_result_two) > 0){
        while($purchase_item_row_two = mysqli_fetch_array($purchase_item_result_two)){
            array_push(
                $purchase_array_two,
                array(
                    'POProductNameTwo' => $purchase_item_row_two['POProductName'],
                    'POQuantityTwo' => $purchase_item_row_two['POQuantity'],
                    'POPackagingSizeTwo' => $purchase_item_row_two['POPackagingSize'],
                    'POUnitPriceTwo' => $purchase_item_row_two['POUnitPrice'],
                )
            );
        }
    } else {
        array_push(
            $purchase_array_two,
            array(
                'POProductNameTwo' => NULL,
                'POQuantityTwo' => NULL,
                'POPackagingSizeTwo' => NULL,
                'POUnitPriceTwo' => NULL,
            )
        );
    }

    $purchase_item_query_three = "SELECT * FROM purchaseOrderItem WHERE PONumber = '$PONumber' ORDER BY POProductName LIMIT 1 OFFSET 2;";
    $purchase_item_result_three = mysqli_query($connect, $purchase_item_query_three);
    $purchase_array_three = array();

    if(mysqli_num_rows($purchase_item_result_three) > 0){
        while($purchase_item_row_three = mysqli_fetch_array($purchase_item_result_three)){
            array_push(
                $purchase_array_three,
                array(
                    'POProductNameThree' => $purchase_item_row_three['POProductName'],
                    'POQuantityThree' => $purchase_item_row_three['POQuantity'],
                    'POPackagingSizeThree' => $purchase_item_row_three['POPackagingSize'],
                    'POUnitPriceThree' => $purchase_item_row_three['POUnitPrice'],
                )
            );
        }
    } else {
        array_push(
            $purchase_array_three,
            array(
                'POProductNameThree' => NULL,
                'POQuantityThree' => NULL,
                'POPackagingSizeThree' => NULL,
                'POUnitPriceThree' => NULL,
            )
        );
    }

    $purchase_item_query_four = "SELECT * FROM purchaseOrderItem WHERE PONumber = '$PONumber' ORDER BY POProductName LIMIT 1 OFFSET 3;";
    $purchase_item_result_four = mysqli_query($connect, $purchase_item_query_four);
    $purchase_array_four = array();

    if(mysqli_num_rows($purchase_item_result_four) > 0){
        while($purchase_item_row_four = mysqli_fetch_array($purchase_item_result_four)){
            array_push(
                $purchase_array_four,
                array(
                    'POProductNameFour' => $purchase_item_row_four['POProductName'],
                    'POQuantityFour' => $purchase_item_row_four['POQuantity'],
                    'POPackagingSizeFour' => $purchase_item_row_four['POPackagingSize'],
                    'POUnitPriceFour' => $purchase_item_row_four['POUnitPrice'],
                )
            );
        }
    } else {
        array_push(
            $purchase_array_four,
            array(
                'POProductNameFour' => NULL,
                'POQuantityFour' => NULL,
                'POPackagingSizeFour' => NULL,
                'POUnitPriceFour' => NULL,
            )
        );
    }

    $purchase_item_query_five = "SELECT * FROM purchaseOrderItem WHERE PONumber = '$PONumber' ORDER BY POProductName LIMIT 1 OFFSET 4;";
    $purchase_item_result_five = mysqli_query($connect, $purchase_item_query_five);
    $purchase_array_five = array();

    if(mysqli_num_rows($purchase_item_result_five) > 0){
        while($purchase_item_row_five = mysqli_fetch_array($purchase_item_result_five)){
            array_push(
                $purchase_array_five,
                array(
                    'POProductNameFive' => $purchase_item_row_five['POProductName'],
                    'POQuantityFive' => $purchase_item_row_five['POQuantity'],
                    'POPackagingSizeFive' => $purchase_item_row_five['POPackagingSize'],
                    'POUnitPriceFive' => $purchase_item_row_five['POUnitPrice'],
                )
            );
        }
    } else {
        array_push(
            $purchase_array_five,
            array(
                'POProductNameFive' => NULL,
                'POQuantityFive' => NULL,
                'POPackagingSizeFive' => NULL,
                'POUnitPriceFive' => NULL,
            )
        );
    }

    echo json_encode(
        array(
            'StatusCode' => 200,
            'Status' => 'Success',
            'Data' => [
                'Details' => $purchase_array,
                'Items 1' =>  $purchase_array_one,
                'Items 2' =>  $purchase_array_two,
                'Items 3' =>  $purchase_array_three,
                'Items 4' =>  $purchase_array_four,
                'Items 5' =>  $purchase_array_five
            ]
        )
    );

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