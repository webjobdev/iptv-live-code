<div class="sidepanel">
   <div class="overlay"></div>
   <div class="pop_over_continer form-page">
      <form name="LanguagesForm" method="POST" data-base-validator data-ng-submit="langgridCtrl.languageSave($event, langgridCtrl.language.id)" enctype="multipart/form-data">
         {!! csrf_field() !!}
         <div class="sidepanel-header flexbox align-items-center">
            <h5 data-ng-if="!langgridCtrl.language.id">{{trans('audio::languages.add_new_language')}} </h5>
            <h5 data-ng-if="langgridCtrl.language.id">{{trans('audio::languages.edit_language')}} </h5>
         </div>
         <div class="sidepanel-scroll">
            @include('base::partials.errors')
            <div class="form-group" data-ng-class="{'has-error': errors.language_name.has}">
               <label>{{trans('audio::languages.name')}} <span class="required">*</span></label>
               <div class="form-input">
                  <input type="text" name="language_name" maxlength="255" class="form-control" data-ng-model="langgridCtrl.language.language_name" placeholder="{{trans('audio::languages.name')}}" value="{{old('name')}}" />
               </div>
               <p class="error-msg" data-ng-show="errors.language_name.has">@{{ errors.language_name.message }}</p>
            </div>
            @include('audio::admin.common.commonFormFields',['field' =>  'order', 'ngmodel' => 'langgridCtrl.language.order'])
            @include('audio::admin.common.commonFormFields',['field' =>  'status', 'ngmodel' => 'langgridCtrl.language.is_active'])
         </div>
         @include('audio::admin.common.commonFormFields',['field' =>  'side-panel-form-btns'])
      </form>
   </div>
</div>