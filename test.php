<?php
// filepath: /c:/xampp2/htdocs/synhawk/js/apanel/js.services.php

// Set the character set
echo "SET NAMES utf8mb4;\n";

// Loop to generate 50 INSERT statements
for ($i = 1; $i <= 50; $i++) {
    $id = 16 + $i;
    $title = "title_$i";
    $added_date = date('Y-m-d H:i:s', strtotime("+$i days", strtotime('2025-02-17 10:43:58')));
    echo "INSERT INTO `tbl_faq` (`id`, `title`, `title_gr`, `content`, `content_gr`, `status`, `sortorder`, `added_date`) VALUES
    ($id, '$title', '', '', '', 1, 1, '$added_date');\n";
}
?>