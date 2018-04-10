<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CopyLanguageFiles extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('languages', function($table)
        {
            $table->boolean('hide')->default(0)->after('name');
        });


        if (!File::exists(storage_path() . '/app/flags/')) {
            \Illuminate\Support\Facades\File::makeDirectory(storage_path() . '/app/flags/');
        }

        $languages = \App\Language::get();
        foreach ($languages as $language) {
            $old_path = self::oldLanguagePath($language);
            $new_path = $language->imagePath();
            \Illuminate\Support\Facades\File::copy($old_path, $new_path);
        }


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

    public static function oldLanguagePath( \App\Language $language)
    {
        $image_name = strtolower($language->name);

        return public_path() . "/img/pages/flags/$image_name.png";
    }

}