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
    $PONumber = $_GET['PONumber'];

    $count_query = "SELECT COUNT(*) as total FROM purchaseOrderItem WHERE PONumber = '$PONumber'";
    $count_result = mysqli_query($connect, $count_query);
    $count_row = mysqli_fetch_assoc($count_result);
    $total_records = $count_row['total'];

    if($total_records > 0){
        $purchase_query = "SELECT A1.PONumber, A1.PODate, A1.POSupplier, A2.supplier_pic_name, A1.POPayment, A1.POOrigin, A1.POStatus, A1.POType, A1.POShipment, A3.PO_Status_Name, A1.InsertDt, A1.POShippingMarks, A1.PORemarks, A4.term_name, A1.POTerm, A1.POCurrency, A2.supplier_name, A5.shipment_name, A6.origin_name, A7.payment_name, A8.currency_name, A1.InsertBy
            FROM purchaseOrder A1
            LEFT JOIN supplier A2 ON A1.POSupplier = A2.supplier_id
            LEFT JOIN purchaseStatus A3 ON A1.POStatus = A3.PO_Status_ID
            LEFT JOIN term A4 ON A1.POTerm = A4.term_id
            LEFT JOIN shipment A5 ON A1.POShipment = A5.shipment_id
            LEFT JOIN origin A6 ON A1.POOrigin = A6.origin_id
            LEFT JOIN payment A7 ON A1.POPayment = A7.payment_id
            LEFT JOIN currency A8 ON A1.POCurrency = A8.currency_id
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
                    'InsertDt' => $purchase_row['InsertDt'],
                    'InsertBy' => $purchase_row['InsertBy'],
                    'SupplierName' => $purchase_row['supplier_name'],
                    'ShipmentName' => $purchase_row['shipment_name'],
                    'OriginName' => $purchase_row['origin_name'],
                    'PaymentName' => $purchase_row['payment_name'],
                    'CurrencyName' => $purchase_row['currency_name'],
                )
            );
        }
    } else {

    }

    function fetchItems($connect, $PONumber, $limit, $offset){
        $sales_item_query = "SELECT *
            FROM purchaseOrderItem
            WHERE PONumber = '$PONumber' 
            ORDER BY POProductName 
            LIMIT $limit OFFSET $offset;";

        $sales_item_result = mysqli_query($connect, $sales_item_query);
        $sales_array = array();

        if (mysqli_num_rows($sales_item_result) > 0) {
            while ($sales_item_row = mysqli_fetch_array($sales_item_result)) {
                array_push(
                    $sales_array,
                    array(
                        'POProductName' => $sales_item_row['POProductName'],
                        'POQuantity' => $sales_item_row['POQuantity'],
                        'POPackagingSize' => $sales_item_row['POPackagingSize'],
                        'POUnitPrice' => $sales_item_row['POUnitPrice']
                    )
                );
            }
        } else {
            array_push(
                $sales_array,
                array(
                    'POProductName' => NULL,
                    'POQuantity' => NULL,
                    'POPackagingSize' => NULL,
                    'POUnitPrice' => NULL
                )
            );
        }

        return $sales_array;
    }

    // Fetch items
    $sales_items = fetchItems($connect, $PONumber, $total_records, 0);

    echo json_encode(
        array(
            'StatusCode' => 200,
            'Status' => 'Success',
            'TotalRecords' => $total_records,
            'Data' => [
                'Details' => $purchase_array,
                'Items' => $sales_items
            ]
        )
    );

    // $purchase_query = "SELECT A1.PONumber, A1.PODate, A1.POSupplier, A2.supplier_pic_name, A1.POPayment, A1.POOrigin, A1.POStatus, A1.POType, A1.POShipment, A3.PO_Status_Name, A1.InsertDt, A1.POShippingMarks, A1.PORemarks, A4.term_name, A1.POTerm, A1.POCurrency, A2.supplier_name, A5.shipment_name, A6.origin_name, A7.payment_name, A8.currency_name, A1.InsertBy
    // FROM purchaseOrder A1
    // LEFT JOIN supplier A2 ON A1.POSupplier = A2.supplier_id
    // LEFT JOIN purchaseStatus A3 ON A1.POStatus = A3.PO_Status_ID
    // LEFT JOIN term A4 ON A1.POTerm = A4.term_id
    // LEFT JOIN shipment A5 ON A1.POShipment = A5.shipment_id
    // LEFT JOIN origin A6 ON A1.POOrigin = A6.origin_id
    // LEFT JOIN payment A7 ON A1.POPayment = A7.payment_id
    // LEFT JOIN currency A8 ON A1.POCurrency = A8.currency_id
    // WHERE A1.PONumber = '$PONumber';";

    // $purchase_result = mysqli_query($connect, $purchase_query);
    // $purchase_array = array();
    // while($purchase_row = mysqli_fetch_array($purchase_result)){
    //     array_push(
    //         $purchase_array,
    //         array(
    //             'PONumber' => $purchase_row['PONumber'],
    //             'PODate' => $purchase_row['PODate'],
    //             'POSupplier' => $purchase_row['POSupplier'],
    //             'POShipment' => $purchase_row['POShipment'],
    //             'POSupplierPIC' => $purchase_row['supplier_pic_name'],
    //             'POPayment' => $purchase_row['POPayment'],
    //             'POOrigin' => $purchase_row['POOrigin'],
    //             'POStatus' => $purchase_row['POStatus'],
    //             'POStatusName' => $purchase_row['PO_Status_Name'],
    //             'POType' => $purchase_row['POType'],
    //             'POShippingMarks' => $purchase_row['POShippingMarks'],
    //             'PORemarks' => $purchase_row['PORemarks'],
    //             'term_name' => $purchase_row['term_name'],
    //             'POTerm' => $purchase_row['POTerm'],
    //             'POCurrency' => $purchase_row['POCurrency'],
    //             'InsertDt' => $purchase_row['InsertDt'],
    //             'InsertBy' => $purchase_row['InsertBy'],
    //             'SupplierName' => $purchase_row['supplier_name'],
    //             'ShipmentName' => $purchase_row['shipment_name'],
    //             'OriginName' => $purchase_row['origin_name'],
    //             'PaymentName' => $purchase_row['payment_name'],
    //             'CurrencyName' => $purchase_row['currency_name'],
    //         )
    //     );
    // }

    // $purchase_item_query_one = "SELECT * FROM purchaseOrderItem WHERE PONumber = '$PONumber' ORDER BY POProductName LIMIT 1;";
    // $purchase_item_result_one = mysqli_query($connect, $purchase_item_query_one);
    // $purchase_array_one = array();

    // if(mysqli_num_rows($purchase_item_result_one) > 0){
    //     while($purchase_item_row_one = mysqli_fetch_array($purchase_item_result_one)){
    //         array_push(
    //             $purchase_array_one,
    //             array(
    //                 'POProductName1' => $purchase_item_row_one['POProductName'],
    //                 'POQuantity1' => $purchase_item_row_one['POQuantity'],
    //                 'POPackagingSize1' => $purchase_item_row_one['POPackagingSize'],
    //                 'POUnitPrice1' => $purchase_item_row_one['POUnitPrice'],
    //             )
    //         );
    //     }
    // } else {
    //     array_push(
    //         $purchase_array_one,
    //         array(
    //             'POProductName1' => NULL,
    //             'POQuantity1' => NULL,
    //             'POPackagingSize1' => NULL,
    //             'POUnitPrice1' => NULL,
    //         )
    //     );
    // }

    // $purchase_item_query_two = "SELECT * FROM purchaseOrderItem WHERE PONumber = '$PONumber' ORDER BY POProductName LIMIT 1 OFFSET 1;";
    // $purchase_item_result_two = mysqli_query($connect, $purchase_item_query_two);
    // $purchase_array_two = array();

    // if(mysqli_num_rows($purchase_item_result_two) > 0){
    //     while($purchase_item_row_two = mysqli_fetch_array($purchase_item_result_two)){
    //         array_push(
    //             $purchase_array_two,
    //             array(
    //                 'POProductName2' => $purchase_item_row_two['POProductName'],
    //                 'POQuantity2' => $purchase_item_row_two['POQuantity'],
    //                 'POPackagingSize2' => $purchase_item_row_two['POPackagingSize'],
    //                 'POUnitPrice2' => $purchase_item_row_two['POUnitPrice'],
    //             )
    //         );
    //     }
    // } else {
    //     array_push(
    //         $purchase_array_two,
    //         array(
    //             'POProductName2' => NULL,
    //             'POQuantity2' => NULL,
    //             'POPackagingSize2' => NULL,
    //             'POUnitPrice2' => NULL,
    //         )
    //     );
    // }

    // $purchase_item_query_three = "SELECT * FROM purchaseOrderItem WHERE PONumber = '$PONumber' ORDER BY POProductName LIMIT 1 OFFSET 2;";
    // $purchase_item_result_three = mysqli_query($connect, $purchase_item_query_three);
    // $purchase_array_three = array();

    // if(mysqli_num_rows($purchase_item_result_three) > 0){
    //     while($purchase_item_row_three = mysqli_fetch_array($purchase_item_result_three)){
    //         array_push(
    //             $purchase_array_three,
    //             array(
    //                 'POProductName3' => $purchase_item_row_three['POProductName'],
    //                 'POQuantity3' => $purchase_item_row_three['POQuantity'],
    //                 'POPackagingSize3' => $purchase_item_row_three['POPackagingSize'],
    //                 'POUnitPrice3' => $purchase_item_row_three['POUnitPrice'],
    //             )
    //         );
    //     }
    // } else {
    //     array_push(
    //         $purchase_array_three,
    //         array(
    //             'POProductName3' => NULL,
    //             'POQuantity3' => NULL,
    //             'POPackagingSize3' => NULL,
    //             'POUnitPrice3' => NULL,
    //         )
    //     );
    // }

    // $purchase_item_query_four = "SELECT * FROM purchaseOrderItem WHERE PONumber = '$PONumber' ORDER BY POProductName LIMIT 1 OFFSET 3;";
    // $purchase_item_result_four = mysqli_query($connect, $purchase_item_query_four);
    // $purchase_array_four = array();

    // if(mysqli_num_rows($purchase_item_result_four) > 0){
    //     while($purchase_item_row_four = mysqli_fetch_array($purchase_item_result_four)){
    //         array_push(
    //             $purchase_array_four,
    //             array(
    //                 'POProductName4' => $purchase_item_row_four['POProductName'],
    //                 'POQuantity4' => $purchase_item_row_four['POQuantity'],
    //                 'POPackagingSize4' => $purchase_item_row_four['POPackagingSize'],
    //                 'POUnitPrice4' => $purchase_item_row_four['POUnitPrice'],
    //             )
    //         );
    //     }
    // } else {
    //     array_push(
    //         $purchase_array_four,
    //         array(
    //             'POProductName4' => NULL,
    //             'POQuantity4' => NULL,
    //             'POPackagingSize4' => NULL,
    //             'POUnitPrice4' => NULL,
    //         )
    //     );
    // }

    // $purchase_item_query_five = "SELECT * FROM purchaseOrderItem WHERE PONumber = '$PONumber' ORDER BY POProductName LIMIT 1 OFFSET 4;";
    // $purchase_item_result_five = mysqli_query($connect, $purchase_item_query_five);
    // $purchase_array_five = array();

    // if(mysqli_num_rows($purchase_item_result_five) > 0){
    //     while($purchase_item_row_five = mysqli_fetch_array($purchase_item_result_five)){
    //         array_push(
    //             $purchase_array_five,
    //             array(
    //                 'POProductName5' => $purchase_item_row_five['POProductName'],
    //                 'POQuantity5' => $purchase_item_row_five['POQuantity'],
    //                 'POPackagingSize5' => $purchase_item_row_five['POPackagingSize'],
    //                 'POUnitPrice5' => $purchase_item_row_five['POUnitPrice'],
    //             )
    //         );
    //     }
    // } else {
    //     array_push(
    //         $purchase_array_five,
    //         array(
    //             'POProductName5' => NULL,
    //             'POQuantity5' => NULL,
    //             'POPackagingSize5' => NULL,
    //             'POUnitPrice5' => NULL,
    //         )
    //     );
    // }

    // echo json_encode(
    //     array(
    //         'StatusCode' => 200,
    //         'Status' => 'Success',
    //         'Data' => [
    //             'Details' => $purchase_array,
    //             'Items 1' =>  $purchase_array_one,
    //             'Items 2' =>  $purchase_array_two,
    //             'Items 3' =>  $purchase_array_three,
    //             'Items 4' =>  $purchase_array_four,
    //             'Items 5' =>  $purchase_array_five
    //         ]
    //     )
    // );

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