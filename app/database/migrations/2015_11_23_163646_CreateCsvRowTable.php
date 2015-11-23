<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Schema;

class CreateCsvRowTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('csv_row', function(Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->integer('csv_import_id')->unsigned();
			$table->foreign('csv_import_id')->references('id')->on('csv_import');
			$table->text('header');
			$table->text('content');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('csv_row', function(Blueprint $table) {
			$table->dropForeign('csv_row_csv_import_id_foreign');
		});
		
		Schema::dropIfExists('csv_row');
	}

}
