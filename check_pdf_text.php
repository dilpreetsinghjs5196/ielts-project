<?php
require 'vendor/autoload.php';

use Smalot\PdfParser\Parser;

try {
    $parser = new Parser();
    $pdf = $parser->parseFile('writing.pdf');
    $text = $pdf->getText();
    
    echo "--- START OF TEXT ---\n";
    echo $text;
    echo "\n--- END OF TEXT ---\n";
    echo "Total characters: " . strlen($text);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
