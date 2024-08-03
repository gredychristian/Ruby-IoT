<?php
include "../config/database.php";

$sql = "INSERT INTO devices (serial_number, mcu_type, location) VALUES('9876543210', 'Test', 'Berlin')";

if(mysqli_query($conn, $sql)){
    echo "New data successfully added. ";
}
else{
    echo "Failed to add new data. ";
}

?>