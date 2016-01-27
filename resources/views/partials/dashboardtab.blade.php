<div class="page_header">
  <h1>{{ trans('messages.groups') }}</h1>
  <p>{{ trans('documentation.intro') }}</p>
</div>


<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
  <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
  <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
  <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="home">...</div>
  <div role="tabpanel" class="tab-pane" id="profile">...</div>
  <div role="tabpanel" class="tab-pane" id="messages">...</div>
  <div role="tabpanel" class="tab-pane" id="settings">...</div>
</div>



<ul class="nav nav-tabs">
  <li role="presentation" @if (isset($tab) && ($tab == 'discussions')) class="active" @endif>
    <a href="">
      <i class="fa fa-user"></i> {{ trans('messages.discussions') }}
    </a>
  </li>



  <li role="presentation" @if (isset($tab) && ($tab == 'actions')) class="active" @endif>
    <a href=""><i class="fa fa-envelope-o"></i>
      {{trans('messages.actions')}}</a>
    </a>
  </li>


  <li role="presentation" @if (isset($tab) && ($tab == 'groups')) class="active" @endif>
    <a href="">
      <i class="fa fa-pencil"></i>
      {{trans('messages.groups')}}
    </a>
  </li>



</ul>
