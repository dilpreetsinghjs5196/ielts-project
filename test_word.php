<?php
require 'vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;

try {
    echo "Checking if file exists: " . (file_exists('writing.docx') ? "YES" : "NO") . "\n";
    echo "Size: " . filesize('writing.docx') . " bytes\n";
    
    $phpWord = IOFactory::load('writing.docx');
    echo "SUCCESS: Word file loaded!\n";
    
    foreach ($phpWord->getSections() as $section) {
        echo "Section found.\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "TRACE: " . $e->getTraceAsString() . "\n";
}
