@if (null !== env('GITHUB_ID'))
    <a  href="{{ url('/auth/github') }}" class="btn btn-sm btn-github btn-primary"><i class="fa fa-github"></i> Github</a>
@endif
@if (null !== env('TWITTER_ID'))
    <a  href="{{ url('/auth/twitter') }}" class="btn btn-sm btn-twitter btn-primary"><i class="fa fa-twitter"></i> Twitter</a>
@endif
@if (null !== env('GOOGLE_ID'))
    <a  href="{{ url('/auth/google') }}" class="btn btn-sm btn-google btn-primary"><i class="fa fa-google"></i> Google</a>
@endif
@if (null !== env('FACEBOOK_ID'))
    <a  href="{{ url('/auth/facebook') }}" class="btn btn-sm btn-facebook btn-primary"><i class="fa fa-facebook"></i> Facebook</a>
@endif
