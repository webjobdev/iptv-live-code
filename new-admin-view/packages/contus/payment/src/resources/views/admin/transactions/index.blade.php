@extends('base::layouts.default') @section('header')

@include('base::layouts.headers.dashboard') @endsection
@section('stylesheet')
<link rel="stylesheet" href="{{asset('adminview/assets/css/angularjs-datetime-picker.css')}}" />
@endsection
@section('content')

<div data-ng-controller="transactionController as transCtrl">	
	<div class="page-heading flexbox align-items-center flex-wrap">
		<div class="left-side">
		<h4>{{trans('payment::transaction.transactions')}}</h4>
		</div>
	</div>
	<div class="contentpanel product order_list">
		@include('base::partials.errors')
		<div class="alert alert-success"
			data-ng-if="transCtrl.showResponseMessage">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<span>@{{transCtrl.responseMessage}}</span>
		</div>
		<div data-grid-view data-rows-per-page="10"
			data-route-name="transactions"
			data-template-route="admin/transactions" data-count="false"></div>
	</div>
</div>

@endsection @section('scripts')
<script src="{{asset('adminview/assets/js/angularjs-datetime-picker.js')}}"></script>
<script src="{{asset('adminview/assets/js/classieSidebarEffects.js')}}"></script>
<script	src="{{asset('adminview/assets/js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
<script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
<script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/gridView.js')}}"></script>
<script src="{{asset('adminview/assets/js/transactions/index.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
<script src="{{asset('adminview/assets/js/grid.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection
