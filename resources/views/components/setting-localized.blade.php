@props(['name', 'value'])


<ul class="nav nav-tabs" role="tablist">
    @foreach(\Config::get('app.locales') as $locale)
    <li class="nav-item" role="presentation">
        <button class="nav-link  @if ($loop->first) active @endif" type="button" role="tab" data-bs-toggle="tab" data-bs-target="#{{$name}}-{{$locale}}" role="tab" aria-controls="home">
            {{strtoupper($locale)}}
        </button>
    </li>
    @endforeach
    <li class="nav-item" role="presentation">
        <button class="nav-link" type="button" role="tab" data-bs-toggle="tab" data-bs-target="#{{$name}}-default" role="tab" aria-controls="home">
            Default
        </button>
    </li>
</ul>

<div class="tab-content">
    @foreach(\Config::get('app.locales') as $locale)
    <div class="tab-pane @if ($loop->first) active @endif" id="{{$name}}-{{$locale}}" role="tabpanel" aria-labelledby="profile-tab">
        {!! Form::textarea($name . "[" .
        $locale . "]" , setting()->localized($locale)->get($name),
        ['class' =>
        'form-control wysiwyg']) !!}
    </div>
    @endforeach
    <div class="tab-pane" id="{{$name}}-default" role="tabpanel" aria-labelledby="profile-tab">
        {!! Form::textarea($name . "[default]" , setting()->get($name),
        ['class' =>
        'form-control wysiwyg']) !!}
    </div>
</div>