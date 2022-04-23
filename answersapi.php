<?php
include 'db_config.php';
include 'cors.php';
require 'time_elapsed_string.php';
if(isset($_GET['method']) && $_GET['method'] == 'submitAnswer') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $question_id = $data->question_id;
    $answer = $data->answer;
    $email = $data->email;
    $get = "SELECT user_id FROM `users` WHERE email = '$email'";
    $result = mysqli_query($conn, $get);
    $row = $result->fetch_assoc();
    $posted_by =  $row['user_id']; 
    try {
        $query = "INSERT INTO `answers` (`answer`, `question_id`, `posted_by`) VALUES('$answer', '$question_id', '$posted_by')";
        //echo $query; die;
        if(mysqli_query($conn, $query))  
        {  
            $answerQuery = "SELECT * FROM `answers` WHERE question_id = '$question_id'" ;
            $answerResult = mysqli_query($conn, $answerQuery);
            if ($answerResult->num_rows > 0) {
            // output data of each row
                if($answerResult->num_rows > 1) {
                    $answers = $answerResult->num_rows . ' Answers';
                } else {
                    $answers = $answerResult->num_rows . ' Answer';
                }
            } else {
                $answers = '0 Answer';
            }
            $response['error'] = 0;
            $response['success'] = 1;
            $response['msg'] = 'Answer submitted successfully';
            $response['totalAnswers'] = $answers;
            echo json_encode($response);
            die; 
        }  
        else {
            $response['error'] = 0;
            $response['success'] = 1;
            $response['msg'] = 'Error while submitting answer';
            echo json_encode($response);
            die;
        }
    }  
    catch(Exception $e) {
        echo 'Message: ' .$e->getMessage(); die;
    }

}

if(isset($_GET['method']) && $_GET['method'] == 'getSubmittedAnswers') {
    if(isset($_GET['id'])) {
        $posts= array();
        $id = $_GET['id'];
        $sql = "SELECT * FROM `answers` 
        JOIN users ON answers.posted_by = users.user_id 
        WHERE question_id = '$id'" ;
        $result = mysqli_query($conn, $sql);
    
        if ($result->num_rows > 0) {
        // output data of each row
        while($row =$result->fetch_assoc()) {
            $data = array();
            $data['posted_time'] = time_elapsed_string($row['posted_on']);
            $data['answer'] = $row['answer'];
            $data['name'] = $row['name'];
            $posts[] = $data;
        }
        } else {
            $posts = [];
        }
    
        echo  json_encode($posts); die;
    }
}

?>