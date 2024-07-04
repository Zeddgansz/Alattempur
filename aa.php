<?php

function find_all_wp_config($start_dir) {
    $wp_configs = [];
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($start_dir));

    foreach ($rii as $file) {
        if ($file->isDir()) { 
            continue;
        }
        if (basename($file) === 'wp-config.php') {
            $wp_configs[] = $file->getPathname();
        }
    }

    return $wp_configs;
}

function update_wp_config($path_wp_config) {
    echo "Memeriksa: $path_wp_config<br>";

    $isi_wp_config = file_get_contents($path_wp_config);
    if ($isi_wp_config === false) {
        echo "Gagal membaca isi wp-config.php di: $path_wp_config<br>";
        return false;
    }

    $disallow_file_edit = "define('DISALLOW_FILE_EDIT', true);";
    $disallow_file_mods = "define('DISALLOW_FILE_MODS', true);";

    $modified = false;

    if (strpos($isi_wp_config, $disallow_file_edit) === false) {
        $isi_wp_config = str_replace(
            "/* That's all, stop editing! Happy publishing. */",
            "$disallow_file_edit\n/* That's all, stop editing! Happy publishing. */",
            $isi_wp_config
        );
        echo "Menambahkan: $disallow_file_edit<br>";
        $modified = true;
    } else {
        echo "Baris '$disallow_file_edit' sudah ada.<br>";
    }

    if (strpos($isi_wp_config, $disallow_file_mods) === false) {
        $isi_wp_config = str_replace(
            "/* That's all, stop editing! Happy publishing. */",
            "$disallow_file_mods\n/* That's all, stop editing! Happy publishing. */",
            $isi_wp_config
        );
        echo "Menambahkan: $disallow_file_mods<br>";
        $modified = true;
    } else {
        echo "Baris '$disallow_file_mods' sudah ada.<br>";
    }

    if ($modified) {
        if (file_put_contents($path_wp_config, $isi_wp_config) === false) {
            echo "Gagal menulis kembali isi wp-config.php di: $path_wp_config<br>";
            return false;
        }
        echo "wp-config.php telah diperbarui di: $path_wp_config<br>";
    } else {
        echo "Tidak ada perubahan yang diperlukan untuk: $path_wp_config<br>";
    }

    return true;
}

// Tentukan direktori awal (di mana skrip ini berada)
$start_dir = __DIR__;
echo "Memulai pencarian dari: $start_dir<br>";

// Temukan semua path ke file wp-config.php
$wp_configs = find_all_wp_config($start_dir);

if (empty($wp_configs)) {
    die('Tidak ada wp-config.php ditemukan.');
}

foreach ($wp_configs as $path_wp_config) {
    update_wp_config($path_wp_config);
}

echo 'Semua wp-config.php telah diperiksa dan diperbarui jika diperlukan.';
?>
