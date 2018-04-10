<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Bonus;
use App\Salary;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;

class SalaryController extends Controller {

	public function index()
	{
		$users = User::whereHas('roles', function($q) {
			$q->where(function($q) {
				$q->whereIn('name', ['transcriber', 'editor', 'subtitler']);
			});
		})->where('deleted', 0)->get();
		$users->load('roles');

		$data = [];
		foreach ($users as $user) {

			$from = Salary::from($user);
			$till = Salary::till($user);
			$amount = Salary::amount($user, $from, $till);
            $bonus = Salary::amount($user, $from, $till, 'bonus');

			$user->data  = [
				'from' => $from,
				'till' => $till,
                'amount' => $amount, // full amount with bonus
                'bonus' => $bonus,
			];
		}
		
		return view('admin.salaries', compact('users'));
	}

	public function pay($user_id)
	{
		$user = User::findOrFail($user_id);

		$from = Salary::from($user);
		$till = Salary::till($user);
		$amount = Salary::amount($user, $from, $till);

		Transaction::payToUser($user->id, $from, $till, $amount);

		return redirect()->back();
	}


}
