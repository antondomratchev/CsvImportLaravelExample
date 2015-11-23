<?php

namespace Acme\Importing;

use DB;

class CsvFileImporter
{
    public function import($csv_import, $csv_import_id)
    {
        $moved_file = $this->moveFile($csv_import);

        $normalized_file = $this->normalize($moved_file);

        $csv_header = $this->getCSVHeader($moved_file);

        return $this->importFileContents($normalized_file, $csv_import_id, $csv_header);
    }

    private function moveFile($csv_import)
    {
        if (is_dir($destination_directory = storage_path('imports/tmp'))) {
            chmod($destination_directory, 0755);
        } else {
            mkdir($destination_directory, 0755, true);
        }

        $original_file_name = $csv_import->getClientOriginalName();

        return $csv_import->move($destination_directory, $original_file_name);
    }

    private function normalize($file_path)
    {
        $string = @file_get_contents($file_path);

        if (!$string) {
            return $file_path;
        }

        $string = preg_replace('~\r\n?~', "\n", $string);

        file_put_contents($file_path, $string);

        return $file_path;
    }

    private function importFileContents($file_path, $csv_import_id, $csv_header)
    {
        $query = sprintf("LOAD DATA LOCAL INFILE '%s' INTO TABLE csv_rows
            LINES TERMINATED BY '\\n'
            FIELDS TERMINATED BY '\\n'
            IGNORE 1 LINES (`content`)
            SET csv_import_id = '%s', header = '%s'", addslashes($file_path), $csv_import_id, $csv_header);

        return DB::connection()->getpdo()->exec($query);
    }

    private function getCSVHeader($file)
    {
        if (($file = fopen($file, 'r')) === false) {
            throw new \Exception("The file ({$file}) could not be opened for reading");
        }

        ini_set('auto_detect_line_endings', true);

        $header = fgets($file);

        fclose($file);

        return $header;
    }
}
