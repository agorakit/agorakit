<ol class="breadcrumb">
@foreach ($breadcrumbs as $breadcrumb)
  <li><a href="{{$breadcrumb->link}}">{{$breadcrumb->title}}</a></li>
@endforeach
</ol>
