var CurrencyController = ['$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, $sce, $timeout, $compile, $interval, rootScope) {
        var self = this;
        this.info = {};
        this.currency = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.closeDeviceEdit = function () {
            scope.gridSideFormClose();
        };

        this.fetchInfo = function () {
            requestFactory.get(requestFactory.getUrl('organization/payment-service/currency/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        // ===========================================*******************************************======================================
        //                                                      open page code
        // ===========================================*******************************************======================================
        this.addCurr = function () {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.currency = {};
            $("#CurrencyForm").css('display', 'block');
        }

        this.editcurrencydata = function (records) {
            $(".sidepanel").addClass("in");
            scope.errors = {};
            this.currency.id = records.id;
            this.currency.currency_code = records.currency_code;
            this.currency.currency_symbol = records.currency_symbol;
            this.currency.position = records.position;
            this.currency.sample = records.sample;
            $("#CurrencyForm").css('display', 'block');
            // $("#subscriptionTranslationForm").css('display', "none");
        }
        // ===========================================*******************************************======================================
        //                                                      save data code
        // ===========================================*******************************************======================================
        scope.togglePublishNow = function (record, id) {
            const currentUrl = window.location.href;
            const urlParams = new URLSearchParams(currentUrl.split('?')[1]);
            const queryId = urlParams.get('id');

            if (record.organization_currencies && record.organization_currencies.length > 0) {
                record.organization_currencies[0].is_active = record.organization_currencies[0].is_active == 1 ? 0 : 1;
            } else {
                record.is_active = record.is_active == 1 ? 0 : 1;
            }

            const payload = {
                organization_id: queryId,
                currency_id: record.id,
                is_active: (record.organization_currencies && record.organization_currencies.length > 0) ? record.organization_currencies[0].is_active : record.is_active
            };
            // console.log("payload", payload);

            requestFactory.post(
                requestFactory.getUrl('organization/payment-service/currency'),
                payload,
                function (response) {
                    requestFactory.getToaster();
                    requestFactory.setToaster('success', 'Publish status updated');
                    $timeout(function () {
                        location.reload();
                    }, 100);
                }
            );
        }
        // ===========================================*******************************************======================================
        //                                                      fetch data code
        // ===========================================*******************************************======================================

        scope.$on('afterGetRecords', function (e, data) {
            if (!scope.searchRecords) {
                scope.searchRecords = {};
            }
            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        });

        this.currencyMap = {
            "AED": "(د.إ)", // United Arab Emirates Dirham
            "AFN": "(؋)", // Afghan Afghani
            "ALL": "(L)", // Albanian Lek
            "AMD": "(֏)", // Armenian Dram
            "ANG": "(ƒ)", // Netherlands Antillean Guilder
            "AOA": "(Kz)", // Angolan Kwanza
            "ARS": "($)", // Argentine Peso
            "AUD": "(A$)", // Australian Dollar
            "AWG": "(ƒ)", // Aruban Florin
            "AZN": "(₼)", // Azerbaijani Manat
            "BAM": "(KM)", // Bosnia-Herzegovina Convertible Mark
            "BBD": "(Bds$)", // Barbadian Dollar
            "BDT": "(৳)", // Bangladeshi Taka
            "BGN": "(лв)", // Bulgarian Lev
            "BHD": "(.د.ب)", // Bahraini Dinar
            "BIF": "(FBu)", // Burundian Franc
            "BMD": "(BD$)", // Bermudian Dollar
            "BND": "(B$)", // Brunei Dollar
            "BOB": "(Bs.)", // Bolivian Boliviano
            "BRL": "(R$)", // Brazilian Real
            "BSD": "(B$)", // Bahamian Dollar
            "BTN": "(Nu.)", // Bhutanese Ngultrum
            "BWP": "(P)", // Botswanan Pula
            "BYN": "(Br)", // Belarusian Ruble
            "BZD": "(BZ$)", // Belize Dollar
            "CAD": "(C$)", // Canadian Dollar
            "CDF": "(FC)", // Congolese Franc
            "CHF": "(CHF)", // Swiss Franc
            "CLP": "(CLP$)", // Chilean Peso
            "CNY": "(¥)", // Chinese Yuan
            "COP": "(COL$)", // Colombian Peso
            "CRC": "(₡)", // Costa Rican Colón
            "CUP": "(₱)", // Cuban Peso
            "CVE": "(Esc)", // Cape Verdean Escudo
            "CZK": "(Kč)", // Czech Koruna
            "DJF": "(Fdj)", // Djiboutian Franc
            "DKK": "(kr)", // Danish Krone
            "DOP": "(RD$)", // Dominican Peso
            "DZD": "(دج)", // Algerian Dinar
            "EGP": "(£E)", // Egyptian Pound
            "ERN": "(Nfk)", // Eritrean Nakfa
            "ETB": "(Br)", // Ethiopian Birr
            "EUR": "(€)", // Euro
            "FJD": "(FJ$)", // Fijian Dollar
            "FKP": "(£)", // Falkland Islands Pound
            "FOK": "(kr)", // Faroese Króna
            "GBP": "(£)", // British Pound Sterling
            "GEL": "(₾)", // Georgian Lari
            "GGP": "(£)", // Guernsey Pound
            "GHS": "(₵)", // Ghanaian Cedi
            "GIP": "(£)", // Gibraltar Pound
            "GMD": "(D)", // Gambian Dalasi
            "GNF": "(FG)", // Guinean Franc
            "GTQ": "(Q)", // Guatemalan Quetzal
            "GYD": "(G$)", // Guyanaese Dollar
            "HKD": "(HK$)", // Hong Kong Dollar
            "HNL": "(L)", // Honduran Lempira
            "HRK": "(kn)", // Croatian Kuna
            "HTG": "(G)", // Haitian Gourde
            "HUF": "(Ft)", // Hungarian Forint
            "IDR": "(Rp)", // Indonesian Rupiah
            "ILS": "(₪)", // Israeli New Shekel
            "IMP": "(£)", // Isle of Man Pound
            "INR": "(₹)", // Indian Rupee
            "IQD": "(ع.د)", // Iraqi Dinar
            "IRR": "(﷼)", // Iranian Rial
            "ISK": "(kr)", // Icelandic Króna
            "JEP": "(£)", // Jersey Pound
            "JMD": "(J$)", // Jamaican Dollar
            "JOD": "(JD)", // Jordanian Dinar
            "JPY": "(¥)", // Japanese Yen
            "KES": "(KSh)", // Kenyan Shilling
            "KGS": "(с)", // Kyrgystani Som
            "KHR": "(៛)", // Cambodian Riel
            "KID": "($)", // Kiribati Dollar
            "KMF": "(CF)", // Comorian Franc
            "KRW": "(₩)", // South Korean Won
            "KWD": "(KD)", // Kuwaiti Dinar
            "KYD": "(KYD$)", // Cayman Islands Dollar
            "KZT": "(₸)", // Kazakhstani Tenge
            "LAK": "(₭)", // Laotian Kip
            "LBP": "(£)", // Lebanese Pound
            "LKR": "(Rs)", // Sri Lankan Rupee
            "LRD": "(L$)", // Liberian Dollar
            "LSL": "(L)", // Lesotho Loti
            "LYD": "(LD)", // Libyan Dinar
            "MAD": "(MAD)", // Moroccan Dirham
            "MDL": "(L)", // Moldovan Leu
            "MGA": "(Ar)", // Malagasy Ariary
            "MKD": "(ден)", // Macedonian Denar
            "MMK": "(K)", // Myanmar Kyat
            "MNT": "(₮)", // Mongolian Tugrik
            "MOP": "(MOP$)", // Macanese Pataca
            "MRU": "(UM)", // Mauritanian Ouguiya
            "MUR": "(₨)", // Mauritian Rupee
            "MVR": "(Rf)", // Maldivian Rufiyaa
            "MWK": "(MK)", // Malawian Kwacha
            "MXN": "(Mex$)", // Mexican Peso
            "MYR": "(RM)", // Malaysian Ringgit
            "MZN": "(MT)", // Mozambican Metical
            "NAD": "(N$)", // Namibian Dollar
            "NGN": "(₦)", // Nigerian Naira
            "NIO": "(C$)", // Nicaraguan Córdoba
            "NOK": "(kr)", // Norwegian Krone
            "NPR": "(Rs)", // Nepalese Rupee
            "NZD": "(NZ$)", // New Zealand Dollar
            "OMR": "(﷼)", // Omani Rial
            "PAB": "(B/.)", // Panamanian Balboa
            "PEN": "(S/.)", // Peruvian Sol
            "PGK": "(K)", // Papua New Guinean Kina
            "PHP": "(₱)", // Philippine Peso
            "PKR": "(₨)", // Pakistani Rupee
            "PLN": "(zł)", // Polish Zloty
            "PYG": "(₲)", // Paraguayan Guarani
            "QAR": "(﷼)", // Qatari Rial
            "RON": "(lei)", // Romanian Leu
            "RSD": "(дин)", // Serbian Dinar
            "RUB": "(₽)", // Russian Ruble
            "RWF": "(FRw)", // Rwandan Franc
            "SAR": "(﷼)", // Saudi Riyal
            "SBD": "(SI$)", // Solomon Islands Dollar
            "SCR": "(₨)", // Seychellois Rupee
            "SDG": "(SDG)", // Sudanese Pound
            "SEK": "(kr)", // Swedish Krona
            "SGD": "(S$)", // Singapore Dollar
            "SHP": "(£)", // Saint Helena Pound
            "SLE": "(Le)", // Sierra Leonean Leone
            "SOS": "(Sh)", // Somali Shilling
            "SRD": "(SR$)", // Surinamese Dollar
            "SSP": "(SS£)", // South Sudanese Pound
            "STN": "(Db)", // São Tomé and Príncipe Dobra
            "SYP": "(£S)", // Syrian Pound
            "SZL": "(E)", // Swazi Lilangeni
            "THB": "(฿)", // Thai Baht
            "TJS": "(SM)", // Tajikistani Somoni
            "TMT": "(T)", // Turkmenistani Manat
            "TND": "(DT)", // Tunisian Dinar
            "TOP": "(T$)", // Tongan Paʻanga
            "TRY": "(₺)", // Turkish Lira
            "TTD": "(TT$)", // Trinidad and Tobago Dollar
            "TVD": "($)", // Tuvaluan Dollar
            "TWD": "(NT$)", // New Taiwan Dollar
            "TZS": "(TSh)", // Tanzanian Shilling
            "UAH": "(₴)", // Ukrainian Hryvnia
            "UGX": "(USh)", // Ugandan Shilling
            "USD": "($)", // United States Dollar
            "UYU": "(UY$)", // Uruguayan Peso
            "UZS": "(лв)", // Uzbekistan Som
            "VES": "(Bs)", // Venezuelan Bolívar
            "VND": "(₫)", // Vietnamese Dong
            "VUV": "(VT)", // Vanuatu Vatu
            "WST": "(WS$)", // Samoan Tala
            "XAF": "(FCFA)", // Central African CFA Franc
            "XCD": "(EC$)", // East Caribbean Dollar
            "XOF": "(CFA)", // West African CFA Franc
            "XPF": "(₣)", // CFP Franc
            "YER": "(﷼)", // Yemeni Rial
            "ZAR": "(R)", // South African Rand
            "ZMW": "(ZK)", // Zambian Kwacha
            "ZWL": "(Z$)" // Zimbabwean Dollar
        };

        this.updateCardPattern = function () {
            var selectedCode = this.currency.currency_code;
            if (selectedCode && this.currencyMap[selectedCode]) {
                this.currency.currency_symbol = this.currencyMap[selectedCode];
            }
        };

    }
];


window.gridControllers = {
    CurrencyController: CurrencyController
};