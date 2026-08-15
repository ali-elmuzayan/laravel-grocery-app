<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\ProductApproval;
use App\Models\ProductVariant;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Models\User;
use App\Models\VendorProfile;
use App\Models\WalletAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    private const PASSWORD = 'password';

    public function run(): void
    {
        $admin = $this->createUser(
            name: 'Admin User',
            email: 'admin@grocery.test',
            role: 'admin',
            phone: '+15550000001',
        );

        $vendor = $this->createUser(
            name: 'Vendor User',
            email: 'vendor@gmail.com',
            role: 'vendor',
            phone: '+15550000002',
        );

        $this->createUser(
            name: 'User',
            email: 'user@gmail.com',
            role: 'user',
            phone: '+15550000003',
        );

        VendorProfile::updateOrCreate(
            ['user_id' => $vendor->id],
            [
                'business_name' => 'Fresh Farms Market',
                'status' => 'approved',
                'rating' => 4.85,
            ]
        );

        WalletAccount::updateOrCreate(
            ['user_id' => $vendor->id],
            [
                'currency' => 'USD',
                'available_balance' => 250.00,
                'pending_balance' => 75.50,
            ]
        );



        // $this->seedSampleOrder($customer, $vendor, $products['organic-bananas'], $products['whole-milk']);
    }

    private function createUser(string $name, string $email, string $role, ?string $phone = null): User
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make(self::PASSWORD),
                'phone' => $phone,
                'status' => 'active',
                'email_verified_at' => now(),
                'is_identity_verified' => true,
            ]
        );

        if (! $user->hasRole($role)) {
            $user->assignRole($role);
        }

        return $user;
    }


}
