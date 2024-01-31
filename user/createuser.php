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
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $permission_id = 'a9b8390e-bfd8-11ee-9';
    $unique_id = $_POST['unique_id'];

    //Hash user password
    $password = password_hash($password, PASSWORD_DEFAULT);

    //Check if username is not exist 
    $search_username_query = "SELECT username FROM user WHERE username = '$username';";
    $result = $connect->query($search_username_query);
    $row = $result->fetch_assoc();

    if($row){
        //If username is exist 
        http_response_code(404);
        echo json_encode(
            array(
                "StatusCode" => 404,
                'Status' => 'Not Found',
                "message" => "Error: The username is already exist. Please use another username"
            )
        );
    } else {
        //Check count is more than 1 or not 
        $search_limitUser_query = "SELECT limit_user FROM refferal WHERE refferal_id = '$unique_id'";
        $limitUser_result = $connect->query($search_limitUser_query);
        $limitUser_row = $limitUser_result->fetch_assoc();
        $limitUserInt = intval($limitUser_row['limit_user']);

        if($limitUserInt >= 1){
            //If username is unique and not exist 
            $limitUserInt = $limitUserInt - 1;
            $insert_user_query = "INSERT IGNORE INTO user (first_name, last_name, username, password, permission_id, unique_id) VALUES ('$first_name','$last_name','$username','$password','$permission_id','$unique_id')";   
            $update_refferal_query = "UPDATE refferal SET limit_user = '$limitUserInt' WHERE refferal_id = '$unique_id'";

            if(mysqli_query($connect, $insert_user_query) && mysqli_query($connect, $update_refferal_query)){
                http_response_code(200);
                echo json_encode(
                    array(
                        "StatusCode" => 200,
                        'Status' => 'Success',
                        "message" => "Success: Data inserted successfully"
                    )
                );
            } else {
                http_response_code(500);
                echo json_encode(
                    array(
                        "StatusCode" => 500,
                        'Status' => 'Error',
                        "message" => "Error: Unable to update data - " . mysqli_error($connect)
                    )
                );
            }
        } else {
            http_response_code(404);
            echo json_encode(
                array(
                    "StatusCode" => 404,
                    'Status' => 'Not Found',
                    "message" => "Error: You have reached the limit user. Please call IT Support for help"
                )
            );
        }
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