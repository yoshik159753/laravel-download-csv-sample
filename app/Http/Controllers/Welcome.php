<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Child;
use App\Models\ParentModel as ParentModel;
use App\Models\ChildToClass;

class Welcome extends Controller
{
    public function welcome()
    {
        return view('welcome', [
            'children' => Child::orderby('id')->paginate(15),
            'parents' => ParentModel::orderby('id')->paginate(15),
        ]);
    }

    public function downloadCsvCase1()
    {
        $query = Child::join('parents', 'children.parent', 'parents.id')
            ->select([
                'children.id as child_id',
                'children.name as child_name',
                'children.kana as child_kana',
                'children.sex as child_sex',
                'children.birthday as child_birthday',
                'parents.id as parent_id',
                'parents.name as parent_name',
                'parents.kana as parent_kana',
                'parents.sex as parent_sex',
                'parents.zip as zip',
                'parents.address as address',
                'parents.tel as tel',
                'parents.email as email',
            ])
            ->orderby('children.id', 'asc');
        $families = $query->get();

        $now = \now();
        $nowYyyyMmDdHhMmSs = $now->format('Ymd-His');
        $workspace = 'tmp/'.$nowYyyyMmDdHhMmSs;
        Storage::disk('local')->makeDirectory($workspace);
        $filename = 'families.csv';
        $outputCsv = storage_path('app/'.$workspace.'/'.$filename);

        $fp = fopen($outputCsv, 'w');
        fputcsv($fp, [
            '子ID',
            '子氏名',
            '子氏名ｶﾅ',
            '子性別',
            '子生年月日',
            '所属クラス1',
            '所属クラス2',
            '所属クラス3',
            '親ID',
            '親氏名',
            '親氏名ｶﾅ',
            '親性別',
            '郵便番号',
            '住所',
            '電話番号',
            'Eメールアドレス',
        ]);
        foreach ($families as $family) {
            $query = ChildToClass::join('class', 'child_to_class.class_id', 'class.id')
                ->where('child_to_class.child_id', '=', $family->child_id);
            $childToClass = $query->get();
            $childToClassNames = $childToClass->pluck('name')->toArray();
            fputcsv($fp, [
                $family->child_id,
                $family->child_name,
                $family->child_kana,
                $family->child_sex,
                $family->child_birthday,
                $childToClassNames[0] ?? null,
                $childToClassNames[1] ?? null,
                $childToClassNames[2] ?? null,
                $family->parent_id,
                $family->parent_name,
                $family->parent_kana,
                $family->parent_sex,
                $family->zip,
                $family->address,
                $family->tel,
                $family->email,
            ]);
        }
        rewind($fp);
        $buffer = str_replace(PHP_EOL, "\r\n", stream_get_contents($fp));
        $buffer = mb_convert_encoding($buffer, 'SJIS-win', 'UTF-8');
        rewind($fp);
        fwrite($fp, $buffer);
        fclose($fp);

        return response()->download($outputCsv, $filename);
    }

    public function downloadCsvCase2()
    {
        $query = Child::join('parents', 'children.parent', 'parents.id')
            ->select([
                'children.id as child_id',
                'children.name as child_name',
                'children.kana as child_kana',
                'children.sex as child_sex',
                'children.birthday as child_birthday',
                'parents.id as parent_id',
                'parents.name as parent_name',
                'parents.kana as parent_kana',
                'parents.sex as parent_sex',
                'parents.zip as zip',
                'parents.address as address',
                'parents.tel as tel',
                'parents.email as email',
            ])
            ->orderby('children.id', 'asc');
        // $families = $query->get();

        $now = \now();
        $nowYyyyMmDdHhMmSs = $now->format('Ymd-His');
        $workspace = 'tmp/'.$nowYyyyMmDdHhMmSs;
        Storage::disk('local')->makeDirectory($workspace);
        $filename = 'families.csv';
        $outputCsv = storage_path('app/'.$workspace.'/'.$filename);

        $fp = fopen($outputCsv, 'w');
        fputcsv($fp, [
            '子ID',
            '子氏名',
            '子氏名ｶﾅ',
            '子性別',
            '子生年月日',
            '所属クラス1',
            '所属クラス2',
            '所属クラス3',
            '親ID',
            '親氏名',
            '親氏名ｶﾅ',
            '親性別',
            '郵便番号',
            '住所',
            '電話番号',
            'Eメールアドレス',
        ]);
        foreach ($query->cursor() as $family) {
            $query = ChildToClass::join('class', 'child_to_class.class_id', 'class.id')
                ->where('child_to_class.child_id', '=', $family->child_id);
            $childToClass = $query->get();
            $childToClassNames = $childToClass->pluck('name')->toArray();
            fputcsv($fp, [
                $family->child_id,
                $family->child_name,
                $family->child_kana,
                $family->child_sex,
                $family->child_birthday,
                $childToClassNames[0] ?? null,
                $childToClassNames[1] ?? null,
                $childToClassNames[2] ?? null,
                $family->parent_id,
                $family->parent_name,
                $family->parent_kana,
                $family->parent_sex,
                $family->zip,
                $family->address,
                $family->tel,
                $family->email,
            ]);
        }
        rewind($fp);
        $buffer = str_replace(PHP_EOL, "\r\n", stream_get_contents($fp));
        $buffer = mb_convert_encoding($buffer, 'SJIS-win', 'UTF-8');
        rewind($fp);
        fwrite($fp, $buffer);
        fclose($fp);

        return response()->download($outputCsv, $filename);
    }
}
