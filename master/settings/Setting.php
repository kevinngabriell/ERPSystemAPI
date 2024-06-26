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
require_once('../../connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] == '1') {
        $query = "SELECT * FROM settingMenu;";
        $setting_result = mysqli_query($connect, $query);

        $setting_array = array();
        while ($setting_row = mysqli_fetch_assoc($setting_result)) {
            $setting_array[] = $setting_row;
        }

        if (!empty($setting_array)) {
            echo json_encode(
                array(
                    'StatusCode' => 200,
                    'Status' => 'Success',
                    'Data' => $setting_array
                )
            );
        } else {
            http_response_code(404);
            echo json_encode(
                array(
                    'StatusCode' => 404,
                    'Status' => 'Error',
                    'Message' => 'Settings not found'
                )
            );
        }
    } else if(isset($_GET['action']) && $_GET['action'] == '2') {
        $settingId = $_GET['settingID'];
        $query = "SELECT * FROM settingDetail WHERE settingID = '$settingID';";
        $setting_result = mysqli_query($connect, $query);

        $setting_array = array();
        while ($setting_row = mysqli_fetch_assoc($setting_result)) {
            $setting_array[] = $setting_row;
        }

        if (!empty($setting_array)) {
            echo json_encode(
                array(
                    'StatusCode' => 200,
                    'Status' => 'Success',
                    'Data' => $setting_array
                )
            );
        } else {
            http_response_code(404);
            echo json_encode(
                array(
                    'StatusCode' => 404,
                    'Status' => 'Error',
                    'Message' => 'Settings not found'
                )
            );
        }
    } else {
        http_response_code(400);
        echo json_encode(
            array(
                'StatusCode' => 400,
                'Status' => 'Error',
                'Message' => 'Invalid action'
            )
        );
    }
} else {
    http_response_code(405);
    echo json_encode(
        array(
            'StatusCode' => 405,
            'Status' => 'Error',
            'Message' => 'Method Not Allowed'
        )
    );
}
?>
