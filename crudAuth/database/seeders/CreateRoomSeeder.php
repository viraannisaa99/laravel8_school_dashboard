<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class CreateRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Create room class seeder
     * @return void
     */
    public function run()
    {
        $rooms = [
            [
                'room' => '1A',
                'details' => 'Class 1A'
            ],
            [
                'room' => '2A',
                'details' => 'Class 2A'
            ],
            [
                'room' => '3A',
                'details' => 'Class 3A'
            ],
            [
                'room' => '1B',
                'details' => 'Class 1B'
            ],
            [
                'room' => '2B',
                'details' => 'Class 2B'
            ],
            [
                'room' => '3B',
                'details' => 'Class 3B'
            ]
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
