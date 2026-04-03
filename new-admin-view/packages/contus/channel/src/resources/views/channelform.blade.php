<style>
    .time-picker-wrapper {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .time-section {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .arrow-btn {
        background: none;
        border: none;
        font-size: 16px;
        cursor: pointer;
        line-height: 1;
        padding: 0;
        margin: 2px 0;
    }

    .circle-input {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid #aaa;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin: 2px 0;
    }
</style>

<div class="card main-container">
    <div class="card-left">
        <ul>
            <!-- title code -->
            <li class="active" ng-if="livePage && !editPage">
                <div class="status-upload not-saved live-video flexbox align-items-center" id="@{{ video.id }}">
                    <svg _ngcontent-c1="" xml:space="preserve" xmlns:graph="http://ns.adobe.com/Graphs/1.0/"
                        xmlns:i="http://ns.adobe.com/AdobeIllustrator/10.0/"
                        xmlns:x="http://ns.adobe.com/Extensibility/1.0/" xmlns:xlink="http://www.w3.org/1999/xlink"
                        enable-background="new 0 0 30 30" height="30px" id="Layer_1" version="1.1" viewBox="0 0 30 30"
                        width="30px" x="0px" xmlns="http://www.w3.org/2000/svg" y="0px">
                        <path _ngcontent-c1=""
                            d="M26.387,23.657l-1.261-1.263c4.122-4.123,4.121-10.83-0.002-14.953l1.263-1.261  C31.205,10.998,31.205,18.84,26.387,23.657z M22.942,20.212l1.261,1.266c3.614-3.617,3.614-9.5,0-13.115l-1.261,1.261  C25.86,12.542,25.86,17.295,22.942,20.212z M21.952,19.224c2.374-2.373,2.374-6.238-0.001-8.611l-1.261,1.262  c1.68,1.68,1.68,4.411,0,6.086L21.952,19.224z M5.353,7.441L4.09,6.18c-4.818,4.817-4.818,12.66,0,17.477l1.261-1.263  C1.229,18.273,1.23,11.565,5.353,7.441z M6.271,21.478l1.262-1.266c-2.918-2.917-2.918-7.667,0-10.588L6.271,8.361  C2.656,11.976,2.656,17.86,6.271,21.478z M8.522,19.224l1.263-1.263c-1.678-1.679-1.678-4.409,0.001-6.086l-1.263-1.262  C6.149,12.989,6.149,16.852,8.522,19.224z"
                            fill="#5cb85c"></path>
                        <path _ngcontent-c1="" clip-rule="evenodd"
                            d="M15.366,12.178c1.646,0,2.98,1.335,2.98,2.981  s-1.334,2.98-2.98,2.98s-2.981-1.334-2.981-2.98S13.72,12.178,15.366,12.178z"
                            fill="#5cb85c" fill-rule="evenodd"></path>
                    </svg>
                    <h3>Channel In Progress</h3>
                </div>
            </li>
        </ul>
    </div>

    <div class="card-right">
        <div class="card-content">
            <div class="header-section flexbox align-items-center flex-wrap">
                <h3>@{{ channel.channel_name }}</h3>
            </div>

            <form id="channelEditForm" name="channelEditForm" method="POST" data-base-validator
                enctype="multipart/form-data" data-ng-submit="channelGridCtrl.saveChannelEdit($event, channel.id)">

                <div class="upload-cover-thumbnail flexbox" data-ng-class="{'has-error': errors.poster_image.has}">
                    <!-- poster image code -->
                    <div class="cover-image">
                        <h4>{{ __('video::videos.poster') }}</h4>
                        <div class="image-content" style="width: 65%;">
                            <!-- image fetch code -->
                            <img ng-show="channel.poster_image.length > 0" ng-class="{'active':channel.poster_image}"
                                class="uploaded_poster_img uploaded_poster_img_@{{ channel.id }}" alt=""
                                ng-src="@{{channel.poster_image  }}" />

                            <img ng-show="channel.poster_image.length == 0" ng-class="{'active':channel.poster_image}"
                                class="uploaded_poster_img uploaded_poster_img_@{{ channel.id }}" alt="" ng-src="" />

                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileuploadbox">
                                    <div class="input-append">
                                        <div class="overlay-content"
                                            data-ng-class="{'change-image': channel.poster_image.length > 0}">
                                            <div class="input">
                                                <div ng-hide="channel.poster_image.length"
                                                    class="flexbox align-items-center">
                                                    <svg viewBox="0 0 27 27" version="1.1" x="0px" y="0px" width="27px"
                                                        height="27px">
                                                        <g>
                                                            <path opacity="0.702"
                                                                d="M 13.2792 -0.0598 C 5.9642 -0.0598 0.0342 5.8719 0.0342 13.1886 C 0.0342 20.5052 5.9642 26.4371 13.2792 26.4371 C 20.5941 26.4371 26.5249 20.5052 26.5249 13.1886 C 26.5248 5.8716 20.5941 -0.0598 13.2792 -0.0598 ZM 6.2816 19.976 C 5.9301 19.976 5.6454 19.6915 5.6454 19.3398 L 5.6454 7.0371 C 5.6454 6.6857 5.9301 6.4011 6.2816 6.4011 L 20.2774 6.4011 C 20.6288 6.4011 20.9136 6.6857 20.9136 7.0371 L 20.9136 13.8455 L 19.9603 13.8455 L 19.4176 13.8455 L 19.4176 7.8747 L 7.1269 7.8747 L 7.1269 16.0911 L 7.8289 16.0911 L 10.665 11.6672 L 12.5605 14.6233 L 13.5018 16.0912 L 14.3177 16.0912 L 12.9685 13.9868 L 13.8479 12.6151 L 16.0767 16.0911 L 16.9754 16.0911 L 16.9754 16.8307 L 16.9754 18.2803 L 16.9754 19.1967 L 17.8915 19.1967 L 19.044 19.1967 L 19.044 19.9761 L 6.2816 19.9761 L 6.2816 19.976 ZM 18.3362 10.631 C 18.3362 11.3581 17.7467 11.9478 17.0198 11.9478 C 16.2928 11.9478 15.7034 11.3581 15.7034 10.631 C 15.7034 9.904 16.2928 9.3143 17.0198 9.3143 C 17.7467 9.3143 18.3362 9.9041 18.3362 10.631 ZM 21.4094 18.2803 L 21.4094 20.349 L 19.9606 20.349 L 19.9606 19.976 L 19.9606 18.2803 L 17.8918 18.2803 L 17.8918 16.8307 L 19.9606 16.8307 L 19.9606 14.7618 L 20.9138 14.7618 L 21.4095 14.7618 L 21.4095 16.8307 L 23.478 16.8307 L 23.478 18.2803 L 21.4094 18.2803 Z"
                                                                fill="#ffffff"></path>
                                                        </g>
                                                    </svg>
                                                    <span>{{ __('video::videos.upload_cover_picture') }}</span>
                                                </div>
                                                <div ng-hide="!channel.poster_image.length"
                                                    class="flexbox align-items-center ng-hide">
                                                    <svg x="0px" y="0px" width="13" height="13"
                                                        viewBox="0 0 528.899 528.899">
                                                        <g>
                                                            <path
                                                                d="M328.883,89.125l107.59,107.589l-272.34,272.34L56.604,361.465L328.883,89.125z M518.113,63.177l-47.981-47.981   c-18.543-18.543-48.653-18.543-67.259,0l-45.961,45.961l107.59,107.59l53.611-53.611   C532.495,100.753,532.495,77.559,518.113,63.177z M0.3,512.69c-1.958,8.812,5.998,16.708,14.811,14.565l119.891-29.069   L27.473,390.597L0.3,512.69z"
                                                                fill="#ffffff"></path>
                                                        </g>
                                                    </svg>
                                                    <span>{{ __('video::videos.change_cover_picture') }}</span>
                                                </div>
                                                <input type="file" class="uploadPosterImg" name="image"
                                                    data-video-index="@{{ channel.id }}">
                                            </div>
                                            <p>( Only jpeg, png files allowed with a minimum dimension of 1180x665 )</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="error-msg" data-ng-show="errors.poster_image.has">@{{errors.poster_image.message}}</p>
                    </div>
                </div>

                <div class="division flexbox">
                    <div class="one-set width-50">
                        <!-- Channel Name -->
                        <div class="form-group" data-ng-class="{'has-error': errors.channel_name.has}">
                            <label>
                                Channel Name
                                <span class="required">*</span>
                            </label>
                            <div class="form-input" data-ng-class="{'has-error': errors.channel_name.has}">
                                <input type="text" name="channel_name" class="form-control"
                                    placeholder="Enter Channel Name" data-ng-model="channel.channel_name"
                                    ng-change="channelGridCtrl.generateEpgId()">
                            </div>
                            <p class="error-msg" data-ng-show="errors.channel_name.has">
                                @{{errors.channel_name.message}}
                            </p>
                        </div>

                        <!-- Sorting Number -->
                        <div class="form-group">
                            <label>Sorting Number</label>
                            <div class="form-input">
                                <input type="number" name="sorting_number" class="form-control"
                                    placeholder="Enter Sorting Number" data-ng-model="channel.sorting_number">
                            </div>
                            <p class="error-msg" data-ng-show="errors.director.has">
                                @{{errors.sorting_number.message}}
                            </p>
                        </div>

                        <!-- Language -->
                        <div class="form-group">
                            <div>
                                <label>
                                    Language
                                    <!-- <span class="required">*</span> -->
                                </label>
                                <div class="form-input">
                                    <select allowClear="1" data-jquery="select2_custom_ddl" name="language"
                                        class="admin_category_sub form-control select2_custom_ddl"
                                        myValue="channel.language" myPlaceholder="Select Language"
                                        data-ng-model="channel.language" ng-init="channel.language">
                                        <option value="">--- Select ---</option>
                                        <option value="abkhaz">Abkhaz</option>
                                        <option value="afar">Afar</option>
                                        <option value="afrikaans">Afrikaans</option>
                                        <option value="akan">Akan</option>
                                        <option value="albanian">Albanian</option>
                                        <option value="amharic">Amharic</option>
                                        <option value="arabic">Arabic</option>
                                        <option value="aragonese">Aragonese</option>
                                        <option value="armenian">Armenian</option>
                                        <option value="assamese">Assamese</option>
                                        <option value="avaric">Avaric</option>
                                        <option value="avestan">Avestan</option>
                                        <option value="aymara">Aymara</option>
                                        <option value="azerbaijani">Azerbaijani</option>
                                        <option value="bambara">Bambara</option>
                                        <option value="bashkir">Bashkir</option>
                                        <option value="basque">Basque</option>
                                        <option value="belarusian">Belarusian</option>
                                        <option value="bengali">Bengali</option>
                                        <option value="bihari">Bihari</option>
                                        <option value="bislama">Bislama</option>
                                        <option value="bosnian">Bosnian</option>
                                        <option value="breton">Breton</option>
                                        <option value="bulgarian">Bulgarian</option>
                                        <option value="burmese">Burmese</option>
                                        <option value="catalan">Catalan</option>
                                        <option value="chamorro">Chamorro</option>
                                        <option value="chechen">Chechen</option>
                                        <option value="chichewa">Chichewa</option>
                                        <option value="chinese">Chinese</option>
                                        <option value="chuvash">Chuvash</option>
                                        <option value="cornish">Cornish</option>
                                        <option value="corsican">Corsican</option>
                                        <option value="cree">Cree</option>
                                        <option value="croatian">Croatian</option>
                                        <option value="czech">Czech</option>
                                        <option value="danish">Danish</option>
                                        <option value="divehi">Divehi</option>
                                        <option value="dutch">Dutch</option>
                                        <option value="dzongkha">Dzongkha</option>
                                        <option value="english">English</option>
                                        <option value="esperanto">Esperanto</option>
                                        <option value="estonian">Estonian</option>
                                        <option value="ewe">Ewe</option>
                                        <option value="faroese">Faroese</option>
                                        <option value="fijian">Fijian</option>
                                        <option value="finnish">Finnish</option>
                                        <option value="french">French</option>
                                        <option value="fula">Fula</option>
                                        <option value="galician">Galician</option>
                                        <option value="georgian">Georgian</option>
                                        <option value="german">German</option>
                                        <option value="greek">Greek</option>
                                        <option value="guarani">Guarani</option>
                                        <option value="gujarati">Gujarati</option>
                                        <option value="haitian">Haitian</option>
                                        <option value="hausa">Hausa</option>
                                        <option value="hebrew">Hebrew</option>
                                        <option value="herero">Herero</option>
                                        <option value="hindi">Hindi</option>
                                        <option value="hiri-motu">Hiri Motu</option>
                                        <option value="hungarian">Hungarian</option>
                                        <option value="interlingua">Interlingua</option>
                                        <option value="indonesian">Indonesian</option>
                                        <option value="interlingue">Interlingue</option>
                                        <option value="irish">Irish</option>
                                        <option value="igbo">Igbo</option>
                                        <option value="inupiaq">Inupiaq</option>
                                        <option value="ido">Ido</option>
                                        <option value="icelandic">Icelandic</option>
                                        <option value="italian">Italian</option>
                                        <option value="inuktitut">Inuktitut</option>
                                        <option value="japanese">Japanese</option>
                                        <option value="javanese">Javanese</option>
                                        <option value="kalaallisut">Kalaallisut</option>
                                        <option value="kannada">Kannada</option>
                                        <option value="kanuri">Kanuri</option>
                                        <option value="kashmiri">Kashmiri</option>
                                        <option value="kazakh">Kazakh</option>
                                        <option value="khmer">Khmer</option>
                                        <option value="kikuyu">Kikuyu</option>
                                        <option value="kinyarwanda">Kinyarwanda</option>
                                        <option value="kyrgyz">Kyrgyz</option>
                                        <option value="komi">Komi</option>
                                        <option value="kongo">Kongo</option>
                                        <option value="korean">Korean</option>
                                        <option value="kurdish">Kurdish</option>
                                        <option value="kwanyama">Kwanyama</option>
                                        <option value="latin">Latin</option>
                                        <option value="luxembourgish">Luxembourgish</option>
                                        <option value="ganda">Ganda</option>
                                        <option value="limburgish">Limburgish</option>
                                        <option value="lingala">Lingala</option>
                                        <option value="lao">Lao</option>
                                        <option value="lithuanian">Lithuanian</option>
                                        <option value="luba-katanga">Luba-Katanga</option>
                                        <option value="latvian">Latvian</option>
                                        <option value="manx">Manx</option>
                                        <option value="macedonian">Macedonian</option>
                                        <option value="malagasy">Malagasy</option>
                                        <option value="malay">Malay</option>
                                        <option value="malayalam">Malayalam</option>
                                        <option value="maltese">Maltese</option>
                                        <option value="maori">Maori</option>
                                        <option value="marathi">Marathi</option>
                                        <option value="marshallese">Marshallese</option>
                                        <option value="mongolian">Mongolian</option>
                                        <option value="nauru">Nauru</option>
                                        <option value="navajo">Navajo</option>
                                        <option value="norwegian-bokmal">Norwegian Bokmål</option>
                                        <option value="north-ndebele">North Ndebele</option>
                                        <option value="nepali">Nepali</option>
                                        <option value="ndonga">Ndonga</option>
                                        <option value="norwegian-nynorsk">Norwegian Nynorsk</option>
                                        <option value="norwegian">Norwegian</option>
                                        <option value="nuosu">Nuosu</option>
                                        <option value="south-ndebele">South Ndebele</option>
                                        <option value="occitan">Occitan</option>
                                        <option value="ojibwe">Ojibwe</option>
                                        <option value="old-church-slavonic">Old Church Slavonic</option>
                                        <option value="oromo">Oromo</option>
                                        <option value="oriya">Oriya</option>
                                        <option value="ossetian">Ossetian</option>
                                        <option value="punjabi">Punjabi</option>
                                        <option value="pali">Pali</option>
                                        <option value="persian">Persian</option>
                                        <option value="polish">Polish</option>
                                        <option value="pashto">Pashto</option>
                                        <option value="portuguese">Portuguese</option>
                                        <option value="quechua">Quechua</option>
                                        <option value="romansh">Romansh</option>
                                        <option value="kirundi">Kirundi</option>
                                        <option value="romanian">Romanian</option>
                                        <option value="russian">Russian</option>
                                        <option value="sanskrit">Sanskrit</option>
                                        <option value="sardinian">Sardinian</option>
                                        <option value="sindhi">Sindhi</option>
                                        <option value="northern-sami">Northern Sami</option>
                                        <option value="samoan">Samoan</option>
                                        <option value="sango">Sango</option>
                                        <option value="serbian">Serbian</option>
                                        <option value="scottish-gaelic">Scottish Gaelic</option>
                                        <option value="shona">Shona</option>
                                        <option value="sinhala">Sinhala</option>
                                        <option value="slovak">Slovak</option>
                                        <option value="slovenian">Slovenian</option>
                                        <option value="somali">Somali</option>
                                        <option value="southern-sotho">Southern Sotho</option>
                                        <option value="spanish">Spanish</option>
                                        <option value="sundanese">Sundanese</option>
                                        <option value="swahili">Swahili</option>
                                        <option value="swedish">Swedish</option>
                                        <option value="tagalog">Tagalog</option>
                                        <option value="tajik">Tajik</option>
                                        <option value="tamil">Tamil</option>
                                        <option value="tatar">Tatar</option>
                                        <option value="telugu">Telugu</option>
                                        <option value="thai">Thai</option>
                                        <option value="tibetan">Tibetan</option>
                                        <option value="tigrinya">Tigrinya</option>
                                        <option value="tonga">Tonga</option>
                                        <option value="tsonga">Tsonga</option>
                                        <option value="tswana">Tswana</option>
                                        <option value="turkish">Turkish</option>
                                        <option value="turkmen">Turkmen</option>
                                        <option value="twi">Twi</option>
                                        <option value="uyghur">Uyghur</option>
                                        <option value="ukrainian">Ukrainian</option>
                                        <option value="urdu">Urdu</option>
                                        <option value="uzbek">Uzbek</option>
                                        <option value="venda">Venda</option>
                                        <option value="vietnamese">Vietnamese</option>
                                        <option value="volapuk">Volapük</option>
                                        <option value="walloon">Walloon</option>
                                        <option value="welsh">Welsh</option>
                                        <option value="wolof">Wolof</option>
                                        <option value="xhosa">Xhosa</option>
                                        <option value="yiddish">Yiddish</option>
                                        <option value="yoruba">Yoruba</option>
                                        <option value="zhuang">Zhuang</option>
                                        <option value="zulu">Zulu</option>
                                    </select>
                                </div>
                                <p class="error-msg" data-ng-show="errors.age_limit.has">Age limit required</p>
                            </div>
                        </div>
                    </div>

                    <div class="one-set width-50">
                        <!-- publish code -->
                        <div class="form-group">
                            <div class="switch-concept flexbox align-items-center">
                                <div class="swich-content flexbox align-items-center flex-wrap">
                                    <span>Publish Now</span>
                                    <div class="right-side flexbox align-items-center">
                                        <span class="text">{{ __('video::videos.inactive') }}</span>
                                        <label class="switch">
                                            <input type="checkbox" name="status" ng-model="channel.is_active"
                                                ng-change="togglePublishDate()">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="text">{{ __('video::videos.active') }}</span>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <label>
                                    Resolution
                                    <!-- <span class="required">*</span> -->
                                </label>
                                <div class="form-input">
                                    <select allowClear="1" data-jquery="select2_custom_ddl" name="video_quality"
                                        class="admin_category_sub form-control select2_custom_ddl"
                                        myValue="channel.video_quality" myPlaceholder="Select video quality"
                                        data-ng-model="channel.video_quality" ng-init="channel.video_quality">
                                        <option value="">--- Select ---</option>
                                        <option ng-selected="channel.video_quality == 'SD'" value="SD">SD
                                        </option>
                                        <option ng-selected="channel.video_quality == 'HD'" value="HD">HD
                                        </option>
                                        <option ng-selected="channel.video_quality == 'FHD'" value="FHD">
                                            FHD
                                        </option>
                                        <option ng-selected="channel.video_quality == 'UHD'" value="UHD">
                                            UHD
                                        </option>
                                        <option ng-selected="channel.video_quality == '4K'" value="4K">4K
                                        </option>
                                    </select>
                                </div>
                                <p class="error-msg" data-ng-show="errors.age_limit.has">Age limit required</p>
                            </div>
                        </div>

                        <!-- Organizations -->
                        <div class="form-group" data-ng-class="{'has-error': errors.organization.has}">
                            <label>
                                Organizations
                                <span class="required">*</span>
                            </label>
                            <div class="form-input">
                                <select multiple data-jquery="select2_custom_ddl" myValue="channel.organization"
                                    myPlaceholder="Select organization" ng-init="vodGridCtrl.editVideo.category"
                                    name="organization" class="admin_category_sub form-control select2_custom_ddl"
                                    data-ng-model="channel.organization" style="width: 100%;"
                                    ng-options="org.organization_id as org.organization_name for org in channelGridCtrl.OrganizationList">
                                </select>
                            </div>
                            <p class="error-msg" data-ng-show="errors.organization.has">
                                @{{errors.organization.message}}
                            </p>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed #e0e4e9;">

                <!-- Streaming Url -->
                <div class="panel-heading">
                    <label class="fs-4 fw-bold"
                        style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                        Streaming Url
                    </label>

                    <div class="division flexbox">
                        <div class="one-set width-50">
                            <div class="form-group" data-ng-class="{'has-error': errors.streaming_url.has}">
                                <label>
                                    Default Streaming Url
                                    <span class="required">*</span>
                                </label>
                                <div class="form-input" data-ng-class="{'has-error': errors.streaming_url.has}">
                                    <input type="text" name="streaming_url" class="form-control"
                                        placeholder="Enter Streaming Url" data-ng-model="channel.streaming_url">
                                </div>
                                <p class="error-msg" data-ng-show="errors.streaming_url.has">
                                    @{{errors.streaming_url.message}}</p>
                            </div>
                            <div class="form-group" data-ng-class="{'has-error': errors.policy.has}">
                                <label>
                                    Select Policy
                                    <span class="required">*</span>
                                </label>
                                <div class="form-input">
                                    <select allowClear="1" data-jquery="select2_custom_ddl" name="policy"
                                        class="admin_category_sub form-control select2_custom_ddl"
                                        myValue="channel.policy" myPlaceholder="Select Policy"
                                        data-ng-model="channel.policy"
                                        ng-options="policy.id as policy.policy_name for policy in channelGridCtrl.PolicyList">
                                        <option value="">--- Select ---</option>
                                        <!-- <option value="hello">hello</option>
                                        <option value="byy">byy</option> -->
                                    </select>
                                </div>
                                <p class="error-msg" data-ng-show="errors.policy.has">
                                    The policy field is required.
                                </p>
                            </div>
                        </div>

                        <div class="one-set width-50">
                            <!-- DRM Type -->
                            <div class="form-group" data-ng-class="{'has-error': errors.drm_type.has}">
                                <label>
                                    Select Drm Provider
                                    <span class="required">*</span>
                                </label>
                                <div class="form-input">
                                    <select allowClear="1" data-jquery="select2_custom_ddl" name="drm_type"
                                        class="admin_category_sub form-control select2_custom_ddl"
                                        myValue="channel.drm_type" myPlaceholder="Select DRM Type"
                                        data-ng-model="channel.drm_type"
                                        data-ng-options="drm.drm_provider as drm.drm_provider for drm in channelGridCtrl.drmProfiles | unique:'drm_provider'">
                                        <option value="">--- Select DRM Type ---</option>
                                    </select>
                                </div>
                                <p class="error-msg" data-ng-show="errors.drm_type.has">
                                    The drm type field is required.
                                </p>
                            </div>

                            <!-- DRM Profile: PallyCon -->
                            <div class="form-group"
                                data-ng-if="channel.drm_type === 'Pallycon' || channel.drm_type === 'EZDRM'">
                                <label>
                                    Select DRM Profile
                                    <span class="required">*</span>
                                </label>
                                <div class="form-input">
                                    <select allowClear="1" data-jquery="select2_custom_ddl" name="drm_profile"
                                        class="admin_category_sub form-control select2_custom_ddl"
                                        myValue="channel.drm_profile" myPlaceholder="Select DRM Profile"
                                        data-ng-model="channel.drm_profile"
                                        data-ng-options="drm.drmprofile.id as drm.drmprofile.drm_name for drm in channelGridCtrl.drmProfiles | filter:{drm_provider: channel.drm_type}">
                                        <option value="">--- Select DRM Profile ---</option>
                                    </select>

                                </div>
                                <!-- <p class="error-msg" data-ng-show="errors.age_limit.has">@{{ errors.age_limit.has }}
                                </p> -->
                            </div>

                            <!-- Playback Token Generator -->
                            <div class="form-group">
                                <label>
                                    Select Playback Token Generator
                                    <span class="required">*</span>
                                </label>
                                <div class="form-input" data-ng-class="{'has-error': errors.playback_token.has}">
                                    <select allowClear="1" data-jquery="select2_custom_ddl" name="playback_token"
                                        class="admin_category_sub form-control select2_custom_ddl"
                                        myValue="channel.playback_token" myPlaceholder="Select Playback Token Generator"
                                        ng-options="token.id as token.name for token in channelGridCtrl.playbackTokenList"
                                        data-ng-model="channel.playback_token">
                                        <option value="">--- Select ---</option>
                                        <!-- <option value="hello">hello</option>
                                        <option value="byy">byy</option> -->
                                    </select>
                                </div>
                                <p class="error-msg" data-ng-show="errors.playback_token.has">
                                    The playback token field is required.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr ng-if="!livePage" style="border-top: 1px dashed #e0e4e9;">

                <!-- assign Content -->
                <div ng-if="!livePage" class="panel-group" id="accordian-content-set" role="tablist"
                    aria-multiselectable="true">
                    <div class="panel panel-default" style="margin-bottom: 20px; border-radius: 5px;">
                        <!-- drop down code -->
                        <div class="panel-heading" role="tab" id="heading-content-set">
                            <a role="button" data-toggle="collapse" data-parent="#accordion-content-set"
                                href="#collapse-content-set" aria-expanded="false" aria-controls="collapse-content-set"
                                class="collapsed"
                                style="display: flex; align-items: center; text-decoration: none; color: #333;">
                                <label
                                    style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                                    Assigned Content Sets
                                </label>
                                <i class="arrow-icon fa fa-chevron-down" style="transition: transform 0.3s;"></i>
                            </a>
                        </div>

                        <div id="collapse-content-set" class="panel-collapse collapse" role="tabpanel"
                            aria-labelledby="heading-content-set">
                            <div class="panel-body">

                                <div class="page-heading flexbox align-items-center flex-wrap">
                                    <!-- <h1 style="font-weight: 900; font-size: 1.2rem;">Credit Card</h1><br> -->
                                    <div class="right-side flexbox align-items-center" data-toggle="modal"
                                        data-target="#assigned-content">
                                        <a data-ng-if="checkAccess('vod')"
                                            data-ng-click="dashCtrl.addOrganization($event)" href="javascript:void(0)"
                                            class="button button-blue sidepanel-open">
                                            <svg viewBox="0 0 18 18" version="1.1" x="0px" y="0px" width="18px"
                                                height="18px">
                                                <g>
                                                    <path
                                                        d="M 9.3198 0.3768 C 4.5397 0.3768 0.7 4.218 0.7 8.9999 C 0.7 13.7819 4.5397 17.6231 9.3198 17.6231 C 14.1 17.6231 17.9397 13.7819 17.9397 8.9999 C 17.9397 4.218 14.1 0.3768 9.3198 0.3768 ZM 14.0216 9.3919 C 14.0216 9.6271 13.8649 9.7838 13.6299 9.7838 L 10.2993 9.7838 C 10.1819 9.7838 10.1034 9.8622 10.1034 9.9798 L 10.1034 13.3115 C 10.1034 13.5467 9.9467 13.7035 9.7117 13.7035 L 8.9281 13.7035 C 8.6929 13.7035 8.5363 13.5467 8.5363 13.3115 L 8.5363 9.9798 C 8.5363 9.8622 8.4579 9.7838 8.3403 9.7838 L 5.0099 9.7838 C 4.7748 9.7838 4.6182 9.6271 4.6182 9.3919 L 4.6182 8.6079 C 4.6182 8.3728 4.7748 8.216 5.0099 8.216 L 8.3403 8.216 C 8.4579 8.216 8.5363 8.1376 8.5363 8.02 L 8.5363 4.6883 C 8.5363 4.4532 8.6929 4.2964 8.9281 4.2964 L 9.7117 4.2964 C 9.9467 4.2964 10.1034 4.4532 10.1034 4.6883 L 10.1034 8.02 C 10.1034 8.1376 10.1819 8.216 10.2993 8.216 L 13.6299 8.216 C 13.8649 8.216 14.0216 8.3728 14.0216 8.6079 L 14.0216 9.3919 Z"
                                                        fill="#ffffff" />
                                                </g>
                                            </svg>
                                            <!-- <span>{{trans('subscribers::index.add_subscribers')}}</span> -->
                                            <span>Assigned Content Sets</span>
                                        </a>
                                    </div>
                                </div>

                                <div style="margin-top: 10px;">
                                    <div style="max-height: 200px; overflow-y: auto; padding: 5px;">
                                        <!-- <div class="bundle-item"
                                            ng-repeat="bundle in channelGridCtrl.selectedVideo.bundles"
                                            data-id="@{{bundle.id}}" data-ng-model="channel.content_sets"
                                            style="border: 1px solid #ccc; padding: 10px; margin-bottom: 5px; border-radius: 4px;">

                                            <span class="bundle-title">@{{bundle.organization_name}}</span>
                                            <span class="bundle-sub center"> Post Event</span>
                                            <span class="bundle-delete" ng-click="removeBundle(bundle)"
                                                style="float: right; color: red; cursor: pointer;">
                                                <i class="glyphicon glyphicon-remove-circle"></i>
                                            </span>
                                        </div> -->

                                        <div class="bundle-item"
                                            ng-repeat="org in channelGridCtrl.selectedVideo.bundles"
                                            data-id="@{{org.organization_id}}" data-ng-model="channel.content_sets"
                                            style="border:1px solid #ccc; padding:10px; margin-bottom:5px; border-radius:4px;">

                                            <span class="bundle-title" style="font-weight:bold;">
                                                @{{org.organization_name}}
                                            </span>
                                            <br>

                                            <span class="bundle-sub center">
                                                <span ng-repeat="bundle in org.bundles | limitTo:3">
                                                    @{{bundle.name}}<span ng-if="!$last">, </span>
                                                </span>
                                                <span ng-if="org.bundles.length > 3">, more</span>
                                            </span>

                                            <span class="bundle-delete" ng-click="removeBundle(org)"
                                                style="float:right; color:red; cursor:pointer;">
                                                <i class="glyphicon glyphicon-remove-circle"></i>
                                            </span>
                                        </div>


                                        <!-- <div class="bundle-item"
                                            ng-repeat="org in channelGridCtrl.selectedVideo.bundles"
                                            data-id="@{{org.organization_id}}"
                                            style="border:1px solid #ccc; padding:10px; margin-bottom:5px; border-radius:4px;">

                                            <span class="bundle-title" style="font-weight:bold;">
                                                @{{org.organization_name}}
                                            </span>
                                            <br>

                                            <span class="bundle-sub center">
                                                <span ng-repeat="bundle in org.bundles | limitTo:3">
                                                    @{{bundle.name}}<span ng-if="!$last">, </span>
                                                </span>
                                                <span ng-if="org.bundles.length > 3">, more</span>
                                            </span>

                                            <span class="bundle-delete" ng-click="removeOrganization(org)"
                                                style="float:right; color:red; cursor:pointer;">
                                                <i class="glyphicon glyphicon-remove-circle"></i>
                                            </span>
                                        </div> -->


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed #e0e4e9;">

                <div class="division flexbox">
                    <div class="one-set width-50">
                        <!-- epg -->
                        <label class="fs-4 fw-bold"
                            style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                            EPG
                        </label>
                        <div class="form-group" data-ng-class="{'has-error': errors.epg_id.has}">
                            <label>
                                Epg Id
                            </label>
                            <div class="form-input" data-ng-class="{'has-error': errors.epg_id.has}">
                                <input type="text" name="epg_id" class="form-control" placeholder="Enter Epg Id"
                                    data-ng-model="channel.epg_id">
                            </div>
                            <p class="error-msg" data-ng-show="errors.epg_id.has">
                                @{{errors.epg_id.message}}
                            </p>
                        </div>

                        <!-- Age Rating and Parental Control -->
                        <label class="fs-4 fw-bold"
                            style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                            Age Rating and Parental Control
                        </label>
                        <div class="form-group">
                            <div class="switch-concept flexbox align-items-center">
                                <svg fill="#000000" width="20px" height="20px" viewBox="0 0 24 24" id="lock"
                                    data-name="Line Color" xmlns="http://www.w3.org/2000/svg" class="icon line-color">
                                    <path id="secondary"
                                        d="M13.5,14.5A1.5,1.5,0,1,1,12,13,1.5,1.5,0,0,1,13.5,14.5ZM12,17V16"
                                        style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                    </path>
                                    <rect id="primary" x="6" y="8" width="12" height="14" rx="1"
                                        transform="translate(27 3) rotate(90)"
                                        style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                    </rect>
                                    <path id="primary-2" data-name="primary" d="M16,9V7a4,4,0,0,0-4-4h0A4,4,0,0,0,8,7V9"
                                        style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                    </path>
                                </svg>

                                <div class="swich-content flexbox align-items-center flex-wrap">
                                    <label>Always Pin Locked</label>
                                    <div class="right-side flexbox align-items-center">
                                        <span class="text">{{ __('video::videos.inactive') }}</span>
                                        <label class="switch">
                                            <input type="checkbox" name="pin_locked" ng-model="channel.pin_locked">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="text">{{ __('video::videos.active') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="one-set width-50">
                        <!-- Geo Blocking Policy -->
                        <label class="fs-4 fw-bold"
                            style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                            Geo Blocking Policy
                        </label>
                        <div class="form-group">
                            <div class="switch-concept flexbox align-items-center">
                                <svg viewBox="0 0 17 14" version="1.1" x="0px" y="0px" width="17px" height="14px">
                                    <g>
                                        <path
                                            d="M 12.6775 0.4999 L 0.6816 0.4999 C 0.3159 0.4999 -0.0001 0.8102 -0.0001 1.2068 L -0.0001 12.7929 C -0.0001 13.1722 0.2991 13.4999 0.6816 13.4999 L 12.6775 13.4999 C 13.0433 13.4999 13.3591 13.1894 13.3591 12.7929 L 13.3591 9.4481 L 15.8362 12.0171 C 15.9692 12.155 16.1355 12.2239 16.3184 12.2239 C 16.4015 12.2239 16.5012 12.2067 16.5844 12.1722 C 16.8337 12.0688 17 11.8101 17 11.5171 L 17 2.4655 C 17 2.1895 16.8337 1.9309 16.5844 1.8102 C 16.335 1.7067 16.0358 1.7584 15.8529 1.9653 L 13.3758 4.5343 L 13.3758 1.2068 C 13.3591 0.8102 13.0599 0.4999 12.6775 0.4999 ZM 11.9958 6.2413 L 11.9958 7.7584 L 11.9958 12.1033 L 1.3466 12.1033 L 1.3466 1.8964 L 11.9958 1.8964 L 11.9958 6.2413 ZM 15.6367 4.1722 L 15.6367 9.8447 L 13.3591 7.4826 L 13.3591 6.5516 L 15.6367 4.1722 Z"
                                            fill="#3d3d3d" />
                                    </g>
                                </svg>
                                <div class="swich-content flexbox align-items-center flex-wrap">
                                    <label>Geo Blocking Policy</label>
                                    <!-- <span>{{ __('video::videos.status') }}</span> -->
                                    <div class="right-side flexbox align-items-center">
                                        <span class="text">{{ __('video::videos.inactive') }}</span>
                                        <label class="switch">
                                            <input type="checkbox" name="geo_policy" ng-model="channel.geo_policy">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="text">{{ __('video::videos.active') }}</span>
                                    </div>
                                </div>
                            </div><br>

                            <div class="form-group" data-ng-class="{'has-error': errors.category.has}"
                                ng-if="channel.geo_policy">
                                <label>
                                    <span>
                                        Geo Country
                                    </span>
                                    <span class="required">*</span>
                                </label>
                                <div class="form-input">
                                    <select multiple data-jquery="select2_custom_ddl"
                                        myValue="channel.geo_block_country_list" myPlaceholder="Slect Country"
                                        ng-init="vodGridCtrl.editVideo.category" name="geo_block_country_list"
                                        class="admin_category_sub form-control select2_custom_ddl"
                                        data-ng-model="channel.geo_block_country_list"
                                        ng-options="geoblock.name as geoblock.name for geoblock in channelGridCtrl.geoBlockList">
                                    </select>
                                </div>
                                <p class="error-msg" data-ng-show="errors.category.has">@{{ errors.category.message }}
                                </p>
                            </div>
                        </div><br><br>

                        <!-- Group Chat -->
                        <!-- <label class="fs-4 fw-bold"
                            style="flex-grow: 1; font-size: 1.3rem; font-weight: 900; padding: 0.5rem 0 0.5rem 0;">
                            Group Chat
                        </label>
                        <div class="form-group" >
                            <div class="switch-concept flexbox align-items-center">
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve">
                                    <circle style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:10;"
                                        cx="23" cy="13" r="5" />
                                    <path style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:10;"
                                        d="M16,25c0-3.866,3.134-7,7-7s7,3.134,7,7" />
                                    <circle style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:10;" cx="9"
                                        cy="13" r="5" />
                                    <path style="fill:none;stroke:#000000;stroke-width:2;stroke-miterlimit:10;"
                                        d="M2,25c0-3.866,3.134-7,7-7s7,3.134,7,7" />
                                </svg>

                                <div class="swich-content flexbox align-items-center flex-wrap">
                                    <label>Group Chat Allowed</label>
                                    <div class="right-side flexbox align-items-center">
                                        <span class="text">{{ __('video::videos.inactive') }}</span>
                                        <label class="switch">
                                            <input type="checkbox" name="group_chat" ng-model="channel.group_chat">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="text">{{ __('video::videos.active') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </form>
        </div>

        <!-- bottom button -->
        <div class="bottom-button text-right">
            <a ng-if="channel.id" class="delete-button" href="javascript:void(0)" data-toggle="modal"
                data-target="#videoDeleteModal" style="padding: 0px;"
                data-ng-click="channelGridCtrl.deleteSingleRecordVideos(channel.id, channel.channel_name)">
                <svg viewBox="0 0 14 17" version="1.1" x="0px" y="0px" width="14px" height="17px"
                    style="margin: 0 0 -3px 0;">
                    <g>
                        <path
                            d="M 12.9751 3.1697 L 1.0323 3.1697 C 0.7284 3.1697 0.4821 2.9233 0.4821 2.6193 L 0.4821 1.574 C 0.4821 1.2699 0.7284 1.0238 1.0323 1.0238 L 4.8785 1.0238 C 4.9061 0.7457 5.1406 0.019 5.426 0.019 L 8.5814 0.019 C 8.8668 0.019 9.1013 0.7457 9.1289 1.0238 L 12.9751 1.0238 C 13.2791 1.0238 13.5254 1.2699 13.5255 1.5741 L 13.5255 2.6193 C 13.5255 2.9231 13.2791 3.1697 12.9751 3.1697 ZM 12.3715 15.5684 C 12.3715 15.8722 12.1252 16.1185 11.8212 16.1185 L 2.1863 16.1185 C 1.8822 16.1185 1.6359 15.8722 1.6359 15.5684 L 1.6359 4.2266 L 12.3715 4.2266 L 12.3715 15.5684 ZM 5.6652 6.7257 C 5.6652 6.3907 5.3936 6.1191 5.0585 6.1191 C 4.7233 6.1191 4.4518 6.3907 4.4518 6.7257 L 4.4518 12.7393 C 4.4518 13.0741 4.7233 13.3459 5.0585 13.3459 C 5.3936 13.3459 5.6652 13.0741 5.6652 12.7393 L 5.6652 6.7257 ZM 9.5558 6.7257 C 9.5558 6.3907 9.2839 6.1191 8.949 6.1191 C 8.6139 6.1191 8.3422 6.3907 8.3422 6.7257 L 8.3422 12.7393 C 8.3422 13.0741 8.6138 13.3459 8.949 13.3459 C 9.2841 13.3459 9.5558 13.0741 9.5558 12.7393 L 9.5558 6.7257 Z"
                            fill="#fc4e4e" />
                    </g>
                </svg>
            </a>

            <a class="save" ng-if="livePage" href="{{ url('admin/channel') }}">
                {{ __('video::videos.back') }}
            </a>

            <a class="save" ng-if="!livePage" href="{{ url('admin/channel') }}">
                {{ __('video::videos.back') }}
            </a>

            <button ng-if="livePage && !editPage" id="channelEditFormSubmit"
                data-ng-click="channelGridCtrl.saveChannel($event, channel.id)" class="publish-now">
                {{ __('video::videos.publish_now') }}
            </button>

            <button ng-if="!livePage" id="channelEditFormSubmit"
                data-ng-click="channelGridCtrl.saveChannelEdit($event, channel.id)" class="publish-now">
                {{ __('video::videos.publish_now') }}
            </button>
        </div>
    </div>
</div>

<style>
    .content-container {
        border: 1px solid #ccc;
        /* border: 2px dashed #337ab7; */
        background-color: #f9f9f9;
        border-radius: 8px;
        /* min-height: 150px; */
        padding: 10px;
        margin-bottom: 15px;
        background: #fff;
        cursor: move;
    }

    .content-header {
        font-weight: bold;
        margin-bottom: 5px;
        display: flex;
    }

    .item-box {
        border: 1px solid #ddd;
        padding: 8px;
        margin: 5px 0;
        border-radius: 4px;
        background-color: #f9f9f9;
        /* cursor: move; */
    }

    .drop-zone {
        border: 2px dashed #ccc;
        padding: 10px;
        text-align: center;
        color: #999;
        font-style: italic;
        margin-top: 10px;
    }

    .assign-btns {
        margin-top: 15px;
        text-align: center;
    }

    .search-box {
        margin-bottom: 10px;
    }

    .bundle-item {
        background-color: #f5f5f5;
        border-radius: 50px;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .bundle-title {
        font-weight: 600;
        flex-shrink: 0;
    }

    .bundle-sub {
        flex-grow: 1;
        color: #555;
    }

    .bundle-price {
        white-space: nowrap;
        flex-shrink: 0;
    }

    .bundle-price del {
        color: #999;
        margin-right: 3px;
    }

    .bundle-rent {
        color: #333;
        font-weight: 500;
        margin-left: 10px;
    }

    .bundle-remove {
        color: red;
        margin-left: auto;
        cursor: pointer;
        font-size: 16px;
        /* position: absolute; */
        /* top: 5.2rem; */
        right: 3rem;
    }

    .bundle-delete {
        color: red;
        margin-left: auto;
        cursor: pointer;
        font-size: 16px;
        /* position: absolute;
        top: 5.2rem;
        right: 2rem; */
    }

    .scroll-box {
        max-height: 350px;
        overflow-y: auto;
        padding: 5px;
    }
</style>

<!-- Assigned Content Sets model code start -->
<div class="modal fade" id="assigned-content" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="padding: 10px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
                <div class="page-heading flexbox align-items-center flex-wrap">
                    <h4 class="modal-title">
                        Add Bundles
                    </h4>
                </div>
                <p style="margin: 0; font-size: 13px;">Drag and drop to assign bundles</p>
            </div>

            <div class="row">
                <!-- drag zone -->
                <div class="col-md-6">
                    <div class="page-heading flexbox align-items-center flex-wrap" style="margin-top: 15px;">
                        <h4>Available Content Sets</h4>
                    </div>
                    <input type="text" id="searchAvailable" class="form-control search-box"
                        placeholder="Search Organization or Content Set">

                    <div style="max-height: 350px; overflow-y: auto; padding: 5px;">
                        <div id="availableBundles">
                            <div class="content-container panel panel-default" draggable="true"
                                data-ng-repeat="org in ChannelContentList" data-id="@{{ org.organization_id }}">
                                <div class="content-header">
                                    <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                    @{{ org.organization_name }}
                                </div>
                                <div class="item-box" ng-repeat="bundle in org.bundles">
                                    <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                    <i class="glyphicon glyphicon-blackboard"></i>
                                    @{{ bundle.name }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- drop zone -->
                <div class="col-md-6">
                    <div class="page-heading flexbox align-items-center flex-wrap" style="margin-top: 15px;">
                        <h4>Assigned Content Sets</h4>
                    </div>

                    <input type="text" id="searchAdded" class="form-control search-box"
                        placeholder="Search Organization or Content Set">

                    <div class="scroll-box">
                        <div class="content-container panel panel-default" draggable="true"
                            data-ng-repeat="org in channel.selectedBundles" data-id="@{{ org.organization_id }}">
                            <div class="content-header">
                                <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                @{{ org.organization_name }}
                                <span class="bundle-remove" ng-click="removeBundle(org)">
                                    <i class="glyphicon glyphicon-remove-circle"></i>
                                </span>
                            </div>
                            <div class="item-box" ng-repeat="bundle in org.bundles">
                                <span class="channel-drag"><i class="glyphicon glyphicon-move"></i></span>
                                <i class="glyphicon glyphicon-blackboard"></i>
                                @{{ bundle.name }}
                            </div>
                        </div>

                        <div id="addedBundles" style="min-height: 145px;">
                            <div class="drop-zone">DROP HERE</div>
                        </div>
                    </div>

                    <!-- <pre>@{{ channel.selectedBundles | json }}</pre> -->
                </div>
            </div>

            <div class="assign-btns">
                <button type="button" class="button button-blue" data-dismiss="modal"
                    ng-click="channelGridCtrl.assignSelectedBundles()">
                    Assign
                </button>&nbsp;
                <button class="button button-gray" data-dismiss="modal">
                    Cancel
                </button>
            </div>

        </div>
    </div>
</div>