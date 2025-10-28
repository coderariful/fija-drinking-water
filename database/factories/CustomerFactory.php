<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'      => User::where('user_type', USER_EMPLOYEE)->inRandomOrder()->first()->id,
            'name'         => $this->faker->name(),
            'phone'        => str($this->faker->e164PhoneNumber())->trim('+')->toString(),
            'address'      => $this->faker->randomElement(['Khulna', 'Shib bari', 'New Market', 'Boyra', 'Boikali', 'Mujgunni', 'Khalishpur', 'Jora Gate', 'Sonadanga', 'Gollamari', 'Dak-Bangla']),
            'issue_date'   => Carbon::now(),
            'jar_rate'     => "40.00",
            'billing_type' => $this->faker->randomElement(['daily', 'monthly']),
            'status'       => Customer::APPROVED,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ];
    }
}
