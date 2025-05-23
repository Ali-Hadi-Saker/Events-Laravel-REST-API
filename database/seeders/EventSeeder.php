<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();//get all users from dbs

        for ($i = 0 ; $i < 200; $i++) {//create 200 events
            $user = $users->random();//get a random user from list of users
            Event::factory()->create([
                'user_id' => $user->id
            ]); 
        }
    }
}
