<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Test;

$testId = 8;
$test = Test::with(['questionGroups.questions'])->find($testId);

if (!$test) {
    echo "Test $testId not found\n";
    exit;
}

echo "Test: " . $test->name . "\n";
foreach ($test->questionGroups as $group) {
    echo "--- Group: " . $group->title . " ---\n";
    echo "Instructions: " . $group->instruction . "\n";
    foreach ($group->questions as $q) {
        echo "  Q" . $q->question_number . " (" . $q->question_type . "): " . $q->content . "\n";
    }
}
