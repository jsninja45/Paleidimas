<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusesToOrdersAndAudios extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		\Illuminate\Database\Eloquent\Model::unguard();

		DB::statement("ALTER TABLE audios CHANGE COLUMN status status ENUM('unpaid', 'in_transcription', 'available_for_editing', 'in_editing', 'available_for_subtitling', 'in_subtitling', 'finished')");

		// new column for audios table
		Schema::table('audios', function($table)
		{
			$table->integer('subtitler_id')->nullable()->after('editor_id');
			$table->decimal('subtitler_price')->nullable()->after('editor_price');
		});

		\Illuminate\Database\Eloquent\Model::reguard();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
