 <footer class="h-40  p-10 text-xs text-gray-600 sm:rounded-lg text-center">
     {{ trans('messages.made_with') }}
     <a href="https://www.agorakit.org" up-follow>Agorakit ({{ config('agorakit.version') }})</a>
     - <a href="{{ request()->fullUrlWithQuery(['embed' => 1]) }}" up-follow>{{ trans('messages.embed') }}</a>
 </footer>


<!--
 <script>
     if ('serviceWorker' in navigator) {
         window.addEventListener('load', function() {
             navigator.serviceWorker.register('/service-worker.js');
         });
     }
 </script>

 -->

 <!-- footer -->
 @yield('footer')

 <!-- Custom footer content added by admin -->
 {!! setting('custom_footer') !!}
