<?php 

namespace Facenoob\Http\Controllers;

use Auth;
use Facenoob\Models\User;
use Illuminate\Http\Request;


class AuthController extends Controller{
	public function getSignup(){

		return view('auth.signup');

	}

	public function postSignup(Request $request){

		$this->validate($request,[
			'email' => 'required|unique:users|email|max:255',
			'username' => 'required|unique:users|alpha_dash|max:20',
			'password' => 'required|min:6'
			]);

		User::create([
			'email' => $request->input('email'),
			'username' => $request->input('username'),
			'password' => bcrypt($request->input('password')),
		]);

		return redirect()
			->route('home')
			->with('info',
			'Your account has been created, you may now sign in.');
	}

	public function getSignin(){

		return view('auth.signin');
	}

	public function postSignin(Request $request){

		$this->validate( $request,[
			'email' => 'required',
			'password' => 'required'

		]);

		if(!Auth::attempt($request->only(['email', 'password']), $request->has('remember'))){

			return redirect()->back()->with('info', 'Something went wrong.');

		}
		
		return redirect()->route('home')->with('info', 'Youre now signed in!');

	}

	public function getSignout(){

		Auth::logout();
		return redirect()->route('home')->with('info', 'You have been logged out. Come back later.');
	}
}
?>