<?php

use Illuminate\Database\Seeder;

class QuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insert = [];

        $faker = Faker\Factory::create();

        for($i = 0; $i <= 50; $i ++) {
            $insert[] = [
                'title' => $faker->word,
                'body' => $faker->text(500),
            ];
        }

        DB::table('questions')->insert($insert);
    }
}
