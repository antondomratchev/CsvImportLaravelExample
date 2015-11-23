<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Schema;

class CreateCsvImportTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('csv_import', function(Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->sting('original_filename');
			$table->enum('status', ['pending', 'processing', 'processed']);
        	$table->integer('row_count');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('csv_import');
	}

}
