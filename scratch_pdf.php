<?php
require 'vendor/autoload.php';
use Smalot\PdfParser\Parser;

$parser = new Parser();
$pdf = $parser->parseFile('writing.pdf');
$pages = $pdf->getPages();
echo "Total pages: " . count($pages) . "\n";
foreach ($pages as $index => $page) {
    echo "--- Page " . ($index + 1) . " ---\n";
    echo $page->getText() . "\n";
}
