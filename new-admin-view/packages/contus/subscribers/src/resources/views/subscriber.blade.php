@include('subscribers::subscriber_common', [
    'mode' => 'edit',
    'target_id' => request()->query('subscriber-id'),
    'nav_type' => 'subscriber'
])