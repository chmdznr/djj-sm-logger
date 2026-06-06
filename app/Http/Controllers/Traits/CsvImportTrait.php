<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use OpenSpout\Reader\CSV\Reader as CsvReader;
use OpenSpout\Reader\ReaderInterface;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;

trait CsvImportTrait
{
    /**
     * Models that may be targeted by a CSV import. Defense-in-depth against
     * a crafted modelName resolving to an arbitrary App\Models class.
     */
    private static array $allowedImportModels = ['Responden', 'IotReading', 'SmReading'];

    public function processCsvImport(Request $request)
    {
        try {
            $filename = $request->input('filename', false);
            $path = storage_path('app/csv_import/'.basename($filename));

            $hasHeader = $request->input('hasHeader', false);

            $fields = $request->input('fields', false);
            $fields = array_flip(array_filter($fields));

            $modelName = $request->input('modelName', false);

            if (! in_array($modelName, self::$allowedImportModels, true)) {
                abort(422, 'Invalid model');
            }

            $model = "App\Models\\".$modelName;

            $insert = [];
            $reader = $this->openReader($path);

            foreach ($reader->getSheetIterator() as $sheet) {
                // OpenSpout row keys are 1-based (first row has key 1)
                foreach ($sheet->getRowIterator() as $key => $row) {
                    if ($hasHeader && $key === 1) {
                        continue;
                    }

                    $values = $row->toArray();

                    $tmp = [];
                    foreach ($fields as $header => $k) {
                        if (isset($values[$k])) {
                            $tmp[$header] = $values[$k];
                        }
                    }

                    if (count($tmp) > 0) {
                        $insert[] = $tmp;
                    }
                }

                break; // only the first sheet is imported
            }

            $reader->close();

            $for_insert = array_chunk($insert, 100);

            foreach ($for_insert as $insert_item) {
                $model::insert($insert_item);
            }

            $rows = count($insert);
            $table = Str::plural($modelName);

            File::delete($path);

            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $rows, 'table' => $table]));

            return redirect($request->input('redirect'));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function parseCsvImport(Request $request)
    {
        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $modelName = $request->input('model', false);

        if (! in_array($modelName, self::$allowedImportModels, true)) {
            abort(422, 'Invalid model');
        }

        $headers = [];
        $lines = [];

        $reader = $this->openReader($path);

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $key => $row) {
                if ($key === 1) {
                    $headers = $row->toArray();

                    continue;
                }

                $lines[] = $row->toArray();

                if (count($lines) >= 5) {
                    break 2;
                }
            }

            break;
        }

        $reader->close();

        $filename = Str::random(10).'.csv';
        $file->storeAs('csv_import', $filename);

        $fullModelName = "App\Models\\".$modelName;

        $model = new $fullModelName();
        $fillables = $model->getFillable();

        $redirect = url()->previous();

        $routeName = 'admin.'.strtolower(Str::plural(Str::kebab($modelName))).'.processCsvImport';

        return view('csvImport.parseInput', compact('headers', 'filename', 'fillables', 'hasHeader', 'modelName', 'lines', 'redirect', 'routeName'));
    }

    private function openReader(string $path): ReaderInterface
    {
        $reader = match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'xlsx' => new XlsxReader(),
            default => new CsvReader(),
        };

        $reader->open($path);

        return $reader;
    }
}
