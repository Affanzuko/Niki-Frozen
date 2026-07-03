<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$gm = App\Models\User::where('role', 'general_manager')->first();
auth()->login($gm);

$c = new App\Http\Controllers\NotificationController();
$res = $c->getUnread();

echo json_encode($res->getData());
