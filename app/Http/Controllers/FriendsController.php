<?php 

namespace Facenoob\Http\Controllers;

use Auth;
use Facenoob\Models\User;
use Illuminate\Http\Request;

class FriendsController extends Controller{

	public function getIndex(){

		$friends = Auth::user()->friends();
		$requests = Auth::user()->friendsRequests();

		return view('friends.index')
			->with('friends', $friends)
			->with('requests', $requests);
	}

	public function getAdd($username){

		$user = User::where('username', $username)->first();

		if (!$user){
			return redirect()
				->route('home')
				->with('info', 'User not found');
		}

		/*Self-friending protection*/
		if (Auth::user()->id === $user->id){
			return redirect()
				->route('home')
				->with('info', 'You cannot add yourself as a friend.');
		}

		/*Friend request pending*/
		if (Auth::user()->hasFriendRequestPending($user) || $user->hasFriendRequestPending(Auth::user())){
			return redirect()
				->route('profile.index', ['username' => $user->username])
				->with('info', 'Friend request is already pending.');
		}

		/*Already friends*/
		if (Auth::user()->isFriendsWith($user)){
			return redirect()
				->route('profile.index', ['username' => $user->username])
				->with('info', 'You are already friends.');
		}

		Auth::user()->addFriend($user);

		return redirect()
			->route('profile.index', ['username' => $user->username])
			->with('info', 'Friend request sent.');

	}

	public function getAccept($username){

		$user = User::where('username', $username)->first();

		if (!$user){
			return redirect()
				->route('home')
				->with('info', 'User account has not been found, please try again.');
		}

		if (!Auth::user()->hasFriendRequestReceived($user)){
			return redirect()
				->route('home');
		}

		Auth::user()->acceptFriendRequest($user);

		return redirect()
				->route('profile.index', ['username' => $username])
				->with('info', 'Friendship request has been accepted.');

	}
}
?>