<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OldOrder extends Model {

    /**
     * The database connection used by the model.
     *
     * @var string
     */
    protected $connection = 'drupal_mysql';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'speech2txt';

    public function getTranscriptionPath()
    {
        $file = str_replace('private://', '', $this->t_uri);

        return base_path('drupal/files/') . $file;
    }
}