<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Voice Input and Display</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- A .container osztály a fő tartalom körül -->
    <div class="container">
        <h1>Szöveg és szám hangalapú bevitele és megjelenítése</h1>
        <button id="start-record-btn">Kezdés</button>
        <p id="status"></p>
        
        <h2>Felvett értékek:</h2>
        <table>
            <tr>
                <th>Id</th>
                <th>Szöveg/Szám</th>
                <th>Műveletek</th>
            </tr>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "mysql";
            $dbname = "voice_input";

            $conn = new mysqli($servername, $username, $password, $dbname);

           
            if ($conn->connect_error) {
                die("Kapcsolódási hiba: " . $conn->connect_error);
            }
          
            
            $sql = "SELECT id, number FROM number";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["id"]. "</td>
                            <td>" . $row["number"]. "</td>
                            <td><button onclick='deleteEntry(" . $row["id"] . ")'>Törlés</button></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Nincs adat</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>

    <script src="app.js"></script>
    <script>
        function deleteEntry(id) {
            if (confirm("Biztosan törölni szeretnéd ezt az elemet?")) {
                fetch('delete_number.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Elem törölve!");
                        location.reload(); 
                    } else {
                        alert("Hiba történt: " + data.message);
                    }
                })
                .catch(error => {
                    alert("Hiba történt!");
                    console.error('Hiba:', error);
                });
            }
        }
    </script>
</body>
</html>

