<div class="page-heading flexbox align-items-center flex-wrap">
    <div class="left-side">
        @if($template == 'vod')
        <h4>Video On Demand</h4>
        @endif

        @if ($template == 'add_vod')
        <h4>Add Vod</h4>
        @endif

        @if ($template == 'edit_vod')
            <h4>Edit Vod</h4>
        @endif
    </div>

    <div class="right-side flexbox align-items-center">
        @if($template == 'vod')
        <a ng-if="checkAccess('video_on_demand.create')"
            title="Video On Demand" href="{{url('admin/vod/add')}}" class="button button-blue">
            <svg viewBox="0 0 16 18" x="0px" y="0px" width="15px" height="17px">
                <g id="Layer%201">
                    <path id="Forma%201" d="M 0.4998 17.9998 L 0.4998 15.9999 L 15.4998 15.9999 L 15.4998 17.9998 L 0.4998 17.9998 ZM 11.2141 13.9999 L 4.7855 13.9999 L 4.7855 7.9999 L 0.4998 7.9999 L 7.9999 0.9999 L 15.4998 7.9999 L 11.2141 7.9999 L 11.2141 13.9999 Z"
                        fill="#ffffff" />
                </g>
            </svg>
            <span>Add Vod</span>
        </a>
        
        @endif
    </div>
</div>