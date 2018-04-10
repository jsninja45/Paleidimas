<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model {

    protected $fillable = [
        'user_id',
        'amount',
        'comment',
    ];

    // ------------------------------------- relationships -------------------------------------------------------------

    public function user()
    {
        return $this->belongsTo('App\User');
    }


    // ------------------------------------- methods -------------------------------------------------------------------

    // if salary is already payed, then bonus cant be changed
    public function isEditable()
    {
        $salary_from = Salary::from($this->user);

        return ($salary_from < $this->created_at);
    }

}