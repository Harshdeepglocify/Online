<?php

include "config/config.php";

$query = "SELECT * FROM user";

if ($result = $con->query($query)) {

    while ($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"];
        echo "<br>";
        echo "Email: " . base64_decode($row["email"]);
        echo "<br>";
        echo "firstname: " . base64_decode($row["firstname"]);
        echo "<br>";
        echo "lastname: " . base64_decode($row["lastname"]);
        echo "<br>";
        echo "organization: " . base64_decode($row["organization"]);
        echo "<br>-------<br>";
    }
    /* free result set */
    //$result->free();
}
?>