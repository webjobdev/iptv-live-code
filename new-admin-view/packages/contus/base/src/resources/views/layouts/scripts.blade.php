<script src="{{url('adminview/assets/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{url('adminview/assets/js/jquery.mCustomScrollbar.min.js')}}"></script>
<script src="{{url('adminview/assets/js/bootstrap.min.js')}}"></script>
<script src="{{url('adminview/assets/js/angular/libs/angular.min.js')}}"></script>
<script src="{{url('adminview/assets/js/common.js')}}"></script>
<script src="{{url('adminview/assets/js/tableHeadFixer.js')}}"></script>
<script src="{{url('adminview/assets/js/tablesaw.js')}}"></script>
<script src="{{url('adminview/assets/js/tablesaw-init.js')}}"></script>
<script src="{{url('adminview/assets/js/common/gridFilters.js')}}"></script>
<script src="{{url('adminview/assets/js/select2.min.js')}}"></script>


<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>

<script>
  const appUrl = `{{ env('APP_URL') }}`;
  const apiUrl = `{{ env('API_URL') }}`;
</script>

<!-- <script type="text/javascript">

  window.VPlay = {
    route: {

      apiUrl: 'https://new-admin-api.test/',
      viewURL: 'https://new-admin-view.test/',
    //   apiUrl: 'http://15.204.253.153/ott-laravel/new-admin-api/public/',
    //   viewURL: 'http://15.204.253.153/ott-laravel/new-admin-view/public/',
      videoUploadEndpoint: 'https://admin-api.test/'

    }
  };

</script> -->

<script type="text/javascript">
  (function () {
    var currentURL = window.location.href;

    // Define local and server URLs
    var localApi = 'https://new-admin-api.test/';
    var localView = 'https://new-admin-view.test/';
    var serverApi = 'http://15.204.253.153/ott-laravel/new-admin-api/public/';
    var serverView = 'http://15.204.253.153/ott-laravel/new-admin-view/public/';

    // Check if the current URL is the server one
    var isServer = currentURL.includes('15.204.253.153/ott-laravel/new-admin-view/public');

    // Set route values based on environment
    window.VPlay = {
      route: {
        apiUrl: isServer ? serverApi : localApi,
        viewURL: isServer ? serverView : localView,
        videoUploadEndpoint: isServer ? serverApi : localApi
      }
    };
  })();
</script>


