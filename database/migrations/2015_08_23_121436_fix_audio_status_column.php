<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixAudioStatusColumn extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        \Illuminate\Database\Eloquent\Model::unguard();

        DB::statement("update audios set `status` = 'unpaid' where `status` is null");
        DB::statement("ALTER TABLE `audios` CHANGE `status` `status` ENUM('unpaid','in_transcription','available_for_editing','in_editing','available_for_subtitling','in_subtitling','finished') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unpaid';");

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