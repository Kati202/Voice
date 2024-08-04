<?php
header("Content-Type: application/json; charset=UTF-8");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['id'])) {
        $id = intval($input['id']);

        
        $servername = "localhost";
        $username = "root";
        $password = "mysql";
        $dbname = "voice_input";

        $conn = new mysqli($servername, $username, $password, $dbname);

        
        if ($conn->connect_error) {
            die(json_encode(array("success" => false, "message" => "Kapcsolódási hiba: " . $conn->connect_error)));
        }

     
        $stmt = $conn->prepare("DELETE FROM number WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, "message" => "Hiba: " . $stmt->error));
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(array("success" => false, "message" => "Nincs Id megadva"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Helytelen kérés"));
}
?>
