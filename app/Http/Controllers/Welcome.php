<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;
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
            ->select($this->selectBaseItems())
            ->orderby('children.id', 'asc');
        $families = $query->get();

        $now = \now();
        $nowYyyyMmDdHhMmSs = $now->format('Ymd-His');
        $workspace = 'tmp/'.$nowYyyyMmDdHhMmSs;
        Storage::disk('local')->makeDirectory($workspace);
        $filename = 'families.csv';
        $outputCsv = storage_path('app/'.$workspace.'/'.$filename);

        $fp = fopen($outputCsv, 'w');
        fputcsv($fp, $this->header());
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
            ->select($this->selectBaseItems())
            ->orderby('children.id', 'asc');

        $now = \now();
        $nowYyyyMmDdHhMmSs = $now->format('Ymd-His');
        $workspace = 'tmp/'.$nowYyyyMmDdHhMmSs;
        Storage::disk('local')->makeDirectory($workspace);
        $filename = 'families.csv';
        $outputCsv = storage_path('app/'.$workspace.'/'.$filename);

        $fp = fopen($outputCsv, 'w');
        fputcsv($fp, $this->header());
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

    public function downloadCsvCase3()
    {
        $query = Child::join('parents', 'children.parent', 'parents.id')
            ->join('child_to_class', 'children.id', 'child_to_class.child_id')
            ->join('class', 'child_to_class.class_id', 'class.id');
        $selectBaseItems = array_merge(
            $this->selectBaseItems(),
            [DB::raw('GROUP_CONCAT(class.name) AS class_names')]
        );
        $query->select($selectBaseItems)
            ->groupby('children.id')
            ->orderby('children.id', 'asc');

        $now = \now();
        $nowYyyyMmDdHhMmSs = $now->format('Ymd-His');
        $workspace = 'tmp/'.$nowYyyyMmDdHhMmSs;
        Storage::disk('local')->makeDirectory($workspace);
        $filename = 'families.csv';
        $outputCsv = storage_path('app/'.$workspace.'/'.$filename);

        $fp = fopen($outputCsv, 'w+');
        fputcsv($fp, $this->header());
        foreach ($query->cursor() as $family) {
            fputcsv($fp, $this->familyToColumn($family));
        }
        // ??????????????????
        fflush($fp);
        rewind($fp);
        $buffer = str_replace(PHP_EOL, "\r\n", stream_get_contents($fp));
        $buffer = mb_convert_encoding($buffer, 'SJIS-win', 'UTF-8');
        rewind($fp);
        // ???????????????????????????????????????????????????????????????????????????????????????
        ftruncate($fp, 0);
        fwrite($fp, $buffer);
        fclose($fp);

        // js ?????? watch ???????????? httponly ??? false ?????????
        Cookie::queue("watchKeyDownloadCsv", "true", 0, "", "", false, false);
        return response()->download($outputCsv, $filename);
    }

    public function downloadCsvCase4()
    {
        $query = Child::join('parents', 'children.parent', 'parents.id')
            ->join('child_to_class', 'children.id', 'child_to_class.child_id')
            ->join('class', 'child_to_class.class_id', 'class.id');
        $selectBaseItems = array_merge(
            $this->selectBaseItems(),
            [DB::raw('GROUP_CONCAT(class.name) AS class_names')]
        );
        $query->select($selectBaseItems)
            ->groupby('children.id')
            ->orderby('children.id', 'asc');

        $filename = 'families.csv';

        $fp = fopen('php://temp', 'r+b');
        fputcsv($fp, $this->header());
        foreach ($query->cursor() as $family) {
            fputcsv($fp, $this->familyToColumn($family));
        }
        rewind($fp);
        $buffer = str_replace(PHP_EOL, "\r\n", stream_get_contents($fp));
        $buffer = mb_convert_encoding($buffer, 'SJIS-win', 'UTF-8');

        Cookie::queue("watchKeyDownloadCsv", "true", 0, "", "", false, false);
        return Response::make($buffer, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function downloadCsvCase5()
    {
        $query = Child::join('parents', 'children.parent', 'parents.id')
            ->join('child_to_class', 'children.id', 'child_to_class.child_id')
            ->join('class', 'child_to_class.class_id', 'class.id');
        $selectBaseItems = array_merge(
            $this->selectBaseItems(),
            [DB::raw('GROUP_CONCAT(class.name) AS class_names')]
        );
        $query->select($selectBaseItems)
            ->groupby('children.id')
            ->orderby('children.id', 'asc');

        $now = \now();
        $nowYyyyMmDdHhMmSs = $now->format('Ymd-His');
        $workspace = 'tmp/'.$nowYyyyMmDdHhMmSs;
        Storage::disk('local')->makeDirectory($workspace);
        $filename = 'families.csv';
        $outputCsv = storage_path('app/'.$workspace.'/'.$filename);

        $csvWriter = $this->csvWriter($outputCsv);
        $csvWriter->insertOne($this->header());
        foreach ($query->cursor() as $family) {
            $csvWriter->insertOne($this->familyToColumn($family));
        }

        Cookie::queue("watchKeyDownloadCsv", "true", 0, "", "", false, false);
        return response()->download($outputCsv, $filename);
    }

    public function downloadCsvCase6()
    {
        $query = Child::join('parents', 'children.parent', 'parents.id')
            ->join('child_to_class', 'children.id', 'child_to_class.child_id')
            ->join('class', 'child_to_class.class_id', 'class.id');
        $selectBaseItems = array_merge(
            $this->selectBaseItems(),
            [DB::raw('GROUP_CONCAT(class.name) AS class_names')]
        );
        $query->select($selectBaseItems)
            ->groupby('children.id')
            ->orderby('children.id', 'asc');

        $now = \now();
        $nowYyyyMmDdHhMmSs = $now->format('Ymd-His');
        $workspace = 'tmp/'.$nowYyyyMmDdHhMmSs;
        Storage::disk('local')->makeDirectory($workspace);
        $filename = 'families.csv';
        $outputCsv = storage_path('app/'.$workspace.'/'.$filename);

        $csvWriter = $this->csvWriter($outputCsv);
        $csvWriter->insertOne($this->header());

        $records = [];
        foreach ($query->cursor() as $family) {
            $records[] = $this->familyToColumn($family);
            if (count($records) >= 10000) {
                $csvWriter->insertAll($records);
                $records = [];
            }
        }
        if (count($records) > 0) {
            $csvWriter->insertAll($records);
        }

        Cookie::queue("watchKeyDownloadCsv", "true", 0, "", "", false, false);
        return response()->download($outputCsv, $filename);
    }

    public function downloadCsvCase7()
    {
        $query = Child::orderby('children.id', 'asc');

        $now = \now();
        $nowYyyyMmDdHhMmSs = $now->format('Ymd-His');
        $workspace = 'tmp/'.$nowYyyyMmDdHhMmSs;
        Storage::disk('local')->makeDirectory($workspace);
        $filename = 'families.csv';
        $outputCsv = storage_path('app/'.$workspace.'/'.$filename);

        $fp = fopen($outputCsv, 'w');
        fputcsv($fp, $this->header());
        foreach ($query->cursor() as $child) {
            $classes = $child->classes;
            fputcsv($fp, [
                $child->id,
                $child->name,
                $child->kana,
                $child->sex,
                $child->birthday,
                $classes->get(0)->class->name ?? null,
                $classes->get(1)->class->name ?? null,
                $classes->get(2)->class->name ?? null,
                $child->toParent->id,
                $child->toParent->name,
                $child->toParent->kana,
                $child->toParent->sex,
                $child->toParent->zip,
                $child->toParent->address,
                $child->toParent->tel,
                $child->toParent->email,
            ]);
        }
        rewind($fp);
        $buffer = str_replace(PHP_EOL, "\r\n", stream_get_contents($fp));
        $buffer = mb_convert_encoding($buffer, 'SJIS-win', 'UTF-8');
        rewind($fp);
        fwrite($fp, $buffer);
        fclose($fp);

        Cookie::queue("watchKeyDownloadCsv", "true", 0, "", "", false, false);
        return response()->download($outputCsv, $filename);
    }

    public function downloadCsvCase8()
    {
        $query = Child::with('classes.class')->orderby('children.id', 'asc');

        $now = \now();
        $nowYyyyMmDdHhMmSs = $now->format('Ymd-His');
        $workspace = 'tmp/'.$nowYyyyMmDdHhMmSs;
        Storage::disk('local')->makeDirectory($workspace);
        $filename = 'families.csv';
        $outputCsv = storage_path('app/'.$workspace.'/'.$filename);

        $fp = fopen($outputCsv, 'w');
        fputcsv($fp, $this->header());
        foreach ($query->cursor() as $child) {
            $classes = $child->classes;
            fputcsv($fp, [
                $child->id,
                $child->name,
                $child->kana,
                $child->sex,
                $child->birthday,
                $classes->get(0)->class->name ?? null,
                $classes->get(1)->class->name ?? null,
                $classes->get(2)->class->name ?? null,
                $child->toParent->id,
                $child->toParent->name,
                $child->toParent->kana,
                $child->toParent->sex,
                $child->toParent->zip,
                $child->toParent->address,
                $child->toParent->tel,
                $child->toParent->email,
            ]);
        }
        // ??????????????????
        fflush($fp);
        rewind($fp);
        $buffer = str_replace(PHP_EOL, "\r\n", stream_get_contents($fp));
        $buffer = mb_convert_encoding($buffer, 'SJIS-win', 'UTF-8');
        rewind($fp);
        // ???????????????????????????????????????????????????????????????????????????????????????
        ftruncate($fp, 0);
        fwrite($fp, $buffer);
        fclose($fp);

        Cookie::queue("watchKeyDownloadCsv", "true", 0, "", "", false, false);
        return response()->download($outputCsv, $filename);
    }

    public function downloadCsvCase9()
    {
        $query = Child::with('classes')->orderby('children.id', 'asc');

        $now = \now();
        $nowYyyyMmDdHhMmSs = $now->format('Ymd-His');
        $workspace = 'tmp/'.$nowYyyyMmDdHhMmSs;
        Storage::disk('local')->makeDirectory($workspace);
        $filename = 'families.csv';
        $outputCsv = storage_path('app/'.$workspace.'/'.$filename);

        $fp = fopen($outputCsv, 'w+');
        fputcsv($fp, $this->header());
        $query->chunk(10000, function ($children) use (&$fp) {
            foreach ($children as $child) {
                $classes = $child->classes;
                fputcsv($fp, [
                    $child->id,
                    $child->name,
                    $child->kana,
                    $child->sex,
                    $child->birthday,
                    $classes->get(0)->class->name ?? null,
                    $classes->get(1)->class->name ?? null,
                    $classes->get(2)->class->name ?? null,
                    $child->toParent->id,
                    $child->toParent->name,
                    $child->toParent->kana,
                    $child->toParent->sex,
                    $child->toParent->zip,
                    $child->toParent->address,
                    $child->toParent->tel,
                    $child->toParent->email,
                ]);
            }
        });
        // ??????????????????
        fflush($fp);
        rewind($fp);
        $buffer = str_replace(PHP_EOL, "\r\n", stream_get_contents($fp));
        $buffer = mb_convert_encoding($buffer, 'SJIS-win', 'UTF-8');
        rewind($fp);
        // ???????????????????????????????????????????????????????????????????????????????????????
        ftruncate($fp, 0);
        fwrite($fp, $buffer);
        fclose($fp);

        Cookie::queue("watchKeyDownloadCsv", "true", 0, "", "", false, false);
        return response()->download($outputCsv, $filename);
    }

    public function downloadCsvCase10()
    {
        $query = Child::with('classes.class')->orderby('children.id', 'asc');

        $now = \now();
        $nowYyyyMmDdHhMmSs = $now->format('Ymd-His');
        $workspace = 'tmp/'.$nowYyyyMmDdHhMmSs;
        Storage::disk('local')->makeDirectory($workspace);
        $filename = 'families.csv';
        $outputCsv = storage_path('app/'.$workspace.'/'.$filename);

        $fp = fopen($outputCsv, 'w+');
        fputcsv($fp, $this->header());
        $query->chunk(10000, function ($children) use (&$fp) {
            foreach ($children as $child) {
                $classes = $child->classes;
                fputcsv($fp, [
                    $child->id,
                    $child->name,
                    $child->kana,
                    $child->sex,
                    $child->birthday,
                    $classes->get(0)->class->name ?? null,
                    $classes->get(1)->class->name ?? null,
                    $classes->get(2)->class->name ?? null,
                    $child->toParent->id,
                    $child->toParent->name,
                    $child->toParent->kana,
                    $child->toParent->sex,
                    $child->toParent->zip,
                    $child->toParent->address,
                    $child->toParent->tel,
                    $child->toParent->email,
                ]);
            }
        });
        // ??????????????????
        fflush($fp);
        rewind($fp);
        $buffer = str_replace(PHP_EOL, "\r\n", stream_get_contents($fp));
        $buffer = mb_convert_encoding($buffer, 'SJIS-win', 'UTF-8');
        rewind($fp);
        // ???????????????????????????????????????????????????????????????????????????????????????
        ftruncate($fp, 0);
        fwrite($fp, $buffer);
        fclose($fp);

        Cookie::queue("watchKeyDownloadCsv", "true", 0, "", "", false, false);
        return response()->download($outputCsv, $filename);
    }

    public function downloadCsvCaseZ1()
    {
        $query = Child::with('classes.class')->orderby('children.id', 'asc');

        $csvWriter = $this->csvWriter('php://temp');
        $csvWriter->insertOne($this->header());

        $query->chunk(10000, function ($children) use ($csvWriter) {
            foreach ($children as $child) {
                $csvWriter->insertOne($this->childToColumn($child));
            }
        });

        Cookie::queue("watchKeyDownloadCsv", "true", 0, "", "", false, false);
        return Response::make($csvWriter->getContent(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=families.csv',
        ]);
    }

    public function downloadCsvCaseZ2()
    {
        $query = Child::join('parents', 'children.parent', 'parents.id')
            ->join('child_to_class', 'children.id', 'child_to_class.child_id')
            ->join('class', 'child_to_class.class_id', 'class.id');
        $selectBaseItems = array_merge(
            $this->selectBaseItems(),
            [DB::raw('GROUP_CONCAT(class.name) AS class_names')]
        );
        $query->select($selectBaseItems)
            ->groupby('children.id')
            ->orderby('children.id', 'asc');

        $csvWriter = $this->csvWriter('php://temp');
        $csvWriter->insertOne($this->header());

        foreach ($query->cursor() as $family) {
            $csvWriter->insertOne($this->familyToColumn($family));
        }

        Cookie::queue("watchKeyDownloadCsv", "true", 0, "", "", false, false);
        return Response::make($csvWriter->getContent(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=families.csv',
        ]);
    }

    public function selectBaseItems()
    {
        return [
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
        ];
    }

    public function header()
    {
        return [
            '???ID',
            '?????????',
            '???????????????',
            '?????????',
            '???????????????',
            '???????????????1',
            '???????????????2',
            '???????????????3',
            '???ID',
            '?????????',
            '???????????????',
            '?????????',
            '????????????',
            '??????',
            '????????????',
            'E?????????????????????',
        ];
    }

    function csvWriter($path)
    {
        $csvWriter = \League\Csv\Writer::createFromPath($path, 'w+');

        $csvWriter->setDelimiter(",");
        $csvWriter->setEnclosure('"');
        $csvWriter->setEscape("\\");
        $csvWriter->setNewline("\r\n");

        $converter = (new \League\Csv\CharsetConverter())
            ->inputEncoding('UTF-8')
            ->outputEncoding('SJIS-win');
        $csvWriter->addFormatter($converter);

        return $csvWriter;
    }

    function familyToColumn($family)
    {
        return [
            $family->child_id,
            $family->child_name,
            $family->child_kana,
            $family->child_sex,
            $family->child_birthday,
            explode(',', $family->class_names)[0] ?? null,
            explode(',', $family->class_names)[1] ?? null,
            explode(',', $family->class_names)[2] ?? null,
            $family->parent_id,
            $family->parent_name,
            $family->parent_kana,
            $family->parent_sex,
            $family->zip,
            $family->address,
            $family->tel,
            $family->email,
        ];
    }

    function childToColumn($child)
    {
        $classes = $child->classes;
        return [
            $child->id,
            $child->name,
            $child->kana,
            $child->sex,
            $child->birthday,
            $classes->get(0)->class->name ?? null,
            $classes->get(1)->class->name ?? null,
            $classes->get(2)->class->name ?? null,
            $child->toParent->id,
            $child->toParent->name,
            $child->toParent->kana,
            $child->toParent->sex,
            $child->toParent->zip,
            $child->toParent->address,
            $child->toParent->tel,
            $child->toParent->email,
        ];
    }
}
