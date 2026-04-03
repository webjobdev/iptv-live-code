@extends('base::layouts.default') @section('stylesheet')
<link rel="stylesheet"
	href="{{asset('adminview/assets/css/bootstrap-fileupload.min.css')}}" />
<link rel="stylesheet" href="{{asset('adminview/assets/css/uploader.css')}}" />
@endsection @section('header')
@include('base::layouts.headers.dashboard') @endsection

@section('content')
<div ng-controller="EmailController">

	<div class="page-heading flexbox align-items-center flex-wrap">
		<div class="left-side">
			<span ng-hide="true" id="inititate">{{$id}}</span>
			<span ng-hide="true" id="rules">{!! json_encode($rules) !!}</span>
			<h4>{{trans('cms::emailtemplate.edit_new_email')}}</h4>
		</div>

		<div class="right-side">
			<select minimumResults="-1" width="100px" data-jquery="select2_custom_ddl" name="language" class="select2_custom_ddl" ng-change="languageChange()" myValue="editEmailTemp.language" data-ng-model="editEmailTemp.language" data-ng-options="a.id as a.title  for a in languages " ></select>
		</div>
	</div>

	<div class="alert alert-success ng-scope" data-ng-if="showResponseMessage">
       <button type="button" class="close" data-dismiss="alert">×</button>
        <span class="ng-binding"> @{{ responseMessage }}</span>
	</div>

	<div class="video-detail single-page form-page">
		<form name="emailtemplateForm" class="form-top-spacing" id="emailtemplateForm" method="POST" data-ng-submit="submitform($event)" enctype="multipart/form-data">
			{!! csrf_field() !!}
			@include('base::partials.errors')

			<div class="tab-content">
				<div class="division flexbox">
                	<div class="one-set width-50">
						<div class="form-group" data-ng-class="{'has-error': errors.name.has}">
							<label>
								{{trans('cms::emailtemplate.name')}}
								<span class="required">* </span>
							</label>
							<div class="form-input">
								<input type="text" name="name" data-ng-model="emailData.name" class="form-control" placeholder="{{trans('cms::emailtemplate.name_placeholder')}}" value="{{old('name')}}" />
							</div>
							<p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
						</div>
						<div class="form-group"
							data-ng-class="{'has-error': errors.subject.has}">
							<label>
								{{trans('cms::emailtemplate.subject')}}
								<span class="required">*</span>
							</label>
							<div class="form-input">
								<input type="text" name="subject" class="form-control" data-ng-model="emailData.subject" placeholder="{{trans('cms::emailtemplate.subject_placeholder')}}" value="{{old('subject')}}" />
							</div>
							<p class="error-msg" data-ng-show="errors.subject.has">@{{ errors.name.message }}</p>
						</div>
					</div>

					<div class="one-set width-50">
						<div class="form-group" data-ng-class="{'has-error': errors.subject.has}">
							<label>
								{{trans('cms::emailtemplate.content')}}
								<span class="required">*</span>
							</label>
							<div class="form-input">
								<textarea ui-tinymce="{resize:false,height:400}"  name="content" class="form-control" data-ng-model="emailData.content" placeholder="{{trans('cms::emailtemplate.content_placeholder')}}" value="{{old('content')}}"></textarea>
								@{{tinymce}}
							</div>
							<p class="error-msg" data-ng-show="errors.title.has">@{{ errors.content.message }}</p>
						</div>
					</div>
				</div>
			</div>

			<div class="bottom-button text-right flexbox align-items-center">
				<a class="save" href="{{url('admin/emails')}}">
					{{trans('base::general.cancel')}}
				</a>
				<button class="publish-now">
				{{trans('base::general.submit')}}
				</button>
			</div>
		</form>

		<form name="emailtemplateTranslationForm" class="form-top-spacing" style="display: none" id="emailtemplateTranslationForm" method="POST" data-ng-submit="submitTranslationform($event)" enctype="multipart/form-data">
		{!! csrf_field() !!}
		@include('base::partials.errors')
			<div class="tab-content">
				<div class="division flexbox">
                	<div class="one-set width-50">
						<div class="form-group" data-ng-class="{'has-error': errors.name.has}">
							<label>
								{{trans('cms::emailtemplate.name')}}
								<span class="required">* </span>
							</label>
							<input type="text" name="name" data-ng-model="emailData.name" class="form-control" placeholder="{{trans('cms::emailtemplate.name_placeholder')}}" value="{{old('name')}}" disabled="disabled" />
							<p class="error-msg" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
						</div>
					</div>

					<div class="one-set width-50">
						<div class="form-group" data-ng-class="{'has-error': errors.trans_name.has}">
							<label>
								{{trans('cms::emailtemplate.name')}}
								<span class="required">* </span>
							</label>
							<input type="text" name="trans_name" data-ng-model="languageEmailTemplate.name" class="form-control" placeholder="{{trans('cms::emailtemplate.name_placeholder')}}" value="{{old('name')}}" />
							<p class="error-msg" data-ng-show="errors.trans_name.has">@{{ errors.trans_name.message }}</p>
						</div>
					</div>
				</div>
				<div class="division flexbox">
                	<div class="one-set width-50">
						<div class="form-group" data-ng-class="{'has-error': errors.subject.has}">
							<label>
								{{trans('cms::emailtemplate.subject')}}
								<span class="required">*</span>
							</label>
							<div class="form-input">
								<input type="text" name="subject" class="form-control" data-ng-model="emailData.subject" placeholder="{{trans('cms::emailtemplate.subject_placeholder')}}" value="{{old('subject')}}" disabled="disabled" />
							</div>
							<p class="error-msg" data-ng-show="errors.subject.has">@{{ errors.name.message }}</p>
						</div>
					</div>

					<div class="one-set width-50">
						<div class="form-group" data-ng-class="{'has-error': errors.trans_subject.has}">
							<label>
								{{trans('cms::emailtemplate.subject')}}
								<span class="required">*</span>
							</label>
							<div class="form-input">
								<input type="text" name="trans_subject" class="form-control" data-ng-model="languageEmailTemplate.subject" placeholder="{{trans('cms::emailtemplate.subject_placeholder')}}" value="{{old('subject')}}" />
							</div>
							<p class="error-msg" data-ng-show="errors.trans_subject.has">@{{ errors.trans_subject.message }}</p>
						</div>
					</div>
				</div>

				<div class="division flexbox">
                	<div class="one-set width-50">
						<div class="form-group" data-ng-class="{'has-error': errors.content.has}">
							<label>
								{{trans('cms::emailtemplate.content')}}
								<span class="required">*</span>
							</label>
							<div class="form-input">
								<textarea ui-tinymce="{resize:false,height:400}"  name="content" class="form-control" data-ng-model="emailData.content" placeholder="{{trans('cms::emailtemplate.content_placeholder')}}" value="{{old('content')}}" disabled="disabled"></textarea>
								@{{tinymce}}
							</div>
							<p class="error-msg" data-ng-show="errors.content.has">@{{ errors.content.message }}</p>
						</div>
					</div>

					<div class="one-set width-50">
						<div class="form-group" data-ng-class="{'has-error': errors.trans_content.has}">
							<label>
								{{trans('cms::emailtemplate.content')}}
								<span class="required">*</span>
							</label>
							<div class="form-input">
								<textarea ui-tinymce="{resize:false,height:400}"  name="trans_content" class="form-control" data-ng-model="languageEmailTemplate.content" placeholder="{{trans('cms::emailtemplate.content_placeholder')}}" value="{{old('content')}}"></textarea>
								@{{tinymce}}
							</div>
							<p class="error-msg" data-ng-show="errors.trans_content.has">@{{ errors.trans_content.message }}</p>
						</div>
					</div>
				</div>
			</div>

			<div class="bottom-button text-right flexbox align-items-center">
				<a class="save" href="{{url('admin/emails')}}">
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
<script src="{{asset('adminview/assets/js/angular/angular-ui.js')}}"></script>
<script src="{{asset('adminview/assets/tinymce/tiny_mce.js')}}"></script>
<script src="{{asset('adminview/assets/tinymce/jquery.tinymce.js')}}"></script>
<script src="{{asset('adminview/assets/js/Validate.js')}}"></script>
<script src="{{asset('adminview/assets/js/validatorDirective.js')}}"></script>
<script src="{{asset('adminview/assets/js/requestFactory.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/common.js')}}"></script>
<script src="{{asset('adminview/assets/js/email/email.js')}}"></script>
<script src="{{asset('adminview/assets/js/common/directive.js')}}"></script>
@endsection
