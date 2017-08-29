<div class="charts" style="background: {{ $model->background_color }};">
    <div data-duration="{{ $model->loader_duration }}" class="charts-loader {{ $model->loader ? 'enabled' : '' }}" style="display: none; position: relative; top: {{ ($model->height / 2) - 30 }}px; height: 0;">
        <center>
            <div class="loading-spinner" style="border: 3px solid {{ $model->loader_color }}; border-right-color: transparent;"></div>
        </center>
    </div>
    <div class="charts-chart">
