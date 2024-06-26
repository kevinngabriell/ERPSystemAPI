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
    $currentYear = date("Y");
    
    // Define your queries
    $query_total_targeting = "SELECT target_value FROM targeting WHERE target_year = '$currentYear';";

    $query_total_sales = "SELECT SUM(A2.HargaSatuan * A2.Quantity) as total_sales
    FROM salesOrder A1
    LEFT JOIN salesOrderItem A2 ON A1.SONumber = A2.salesOrderNumber
    WHERE YEAR(A1.SODate) = '$currentYear'";

    $query_total_purchase = "SELECT COUNT(PONumber) as total_purchase
    FROM purchaseOrder
    WHERE YEAR(PODate) = '$currentYear'";

    $query_total_invoice = "SELECT COUNT(*) as total_invoice
    FROM purchaseOrder A1
    WHERE A1.POStatus = 'e4376c01-1438-11ef-9';";

    $query_sales_chart = "SELECT
        MONTH(m) AS month,
        COALESCE(SUM(A2.HargaSatuan * A2.Quantity), 0) AS total_sales
    FROM (
        SELECT '$currentYear-01-01' AS m UNION ALL
        SELECT '$currentYear-02-01' UNION ALL
        SELECT '$currentYear-03-01' UNION ALL
        SELECT '$currentYear-04-01' UNION ALL
        SELECT '$currentYear-05-01' UNION ALL
        SELECT '$currentYear-06-01' UNION ALL
        SELECT '$currentYear-07-01' UNION ALL
        SELECT '$currentYear-08-01' UNION ALL
        SELECT '$currentYear-09-01' UNION ALL
        SELECT '$currentYear-10-01' UNION ALL
        SELECT '$currentYear-11-01' UNION ALL
        SELECT '$currentYear-12-01'
    ) AS months
    LEFT JOIN salesOrder A1 ON MONTH(A1.SODate) = MONTH(months.m) AND YEAR(A1.SODate) = YEAR(months.m)
    LEFT JOIN salesOrderItem A2 ON A1.SONumber = A2.salesOrderNumber
    GROUP BY months.m
    ORDER BY months.m";

    // Execute each query and store the results
    $result_total_target = mysqli_query($connect, $query_total_targeting);
    $result_total_sales = mysqli_query($connect, $query_total_sales);
    $result_total_purchase = mysqli_query($connect, $query_total_purchase);
    $result_total_invoice = mysqli_query($connect, $query_total_invoice);
    $result_sales_chart = mysqli_query($connect, $query_sales_chart);

    // Initialize array to store results
    $data = array(
        'total_target' => 0,
        'total_sales' => 0,
        'total_purchase' => 0,
        'total_invoice' => 0,
        'sales_chart' => array()
    );

    // Fetch and store each result
    if ($result_total_target) {
        $row = mysqli_fetch_assoc($result_total_target);
        $data['total_target'] = $row['target_value'];
    }
    if ($result_total_sales) {
        $row = mysqli_fetch_assoc($result_total_sales);
        $data['total_sales'] = $row['total_sales'];
    }
    if ($result_total_purchase) {
        $row = mysqli_fetch_assoc($result_total_purchase);
        $data['total_purchase'] = $row['total_purchase'];
    }
    if ($result_total_invoice) {
        $row = mysqli_fetch_assoc($result_total_invoice);
        $data['total_invoice'] = $row['total_invoice'];
    }
    if ($result_sales_chart) {
        while ($row = mysqli_fetch_assoc($result_sales_chart)) {
            $data['sales_chart'][] = array(
                'month' => $row['month'],
                'total_sales' => $row['total_sales']
            );
        }
    }

    // Output the results
    echo json_encode(
        array(
            'StatusCode' => 200,
            'Status' => 'Success',
            'Data' => $data
        ),
        JSON_PRETTY_PRINT
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
