<?php
include 'db_config.php'; 
include 'cors.php';

//echo '<pre>', print_r($_POST); die;
if(isset($_GET['method']) && $_GET['method'] == 'signUp')  {
  $name = "";
  if(isset($_POST["name"])) {
      $name = $_POST["name"];

  }
  $email = $_POST["email"];
  $password = md5($_POST["password"]);
  $designation = $_POST["designation"];




  $sqlCheck = "SELECT * FROM `users` WHERE `email` = '$email'";
  $resultCheck = $conn->query($sqlCheck);
  if ($resultCheck->num_rows > 0) {
    $response['error'] = 1;
    $response['success'] = 0;
    $response['msg'] = 'Account already exists';
    echo json_encode($response);
    http_response_code(400);
    die;
  }

  $fileName = "";
  if(isset($_FILES['avatar'])) {
      $target_dir = "uploads/";
      $customfilename   = uniqid() . "-" . time();
      $fileName = date('dmYHis')."-".strtolower( str_replace(" ", "_", basename($_FILES["avatar"]["name"]) ));
      $target_file = $target_dir . $fileName;
      $BookFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

      if($BookFileType == 'jpeg' || $BookFileType == 'png' || $BookFileType == 'jpg'  ) {

          if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
              $response['error'] = 1;
              $response['success'] = 0;
              $response['msg'] = 'Sorry, there was an error uploading your file';
              echo json_encode($response);
              die;
          }

      }else{
          $response['error'] = 1;
          $response['success'] = 0;
          $response['msg'] = 'File must be in jpeg, png or jpg format';
          echo json_encode($response);
          http_response_code(400); 
          die;
      }
  }

  $sqlQuery = "INSERT INTO `users` (`name`, `email`, `avatar`, `password`, `designation`) VALUES('$name', '$email', '$fileName', '$password', '$designation')";
  
  if(mysqli_query($conn, $sqlQuery))  
  {  
    $response['error'] = 0;
    $response['success'] = 1;
    $response['msg'] = 'Account created successfully ';
    echo json_encode($response);
    die; 
  }  
  else {
    $response['error'] = 1;
    $response['success'] = 0;
    $response['msg'] = 'Internal error';
    echo json_encode($response);
    http_response_code(400); 
    die;
  }
}

if(isset($_GET['method']) && $_GET['method'] == 'update')  { 
  $name = "";
  if(isset($_POST["name"])) {
      $name = $_POST["name"];

  }
  $id = $_POST['id'];
  $email = $_POST["email"];
  $password = md5($_POST["password"]);
  $designation = $_POST["designation"];
  

  $fileName = "";
  if(isset($_FILES['avatar'])) {
      $target_dir = "uploads/";
      $customfilename   = uniqid() . "-" . time();
      $fileName = date('dmYHis')."-".strtolower( str_replace(" ", "_", basename($_FILES["avatar"]["name"]) ));
      $target_file = $target_dir . $fileName;
      $BookFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

      if($BookFileType == 'jpeg' || $BookFileType == 'png' || $BookFileType == 'jpg'  ) {

          if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
              $response['error'] = 1;
              $response['success'] = 0;
              $response['msg'] = 'Sorry, there was an error uploading your file';
              echo json_encode($response);
              die;
          }

      }else{
          $response['error'] = 1;
          $response['success'] = 0;
          $response['msg'] = 'File must be in jpeg, png or jpg format';
          echo json_encode($response);
          http_response_code(400); 
          die;
      }
  }

  
  $sqlQuery = "UPDATE users SET name = '$name', email = '$email', avatar = '$fileName', password = '$password' WHERE email = '$id'";
  
  if(mysqli_query($conn, $sqlQuery))  
  {  
    $response['error'] = 0;
    $response['success'] = 1;
    $response['msg'] = 'Account Updated successfully. Please sign in again to continue ';
    echo json_encode($response);
    die; 
  }  
  else {
    $response['error'] = 1;
    $response['success'] = 0;
    $response['msg'] = 'Internal error';
    echo json_encode($response);
    http_response_code(400); 
    die;
  }
}

?>