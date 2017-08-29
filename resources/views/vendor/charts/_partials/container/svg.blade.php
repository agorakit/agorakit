@if(!$model->container)
	@include('charts::_partials.loader.container-top')
		<svg id="{{ $model->id }}" @include('charts::_partials.dimension.html')></svg>
	@include('charts::_partials.loader.container-bottom')
@endif
