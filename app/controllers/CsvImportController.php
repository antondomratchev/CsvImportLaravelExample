<?php

namespace Acme\Controllers;

use App;
use Acme\Importing\CsvImporter;
use Acme\Importing\CsvImportValidator;
use CsvImport;
use Input;

class CsvImportController extends BaseController
{
    private $csv_import_validator;

    public function __construct(CsvImportValidator $csv_import_validator)
    {
        $this->csv_import_validator = $csv_import_validator;
    }

    public function store()
    {
        if (Input::hasFile('csv_import')) {
            $csv_file = Input::file('csv_import');

            if ($csv_file->isValid()) {
                $validator = $this->csv_import_validator->validate($csv_file);

                if ($validator->fails()) {
                    return Redirect::back()->withErrors($validator);
                }

                $original_filename = $csv_file->getClientOriginalName();

                $csv_import = CsvImport::create([
                    'original_filename' => $original_filename,
                    'status' => 'pending',
                    'row_count' => 0
                ]);

                $csv_importer = new CsvFileImporter();

                if ($csv_import->row_count = $csv_importer->import($csv_file, $csv_import->id) {
                $message = "{$csv_import->row_count} rows were imported!";
            } else {
                    $message = 'Your file did not import';
                }

            } else {
                $message = 'You must provide a CSV file for import.';
            }

            return Redirect::back()->with('message', $message);
        }
    }
}
