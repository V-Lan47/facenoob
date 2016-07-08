@extends('templates.default')

@section('content')
	<div class="row">
	    <div class="col-lg-5">
	        @include('user.partials.userblock')
	        <hr>

	        @if (!$statuses->count())
	        	<p>{{ $user->getFirstNameOrUsername() }} has not posted anything yet.</p>
	        @else
	        	@foreach ($statuses as $status)	
	        		<!--Posts-->
	        		<div class="post">
						<div class="media">
						    <a class="pull-left" 
						    href="{{ route('profile.index', ['username' => $status->user->username])}}">
						        <!--<img class="media-object" 
						        alt="{{ $status->user->getNameOrUsername() }}" 
						        src="{{ $status->user->getAvatarUrl() }}">-->
						    </a>
						    <div class="media-body">
						        <h4 class="media-heading"><a 
						        href="{{ route('profile.index', ['username' => $status->user->username])}}">
						        {{ $status->user->getNameOrUsername() }}</a></h4>
						        <p> {{ $status->status }}</p>
						        <ul class="list-inline">
						            <li>{{ $status->created_at->diffForHumans() }}</li>
						            <!--Redundant condition, status will always be user's-->
						            @if ($status->user->id !== Auth::user()->id)
						            	<li><a href="{{ route('status.like', ['statusId' => $status->id]) }}">Like</a></li>
						            @endif
						            	<li>{{ $status->addLike->count() }} {{ str_plural('like', $status->addLike->count())}}</li>						            
						        </ul>
							       <!--Comments-->
							       @foreach ($status->comments as $comment)
								        <div class="media">
								            <a class="pull-left" href="{{ route('profile.index', ['username' => $comment->user->username])}}">
								                <img class="media-object" 
								                alt="{{ $comment->user->getNameOrUsername() }}" 
						        				src="{{ $comment->user->getAvatarUrl() }}"
						        				height="40" width="40">
								            </a>
								            <div class="media-body">
								                <h5 class="media-heading">
								                <a href="{{ route('profile.index', ['username' => $comment->user->username])}}">
								                {{ $comment->user->getNameOrUsername() }}</a></h5>
								                <p>{{ $comment->status }}</p>
								                <ul class="list-inline">
								                    <li>{{ $comment->created_at->diffForHumans() }}</li>
								                    @if ($comment->user->id !== Auth::user()->id)
								                    	<li><a href="{{ route('status.like', ['statusId' => $comment->id]) }}">Like</a></li>
								                    @endif
								                    <li>{{ $comment->addLike->count() }} {{ str_plural('like', $comment->addLike->count())}}</li>
								                </ul>
								            </div>
								        </div>
									@endforeach

									@if ($authUserIsFriend || Auth::user()->id === $status->user->id)
							        <form role="form" 
							        action="{{ route('status.comment', ['statusId' => $status->id]) }}" method="post">
							            <div class="form-group{{ $errors->has("reply-{$status->id}") ? ' has-error' : '' }}">
							                <textarea 
							                name="reply-{{ $status->id }}" class="form-control" rows="2" placeholder="Leave a comment"></textarea>
							                @if ($errors->has("reply-{$status->id}"))
							                	<span class="help-block">{{ $errors->first("reply-{$status->id}") }}
							                	</span>
							                @endif
							            </div>
							            <input type="submit" value="Comment" class="btn btn-default btn-sm">
							            <input type="hidden" name="_token" value="{{  Session::token()  }}">
							        </form>
							        @endif

						    </div>
						    <br />
						</div>
					</div>
	        	@endforeach
	        @endif



	    </div>
	    <div class="col-lg-4 col-lg-offset-3">
		    <div class="post">
		    	@if (Auth::user()->hasFriendRequestPending($user))
		    		<p>Waiting for {{ $user->getNameOrUsername()  }} to accept your request.</p>
		    	@elseif (Auth::user()->hasFriendRequestReceived($user))
		    		<a href="{{  route('friends.accept', ['username' =>$user->username])  }}" class="btn btn-primary">Accept friendship request</a>
		    	@elseif (Auth::user()->isFriendsWith($user))
		    		<p>You and {{  $user->getNameOrUsername()  }} are friends.</p>
		    	@elseif(Auth::user()->id !== $user->id)
		    		<a href="{{  route('friends.add', ['username' =>$user->username])  }}" class="btn btn-primary">Send friendship request</a>
				@elseif(Auth::user()->id === $user->id)
		    		<a href="{{ route('profile.edit') }}" class="btn btn-primary">Update profile</a>
		    	@endif
		        <h4>{{ $user->getFirstNameOrUsername()  }}'s friends.</h4>

		        @if (!$user->friends()->count())
		        	<p>{{ $user->getFirstNameOrUsername()  }} has no friends.
		        @else
		        	@foreach($user->friends() as $user)
		        		@include('user/partials/friendsblock')
		        	@endforeach
		        @endif
		    </div>	
	    </div>
</div>
@stop
