@if(!$model->container)
	<div>
		@if($model->title)
			<center>
				<strong>{{ $model->title }}</strong>
			</center>
		@endif
	</div>
@endif
