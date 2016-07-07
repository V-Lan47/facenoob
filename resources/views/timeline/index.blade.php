@extends('templates.default')

@section('content')
	<div class="row">
	    <div class="col-lg-6">
	        <form role="form" action="{{  route('status.post')  }}" method="post">
	            <div class="form-group{{  $errors->has('status') ? 'has-error' : ''  }}">
	                <textarea placeholder="What's up {{  Auth::user()->getFirstNameOrUsername()  }}?" name="status" class="form-control" rows="2"></textarea>
	                @if($errors->has('status'))
	                	<span class="help-block">{{  $errors->first()  }}</span>
	                @endif
	            </div>
	            <button type="submit" class="btn btn-default">Update status</button>
	            <input type="hidden" name="_token" value="{{  Session::token()  }}">
	        </form>
	        <hr>
	    </div>
	</div>

	<div class="row">
	    <div class="col-lg-5">
	        @if (!$statuses->count())
	        	<p>There's absolutely nothing on your timeline. Go fix that, now.</p>
	        @else
	        	@foreach ($statuses as $status)	
	        		<!--Posts-->
					<div class="media">
					    <a class="pull-left" 
					    href="{{ route('profile.index', ['username' => $status->user->username])}}">
					        <img class="media-object" 
					        alt="{{ $status->user->getNameOrUsername() }}" 
					        src="{{ $status->user->getAvatarUrl() }}"
					        height="60" width="60">
					    </a>
					    <div class="media-body">
					        <h4 class="media-heading"><a 
					        href="{{ route('profile.index', ['username' => $status->user->username])}}">
					        {{ $status->user->getNameOrUsername() }}</a></h4>
					        <p> {{ $status->status }}</p>
					        <ul class="list-inline">
					            <li>{{ $status->created_at->diffForHumans() }}</li>
					            <li><a href="#">Like</a></li>
					            <li>10 likes</li>
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
							                    <li><a href="#">Like</a></li>
							                    <li><a href="#">Hate</a></li>
							                    <li>4 likes</li>
							                </ul>
							            </div>
							        </div>
								@endforeach
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
					    </div>
					</div>
	        	@endforeach
	        	{!! $statuses->render() !!}
	        @endif
	    </div>
	</div>
@stop