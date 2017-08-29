@if(!$model->container)
	@include('charts::_partials.container.title')
	@include('charts::_partials.loader.container-top')
		<center>
			<div id="{{ $model->id }}" style="@include('charts::_partials.dimension.css')"></div>
		</center>
	@include('charts::_partials.loader.container-bottom')
@endif
