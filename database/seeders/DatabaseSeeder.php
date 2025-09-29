<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, Event, Ticket, Booking};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin & organizer
        User::factory()->create([
            'name'=>'Admin One','email'=>'admin1@example.com',
            'password'=>Hash::make('password'),'role'=>'admin'
        ]);
        User::factory()->create([
            'name'=>'Organizer One','email'=>'org1@example.com',
            'password'=>Hash::make('password'),'role'=>'organizer'
        ]);

        // Customers
        User::factory(8)->create(['role'=>'customer']);

        // Events + Tickets
        $organizer = User::where('role','organizer')->first();
        $events = Event::factory(5)->create(['created_by'=>$organizer->id]);

        foreach ($events as $event) {
            Ticket::factory(3)->create(['event_id'=>$event->id]);
        }

        // Bookings
        Booking::factory(20)->create();
    }
}
