<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Clazz;
use App\Models\ParentModel;
use App\Models\Child;
use App\Models\ChildToClass;

class FamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo now()->toDateTimeString()." -- FamilySeeder START!\n";

        $this->main();

        echo now()->toDateTimeString()." -- FamilySeeder FINISH!\n";
    }

    public function main()
    {
        $faker = Faker\Factory::create('ja_JP');
        $classes = Clazz::all();
        $classIds = $classes->pluck('id');

        $parents = [];
        $children = [];
        $childrenToClassList = [];

        $limit = 200000;
        for ($index = 1; $index <= $limit; $index++) {
            $now = \now();

            $sei = $faker->lastName();
            $seiKana = $faker->lastKanaName();
            $address = explode("  ", $faker->address());

            $parents[] = [
                'id' => $index,
                'created_at' => $now,
                'updated_at' => $now,
                'name' => $sei.'　'.$faker->firstName(),
                'kana' => mb_convert_kana($seiKana.' '.$faker->firstKanaName(), "rnaskh"),
                'sex' => collect([1, 2])->random(1)->first(),
                'comment' => $faker->paragraph(),
                'zip' => $address[0],
                'address' => $address[1],
                'tel' => $faker->phoneNumber(),
                'email' => $faker->email(),
            ];

            $children[] = [
                'id' => $index,
                'created_at' => $now,
                'updated_at' => $now,
                'parent' => $index,
                'name' => $sei.'　'.$faker->firstName(),
                'kana' => mb_convert_kana($seiKana.' '.$faker->firstKanaName(), "rnaskh"),
                'sex' => collect([1, 2])->random(1)->first(),
                'birthday' => Carbon::instance($faker->dateTimeBetween('-7 years')),
                'comment' => $faker->paragraph(),
            ];

            foreach ($classIds->random(collect([1, 2, 3])->random(1)->first()) as $classId) {
                $childrenToClassList[] = [
                    'created_at' => $now,
                    'updated_at' => $now,
                    'child_id' => $index,
                    'class_id' => $classId,
                ];
            }

            if (count($parents) > 1000) {
                ParentModel::insert($parents);
                Child::insert($children);
                ChildToClass::insert($childrenToClassList);
                $parents = [];
                $children = [];
                $childrenToClassList = [];
            }
        }
        if (count($parents) > 0) {
            ParentModel::insert($parents);
            Child::insert($children);
            ChildToClass::insert($childrenToClassList);
        }
    }
}
