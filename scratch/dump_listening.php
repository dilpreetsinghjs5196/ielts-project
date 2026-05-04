<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ListeningTest;

$test = ListeningTest::with('parts.questions')->latest()->first();
if (!$test) {
    echo "No listening test found\n";
    exit;
}

echo "Listening Test ID: " . $test->id . " | Name: " . $test->name . "\n";
foreach ($test->parts as $part) {
    echo "Part " . $part->part_number . " (ID: " . $part->id . ")\n";
    echo "  Instruction: " . $part->instruction . "\n";
    foreach ($part->questions as $q) {
        echo "    Q" . $q->question_number . " (ID: " . $q->id . "): " . $q->title . "\n";
    }
}
