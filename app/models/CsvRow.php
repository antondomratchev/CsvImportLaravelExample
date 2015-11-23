<?php

class CsvRow extends Eloquent
{
    protected $softDelete = false;

    public $fillable = [
        'csv_import_id',
        'header',
        'content'
    ];

    public function csvImport()
    {
        $this->belongsTo('csvImport');
    }
}
