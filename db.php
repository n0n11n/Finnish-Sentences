<?php
	$serverName = "127.0.0.1";
	$dBUsername = "user";
	$dBPassword = "password";
	$dBName = "suomi1";

	$conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);

	if (!$conn) {
		die("Connection failed: ".mysqli_connect_error());
	}elseif(!mysqli_set_charset($conn, "utf8")) {
        printf("Error loading character set utf8: %s\n", mysqli_error($link));
    exit();
}
    
?>
