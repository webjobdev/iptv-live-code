<ul class="nav nav-tabs" role="tablist" style="margin: 1rem 0;">
    <li class="{{ Request::routeIs('activation') ? 'active' : '' }}">
        <a style="font-weight: 500; color: black;"
            href="{{ url('admin/subscriber/activation') }}?subscriber-id={{ request()->query('subscriber-id') }}">
            <span class="glyphicon glyphicon-list-alt"></span> Subscriptions
        </a>
    </li>

    <li data-ng-if="checkAccess('credit_cards.view')"
        class="{{ Request::routeIs('subscribers.credit-card') ? 'active' : '' }}">
        <a style="font-weight: 500; color: black;"
            href="{{ url('admin/subscribers/credit-card') }}?subscriber-id={{ request()->query('subscriber-id') }}">
            <span class="glyphicon glyphicon-credit-card"></span> Credit Card
        </a>
    </li>

    <li class="{{ Request::routeIs('subscribers.payment-history') ? 'active' : '' }}">
        <a style="font-weight: 500; color: black;"
            href="{{ url('admin/subscribers/payment-history') }}?subscriber-id={{ request()->query('subscriber-id') }}">
            <span class="glyphicon glyphicon-time"></span> Payment History
        </a>
    </li>

    <!-- <li class="{{ Request::routeIs('subscribers.patner-product') ? 'active' : '' }}">
        <a style="font-weight: 500; color: black;"
            href="{{ url('admin/subscribers/patner-product') }}?subscriber-id={{ request()->query('subscriber-id') }}">
            <span class="glyphicon glyphicon-gift"></span> Partner Product
        </a>
    </li> -->
</ul>