<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceOverrideColumns extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audios', function($table)
        {
            $table->decimal('editor_price_override')->nullable()->after('editor_price')->comment('admin can override price, works when audio is available');
        });

        Schema::table('audio_slices', function($table)
        {
            $table->decimal('transcriber_price_override')->nullable()->after('transcriber_price')->comment('admin can override price, works when slice is available');
        });
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