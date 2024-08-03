<?php
header("Content-Type: application/json; charset=UTF-8");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['number'])) {
        $number = $input['number'];

        
        $servername = "localhost";
        $username = "root";
        $password = "mysql";
        $dbname = "voice_input";

        $conn = new mysqli($servername, $username, $password, $dbname);

       
        if ($conn->connect_error) {
            echo json_encode(array("success" => false, "message" => "Kapcsolódási hiba: " . $conn->connect_error));
            exit();
        }

       
        $stmt = $conn->prepare("INSERT INTO number (number) VALUES (?)");
        $stmt->bind_param("s", $number);

        if ($stmt->execute()) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, "message" => "Hiba: " . $stmt->error));
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(array("success" => false, "message" => "Nincs szám megadva"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Helytelen kérés"));
}
?>

