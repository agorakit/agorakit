<form action="{{ url('search') }}" method="get" role="search">

    <div class="form-group">
        <input aria-label="Search" class="form-control" name="query" placeholder="{{ trans('messages.search') }}"
            type="search" value="{{ request()->get('query') }}" />
    </div>
    <div class="form-group">
        <select class="form-control" name="group" required="required">
            <option value="discussions">{{ trans('messages.discussions') }}</option>
            <option value="actions">{{ trans('messages.actions') }}</option>

        </select>
    </div>

    <div class="form-group">
    <select class="form-control" name="group">
        <option> MY groups</option>
        <option> All public groups</option>
        <option> All groups (admin overview)</option>

        <option disabled selected value="">{{ trans('messages.choose_a_group') }}</option>
        @foreach (Auth::user()->groups as $group)
            <option value="{{ $group->id }}">{{ $group->name }}</option>
        @endforeach
    </select>
    </div>



    <div class="form-group">
        <input class="form-controlbtn btn-primary" name="('messages.search')" type="submit" />
    </div>


</form>
