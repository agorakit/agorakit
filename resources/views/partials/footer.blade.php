 <footer class="text-center my-6 text-secondary">
     {{ trans('messages.made_with') }}
     <a href="https://www.agorakit.org">Agorakit ({{ config('agorakit.version') }})</a>
     - <a href="{{ request()->fullUrlWithQuery(['embed' => 1]) }}">{{ trans('messages.embed') }}</a>
 </footer>

 <!-- footer -->
 @yield('footer')

 <!-- Custom footer content added by admin -->
 {!! setting('custom_footer') !!}

 <script>
     if ('serviceWorker' in navigator) {
         window.addEventListener('load', function() {
             navigator.serviceWorker.register('/service-worker.js');
         });
     }
 </script>
