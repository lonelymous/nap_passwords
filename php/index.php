<?php
echo "<h2>Szabó Ármin András - KESC9V</h2>";

$szinek = [
    'piros' => 'red',
    'zold' => 'green',
    'sarga' => 'yellow',
    'kek' => 'blue',
    'fekete' => 'black',
    'feher' => 'white'
];

function dekodolPasswordTxt($filePath) {
    $kulcs = [5, -14, 31, -9, 3];
    $sorok = file($filePath, FILE_IGNORE_NEW_LINES);
    $eredmeny = [];

    foreach ($sorok as $sor) {
        $decoded = '';
        $kulcs_index = 0;

        for ($i = 0; $i < strlen($sor); $i++) {
            $kodolt = ord($sor[$i]);
            if ($kodolt === 0x0A) {
                continue; // EOL
            }

            $eltolas = $kulcs[$kulcs_index % count($kulcs)];
            $decoded .= chr($kodolt - $eltolas);
            $kulcs_index++;
        }

        // Feltételezve, hogy mindig van '*'
        if (strpos($decoded, '*') !== false) {
            list($user, $pass) = explode('*', $decoded, 2);
            $eredmeny[$user] = $pass;
        }
    }

    return $eredmeny;
}

// Ha be lett küldve
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    $jelszavak = dekodolPasswordTxt("password.txt");

    if (!array_key_exists($user, $jelszavak)) {
        echo "<p style='color: red;'>Nincs ilyen felhasználó.</p>";
    } elseif ($jelszavak[$user] !== $pass) {
        echo "<p style='color: red;'>Hibás jelszó. Átirányítás 3 másodperc múlva...</p>";
        header("refresh:3;url=https://www.police.hu");
    } else {
        // Sikeres login
        // Csatlakozás adatbázishoz
        $mysqli = new mysqli("db", "user", "userpass", "adatok");

        if ($mysqli->connect_errno) {
            echo "Sikertelen kapcsolódás: " . $mysqli->connect_error;
            exit;
        }

        $stmt = $mysqli->prepare("SELECT Titkos FROM tabla WHERE Username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->bind_result($szin);
        echo "<h2>Üdvözöljük $user!</h2>";
        if ($stmt->fetch()) {
            $angolSzin = $szinek[strtolower($szin)] ?? 'gray';
            echo "<div style='background-color: $angolSzin; padding: 20px; color: white;'>
            Üdv $user! A kedvenc színed: $szin
          </div>";
    
        } else {
            echo "Hiba: nem található kedvenc szín.";
        }
        $stmt->close();
        $mysqli->close();
    }
}
?>
<form method="POST">
  Username: <input type="text" name="username" required><br>
  Password: <input type="password" name="password" required><br>
  <input type="submit" value="Login">
</form>
