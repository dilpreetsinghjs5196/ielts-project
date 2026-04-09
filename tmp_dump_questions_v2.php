<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$test = App\Models\Test::find(5);
if (!$test) {
    die("Test 5 not found\n");
}

echo "Test: " . $test->name . "\n";
foreach ($test->questionGroups as $index => $group) {
    echo "Group Index $index (ID: {$group->id}): {$group->title}\n";
    foreach ($group->questions as $q) {
        $num = $q->question_number ?? 'NULL';
        echo "  - QID: {$q->id}, Num: '{$num}'\n";
    }
}
