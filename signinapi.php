<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_config.php'; 
include 'cors.php';




if(isset($_GET['method']) && $_GET['method'] == 'signIn') {
    $json = file_get_contents('php://input');

    $data = json_decode($json);
    $email =  $data->email; 
    $password = md5($data->password); 
    try {
        $query = "SELECT * from `users` where `email` = '$email' AND `password` = '$password'";
        $data = mysqli_query($conn, $query);

        if ($data->num_rows == 0) 
        {    
            $response['error'] = 1;
            $response['success'] = 0;
            $response['msg'] = 'Invalid email or password';
            echo json_encode($response);
            die;
        }  
        else {
            $response['error'] = 0;
            $response['success'] = 1;
            $response['msg'] = 'Logged in successfully';
            $response['token']= base64_encode(random_bytes(18));
            echo json_encode($response); 
            die; 
        } 
    } catch(Exception $e) {
        echo 'Message: ' .$e->getMessage(); die;
    }
}

if(isset($_GET['method']) && $_GET['method'] == 'userData') {
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $user= array();
        try {
            $sql = "SELECT * FROM users WHERE email = '$id'" ;
            $result = mysqli_query($conn, $sql);
            $row = $result->fetch_assoc();
            $data = array();
            $data['name'] = $row['name'];
            $data['email'] = $row['email'];
            $data['user_id'] = $row['user_id'];
            $data['avatar'] = $row['avatar'];
            $data['designation'] = $row['designation'];
            $user[] = $data;
            echo json_encode($user);
        } catch(Exception $e) {
            echo 'Message: ' .$e->getMessage(); die;
        }
        
    
    }
}


?>