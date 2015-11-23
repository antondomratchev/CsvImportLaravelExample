<?php

class CsvImport extends Eloquent
{
    protected $softDelete = false;

    public $fillable = [
        'original_filename',
        'status',
        'row_count'
    ];

    public function csvRow()
    {
        return $this->hasMany('csvRows');
    }
}
