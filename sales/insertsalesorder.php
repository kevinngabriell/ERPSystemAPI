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
    $sales_order_number = $_POST['sales_order_number'];
    $sales_oder_date = $_POST['sales_oder_date'];
    $sales_order_ppn = $_POST['sales_order_ppn'];
    $sales_order_customer = $_POST['sales_order_customer'];
    $sales_order_send_to = $_POST['sales_order_send_to'];
    $sales_order_send_date = $_POST['sales_order_send_date'];
    $insert_by = $_POST['insert_by'];
    $product_length = $_POST['product_length'];
    $sales_order_status = '6d352c3a-1efc-11ef-a';
    $currentDateTime = new DateTime();
    $indonesiaTimeZone = new DateTimeZone('Asia/Jakarta');
    $currentDateTime->setTimezone($indonesiaTimeZone);
    $currentDateTimeString = $currentDateTime->format("Y-m-d H:i:s");
    $action = "Draft Sales Order telah berhasil diinput dan menunggu persetujuan";

    $date_parts_sodate = explode(' ', $sales_oder_date);
    $date_string_sodate = $date_parts_sodate[1] . ' ' . $date_parts_sodate[2] . ' ' . $date_parts_sodate[3];
    $sales_order_date_obj = DateTime::createFromFormat('M d Y', $date_string_sodate);
    $formatted_sales_order_date = $sales_order_date_obj->format("Y-m-d");

    $date_parts_sosendto = explode(' ', $sales_order_send_to);
    $date_string_sosendto = $date_parts_sosendto[1] . ' ' . $date_parts_sosendto[2] . ' ' . $date_parts_sosendto[3];
    $sales_sentto_date_obj = DateTime::createFromFormat('M d Y', $date_string_sosendto);
    $formatted_send_to_date = $sales_sentto_date_obj->format("Y-m-d");

    $date_parts_sosenddate = explode(' ', $sales_order_send_date);
    $date_string_sosenddate = $date_parts_sosenddate[1] . ' ' . $date_parts_sosenddate[2] . ' ' . $date_parts_sosenddate[3];
    $sales_sent_date_obj = DateTime::createFromFormat('M d Y', $date_string_sosenddate);
    $formatted_send_date = $sales_sent_date_obj->format("Y-m-d");

    $insert_sales_order_query = "INSERT INTO salesOrder (SONumber, SODate, SOPPN, SOCustomer, SOSendTo, SoSendDate, SOStatus, InsertBy , InsertDt) VALUES ('$sales_order_number','$formatted_sales_order_date', '$sales_order_ppn', '$sales_order_customer', '$formatted_send_to_date', '$formatted_send_date', '$sales_order_status','$insert_by', '$currentDateTimeString');";
    $insert_sales_order_history_query = "INSERT INTO salesOrderHistory (SONumber, Action, ActionBy, ActionDt) VALUES ('$sales_order_number','$action', '$insert_by', '$currentDateTimeString')";

    if(mysqli_query($connect, $insert_sales_order_query) && mysqli_query($connect, $insert_sales_order_history_query) ){
        
        for ($i = 1; $i <= $product_length; $i++) {
            $sales_order_po_number = $_POST['sales_order_PO_' . $i];
            $sales_order_product_name = $_POST['sales_order_product_name_' . $i];
            $sales_order_product_quantity = $_POST['sales_order_product_quantity_' . $i];
            $sales_order_satuan = $_POST['sales_order_satuan_' . $i];
            $sales_order_matauang = $_POST['sales_order_matauang_' . $i];
            $sales_order_hargasatuan = $_POST['sales_order_hargasatuan_' . $i];
            $sales_order_kurs = $_POST['sales_order_kurs_' . $i];
            
            $insert_item_query = "INSERT INTO salesOrderItem (salesOrderNumber, purchaseOrderNumber, ProductName, Quantity, Satuan, MataUang, HargaSatuan, Kurs) VALUES ('$sales_order_number', '$sales_order_po_number', '$sales_order_product_name','$sales_order_product_quantity', '$sales_order_satuan', '$sales_order_matauang', '$sales_order_hargasatuan', '$sales_order_kurs');";
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

?>