<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$test = App\Models\Test::find(5);
if (!$test) {
    echo "Test 5 not found\n";
    exit;
}

foreach ($test->questionGroups as $g) {
    echo "Group: " . $g->title . " (Part " . ($g->id) . ")\n";
    foreach ($g->questions as $q) {
        echo "  - QID: " . $q->id . ", Num: '" . $q->question_number . "', Type: " . $q->question_type . "\n";
    }
}
