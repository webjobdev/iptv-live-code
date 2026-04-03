<nav class="nav nav-pills" style="margin: 1rem 0rem 1rem 0rem;">
    <li class="{{ Request::routeIs('subscribers.detail.add') ? 'active' : '' }}" style="margin-right: 0.5rem;">
        <a href="{{ url('admin/subscribers/detail/add') }}?subscriber-id={{ request()->query('subscriber-id') }}"
            style="{{ Request::routeIs('subscribers.detail.add') ? 'background-color: #00ACCD;' : '' }}"
            class="btn btn-default">
            <input type="hidden" name="id" value="{{ request()->query('subscriber-id') }}">
            General Information
        </a>
    </li>


    <li class="{{ Request::routeIs('devices') ? 'active' : '' }}" style="margin-right: 0.5rem;">
        <a href="{{ url('admin/subscribers/devices') }}?subscriber-id={{ request()->query('subscriber-id') }}"
            style="{{ Request::routeIs('devices') ? 'background-color: #00ACCD;' : '' }}"
            class="btn btn-default" data-ng-if="checkAccess('devices.view')">
            <input type="hidden" name="id" value="{{ request()->query('subscriber-id') }}">
            Devices ({{ $viewdevice }})
        </a>
    </li>

    <li class="{{ Request::routeIs('activation') || Request::routeIs('subscribers.credit-card') || Request::routeIs('subscribers.payment-history') || Request::routeIs('subscribers.patner-product') || Request::routeIs('subsciber.add.slot') ? 'active' : '' }}" style="margin-right: 0.5rem;">
        <a href="{{ url('admin/subscriber/activation') }}?subscriber-id={{ request()->query('subscriber-id') }}"
            style="{{ Request::routeIs('activation') || Request::routeIs('subscribers.credit-card') || Request::routeIs('subscribers.payment-history') || Request::routeIs('subscribers.patner-product') || Request::routeIs('subsciber.add.slot') ? 'background-color: #00ACCD;' : '' }}"
            class="btn btn-default" data-ng-if="checkAccess('activation.view')">
            <input type="hidden" name="id" value="{{ request()->query('subscriber-id') }}">
            Activation ({{ $activationStatus }})
        </a>
    </li>


    <li data-ng-if="checkAccess('custom_streams.view')" class="{{ Request::routeIs('subscriber.custom.stream') || Request::routeIs('subscriber.video.demand') ? 'active' : '' }}" style="margin-right: 0.5rem;">
        <a href="{{ url('admin/subscriber/custom-stream') }}?subscriber-id={{ request()->query('subscriber-id') }}"
            style="{{ Request::routeIs('subscriber.custom.stream') || Request::routeIs('subscriber.video.demand') ? 'background-color: #00ACCD;' : '' }}"
            class="btn btn-default">
            <input type="hidden" name="id" value="{{ request()->query('subscriber-id') }}">
            Custom Streams
        </a>
    </li>

    <li data-ng-if="checkAccess('notes.view')" class="{{ Request::routeIs('subscribers.notes') ? 'active' : '' }}" style="margin-right: 0.5rem;">
        <a href="{{ url('admin/subscriber/notes') }}?subscriber-id={{ request()->query('subscriber-id') }}"
            style="{{ Request::routeIs('subscribers.notes') ? 'background-color: #00ACCD;' : '' }}"
            class="btn btn-default">
            <input type="hidden" name="id" value="{{ request()->query('subscriber-id') }}">
            Notes
        </a>
    </li>
</nav>