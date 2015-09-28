{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('user_id', 'User_id:') !!}
			{!! Form::text('user_id') !!}
		</li>
		<li>
			{!! Form::label('vote', 'Vote:') !!}
			{!! Form::text('vote') !!}
		</li>
		<li>
			{!! Form::label('votable_type', 'Votable_type:') !!}
			{!! Form::text('votable_type') !!}
		</li>
		<li>
			{!! Form::label('votable_id', 'Votable_id:') !!}
			{!! Form::text('votable_id') !!}
		</li>
		<li>
			{!! Form::label('is_spam', 'Is_spam:') !!}
			{!! Form::text('is_spam') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}