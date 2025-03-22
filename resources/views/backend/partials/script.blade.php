 <!-- javascript -->
 <script src="{{ asset('backend/assets/js/jquery-3.7.1.min.js') }}"></script>
 <script src="{{ asset('backend/assets/js/plugins.js') }}"></script>
 <script src="{{ asset('backend/assets/js/main.js') }}"></script>

 <script>
     $(document).ready(function() {
         $(".nice-select").niceSelect();
     });
 </script>

 {{-- toastr start --}}
 <script>
     $(document).ready(function() {
         toastr.options.timeOut = 10000;
         toastr.options.positionClass = 'toast-top-right';

         @if (Session::has('t-success'))
             toastr.options = {
                 'closeButton': true,
                 'debug': false,
                 'newestOnTop': true,
                 'progressBar': true,
                 'positionClass': 'toast-top-right',
                 'preventDuplicates': false,
                 'showDuration': '1000',
                 'hideDuration': '1000',
                 'timeOut': '5000',
                 'extendedTimeOut': '1000',
                 'showEasing': 'swing',
                 'hideEasing': 'linear',
                 'showMethod': 'fadeIn',
                 'hideMethod': 'fadeOut',
             };
             toastr.success("{{ session('t-success') }}");
         @endif

         @if (Session::has('t-error'))
             toastr.options = {
                 'closeButton': true,
                 'debug': false,
                 'newestOnTop': true,
                 'progressBar': true,
                 'positionClass': 'toast-top-right',
                 'preventDuplicates': false,
                 'showDuration': '1000',
                 'hideDuration': '1000',
                 'timeOut': '5000',
                 'extendedTimeOut': '1000',
                 'showEasing': 'swing',
                 'hideEasing': 'linear',
                 'showMethod': 'fadeIn',
                 'hideMethod': 'fadeOut',
             };
             toastr.error("{{ session('t-error') }}");
         @endif

         @if (Session::has('t-info'))
             toastr.options = {
                 'closeButton': true,
                 'debug': false,
                 'newestOnTop': true,
                 'progressBar': true,
                 'positionClass': 'toast-top-right',
                 'preventDuplicates': false,
                 'showDuration': '1000',
                 'hideDuration': '1000',
                 'timeOut': '5000',
                 'extendedTimeOut': '1000',
                 'showEasing': 'swing',
                 'hideEasing': 'linear',
                 'showMethod': 'fadeIn',
                 'hideMethod': 'fadeOut',
             };
             toastr.info("{{ session('t-info') }}");
         @endif

         @if (Session::has('t-warning'))
             toastr.options = {
                 'closeButton': true,
                 'debug': false,
                 'newestOnTop': true,
                 'progressBar': true,
                 'positionClass': 'toast-top-right',
                 'preventDuplicates': false,
                 'showDuration': '1000',
                 'hideDuration': '1000',
                 'timeOut': '5000',
                 'extendedTimeOut': '1000',
                 'showEasing': 'swing',
                 'hideEasing': 'linear',
                 'showMethod': 'fadeIn',
                 'hideMethod': 'fadeOut',
             };
             toastr.warning("{{ session('t-warning') }}");
         @endif
     });
 </script>
 {{-- toastr end --}}

 @stack('script')
