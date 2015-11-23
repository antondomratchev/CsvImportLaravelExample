<?php

namespace Acme\Importing;

use Illuminate\Validation\Factory as ValidationFactory;
use Exception;

class CsvImportValidator
{

    /**
     * Validator object
     * @var \Illuminate\Validation\Factory
     */
    private $validator;

    /**
     * Validation rules for CsvImport
     *
     */
    private $rules = [
        'csv_extension'     => 'in:csv',
        'email_column'      => 'required',
        'first_name_column' => 'required',
        'last_name_column'  => 'required',
        'fist_name'         => 'required',
        'last_name'         => 'required',
        'email'             => 'email|required'
    ];

    /**
     * Constructor for CsvImportValidator
     * @param \Illuminate\Validation\Factory $validator
     */
    public function __construct(ValidationFactory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validation method
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile
     * @return \Illuminate\Validation\Validator $validator
     */
    public function validate($csv_file_path)
    {
        // Line endings fix
        ini_set('auto_detect_line_endings', true);

        $csv_extendion = $csv_file_path->getClientOriginalExtension();

        // Open file into memory
        if ($opened_file = fopen($csv_file_path, 'r') === false) {
            throw new Exception('File cannot be opened for reading');
        }

        // Get first row of the file as the header
        $header = fgetcsv($opened_file, 0, ',');

        // Find email column
        $email_column = $this->getColumnNameByValue($header, 'email');

        // Find first_name column
        $first_name_column = $this->getColumnNameByValue($header, 'first_name');

        // Find last_name column
        $last_name_column = $this->getColumnNameByValue($header, 'last_name');

        // Get second row of the file as the first data row
        $data_row = fgetcsv($opened_file, 0, ',');

        // Combine header and first row data
        $first_row = array_combine($header, $data_row);

        // Find email in the email column
        $first_row_email = array_key_exists('email', $first_row)? $first_row['email'] : '';

        // Find first name in first_name column
        $first_row_first_name = array_key_exists('first_name', $first_row)? $first_row['first_name'] : '';

        // Find last name in last_name column
        $first_row_last_name = array_key_exists('last_name', $first_row)? $first_row['last_name'] : '';

        // Close file and free up memory
        fclose($opened_file);

        // Build our validation array
        $validation_array = [
            'csv_extension' => $csv_extendion,
            'email_column' => $email_column,
            'first_name_column' => $first_name_column,
            'last_name_column' => $last_name_column,
            'email' => $first_row_email,
            'first_name' => $first_row_first_name,
            'last_name' => $first_row_last_name
        ];

        // Return validator object
        return $this->validator->make($validation_array, $this->rules);
    }

    /**
     * Attempts to find a value in array or returns empty string
     * @param array  $array hay stack we are searching
     * @param string $key
     *
     */
    private function getColumnNameByValue($array, $value)
    {
        return in_array($value, $array)? $value : '';
    }

}
