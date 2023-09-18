 <footer class="text-center my-5 text-secondary">
     {{ trans('messages.made_with') }}
     <a href="https://www.agorakit.org" >Agorakit ({{ config('agorakit.version') }})</a>
     - <a href="{{ request()->fullUrlWithQuery(['embed' => 1]) }}" >{{ trans('messages.embed') }}</a>
 </footer>

 <!-- footer -->
 @yield('footer')

 <!-- Custom footer content added by admin -->
 {!! setting('custom_footer') !!}
