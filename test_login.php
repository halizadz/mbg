<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'operator1@mbg.id')->first();
if (!$user) {
    echo "User not found\n";
    exit;
}
echo "User: {$user->name}\n";

$authService = app(\App\Services\AuthService::class);
$request = \Illuminate\Http\Request::create('/login', 'POST', ['email' => 'operator1@mbg.id', 'password' => 'Oper1@2026']);
$request->setLaravelSession(app('session')->driver());
$request->session()->start();

$result = $authService->attemptLogin(['email' => 'operator1@mbg.id', 'password' => 'Oper1@2026'], false, $request);
echo "Attempt Result: " . ($result['success'] ? 'SUCCESS' : 'FAILED - ' . $result['message']) . "\n";
