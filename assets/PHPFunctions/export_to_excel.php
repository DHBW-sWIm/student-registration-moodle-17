<?php


require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
global $CFG;
require_once("$CFG->libdir/phpspreadsheet/vendor/autoload.php");
require_login();

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};


$temporary_html_file = __DIR__ . '\tmp_html\\' . time() . '.html';
touch($temporary_html_file);

foreach ($input as $post) {
    file_put_contents($temporary_html_file, $post, FILE_APPEND);
}

$reader = IOFactory::createReader('Html');

try {
    $spreadsheet = $reader->load($temporary_html_file);
} catch (Exception $e) {
    unlink($temporary_html_file);
    exit;
}


$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

$filename = time() . '.xlsx';

$writer->save($filename);

header('Content-Type: application/x-www-form-urlencoded');

header('Content-Transfer-Encoding: Binary');

header("Content-disposition: attachment; filename=\"" . $filename . "\"");

readfile($filename);

unlink($temporary_html_file);

unlink($filename);

exit;
