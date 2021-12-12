<?php

use Illuminate\Database\Seeder;
use App\Models\Clazz;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo now()->toDateTimeString()." -- ClassSeeder START!\n";

        $now = \now();
        $classnames = [
            [
                'name' => 'クラスAAA',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'クラスBBB',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'クラスCCC',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'クラスDDD',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'クラスEEE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        Clazz::insert($classnames);

        echo now()->toDateTimeString()." -- ClassSeeder FINISH!\n";
    }
}
