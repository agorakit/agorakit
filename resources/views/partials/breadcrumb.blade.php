<ol class="breadcrumb">
@foreach ($breadcrumbs as $breadcrumb)
  <li><a up-follow href="{{$breadcrumb->link}}">{{$breadcrumb->title}}</a></li>
@endforeach
</ol>
