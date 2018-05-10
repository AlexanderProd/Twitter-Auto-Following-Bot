<?php
function writeIdToDb($user_id) {
  $servername = "bernd-mysql.php-friends.de";
  $username = "521_autopilot";
  $password = "a]4jQz";
  $dbname = "521_twitter_autopilot";

  // Create connection
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  // Check connection
  if (!$conn) {
  		die("Connection failed: " . mysqli_connect_error());
  }

  // Insert Values into MySQL Database
  $query = "INSERT INTO `users` (`user_id`)
  VALUES ('$user_id');";

  if (mysqli_query($conn, $query)) {
  	echo "New record created successfully with " .$user_id. "<br />";
  } else {
  	echo "Error: " . $query . "<br>" . mysqli_error($conn);
  }

  mysqli_close($conn);
}

function checkIfIdExistsInDb($user_id){
  $servername = "bernd-mysql.php-friends.de";
  $username = "521_autopilot";
  $password = "a]4jQz";
  $dbname = "521_twitter_autopilot";

  // Create connection
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  //Retrieve First and Last Name from DB depending on ID
  $query = "SELECT * FROM `users` WHERE user_id = '$user_id';";
  $result = mysqli_query($conn, $query);
  echo " Rows: ".$result->num_rows;
  if ($result->num_rows == 0){
    echo " was not found<br />";
    return false;
  } else {
    echo " was found<br />";
    return true;
  }

  mysqli_close($conn);
}
?>
