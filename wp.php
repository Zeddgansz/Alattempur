<?php
function find_wp_config($start_dir) {
    $dir = $start_dir;
    while ($dir !== dirname($dir)) { // Selama belum mencapai direktori root
        echo "Memeriksa: $dir/wp-config.php<br>"; // Tambahkan log untuk debugging
        if (file_exists($dir . '/wp-config.php')) {
            return $dir . '/wp-config.php';
        }
        $dir = dirname($dir);
    }
    return false;
}

// Tentukan direktori awal (di mana skrip ini berada)
$start_dir = __DIR__;
echo "Memulai pencarian dari: $start_dir<br>"; // Tambahkan log untuk debugging

// Temukan path ke file wp-config.php
$path_wp_config = find_wp_config($start_dir);

if ($path_wp_config === false) {
    die('wp-config.php tidak ditemukan.');
}

echo "Ditemukan wp-config.php di: $path_wp_config<br>"; // Tambahkan log untuk debugging

// Baca isi file wp-config.php
$isi_wp_config = file_get_contents($path_wp_config);
if ($isi_wp_config === false) {
    die('Gagal membaca isi wp-config.php.');
}

// Tentukan baris yang akan ditambahkan
$disallow_file_edit = "define('DISALLOW_FILE_EDIT', true);";
$disallow_file_mods = "define('DISALLOW_FILE_MODS', true);";

// Periksa apakah baris sudah ada dalam file wp-config.php
if (strpos($isi_wp_config, $disallow_file_edit) === false) {
    // Jika belum ada, tambahkan baris sebelum 'That's all, stop editing!'
    $isi_wp_config = str_replace(
        "/* That's all, stop editing! Happy publishing. */",
        "$disallow_file_edit\n/* That's all, stop editing! Happy publishing. */",
        $isi_wp_config
    );
    echo "Menambahkan: $disallow_file_edit<br>"; // Tambahkan log untuk debugging
} else {
    echo "Baris '$disallow_file_edit' sudah ada.<br>"; // Tambahkan log untuk debugging
}

if (strpos($isi_wp_config, $disallow_file_mods) === false) {
    // Jika belum ada, tambahkan baris sebelum 'That's all, stop editing!'
    $isi_wp_config = str_replace(
        "/* That's all, stop editing! Happy publishing. */",
        "$disallow_file_mods\n/* That's all, stop editing! Happy publishing. */",
        $isi_wp_config
    );
    echo "Menambahkan: $disallow_file_mods<br>"; // Tambahkan log untuk debugging
} else {
    echo "Baris '$disallow_file_mods' sudah ada.<br>"; // Tambahkan log untuk debugging
}

// Tulis kembali isi yang telah dimodifikasi ke file wp-config.php
if (file_put_contents($path_wp_config, $isi_wp_config) === false) {
    die('Gagal menulis kembali isi wp-config.php.');
}

echo 'wp-config.php telah diperbarui.';
?>
