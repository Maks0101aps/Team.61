<?php
require './vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$account = App\Models\Account::first();
if (!$account) {
    $account = App\Models\Account::create(['name' => 'School Account']);
    echo "Created new account with ID: {$account->id}\n";
}

$parent = App\Models\ParentModel::create([
    'account_id' => $account->id,
    'first_name' => 'Test',
    'middle_name' => 'Parent',
    'last_name' => 'User',
    'email' => 'testparent@example.com',
    'phone' => '555-123-4567',
    'city' => 'Test City',
    'region' => 'Test Region',
    'country' => 'UA',
    'postal_code' => '12345'
]);

echo "Created parent with ID: {$parent->id}\n";

$parents = App\Models\ParentModel::all();
echo "Total parents: {$parents->count()}\n";
foreach ($parents as $p) {
    echo "Parent: {$p->first_name} {$p->middle_name} {$p->last_name} (ID: {$p->id})\n";
} 