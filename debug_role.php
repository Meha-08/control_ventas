<?php

use App\Models\Role;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$r = Role::find(2);
$r->load('permissions');
$a = $r->toArray();
echo 'Has permissions key: '.(array_key_exists('permissions', $a) ? 'yes' : 'no').PHP_EOL;
if (isset($a['permissions'])) {
    echo 'Count: '.count($a['permissions']).PHP_EOL;
    if (count($a['permissions']) > 0) {
        echo 'First nombre: '.$a['permissions'][0]['nombre'].PHP_EOL;
        echo 'Full first: '.json_encode($a['permissions'][0]).PHP_EOL;
    }
} else {
    echo 'Keys in role: '.implode(', ', array_keys($a)).PHP_EOL;
}
