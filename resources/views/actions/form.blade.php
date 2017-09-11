<div class="form-group">
    {!! Form::label('name', trans('messages.title')) !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('body', trans('messages.description')) !!}
    {!! Form::textarea('body', null, ['id' => 'wysiwyg', 'class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('location', trans('messages.location')) !!}
    {!! Form::textarea('location', null, ['class' => 'form-control']) !!}
</div>

<div class="row">

    <div class='col-md-6'>
        <div class="form-group">
            {!! Form::label('start', trans('messages.starts')) !!}<br/>
            <div class='input-group date' id='start'>
                @if (isset($action->start))
                    {!! Form::text('start', $action->start->format('Y-m-d H:i') , ['class' => 'form-control',  'required']) !!}
                @else
                    {!! Form::text('start', \Carbon\Carbon::now()->format('Y-m-d H:i') , ['class' => 'form-control',  'required']) !!}
                @endif
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class='col-md-6'>
        <div class="form-group">
            {!! Form::label('stop', trans('messages.ends')) !!}<br/>
            <div class='input-group date' id='stop'>
                @if (isset($action->stop))
                    {!! Form::text('stop', $action->stop->format('Y-m-d H:i') , ['class' => 'form-control',  'required']) !!}
                @else
                    {!! Form::text('stop', null , ['class' => 'form-control', 'required']) !!}
                @endif
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
</div>

@include ('partials.wysiwyg')


@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" />
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>


    <script type="text/javascript">
    $(function () {
        $('#start').datetimepicker({
            locale: '{{App::getLocale()}}',
            sideBySide: true,
            format: 'YYYY-MM-DD HH:mm'
        });
        $('#stop').datetimepicker({
            useCurrent: false, //Important! See issue #1075
            locale: '{{App::getLocale()}}',
            sideBySide: true,
            format: 'YYYY-MM-DD HH:mm'
        });
        $("#start").on("dp.change", function (e) {
            $('#stop').data("DateTimePicker").minDate(e.date);
        });
        $("#stop").on("dp.change", function (e) {
            $('#start').data("DateTimePicker").maxDate(e.date);
        });
    });
    </script>


@endpush
