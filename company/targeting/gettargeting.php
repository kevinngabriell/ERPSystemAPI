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
    $company_id = $_GET['company_id'];

    $targeting_query = "SELECT targeting_id, target_year, target_value FROM targeting WHERE company = '$company_id'";
    $targeting_result = mysqli_query($connect, $targeting_query);

    $targeting_array = array();
    while($targeting_row = mysqli_fetch_array($targeting_result)){
        array_push(
            $targeting_array,
            array(
                'targeting_id' => $targeting_row['targeting_id'],
                'target_year' => $targeting_row['target_year'],
                'target_value' => $targeting_row['target_value']
            )
        );
    }

    if($targeting_array){
        echo json_encode(
            array(
                'StatusCode' => 200,
                'Status' => 'Success',
                'Data' => $targeting_array
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
?>