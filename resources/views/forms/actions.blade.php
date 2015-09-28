{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('group_id', 'Group_id:') !!}
			{!! Form::text('group_id') !!}
		</li>
		<li>
			{!! Form::label('title', 'Title:') !!}
			{!! Form::text('title') !!}
		</li>
		<li>
			{!! Form::label('body', 'Body:') !!}
			{!! Form::textarea('body') !!}
		</li>
		<li>
			{!! Form::label('start', 'Start:') !!}
			{!! Form::text('start') !!}
		</li>
		<li>
			{!! Form::label('stop', 'Stop:') !!}
			{!! Form::text('stop') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}