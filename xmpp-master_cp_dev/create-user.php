<?php

/* 
 * To create user to specified databse
 */
require_once 'config.php';
$connection = mysqli_connect(database_host, database_user, database_password, database_name);
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$username = @$_POST['username'];
$password = @$_POST['password'];

if($username != '' && $password != ''){
    $query = "INSERT INTO `users` (`username`, `password`, `created_at`) VALUES (?, ?, CURRENT_TIMESTAMP)";
    $prepare = $connection->prepare($query);
    $prepare->bind_param('ss',$username,$password);
    $prepare->execute();
    if($prepare->error) { printf("Error: %s.\n", $prepare->error);die; }
    $prepare->close();
    echo 'user inserted';
}else{
    echo 'no parameter recieved';
}
$connection->close();
return ;