<?php
  ob_start();

  $colors = [
    'piros' => 'red',
    'zold' => 'green',
    'sarga' => 'yellow',
    'kek' => 'blue',
    'fekete' => 'black',
    'feher' => 'white'
  ];

  function decodePasswordTXT($filePath) {
    $key = [5, -14, 31, -9, 3];
    $lines = file($filePath, FILE_IGNORE_NEW_LINES);
    $result = [];

    foreach ($lines as $line) {
      $decoded = '';
      $key_index = 0;

      for ($i = 0; $i < strlen($line); $i++) {
        $encoded = ord($line[$i]);
        if ($encoded === 0x0A) continue;

        $offset = $key[$key_index % count($key)];
        $decoded .= chr($encoded - $offset);
        $key_index++;
      }

      if (strpos($decoded, '*') !== false) {
        list($user, $pass) = explode('*', $decoded, 2);
        $result[$user] = $pass;
      }
    }

    return $result;
  }

  $errorMessage = '';
  $loggedIn = false;
  $userColor = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    $jelszavak = decodePasswordTXT("password.txt");

    if (!array_key_exists($user, $jelszavak)) {
      $errorMessage = "❌ Nincs ilyen felhasználó.";
    } elseif ($jelszavak[$user] !== $pass) {
      header("refresh:3;url=https://www.police.hu");
      $errorMessage = "❌ Hibás jelszó. Átirányítás 3 másodperc múlva...";
    } else {
      $mysqli = new mysqli("db", "user", "userpass", "adatok");

      if ($mysqli->connect_errno) {
        $errorMessage = "Sikertelen kapcsolódás: " . $mysqli->connect_error;
      } else {
        $stmt = $mysqli->prepare("SELECT Titkos FROM tabla WHERE Username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->bind_result($szin);

        $loggedIn = true;

        if ($stmt->fetch()) {
          $angolSzin = $szinek[strtolower($szin)] ?? 'gray';
          $userColor = "<div class='p-6 rounded-lg fade-in mt-4' style='background-color: $angolSzin; color: white;'>
            Üdv <strong>$user</strong>! A kedvenc színed: <strong>$szin</strong>
          </div>";
        } else {
          $userColor = "<p class='text-gray-300'>Hiba: nem található kedvenc szín.</p>";
        }

        $stmt->close();
        $mysqli->close();
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bejelentkezés - KESC9V</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .fade-in {
      opacity: 0;
      animation: fadeIn 0.6s ease-out forwards;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .button:hover {
      transform: scale(1.05);
      transition: 0.2s ease-in-out;
    }
  </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center px-4">

  <div class="text-center fade-in mb-8">
    <h2 class="text-3xl font-bold">Szabó Ármin András - <span class="text-yellow-400">KESC9V</span></h2>
  </div>

  <?php if ($errorMessage): ?>
    <p class="text-red-500 text-lg fade-in mb-4"><?= $errorMessage ?></p>
  <?php endif; ?>

  <?php if ($loggedIn): ?>
    <h2 class='text-xl font-semibold text-green-400 fade-in mb-4'>✅ Üdvözöljük <span class='text-yellow-300'><?= $user ?></span>!</h2>
    <?= $userColor ?>
  <?php endif; ?>

  
  <form method="POST" class="flex flex-col items-center gap-4 mt-8 bg-gray-800 p-6 rounded-xl fade-in w-full max-w-md shadow-lg">
    <input type="text" name="username" required placeholder="Felhasználónév" class="px-4 py-2 rounded bg-gray-700 w-full placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500">
    <input type="password" name="password" required placeholder="Jelszó" class="px-4 py-2 rounded bg-gray-700 w-full placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500">
    <input type="submit" value="Bejelentkezés" class="button mt-4 bg-yellow-500 hover:bg-yellow-400 text-black px-4 py-2 rounded w-full font-bold">
  </form>

</body>
</html>

<?php
  ob_end_flush(); // puffer leürítése
?>