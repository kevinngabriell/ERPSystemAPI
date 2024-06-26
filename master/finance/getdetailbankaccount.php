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
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $bank_account = $_GET['back_account'];

    $query = "SELECT * FROM bank_account WHERE bank_number = '$bank_account';";
    $result = mysqli_query($connect, $query);

    $array = array();
    while ($row = mysqli_fetch_array($result)) {
        array_push(
            $array,
            array(
                'bank_number' => $row['bank_number'],
                'bank_name' => $row['bank_name'],
                'bank_branch' => $row['bank_branch']
            )
        );
    }

    if ($array) {
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
                'Status' => 'No Data Found',
                'Data' => []
            )
        );
    }

} else {
    http_response_code(405);
    echo json_encode(
        array(
            'StatusCode' => 405,
            'Status' => 'Method Not Allowed'
        )
    );
}

?>