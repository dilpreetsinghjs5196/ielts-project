<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Test;
use App\Models\Category;

echo "Total Tests: " . Test::count() . "\n";
$tests = Test::all();
foreach ($tests as $t) {
    echo "ID: " . $t->id . " | Name: " . $t->name . " | Category ID: " . $t->category_id . "\n";
}

$categories = Category::all();
foreach ($categories as $c) {
    echo "Category ID: " . $c->id . " | Name: " . $c->name . " | Slug: " . $c->slug . "\n";
}
