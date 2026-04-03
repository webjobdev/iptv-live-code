@extends('base::layouts.default') @section('stylesheet')
<link rel="stylesheet" href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" />
<link rel="stylesheet" href="{{asset('adminview/assets/css/uploader.css')}}" />
@endsection @section('header') @include('base::layouts.headers.dashboard') @endsection @section('content')
<div data-ng-controller="StaticContentAddController as addCtrl">
	<div class="page-heading flexbox align-items-center flex-wrap">
		<div class="left-side">
			<h4>{{ trans('cms::staticcontent.add_new_content') }}</h4>
		</div>
	</div>


	<div class="video-detail form-page single-page">
		<form name="staticcontentForm" method="POST" data-ng-submit="addCtrl.submitform($event)" enctype="multipart/form-data">
			{!! csrf_field() !!}
			<div class="tab-content">
				<div class="division flexbox">
                	<div class="one-set width-50">
						<div class="form-group" data-ng-class="{'has-error': errors.title.has}">
							<label>
								{{trans('cms::staticcontent.title')}}
								<span class="required">*</span>
							</label>
							<div class="form-input">
								<input type="text" name="title" data-ng-model="addCtrl.staticData.title" class="form-control" placeholder="{{trans('cms::staticcontent.title_placeholder')}}" value="{{old('title')}}" />
							</div>
							<p class="error-msg" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
						</div>

						<div class="form-group" data-ng-class="{'has-error': errors.is_footer_menu.has}">
							<div class="switch-concept flexbox align-items-center">
								<svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
									<g>
										<path d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z" fill="#3d3d3d"></path>
									</g>
								</svg>
								<div class="swich-content flexbox align-items-center flex-wrap">
									<span>{{trans('cms::staticcontent.display_in_footer')}}</span>
									<div class="right-side flexbox align-items-center">
										<span class="text">(No)</span>
										<label class="switch">
											<input type="checkbox" name="is_footer_menu" ng-model="addCtrl.staticData.is_footer_menu">
											<span class="slider round"></span>
										</label>
										<span class="text">(Yes)</span>
									</div>
								</div>
							</div>
							<p class="error-msg" data-ng-show="errors.is_footer_menu.has">@{{ errors.is_footer_menu.message}}</p>
						</div>
					</div>

					<div class="one-set width-50">
						<div class="form-group" data-ng-class="{'has-error': errors.content.has}">
							<label>
								{{trans('cms::staticcontent.content')}}
								<span class="required">*</span>
							</label>
							<div class="form-input">							
								<textarea id="staticContentTextArea" ui-tinymce="{resize:false,height:400}" name="content" class="form-control" data-ng-model="addCtrl.staticData.content" placeholder="{{trans('cms::staticcontent.content_placeholder')}}" value="{{old('content')}}"></textarea>
							</div>
							<p class="error-msg" data-ng-show="errors.title.has">@{{ errors.content.message }}</p>
						</div>
					</div>
				</div>
			</div>

			<div class="bottom-button text-right flexbox align-items-center">
				<a class="save" href="{{url('admin/static-content')}}">
					{{trans('base::general.cancel')}}
				</a>
				<button class="publish-now">
				{{trans('base::general.submit')}}
				</button>
			</div>
		</form>
	</div>

</div>
@endsection @section('scripts')
<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
<!-- {{-- <script src="{{asset('adminview/assets/angular/angular-ui.js')}}"></script> --}} -->
<script src="{{asset('adminview/assets/tinymce/tiny_mce.js')}}"></script>
<script src="{{asset('adminview/assets/tinymce/jquery.tinymce.js')}}"></script>
<script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
<script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
<script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
<script src="{{asset('adminview/assets/js/static/add.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
<script type="text/javascript">

//tinyMCE.get('#staticContentTextArea').getBody();
/* tinymce.init({
  selector: 'textarea#staticContentTextArea',
  height: 500,
  
}); */
</script>
@endsection
