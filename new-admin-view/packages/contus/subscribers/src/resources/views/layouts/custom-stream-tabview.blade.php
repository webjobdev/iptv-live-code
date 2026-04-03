<ul class="nav nav-tabs" role="tablist" style="margin: 1rem 0;">
    <li class="{{ Request::routeIs('subscriber.custom.stream') ? 'active' : '' }}">
        <a style="font-weight: 500; color: black;"
            href="{{ url('admin/subscriber/custom-stream') }}?subscriber-id={{ request()->query('subscriber-id') }}">
            <i class="fa fa-light fa-tv"></i> Tv Channel List
        </a>
    </li>

    <li class="{{ Request::routeIs('subscriber.video.demand') ? 'active' : '' }}">
        <a style="font-weight: 500; color: black;"
            href="{{ url('admin/subscriber/custom-stream/video-on-demand') }}?subscriber-id={{ request()->query('subscriber-id') }}">
            <i class="fa fa-regular fa-film"></i> Video On Demand
        </a>
    </li>
</ul>