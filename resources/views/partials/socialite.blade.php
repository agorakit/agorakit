<div class="form-group">
    <div class="col-md-6 col-md-offset-4">
        @if (null !== env('GITHUB_ID'))
            <a href="{{ url('/auth/github') }}" class="btn btn-sm btn-github btn-default"><i class="fa fa-github"></i> Github</a>
        @endif
        @if (null !== env('TWITTER_ID'))
            <a href="{{ url('/auth/twitter') }}" class="btn btn-sm btn-twitter btn-default"><i class="fa fa-twitter"></i> Twitter</a>
        @endif
        @if (null !== env('GOOGLE_ID'))
            <a href="{{ url('/auth/google') }}" class="btn btn-sm btn-google btn-default"><i class="fa fa-google"></i> Google</a>
        @endif
        @if (null !== env('FACEBOOK_ID'))
            <a href="{{ url('/auth/facebook') }}" class="btn btn-sm btn-facebook btn-default"><i class="fa fa-facebook"></i> Facebook</a>
        @endif
    </div>
</div>
