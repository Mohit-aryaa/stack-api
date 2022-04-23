<?php

include 'db_config.php'; 
include 'cors.php';
require 'time_elapsed_string.php';


function getAnswer($id, $conn) {
    if( !empty( $id ) ) {
        $total = '';
        $answerQuery = "SELECT * FROM `answers` WHERE question_id = '$id'" ;
        $answerResult = mysqli_query($conn, $answerQuery);
        if (!empty($answerResult) && $answerResult->num_rows > 0) {
            if($answerResult->num_rows > 1) {
                $total = $answerResult->num_rows . ' Answers';
            } else {
                $total = $answerResult->num_rows . ' Answer';
            }
        } else {
            $total = '0 Answer';
        }
        return $total;
    }
}

function getLikes($id, $conn) {
    if( !empty( $id ) ) {
        $likes = '';
        $likesQuery = "SELECT * FROM `likes` WHERE question_id = '$id'" ;
        $likesResult = mysqli_query($conn, $likesQuery);
        if (!empty($likesResult) && $likesResult->num_rows > 0) {
        // output data of each row
            if($likesResult->num_rows > 1) {
                $likes = $likesResult->num_rows . ' likes';
            } else {
                $likes = $likesResult->num_rows . ' like';
            }
        } else {
            $likes = 'No likes';
        }
        return $likes;
    }
}



function slugify($text)
{
 $divider = '-';
  // replace non letter or digits by divider
  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, $divider);

  // remove duplicate divider
  $text = preg_replace('~-+~', $divider, $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

function liked($userid, $id, $conn) {
    $liked = false;
    $email = $userid;
    $get = "SELECT user_id FROM `users` WHERE email = '$email'";
    $userResult = mysqli_query($conn, $get);
    $userRow = $userResult->fetch_assoc();
    $posted_by =  $userRow['user_id']; 
    $likedQuery = "SELECT * FROM `likes` WHERE question_id = '$id' && `liked_by` = '$posted_by'" ;
    $likedResult = mysqli_query($conn, $likedQuery);
    if ($likedResult->num_rows > 0) {
    // output data of each row
        $liked = true;
    } else {
        $liked = false;
    }
    return $liked;
}

function answerLiked($userid, $id, $conn) {
    $answerliked = false;
    $email = $userid;
    $get = "SELECT user_id FROM `users` WHERE email = '$email'";
    $userResult = mysqli_query($conn, $get);
    $userRow = $userResult->fetch_assoc();
    $posted_by =  $userRow['user_id']; 
    $likedAnswerQuery = "SELECT * FROM `answerlikes` WHERE answer_id = '$id' && `liked_by` = '$posted_by'" ;
    $likedAnswerResult = mysqli_query($conn, $likedAnswerQuery);
    if ($likedAnswerResult->num_rows > 0) {
    // output data of each row
        $answerliked = true;
    } else {
        $answerliked = false;
    }
    return $answerliked;
}

function getAnswerLikes($id, $conn) {
    if( !empty( $id ) ) {
        $answerlikes = '';
        $answerlikesQuery = "SELECT * FROM `answerlikes` WHERE answer_id = '$id'" ;
        $answerlikesResult = mysqli_query($conn, $answerlikesQuery);
        if (!empty($answerlikesResult) && $answerlikesResult->num_rows > 0) {
        // output data of each row
            if($answerlikesResult->num_rows > 1) {
                $answerlikes = $answerlikesResult->num_rows . ' likes';
            } else {
                $answerlikes = $answerlikesResult->num_rows . ' like';
            }
        } else {
            $answerlikes = 'No likes';
        }
        return $answerlikes;
    }
}

//echo "<pre>"; print_r( getAnswer(1, $conn) ); die;

if(isset($_GET['method']) && $_GET['method'] == 'getData' ) {
    if(isset($_GET['slug'])) {
        $posts= array();
        $slug = $_GET['slug'];
        $get = "SELECT post_id FROM `posts` WHERE slug = '$slug'";
        $result = mysqli_query($conn, $get);
        $postsrow = $result->fetch_assoc();
        $id =  $postsrow['post_id']; 
        $sql = "SELECT * FROM posts LEFT JOIN users ON posts.posted_by = users.user_id WHERE post_id = '$id'" ;
        $result = mysqli_query($conn, $sql);
        $answers = "";
    
        if (!empty($result) && $result->num_rows > 0) {
        // output data of each row
            while($row = $result->fetch_assoc()) {
                $liked = false;
                if(isset($_GET['userid']) && $_GET['userid'] != null) {
                $liked = liked($_GET['userid'], $id, $conn);
                }

                $data = array();
                $data['posted_time'] = time_elapsed_string($row['posted_on']);
                $data['likes'] = getLikes($row['post_id'], $conn);
                $data['liked'] = $liked;
                $data['question'] = $row['question'];
                $data['title'] = $row['title'];
                $data['name'] = $row['name'];
                $data['avatar'] = $row['avatar'];
                $data['post_id'] = $row['post_id'];
                $data['answers'] = getAnswer($row['post_id'], $conn);
                $posts[] = $data;
            }
        } else {
            $posts = [];
        }
    
        echo  json_encode($posts); die;
    } else {
        $posts= array();
        $results_per_page = $_GET['size'];
        $page = $_GET['page'] ;
        $page_first_result = ($page - 1) * $results_per_page;

        $totalQuery = "select *from posts";  
        $totalResult = mysqli_query($conn, $totalQuery);  
        $number_of_result = mysqli_num_rows($totalResult);  

        $sql = "SELECT * FROM posts LEFT JOIN users ON posts.posted_by = users.user_id LIMIT " . $page_first_result . ',' . $results_per_page;
        $result = mysqli_query($conn, $sql);
        $output = [];

        if ($result->num_rows > 0) {
        // output data of each row
        while($row =$result->fetch_assoc()) {
            $postId = $row['post_id'];

            $liked = false;
            if(isset($_GET['userid']) && $_GET['userid'] != null) {
                $liked = liked($_GET['userid'], $postId, $conn);
            }
            
            $data = array();
            $data['posted_time'] = time_elapsed_string($row['posted_on']);
            $data['likes'] = getLikes($row['post_id'], $conn);
            $data['liked'] = $liked;
            $data['question'] = $row['question'];
            $data['title'] = $row['title'];
            $data['name'] = $row['name'];
            $data['slug'] = $row['slug'];
            $data['avatar'] = $row['avatar'];
            $data['post_id'] = $row['post_id'];
            $data['answers'] = getAnswer($row['post_id'], $conn);
            $posts[] = $data;
        }
        } else {
            $posts = [];
        }
      
        $output['data'] = $posts;
        $output['total'] = $number_of_result;

        
        echo  json_encode($output); die;
    }
}

if(isset($_GET['method']) && $_GET['method'] == 'submitAnswer') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    if(!$data) {
        return;
    }
    $slug = $data->slug;
    $getpost = "SELECT post_id FROM `posts` WHERE slug = '$slug'";
    $postresult = mysqli_query($conn, $getpost);
    $postsrow = $postresult->fetch_assoc();
    $question_id =  $postsrow['post_id']; 

    $answer = $data->answer;
    $email = $data->email;
    $getid = "SELECT user_id FROM `users` WHERE email = '$email'";
    $idresult = mysqli_query($conn, $getid);
    $idrow = $idresult->fetch_assoc();
    $posted_by =  $idrow['user_id']; 
    try {
        $query = "INSERT INTO `answers` (`answer`, `question_id`, `posted_by`) VALUES('$answer', '$question_id', '$posted_by')";
        //echo $query; die;
        if(mysqli_query($conn, $query))  
        {  
            $response['error'] = 0;
            $response['success'] = 1;
            $response['msg'] = 'Answer submitted successfully';
            $response['totalAnswers'] = getAnswer($question_id, $conn);;
            echo json_encode($response);
            die; 
        }  
        else {
            $response['error'] = 1;
            $response['success'] = 0;
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
    if(isset($_GET['slug'])) {
        $posts= array();
        $slug = $_GET['slug'];
        $getpostIdQuery = "SELECT post_id FROM `posts` WHERE slug = '$slug'";
        $getpostId = mysqli_query($conn, $getpostIdQuery);
        $postsRow = $getpostId->fetch_assoc();
        $id =  $postsRow['post_id']; 

        try {
            $sql = "SELECT * FROM `answers` 
            JOIN users ON answers.posted_by = users.user_id 
            WHERE question_id = '$id'" ;
            $result = mysqli_query($conn, $sql);
    
            if (!empty($result) && $result->num_rows > 0) {
            // output data of each row
                while($row =$result->fetch_assoc()) {
                    $liked = false;
                    if(isset($_GET['userid']) && $_GET['userid'] != null) {
                        $liked = answerLiked($_GET['userid'], $id, $conn);
                    }

                    $data = array();
                    $data['posted_time'] = time_elapsed_string($row['posted_on']);
                    $data['answer'] = $row['answer'];
                    $data['id'] = $row['id'];
                    $data['name'] = $row['name'];
                    $data['answerlikes'] = getAnswerLikes($row['question_id'], $conn);
                    $data['liked'] = $liked;
                    $data['avatar'] = $row['avatar'];
                    $posts[] = $data;
                }
            } else {
                $posts = [];
            }
    
            echo  json_encode($posts); die;
        } catch(Exception $e) {
            echo 'Message: ' .$e->getMessage(); die;
        }
    }
}


if(isset($_GET['method']) && $_GET['method'] == 'likePost') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    if(!$data) {
        return;
    }
    $question_id = $data->question_id;
    $email = $data->email;
    $get = "SELECT user_id FROM `users` WHERE email = '$email'";
    $result = mysqli_query($conn, $get);
    $row = $result->fetch_assoc();
    $posted_by =  $row['user_id']; 

   
    try {
         $sqlCheck = "SELECT * FROM `likes` WHERE question_id ='$question_id' &&  `liked_by` = '$posted_by'";
        $resultCheck = $conn->query($sqlCheck);
        if ($resultCheck->num_rows > 0) {
            $sql = "DELETE FROM `likes` WHERE question_id ='$question_id' && `liked_by` = '$posted_by'";
            mysqli_query($conn, $sql);
            $response['error'] = 0;
            $response['success'] = 1;
            $response['msg'] = 'Post unliked successfully';
            $response['liked']= false;
            $response['likes'] = getLikes($question_id, $conn);
            echo json_encode($response);
            die; 
        }
    } catch(Exception $e) {
        echo 'Message: ' .$e->getMessage(); die;
    }
    try {
        $query = "INSERT INTO `likes` ( `question_id`, `liked_by`) VALUES('$question_id',  '$posted_by')";
        //echo $query; die;
        if(mysqli_query($conn, $query))  
        {  
            
            //$liked = false;
            //$liked = liked($posted_by, $question_id, $conn);

            $response['error'] = 0;
            $response['success'] = 1;
            $response['msg'] = 'Post liked successfully';
            $response['likes'] = getLikes($question_id, $conn);
            $response['liked']= true;
            echo json_encode($response);
            die; 
        }  
        else {
            $response['error'] = 1;
            $response['success'] = 0;
            $response['msg'] = 'Error';
            echo json_encode($response);
            die;
        }
    } catch(Exception $e) {
        echo 'Message: ' .$e->getMessage(); die;
    }
}

if(isset($_GET['method']) && $_GET['method'] == 'likeAnswer') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    if(!$data) {
        return;
    }
    $answer_id = $data->answer_id;
    $email = $data->email;
    $get = "SELECT user_id FROM `users` WHERE email = '$email'";
    $result = mysqli_query($conn, $get);
    $row = $result->fetch_assoc();
    $posted_by =  $row['user_id']; 

   
    try {
         $sqlCheck = "SELECT * FROM `answerlikes` WHERE answer_id ='$answer_id' &&  `liked_by` = '$posted_by'";
        $resultCheck = $conn->query($sqlCheck);
        if ($resultCheck->num_rows > 0) {
            $sql = "DELETE FROM `answerlikes` WHERE answer_id ='$answer_id' && `liked_by` = '$posted_by'";
            mysqli_query($conn, $sql);
            $response['error'] = 0;
            $response['success'] = 1;
            $response['msg'] = 'Answer unliked successfully';
            $response['liked']= false;
            $response['likes'] = getAnswerLikes($answer_id, $conn);
            echo json_encode($response);
            die; 
        }
    } catch(Exception $e) {
        echo 'Message: ' .$e->getMessage(); die;
    }
    try {
        $query = "INSERT INTO `answerlikes` ( `answer_id`, `liked_by`) VALUES('$answer_id',  '$posted_by')";
        //echo $query; die;
        if(mysqli_query($conn, $query))  
        {  
            

            $response['error'] = 0;
            $response['success'] = 1;
            $response['msg'] = 'Post liked successfully';
            $response['likes'] = getAnswerLikes($answer_id, $conn);
            $response['liked']= true;
            echo json_encode($response);
            die; 
        }  
        else {
            $response['error'] = 0;
            $response['success'] = 1;
            $response['msg'] = 'Error';
            echo json_encode($response);
            die;
        }
    } catch(Exception $e) {
        echo 'Message: ' .$e->getMessage(); die;
    }
}

if(isset($_GET['method']) && $_GET['method'] == 'askQuestion') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    if(!$data) {
        return;
    }
    $question = $conn->real_escape_string($data->question);
    $title = $conn->real_escape_string($data->title);
    $date = date_create();
    $slug = date_timestamp_get($date).'-'.slugify($title);
    $email = $data->email;
    $get = "SELECT user_id FROM `users` WHERE email = '$email'";
    $result = mysqli_query($conn, $get);
    $row = $result->fetch_assoc();
    $posted_by =  $row['user_id']; 
    try {
        $query = "INSERT INTO `posts` (`title`, `question`, `slug`, `posted_by`) VALUES('$title', '$question', '$slug', '$posted_by')";
        //echo $query; die;
        if(mysqli_query($conn, $query))  
        {  
            $response['error'] = 0;
            $response['success'] = 1;
            $response['msg'] = 'Question asked successfully';
            echo json_encode($response);
            die; 
        }  
        else {
            $response['error'] = 1;
            $response['success'] = 0;
            $response['msg'] = $conn->error;
            echo json_encode($response);
            die;
        }
    }  
    catch(Exception $e) {
        echo 'Message: ' .$e->getMessage(); die;
    }
}

if(isset($_GET['method']) && $_GET['method'] == 'getMyQuestions') {
    if(isset($_GET['id'])) {
        $posts= array();
        $id = $_GET['id'];
        $results_per_page = $_GET['size'];
        $page = $_GET['page'] ;
        $page_first_result = ($page - 1) * $results_per_page;

        $totalQuery = "SELECT * FROM posts";  
        $totalResult = mysqli_query($conn, $totalQuery);  
        $number_of_result = mysqli_num_rows($totalResult);  

        $sql = "SELECT * FROM `posts` 
        JOIN users ON posts.posted_by = users.user_id 
        WHERE posted_by = '$id' LIMIT " . $page_first_result . ',' . $results_per_page; ;
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows > 0) {
        // output data of each row
        while($row =$result->fetch_assoc()) {
            $postId = $row['post_id'];

            $liked = false;
            //if(isset($_GET['userid']) && $_GET['userid'] != null) {
                $liked = liked($_GET['userid'], $postId, $conn);
            //}
            
            $data = array();
            $data['posted_time'] = time_elapsed_string($row['posted_on']);
            $data['likes'] = getLikes($row['post_id'], $conn);
            $data['liked'] = $liked;
            $data['question'] = $row['question'];
            $data['title'] = $row['title'];
            $data['name'] = $row['name'];
            $data['avatar'] = $row['avatar'];
            $data['post_id'] = $row['post_id'];
            $data['answers'] = getAnswer($row['post_id'], $conn);
            $posts[] = $data;
        }
        } else {
            $posts = [];
        }
      
        $output['data'] = $posts;
        $output['total'] = $number_of_result;
        
        echo  json_encode($output); die;
    }
}


?>