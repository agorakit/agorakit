@props(['name', 'value'])


<ul class="nav nav-tabs" id="myTab" role="tablist">
    @foreach(\Config::get('app.locales') as $locale)
    <li class="nav-item" role="presentation">
        <a class="nav-link  @if ($loop->first) active @endif" data-toggle="tab" href="#{{$name}}-{{$locale}}" role="tab" aria-controls="home">
            {{strtoupper($locale)}}
        </a>
    </li>
    @endforeach
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
</div>