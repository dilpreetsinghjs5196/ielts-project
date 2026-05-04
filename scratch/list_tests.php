<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Test;

$tests = Test::orderBy('id', 'desc')->limit(10)->get();
foreach ($tests as $t) {
    echo "ID: " . $t->id . " | Name: " . $t->name . " | Category: " . $t->category_id . "\n";
}
