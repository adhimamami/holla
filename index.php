<?php
// Fungsi untuk mengambil data geolokasi berdasarkan IP
function getVisitorCountry() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = "https://ipapi.co/$ip/json/";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $data = json_decode($response, true);
        return $data['country_code'] ?? null;
    }
    return null;
}

// Fungsi untuk mengacak URL jika diperlukan
function getRandomUrl($urls) {
    return is_array($urls) ? $urls[array_rand($urls)] : $urls;
}

// Ambil parameter "s1" dari URL
$s1 = isset($_GET['s1']) ? htmlspecialchars($_GET['s1']) : 'default';

// Konfigurasi URL untuk setiap negara
$urls = [
    "US" => ["https://abc.com", "https://pqr.com"],
    "BR" => "https://def.com",
    "ID" => ["https://jkl.com", "https://mno.com"],
    "default" => "https://default.com"
];

// Dapatkan negara pengunjung
$country = getVisitorCountry();

// Tentukan URL target berdasarkan negara
$targetUrl = $urls['default']; // URL default
if ($country && array_key_exists($country, $urls)) {
    $targetUrl = getRandomUrl($urls[$country]);
}

// Tambahkan parameter "s1" ke URL target
$redirectUrl = $targetUrl . "?s1=" . urlencode($s1);

// Redirect setelah beberapa detik (spinner ditampilkan sementara)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <style>
        /* CSS untuk Spinner */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }

        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .message {
            margin-top: 20px;
            text-align: center;
        }
    </style>
    <script>
        // Timer untuk redirect setelah spinner
        setTimeout(function() {
            window.location.href = "<?= htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8') ?>";
        }, 3000); // Redirect setelah 3 detik
    </script>
</head>
<body>
    <div>
        <div class="spinner"></div>
        <div class="message">
            <h2>Redirecting...</h2>
            <p>Please wait while we redirect you to the appropriate page.</p>
        </div>
    </div>
</body>
</html>
