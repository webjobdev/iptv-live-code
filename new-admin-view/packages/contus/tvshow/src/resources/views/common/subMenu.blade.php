<div class="page-heading flexbox align-items-center flex-wrap">
    <div class="left-side">
        @if($template == 'tvshow')
            <h4>Tv Show</h4>
        @endif

        @if ($template == 'add_tvshow')
            <h4>Add TvShow</h4>
        @endif

        @if ($template == 'edit_tvshow')
            <h4>Edit TvShow</h4>
        @endif

        @if ($template == 'add_tvshow_season')
            <h4>Add TvShow Season</h4>
        @endif

        @if ($template == 'edit_tvshow_seasons')
            <h4>Edit TvShow Season</h4>
        @endif
    </div>

    <div class="right-side flexbox align-items-center">
        @if($template == 'tvshow')
            <a ng-if="checkAccess('tv_shows.create')" title="Add Tv Show" href="{{url('admin/tvshow/add')}}"
                class="button button-blue">
                <svg viewBox="0 0 16 18" x="0px" y="0px" width="15px" height="17px">
                    <g id="Layer%201">
                        <path id="Forma%201"
                            d="M 0.4998 17.9998 L 0.4998 15.9999 L 15.4998 15.9999 L 15.4998 17.9998 L 0.4998 17.9998 ZM 11.2141 13.9999 L 4.7855 13.9999 L 4.7855 7.9999 L 0.4998 7.9999 L 7.9999 0.9999 L 15.4998 7.9999 L 11.2141 7.9999 L 11.2141 13.9999 Z"
                            fill="#ffffff" />
                    </g>
                </svg>
                <span>Add Tv Show</span>
            </a>
            
        @endif
    </div>

</div>