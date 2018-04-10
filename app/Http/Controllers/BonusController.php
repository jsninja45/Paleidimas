<?php namespace App\Http\Controllers;

use App\Bonus;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class BonusController extends Controller {

    use CRUDTrait {
        destroy as traitDestroy;
    }

    protected static $model = 'App\Bonus';
    protected static $route = 'admin/bonuses';
    protected static $views = 'admin/bonuses';

    public function destroy($bonus_id)
    {
        $bonus = Bonus::findOrFail($bonus_id);
        if ($bonus->isEditable()) {
            return $this->traitDestroy($bonus_id);
        } else {
            die('this bonus can\'n be edited');
        }
    }


}