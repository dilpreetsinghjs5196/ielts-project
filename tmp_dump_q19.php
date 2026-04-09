<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$q = App\Models\Question::find(19);
if ($q) {
    echo "QID: " . $q->id . "\n";
    echo "Type: " . $q->question_type . "\n";
    echo "Options: " . json_encode($q->options) . "\n";
    echo "Settings: " . json_encode($q->settings) . "\n";
} else {
    echo "Q19 not found\n";
}
