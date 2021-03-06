<div class="form-group">
    {!! Form::label('name', trans('messages.title')) !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('body', trans('messages.description')) !!}
    {!! Form::textarea('body', null, [
        'class' => 'form-control wysiwyg' ,
        'data-mention-files' => route('groups.files.mention', $group),
        'data-mention-discussions' => route('groups.discussions.mention', $group),
        'data-mention-users' => route('groups.users.mention', $group)
    ]
    ) !!}
</div>

@include('partials.tags_input')

<div class="form-group">
    {!! Form::label('location', trans('messages.location')) !!}
    {!! Form::text('location', null, ['class' => 'form-control', 'rows'=>4]) !!}
</div>


<div class="form-group">
    {{trans('messages.start_date')}}
    {!! Form::date('start_date', $action->start->format('Y-m-d') , ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {{trans('messages.start_time')}}
    {!! Form::time('start_time', $action->start->format('H:i') , ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {{trans('messages.stop_time')}}
    @if (isset($action->stop))
        {!! Form::time('stop_time', $action->stop->format('H:i') , ['class' => 'form-control', 'required']) !!}
    @else
        {!! Form::time('stop_time', null , ['class' => 'form-control']) !!}
    @endif
</div>

<div class="form-group">
    {{trans('messages.stop_date')}}
    @if ((isset($action->stop)) && ($action->stop->format('Y-m-d') <> $action->start->format('Y-m-d')))
        {!! Form::date('stop_date', $action->stop->format('Y-m-d') , ['class' => 'form-control']) !!}
    @else
        {!! Form::date('stop_date', null , ['class' => 'form-control']) !!}
    @endif
</div>