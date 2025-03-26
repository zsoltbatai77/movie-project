<?php
include_once "conn.php";
$username = $_POST['username'];
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = hash("sha512", $_POST['password']);
$sql = "SELECT username FROM users WHERE username = '$username';";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0){
    echo json_encode([
        "status"=>1,
        "message"=>"A felhasználónév már foglalt."
    ]);
    exit;
}else{
    $sql2 = "SELECT email FROM users WHERE email = '$email';";
    $result2 = mysqli_query($conn, $sql2);
    if(mysqli_num_rows($result2) > 0){
        echo json_encode([
            "status"=> 1,
            "message"=> "Ezzel az e-mail címmel van regisztrálva felhasználó."
        ]);
        exit;
    }else{
        $session = hash("sha512", random_bytes(50));
        $token = hash("sha512", random_bytes(50));
        $sql3 = "INSERT INTO users(username, fullname, email, password, token, session) VALUES ('$username', '$fullname', '$email', '$password', '$token', '$session');";
        if(mysqli_query($conn, $sql3)){
            $sql4 = "SELECT id FROM users WHERE username = '$username';";
            $result4 = mysqli_query($conn, $sql4);
            if(mysqli_num_rows($result4) > 0){
                while($row = mysqli_fetch_row($result4)){
                    http_response_code(201);
                    echo json_encode([
                        "status"=> 0,
                        "message"=> "Sikeres regisztráció.",
                        "userid"=>$row[0],
                        "token"=> $token,
                        "session"=>$session
                    ]);
                    exit;
                }
            }
            exit;
        }else{
            echo json_encode([
                "status"=> 1,
                "message"=> "Sikertelen regisztráció."
            ]);
        }
    }
}