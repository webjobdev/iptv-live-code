<div id="announcment">
    <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
            <div class="loader"></div>
        </div>
    </div>
    <div class="table_responsive">
        <table class="table subscription-plan-grid" id="fixTable" data-ng-class="{'no-records': noRecords}">
            <thead>
                <tr>
                    @include('audio::admin.common.bulkActionLayout', ['access_type' => 'currencys'])
                    <!-- <th class="text-center">#</th> -->
                    <th data-ng-repeat="field in heading"
                        ng-class="{'centre': field.name == 'No. of Videos' || field.name == 'order'}">
                        @{{field.name}}
                        <span data-ng-if="field.sort==true" id="" class="th-inner sortable both"
                            data-ng-class="{showGridArrow:field.sort}"
                            data-ng-click="fieldOrder($event,field.value)"></span>
                        <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr class="search_text">
                    <td></td>
                    <td></td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.currency_code"
                            placeholder="Enter Currency Code" data-boot-tooltip="true" title="Currency Code">
                    </td>
                    <td></td>
                    <td class="search_product">
                        <input type="text" class="form-control" data-ng-model="searchRecords.position"
                            placeholder="Enter Position" data-boot-tooltip="true" title="Position">
                    </td>
                    <td></td>
                </tr>
                <tr data-ng-if="noRecords">
                    <td colspan="8" colspan="@{{heading.length + 1}}" class="no-data center">
                        {{trans('base::general.not_found')}}
                    </td>
                </tr>
                <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index"
                    data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                    <td>
                        <div class="ckbox ckbox-default">
                            <input type="checkbox" class="checkbox" id="roles_@{{record.id}}"
                                ng-click="selectRecord($event, record.id)" value="@{{record.id}}"
                                name="selectedCheckbox[]">
                            <label for="roles_@{{record.id}}"></label>
                        </div>
                    </td>

                    <td>@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>


                    <td>@{{ record.currency_code }}</td>
                    <td>@{{ record.currency_symbol }}</td>
                    <td>@{{ record.position }}</td>
                    <td>@{{ record.sample }}</td>

                    <td class="table-action">
                        <div class="flexbox align-items-center justify-center">

                            <div data-ng-if="checkAccess('currencies.edit')" class="form-group row"
                                style="margin-bottom: 0px; margin-right: 5px;">
                                <label class="switch">
                                    <input type="checkbox" ng-checked="record.organization_currencies[0].is_active == 1"
                                        ng-click="togglePublishNow(record, record.id)">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @include('audio::admin.common.singleRecordDeleteModal')
    @include('audio::admin.common.singleRecordStatusUpdateModal')
    @include('base::layouts.pagination')
</div>

<!-- form code -->
<div class="sidepanel">
    <div class="overlay"></div>
    <div class="pop_over_continer form-page">
        <form name="CurrencyForm" id="CurrencyForm" method="POST" data-base-validator
            data-ng-submit="CurrCtrl.save($event, CurrCtrl.currency.id)" enctype="multipart/form-data">

            {!! csrf_field() !!}

            <div class="sidepanel-header flexbox align-items-center">
                <h5 data-ng-if="!CurrCtrl.currency.id">
                    Create Currency Data
                </h5>
                <h5 data-ng-if="CurrCtrl.currency.id">
                    Edit Currency Data
                </h5>
            </div>

            <div class="sidepanel-scroll">
                @include('base::partials.errors')

                <div class="form-group">
                    <label>
                        Currency Code<span class="required">*</span>:</label>
                    <div class="form-input">
                        <select class="form-control" data-jquery="select2_custom_ddl" name="currency_code"
                            data-ng-change="CurrCtrl.updateCardPattern()" myPlaceholder="Select Currency Code"
                            data-ng-model="CurrCtrl.currency.currency_code" myValue="CurrCtrl.currency.currency_code">
                            <option disabled value="">Choose Currency Code</option>
                            <option value="">Select Currency</option>
                            <option value="AED">AED - United Arab Emirates Dirham</option>
                            <option value="AFN">AFN - Afghan Afghani</option>
                            <option value="ALL">ALL - Albanian Lek</option>
                            <option value="AMD">AMD - Armenian Dram</option>
                            <option value="ANG">ANG - Netherlands Antillean Guilder</option>
                            <option value="AOA">AOA - Angolan Kwanza</option>
                            <option value="ARS">ARS - Argentine Peso</option>
                            <option value="AUD">AUD - Australian Dollar</option>
                            <option value="AWG">AWG - Aruban Florin</option>
                            <option value="AZN">AZN - Azerbaijani Manat</option>
                            <option value="BAM">BAM - Bosnia-Herzegovina Convertible Mark</option>
                            <option value="BBD">BBD - Barbadian Dollar</option>
                            <option value="BDT">BDT - Bangladeshi Taka</option>
                            <option value="BGN">BGN - Bulgarian Lev</option>
                            <option value="BHD">BHD - Bahraini Dinar</option>
                            <option value="BIF">BIF - Burundian Franc</option>
                            <option value="BMD">BMD - Bermudian Dollar</option>
                            <option value="BND">BND - Brunei Dollar</option>
                            <option value="BOB">BOB - Bolivian Boliviano</option>
                            <option value="BRL">BRL - Brazilian Real</option>
                            <option value="BSD">BSD - Bahamian Dollar</option>
                            <option value="BTN">BTN - Bhutanese Ngultrum</option>
                            <option value="BWP">BWP - Botswanan Pula</option>
                            <option value="BYN">BYN - Belarusian Ruble</option>
                            <option value="BZD">BZD - Belize Dollar</option>
                            <option value="CAD">CAD - Canadian Dollar</option>
                            <option value="CDF">CDF - Congolese Franc</option>
                            <option value="CHF">CHF - Swiss Franc</option>
                            <option value="CLP">CLP - Chilean Peso</option>
                            <option value="CNY">CNY - Chinese Yuan</option>
                            <option value="COP">COP - Colombian Peso</option>
                            <option value="CRC">CRC - Costa Rican Colón</option>
                            <option value="CUP">CUP - Cuban Peso</option>
                            <option value="CVE">CVE - Cape Verdean Escudo</option>
                            <option value="CZK">CZK - Czech Koruna</option>
                            <option value="DJF">DJF - Djiboutian Franc</option>
                            <option value="DKK">DKK - Danish Krone</option>
                            <option value="DOP">DOP - Dominican Peso</option>
                            <option value="DZD">DZD - Algerian Dinar</option>
                            <option value="EGP">EGP - Egyptian Pound</option>
                            <option value="ERN">ERN - Eritrean Nakfa</option>
                            <option value="ETB">ETB - Ethiopian Birr</option>
                            <option value="EUR">EUR - Euro</option>
                            <option value="FJD">FJD - Fijian Dollar</option>
                            <option value="FKP">FKP - Falkland Islands Pound</option>
                            <option value="FOK">FOK - Faroese Króna</option>
                            <option value="GBP">GBP - British Pound Sterling</option>
                            <option value="GEL">GEL - Georgian Lari</option>
                            <option value="GGP">GGP - Guernsey Pound</option>
                            <option value="GHS">GHS - Ghanaian Cedi</option>
                            <option value="GIP">GIP - Gibraltar Pound</option>
                            <option value="GMD">GMD - Gambian Dalasi</option>
                            <option value="GNF">GNF - Guinean Franc</option>
                            <option value="GTQ">GTQ - Guatemalan Quetzal</option>
                            <option value="GYD">GYD - Guyanaese Dollar</option>
                            <option value="HKD">HKD - Hong Kong Dollar</option>
                            <option value="HNL">HNL - Honduran Lempira</option>
                            <option value="HRK">HRK - Croatian Kuna</option>
                            <option value="HTG">HTG - Haitian Gourde</option>
                            <option value="HUF">HUF - Hungarian Forint</option>
                            <option value="IDR">IDR - Indonesian Rupiah</option>
                            <option value="ILS">ILS - Israeli New Shekel</option>
                            <option value="IMP">IMP - Isle of Man Pound</option>
                            <option value="INR">INR - Indian Rupee</option>
                            <option value="IQD">IQD - Iraqi Dinar</option>
                            <option value="IRR">IRR - Iranian Rial</option>
                            <option value="ISK">ISK - Icelandic Króna</option>
                            <option value="JEP">JEP - Jersey Pound</option>
                            <option value="JMD">JMD - Jamaican Dollar</option>
                            <option value="JOD">JOD - Jordanian Dinar</option>
                            <option value="JPY">JPY - Japanese Yen</option>
                            <option value="KES">KES - Kenyan Shilling</option>
                            <option value="KGS">KGS - Kyrgystani Som</option>
                            <option value="KHR">KHR - Cambodian Riel</option>
                            <option value="KID">KID - Kiribati Dollar</option>
                            <option value="KMF">KMF - Comorian Franc</option>
                            <option value="KRW">KRW - South Korean Won</option>
                            <option value="KWD">KWD - Kuwaiti Dinar</option>
                            <option value="KYD">KYD - Cayman Islands Dollar</option>
                            <option value="KZT">KZT - Kazakhstani Tenge</option>
                            <option value="LAK">LAK - Laotian Kip</option>
                            <option value="LBP">LBP - Lebanese Pound</option>
                            <option value="LKR">LKR - Sri Lankan Rupee</option>
                            <option value="LRD">LRD - Liberian Dollar</option>
                            <option value="LSL">LSL - Lesotho Loti</option>
                            <option value="LYD">LYD - Libyan Dinar</option>
                            <option value="MAD">MAD - Moroccan Dirham</option>
                            <option value="MDL">MDL - Moldovan Leu</option>
                            <option value="MGA">MGA - Malagasy Ariary</option>
                            <option value="MKD">MKD - Macedonian Denar</option>
                            <option value="MMK">MMK - Myanmar Kyat</option>
                            <option value="MNT">MNT - Mongolian Tugrik</option>
                            <option value="MOP">MOP - Macanese Pataca</option>
                            <option value="MRU">MRU - Mauritanian Ouguiya</option>
                            <option value="MUR">MUR - Mauritian Rupee</option>
                            <option value="MVR">MVR - Maldivian Rufiyaa</option>
                            <option value="MWK">MWK - Malawian Kwacha</option>
                            <option value="MXN">MXN - Mexican Peso</option>
                            <option value="MYR">MYR - Malaysian Ringgit</option>
                            <option value="MZN">MZN - Mozambican Metical</option>
                            <option value="NAD">NAD - Namibian Dollar</option>
                            <option value="NGN">NGN - Nigerian Naira</option>
                            <option value="NIO">NIO - Nicaraguan Córdoba</option>
                            <option value="NOK">NOK - Norwegian Krone</option>
                            <option value="NPR">NPR - Nepalese Rupee</option>
                            <option value="NZD">NZD - New Zealand Dollar</option>
                            <option value="OMR">OMR - Omani Rial</option>
                            <option value="PAB">PAB - Panamanian Balboa</option>
                            <option value="PEN">PEN - Peruvian Sol</option>
                            <option value="PGK">PGK - Papua New Guinean Kina</option>
                            <option value="PHP">PHP - Philippine Peso</option>
                            <option value="PKR">PKR - Pakistani Rupee</option>
                            <option value="PLN">PLN - Polish Zloty</option>
                            <option value="PYG">PYG - Paraguayan Guarani</option>
                            <option value="QAR">QAR - Qatari Rial</option>
                            <option value="RON">RON - Romanian Leu</option>
                            <option value="RSD">RSD - Serbian Dinar</option>
                            <option value="RUB">RUB - Russian Ruble</option>
                            <option value="RWF">RWF - Rwandan Franc</option>
                            <option value="SAR">SAR - Saudi Riyal</option>
                            <option value="SBD">SBD - Solomon Islands Dollar</option>
                            <option value="SCR">SCR - Seychellois Rupee</option>
                            <option value="SDG">SDG - Sudanese Pound</option>
                            <option value="SEK">SEK - Swedish Krona</option>
                            <option value="SGD">SGD - Singapore Dollar</option>
                            <option value="SHP">SHP - Saint Helena Pound</option>
                            <option value="SLE">SLE - Sierra Leonean Leone</option>
                            <option value="SOS">SOS - Somali Shilling</option>
                            <option value="SRD">SRD - Surinamese Dollar</option>
                            <option value="SSP">SSP - South Sudanese Pound</option>
                            <option value="STN">STN - São Tomé and Príncipe Dobra</option>
                            <option value="SYP">SYP - Syrian Pound</option>
                            <option value="SZL">SZL - Swazi Lilangeni</option>
                            <option value="THB">THB - Thai Baht</option>
                            <option value="TJS">TJS - Tajikistani Somoni</option>
                            <option value="TMT">TMT - Turkmenistani Manat</option>
                            <option value="TND">TND - Tunisian Dinar</option>
                            <option value="TOP">TOP - Tongan Paʻanga</option>
                            <option value="TRY">TRY - Turkish Lira</option>
                            <option value="TTD">TTD - Trinidad and Tobago Dollar</option>
                            <option value="TVD">TVD - Tuvaluan Dollar</option>
                            <option value="TWD">TWD - New Taiwan Dollar</option>
                            <option value="TZS">TZS - Tanzanian Shilling</option>
                            <option value="UAH">UAH - Ukrainian Hryvnia</option>
                            <option value="UGX">UGX - Ugandan Shilling</option>
                            <option value="USD">USD - United States Dollar</option>
                            <option value="UYU">UYU - Uruguayan Peso</option>
                            <option value="UZS">UZS - Uzbekistan Som</option>
                            <option value="VES">VES - Venezuelan Bolívar</option>
                            <option value="VND">VND - Vietnamese Dong</option>
                            <option value="VUV">VUV - Vanuatu Vatu</option>
                            <option value="WST">WST - Samoan Tala</option>
                            <option value="XAF">XAF - Central African CFA Franc</option>
                            <option value="XCD">XCD - East Caribbean Dollar</option>
                            <option value="XOF">XOF - West African CFA Franc</option>
                            <option value="XPF">XPF - CFP Franc</option>
                            <option value="YER">YER - Yemeni Rial</option>
                            <option value="ZAR">ZAR - South African Rand</option>
                            <option value="ZMW">ZMW - Zambian Kwacha</option>
                            <option value="ZWL">ZWL - Zimbabwean Dollar</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        Currency Symbol:
                    </label>
                    <div class="form-input">
                        <select class="form-control" data-jquery="select2_custom_ddl" name="currency_symbol"
                            myPlaceholder="Select Currency Symbol" data-ng-model="CurrCtrl.currency.currency_symbol"
                            myValue="CurrCtrl.currency.currency_symbol">
                            <option disabled value="">Choose Currency Symbol</option>
                            <option value="">Select Currency</option>
                            <option value="(د.إ)">United Arab Emirates Dirham (د.إ)</option>
                            <option value="(؋)">Afghan Afghani (؋)</option>
                            <option value="(L)">Albanian Lek (L)</option>
                            <option value="(֏)">Armenian Dram (֏)</option>
                            <option value="(ƒ)">Netherlands Antillean Guilder (ƒ)</option>
                            <option value="(Kz)">Angolan Kwanza (Kz)</option>
                            <option value="($)">Argentine Peso ($)</option>
                            <option value="(A$)">Australian Dollar (A$)</option>
                            <option value="(ƒ)">Aruban Florin (ƒ)</option>
                            <option value="(₼)">Azerbaijani Manat (₼)</option>
                            <option value="(KM)">Bosnia-Herzegovina Convertible Mark (KM)</option>
                            <option value="(Bds$)">Barbadian Dollar (Bds$)</option>
                            <option value="(৳)">Bangladeshi Taka (৳)</option>
                            <option value="(лв)">Bulgarian Lev (лв)</option>
                            <option value="(.د.ب)">Bahraini Dinar (.د.ب)</option>
                            <option value="(FBu)">Burundian Franc (FBu)</option>
                            <option value="(BD$)">Bermudian Dollar (BD$)</option>
                            <option value="(B$)">Brunei Dollar (B$)</option>
                            <option value="(Bs.)">Bolivian Boliviano (Bs.)</option>
                            <option value="(R$)">Brazilian Real (R$)</option>
                            <option value="(B$)">Bahamian Dollar (B$)</option>
                            <option value="(Nu.)">Bhutanese Ngultrum (Nu.)</option>
                            <option value="(P)">Botswanan Pula (P)</option>
                            <option value="(Br)">Belarusian Ruble (Br)</option>
                            <option value="(BZ$)">Belize Dollar (BZ$)</option>
                            <option value="(C$)">Canadian Dollar (C$)</option>
                            <option value="(FC)">Congolese Franc (FC)</option>
                            <option value="(CHF)">Swiss Franc (CHF)</option>
                            <option value="(CLP$)">Chilean Peso (CLP$)</option>
                            <option value="(¥)">Chinese Yuan (¥)</option>
                            <option value="(COL$)">Colombian Peso (COL$)</option>
                            <option value="(₡)">Costa Rican Colón (₡)</option>
                            <option value="(₱)">Cuban Peso (₱)</option>
                            <option value="(Esc)">Cape Verdean Escudo (Esc)</option>
                            <option value="(Kč)">Czech Koruna (Kč)</option>
                            <option value="(Fdj)">Djiboutian Franc (Fdj)</option>
                            <option value="(kr)">Danish Krone (kr)</option>
                            <option value="(RD$)">Dominican Peso (RD$)</option>
                            <option value="(دج)">Algerian Dinar (دج)</option>
                            <option value="(£E)">Egyptian Pound (£E)</option>
                            <option value="(Nfk)">Eritrean Nakfa (Nfk)</option>
                            <option value="(Br)">Ethiopian Birr (Br)</option>
                            <option value="(€)">Euro (€)</option>
                            <option value="(FJ$)">Fijian Dollar (FJ$)</option>
                            <option value="(£)">Falkland Islands Pound (£)</option>
                            <option value="(kr)">Faroese Króna (kr)</option>
                            <option value="(£)">British Pound Sterling (£)</option>
                            <option value="(₾)">Georgian Lari (₾)</option>
                            <option value="(£)">Guernsey Pound (£)</option>
                            <option value="(₵)">Ghanaian Cedi (₵)</option>
                            <option value="(£)">Gibraltar Pound (£)</option>
                            <option value="(D)">Gambian Dalasi (D)</option>
                            <option value="(FG)">Guinean Franc (FG)</option>
                            <option value="(Q)">Guatemalan Quetzal (Q)</option>
                            <option value="(G$)">Guyanaese Dollar (G$)</option>
                            <option value="(HK$)">Hong Kong Dollar (HK$)</option>
                            <option value="(L)">Honduran Lempira (L)</option>
                            <option value="(kn)">Croatian Kuna (kn)</option>
                            <option value="(G)">Haitian Gourde (G)</option>
                            <option value="(Ft)">Hungarian Forint (Ft)</option>
                            <option value="(Rp)">Indonesian Rupiah (Rp)</option>
                            <option value="(₪)">Israeli New Shekel (₪)</option>
                            <option value="(£)">Isle of Man Pound (£)</option>
                            <option value="(₹)">Indian Rupee (₹)</option>
                            <option value="(ع.د)">Iraqi Dinar (ع.د)</option>
                            <option value="(﷼)">Iranian Rial (﷼)</option>
                            <option value="(kr)">Icelandic Króna (kr)</option>
                            <option value="(£)">Jersey Pound (£)</option>
                            <option value="(J$)">Jamaican Dollar (J$)</option>
                            <option value="(JD)">Jordanian Dinar (JD)</option>
                            <option value="(¥)">Japanese Yen (¥)</option>
                            <option value="(KSh)">Kenyan Shilling (KSh)</option>
                            <option value="(с)">Kyrgystani Som (с)</option>
                            <option value="(៛)">Cambodian Riel (៛)</option>
                            <option value="($)">Kiribati Dollar ($)</option>
                            <option value="(₩)">South Korean Won (₩)</option>
                            <option value="(KD)">Kuwaiti Dinar (KD)</option>
                            <option value="(KYD$)">Cayman Islands Dollar (KYD$)</option>
                            <option value="(₸)">Kazakhstani Tenge (₸)</option>
                            <option value="(₭)">Laotian Kip (₭)</option>
                            <option value="(£)">Lebanese Pound (£)</option>
                            <option value="(Rs)">Sri Lankan Rupee (Rs)</option>
                            <option value="(L$)">Liberian Dollar (L$)</option>
                            <option value="(L)">Lesotho Loti (L)</option>
                            <option value="(LD)">Libyan Dinar (LD)</option>
                            <option value="(MAD)">Moroccan Dirham (MAD)</option>
                            <option value="(L)">Moldovan Leu (L)</option>
                            <option value="(Ar)">Malagasy Ariary (Ar)</option>
                            <option value="(ден)">Macedonian Denar (ден)</option>
                            <option value="(K)">Myanmar Kyat (K)</option>
                            <option value="(₮)">Mongolian Tugrik (₮)</option>
                            <option value="(MOP$)">Macanese Pataca (MOP$)</option>
                            <option value="(UM)">Mauritanian Ouguiya (UM)</option>
                            <option value="(₨)">Mauritian Rupee (₨)</option>
                            <option value="(Rf)">Maldivian Rufiyaa (Rf)</option>
                            <option value="(MK)">Malawian Kwacha (MK)</option>
                            <option value="(Mex$)">Mexican Peso (Mex$)</option>
                            <option value="(RM)">Malaysian Ringgit (RM)</option>
                            <option value="(MT)">Mozambican Metical (MT)</option>
                            <option value="(N$)">Namibian Dollar (N$)</option>
                            <option value="(₦)">Nigerian Naira (₦)</option>
                            <option value="(C$)">Nicaraguan Córdoba (C$)</option>
                            <option value="(kr)">Norwegian Krone (kr)</option>
                            <option value="(Rs)">Nepalese Rupee (Rs)</option>
                            <option value="(NZ$)">New Zealand Dollar (NZ$)</option>
                            <option value="(﷼)">Omani Rial (﷼)</option>
                            <option value="(B/.)">Panamanian Balboa (B/.)</option>
                            <option value="(S/.)">Peruvian Sol (S/.)</option>
                            <option value="(K)">Papua New Guinean Kina (K)</option>
                            <option value="(₱)">Philippine Peso (₱)</option>
                            <option value="(₨)">Pakistani Rupee (₨)</option>
                            <option value="(zł)">Polish Zloty (zł)</option>
                            <option value="(₲)">Paraguayan Guarani (₲)</option>
                            <option value="(﷼)">Qatari Rial (﷼)</option>
                            <option value="(lei)">Romanian Leu (lei)</option>
                            <option value="(дин)">Serbian Dinar (дин)</option>
                            <option value="(₽)">Russian Ruble (₽)</option>
                            <option value="(FRw)">Rwandan Franc (FRw)</option>
                            <option value="(﷼)">Saudi Riyal (﷼)</option>
                            <option value="(SI$)">Solomon Islands Dollar (SI$)</option>
                            <option value="(₨)">Seychellois Rupee (₨)</option>
                            <option value="(SDG)">Sudanese Pound (SDG)</option>
                            <option value="(kr)">Swedish Krona (kr)</option>
                            <option value="(S$)">Singapore Dollar (S$)</option>
                            <option value="(£)">Saint Helena Pound (£)</option>
                            <option value="(Le)">Sierra Leonean Leone (Le)</option>
                            <option value="(Sh)">Somali Shilling (Sh)</option>
                            <option value="(SR$)">Surinamese Dollar (SR$)</option>
                            <option value="(SS£)">South Sudanese Pound (SS£)</option>
                            <option value="(Db)">São Tomé and Príncipe Dobra (Db)</option>
                            <option value="(£S)">Syrian Pound (£S)</option>
                            <option value="(E)">Swazi Lilangeni (E)</option>
                            <option value="(฿)">Thai Baht (฿)</option>
                            <option value="(SM)">Tajikistani Somoni (SM)</option>
                            <option value="(T)">Turkmenistani Manat (T)</option>
                            <option value="(DT)">Tunisian Dinar (DT)</option>
                            <option value="(T$)">Tongan Paʻanga (T$)</option>
                            <option value="(₺)">Turkish Lira (₺)</option>
                            <option value="(TT$)">Trinidad and Tobago Dollar (TT$)</option>
                            <option value="($)">Tuvaluan Dollar ($)</option>
                            <option value="(NT$)">New Taiwan Dollar (NT$)</option>
                            <option value="(TSh)">Tanzanian Shilling (TSh)</option>
                            <option value="(₴)">Ukrainian Hryvnia (₴)</option>
                            <option value="(USh)">Ugandan Shilling (USh)</option>
                            <option value="($)">United States Dollar ($)</option>
                            <option value="(UY$)">Uruguayan Peso (UY$)</option>
                            <option value="(лв)">Uzbekistan Som (лв)</option>
                            <option value="(Bs)">Venezuelan Bolívar (Bs)</option>
                            <option value="(₫)">Vietnamese Dong (₫)</option>
                            <option value="(VT)">Vanuatu Vatu (VT)</option>
                            <option value="(WS$)">Samoan Tala (WS$)</option>
                            <option value="(FCFA)">Central African CFA Franc (FCFA)</option>
                            <option value="(EC$)">East Caribbean Dollar (EC$)</option>
                            <option value="(CFA)">West African CFA Franc (CFA)</option>
                            <option value="(₣)">CFP Franc (₣)</option>
                            <option value="(﷼)">Yemeni Rial (﷼)</option>
                            <option value="(R)">South African Rand (R)</option>
                            <option value="(ZK)">Zambian Kwacha (ZK)</option>
                            <option value="(Z$)">Zimbabwean Dollar (Z$)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        Position:</label>
                    <div class="form-input">
                        <select class="form-control" data-jquery="select2_custom_ddl" name="position"
                            myPlaceholder="Select Position" data-ng-model="CurrCtrl.currency.position"
                            myValue="CurrCtrl.currency.position">
                            <option disabled value="">Choose Position</option>
                            <option value="Before">Before</option>
                            <option value="After">After</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Sample Value<span class="required">*</span>:</label>
                    <div class="form-input">
                        <!-- @{{ CurrCtrl.currency.currency_symbol }} -->
                        <input type="text" name="sample" data-unique="@{{CurrCtrl.uniqueRoute}}" value=""
                            data-ng-model="CurrCtrl.currency.sample" class="form-control"
                            placeholder="Enter Sample Value" />
                    </div>
                </div>
            </div>

            <div class="bottom-button text-right flexbox align-items-center">
                <input type="button" value="{{ trans('base::general.cancel') }}"
                    data-ng-click="CurrCtrl.closecurrencyEdit()" name="cancel" class="save" />
                <input type="submit" value="{{ trans('base::general.submit') }}" name="submit" class="publish-now" />
            </div>
        </form>
    </div>
</div>