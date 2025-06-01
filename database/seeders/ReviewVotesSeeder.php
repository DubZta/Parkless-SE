<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Review;

class ReviewVotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = [1, 2];

        foreach ($userIds as $userId) {
            $user = User::find($userId);

            $reviews = Review::inRandomOrder()->get();

            foreach ($reviews as $review) {
                $vote = fake()->numberBetween(1, 100) >= 50 ? 1 : -1;

                $review->voters()->syncWithoutDetaching([
                    $user->id => ['vote' => $vote],
                ]);
            }
        }
    }
}
