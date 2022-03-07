@extends('layouts.master')
@section('page-header')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">View Ticket</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Ticket</li>
            </ol>

        </div>

    </div>
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
	<div class="row my-tickets">
		<div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

	        		<div class="ticket-info">
	        			<p>{{ $ticket->message }}</p>
		        		<p>Categry : {{ $ticket->category }}</p>
		        		<p>
	        			@if ($ticket->status === 'Open')
    						Status : <span class="label label-success">{{ $ticket->status }}</span>
    					@else
    						Status : <span class="label label-danger">{{ $ticket->status }}</span>
    					@endif
		        		</p>
		        		<p>Created on: {{ $ticket->created_at->diffForHumans() }}</p>
	        		</div>

	        		<hr>

	        		<div class="comments">
	        			@foreach ($comments as $comment)
	        				<div class="panel panel-box panel-@if($ticket->user->id === $comment->user_id){{"default"}}@else{{"success"}}@endif">
	        					<div class="panel panel-heading p-4">
								@php $user_name = '' @endphp
								@if($comment->is_admin==0)
                    				@php $user_name = !empty($comment->user->name) ? $comment->user->name : ''; @endphp
								@else
									@php $user = DB::table('admin_users')->where('id', $comment->user_id)->first(); 
                    				$user_name = $user->username;
									@endphp
                				@endif
								
	        						{{ $user_name }}
	        						<span class="pull-right">{{ $comment->created_at->format('Y-m-d') }}</span>
	        					</div>

	        					<div class="panel panel-body">
	        						{{ $comment->comment }}
	        					</div>
	        				</div>
	        			@endforeach
	        		</div>

	        		<div class="comment-form">
		        		<form action="{{ url('comment') }}" method="POST" class="form">
		        			{!! csrf_field() !!}

		        			<input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

		        			<div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
                                <textarea rows="10" id="comment" class="form-control" name="comment"></textarea>

                                @if ($errors->has('comment'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('comment') }}</strong>
                                    </span>
                                @endif
	                        </div>

	                        <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
	                        </div>
		        		</form>
	        	</div>
	        </div>
	    </div>
	</div>
@endsection
