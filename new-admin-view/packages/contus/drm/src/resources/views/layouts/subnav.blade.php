<nav class="nav nav-pills" style="margin: 1rem 0rem 1rem 0rem;" >
    <li data-ng-if="checkAccess('drm_accounts.view')" class="{{ Request::routeIs('drm.detail.add') ? 'active' : '' }}" style="margin-right: 0.5rem;">
        <a href="{{ url('admin/drm/detail/add/') }}/{{ request()->route('id') }}" style="{{ Request::routeIs('drm.detail.add') ? 'background-color: #00ACCD;' : '' }}" class="btn btn-default">
            <input type="hidden" name="id" value="{{ request()->route('id') }}">
            <i class="glyphicon glyphicon-pencil"></i> General Settings
        </a>
    </li>

    <li data-ng-if="checkAccess('drm_accounts.view')" class="{{ Request::routeIs('drm.profile.add') ? 'active' : '' }}" style="margin-right: 0.5rem;">
        <a href="{{ url('admin/drm/profile/add/') }}/{{ request()->route('id') }}" style="{{ Request::routeIs('drm.profile.add') ? 'background-color: #00ACCD;' : '' }}" class="btn btn-default">
            <input type="hidden" name="id" value="{{ request()->route('id') }}">
            <i class="glyphicon glyphicon-film"></i> Add Profile
        </a>
    </li>
</nav>