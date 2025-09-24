<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

// Database configuration
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost', 
    'database'  => 'vicnex',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Create regular user
$user = User::create([
    'Role_ID' => 3, // USER role
    'Name' => 'User Test',
    'Email' => 'user@vicnex.com',
    'Phone' => '0987654321',
    'Password' => Hash::make('user123'),
    'Gender' => 'male',
    'Status' => true,
    'Created_at' => now(),
]);

echo "Regular user created successfully!\n";
echo "Email: user@vicnex.com\n";
echo "Password: user123\n";
echo "Role_ID: 3 (USER)\n";
echo "ID: " . $user->ID . "\n";