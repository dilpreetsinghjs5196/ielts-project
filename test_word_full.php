<?php
require 'vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;

function getRecursiveText($element) {
    $text = "";
    if (get_class($element) === 'PhpOffice\PhpWord\Element\Table') {
        foreach ($element->getRows() as $row) {
            foreach ($row->getCells() as $cell) {
                $text .= getRecursiveText($cell) . " | ";
            }
            $text .= "\n";
        }
        return $text;
    }
    if (method_exists($element, 'getElements')) {
        foreach ($element->getElements() as $child) {
            $text .= getRecursiveText($child);
        }
    } elseif (method_exists($element, 'getText')) {
        $val = $element->getText();
        if (is_string($val)) {
            $text .= $val;
        }
    }
    return $text;
}

try {
    $phpWord = IOFactory::load('writing.docx');
    $fullText = "";
    foreach ($phpWord->getSections() as $section) {
        foreach ($section->getElements() as $element) {
            $fullText .= getRecursiveText($element) . "\n";
        }
    }
    
    echo "--- EXTRACTED TEXT START ---\n";
    echo $fullText;
    echo "\n--- EXTRACTED TEXT END ---\n";
    echo "Length: " . strlen($fullText) . "\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
