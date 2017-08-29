@if(!$model->container)
	@include('charts::_partials.loader.container-top')
		<div>
			<canvas id="{{ $model->id }}" @include('charts::_partials.dimension.html')></canvas>
		</div>
	@include('charts::_partials.loader.container-bottom')
@endif
