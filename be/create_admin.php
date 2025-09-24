<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    // Tạo admin user mới
    $admin = new User();
    $admin->Role_ID = 1;
    $admin->Name = 'Admin Test';
    $admin->Email = 'admin@vicnex.com';
    $admin->Password = Hash::make('admin123');
    $admin->Phone = '0123456789';
    $admin->Gender = 'male';
    $admin->Status = true;
    $admin->Created_at = now();
    
    // Check if admin already exists
    $existing = User::where('Email', 'admin@vicnex.com')->first();
    if ($existing) {
        $existing->Password = Hash::make('admin123');
        $existing->save();
        echo "Admin password updated: admin@vicnex.com / admin123\n";
    } else {
        $admin->save();
        echo "Admin created: admin@vicnex.com / admin123\n";
    }
    
    echo "Admin ID: " . ($existing ? $existing->ID : $admin->ID) . "\n";
    echo "Role ID: " . ($existing ? $existing->Role_ID : $admin->Role_ID) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}