<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ListeningTest;

$testId = 16; // Based on previous screenshots
$test = ListeningTest::with('parts.questions')->find($testId);

if (!$test) {
    echo "Test not found\n";
    exit;
}

foreach ($test->parts as $p_index => $part) {
    echo "Part " . ($p_index + 1) . " (ID: {$part->id}):\n";
    foreach ($part->questions as $q) {
        echo "  - Q Num: [{$q->question_number}], Type: {$q->question_type}, Title: " . substr($q->title, 0, 50) . "...\n";
    }
    echo "-------------------\n";
}
