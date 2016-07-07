<?php 

namespace Facenoob\Http\Controllers;

use Auth;
use Facenoob\Models\User;
use Facenoob\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller{

	public function postStatus(Request $request){

		$this->validate($request,[
			'status'=>'required|max:200'
			]);

		Auth::user()->statuses()->create([
			'status' => $request->input('status'),
		]);

		return redirect()
			->route('home')
			->with('info', 'Posted.');

		//return view('search.results')->with('users', $users);

	}

	public function postComment(Request $request, $statusId){

		$this->validate($request,[
			"reply-{$statusId}" => 'required|max:200',
			], [
			'required' => 'The comment field is obviously required. It is quite essential part of commenting, you know. To actually write something in there. So why won`t you try it next time, before clicking that damn button and therefore wasting my precious computation resources, hmm?'
			]);

		$status = Status::notReply()->find($statusId);

		if (!$status){
			return redirect()
				->route('home')
				->with('info', 'The post, on which you have tried to post comment, unfortunately, has not been found.');
		}

		/*Can comment only friends or self*/
		if(!Auth::user()->isFriendsWith($status->user) && Auth::user()->id !== $status->user->id){
			return redirect()
				->route('home')
				->with('info', 'You can`t comment posts of a person you are no longer, or have never been, a friend with.');
		}

		$comment = Status::create([
			'status' => $request->input("reply-{$statusId}"),		
		])->user()->associate(Auth::user());

		$status->comments()->save($comment);

		return redirect()->back();

	}
}
?>