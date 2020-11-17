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
    {!! Form::text('start_date', $action->start->format('Y-m-d') , ['class' => 'form-control datepicker']) !!}
</div>

<div class="form-group">
    {{trans('messages.start_time')}}
    {!! Form::text('start_time', $action->start->format('H:i') , ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {{trans('messages.stop_time')}}
    @if (isset($action->stop))
        {!! Form::text('stop_time', $action->stop->format('H:i') , ['class' => 'form-control', 'required']) !!}
    @else
        {!! Form::text('stop_time', null , ['class' => 'form-control']) !!}
    @endif
</div>

<div class="form-group">
    {{trans('messages.stop_date')}}
    @if ((isset($action->stop)) && ($action->stop->format('Y-m-d') <> $action->start->format('Y-m-d')))
        {!! Form::text('stop_date', $action->stop->format('Y-m-d') , ['class' => 'form-control datepicker']) !!}
    @else
        {!! Form::text('stop_date', null , ['class' => 'form-control datepicker']) !!}
    @endif
</div>



@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker3.min.css" />
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>

    <script>
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
    </script>
@endpush
