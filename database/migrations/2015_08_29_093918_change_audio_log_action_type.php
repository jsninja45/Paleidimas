<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAudioLogActionType extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `audio_logs` CHANGE `action` `action` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
        DB::statement("ALTER TABLE `audios` CHANGE `deadline_at` `editor_deadline_at` TIMESTAMP NULL DEFAULT NULL;");

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