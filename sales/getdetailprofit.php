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
    $SONumber = $_GET['SONumber'];

    // First, count the number of records with the same salesOrderNumber
    $count_query = "SELECT COUNT(*) as total FROM salesProfitItem WHERE SalesOrderNumber = '$SONumber'";
    $count_result = mysqli_query($connect, $count_query);
    $count_row = mysqli_fetch_assoc($count_result);
    $total_records = $count_row['total'];

    if ($total_records > 0) {
        $sales_query = "SELECT A1.SalesNumber, A1.ProfitCustomer, A2.company_name, A1.InsertBy, A1.InsertDt, A4.SO_Status_Name
        FROM salesProfit A1
        LEFT JOIN customer A2 ON A1.ProfitCustomer = A2.company_id
        LEFT JOIN salesOrder A3 ON A1.SalesNumber = A3.SONumber
        LEFT JOIN salesStatus A4 ON A3.SOStatus = A4.SO_Status_ID;
        WHERE A1.SalesNumber = '$SONumber';";

        $sales_result = mysqli_query($connect, $sales_query);
        $sales_array = array();
        while($sales_row = mysqli_fetch_array($sales_result)){
            array_push(
                $sales_array,
                array(
                    'SalesNumber' => $sales_row['SalesNumber'],
                    'ProfitCustomer' => $sales_row['ProfitCustomer'],
                    'company_name' => $sales_row['company_name'],
                    'InsertBy' => $sales_row['InsertBy'],
                    'InsertDt' => $sales_row['InsertDt'],
                    'SO_Status_Name' => $sales_row['SO_Status_Name']
                )
            );
        }

        // Function to fetch items with a given offset
        function fetchItems($connect, $SONumber, $limit, $offset) {
            $sales_item_query = "SELECT PONumber, SalesOrderNumber, ProductName, Quantity, Price, LandedCost
            FROM salesProfitItem;
            WHERE SalesOrderNumber = '$SONumber' 
            ORDER BY ProductName 
            LIMIT $limit OFFSET $offset;";
            $sales_item_result = mysqli_query($connect, $sales_item_query);
            $sales_array = array();

            if (mysqli_num_rows($sales_item_result) > 0) {
                while ($sales_item_row = mysqli_fetch_array($sales_item_result)) {
                    array_push(
                        $sales_array,
                        array(
                            'PONumber' => $sales_item_row['PONumber'],
                            'SalesOrderNumber' => $sales_item_row['SalesOrderNumber'],
                            'ProductName' => $sales_item_row['ProductName'],
                            'Quantity' => $sales_item_row['Quantity'],
                            'Price' => $sales_item_row['Price'],
                            'LandedCost' => $sales_item_row['LandedCost']
                        )
                    );
                }
            } else {
                array_push(
                    $sales_array,
                    array(
                        'PONumber' => NULL,
                        'SalesOrderNumber' => NULL,
                        'ProductName' => NULL,
                        'Quantity' => NULL,
                        'Price' => NULL,
                        'LandedCost' => NULL
                    )
                );
            }

            return $sales_array;
        }

        // Fetch items
        $sales_items = fetchItems($connect, $SPPBNumber, $total_records, 0);

        echo json_encode(
            array(
                'StatusCode' => 200,
                'Status' => 'Success',
                'TotalRecords' => $total_records,
                'Data' => [
                    'Details' => $sales_array,
                    'Items' => $sales_items
                ]
            )
        );
    } else {
        http_response_code(404);
        echo json_encode(
            array(
                "StatusCode" => 404,
                'Status' => 'Error',
                "message" => "No records found for SONumber: $SONumber."
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
