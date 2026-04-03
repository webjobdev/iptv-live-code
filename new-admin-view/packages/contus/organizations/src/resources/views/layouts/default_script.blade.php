<script>
    document.addEventListener("DOMContentLoaded", function () {

        function getDefaultSetting(key) {
            let scope = angular.element(document.getElementById('menu1')).scope();
            // Read from the defaultSettings dynamically populated by the API in addorganizationdtl.js
            return scope && scope.adoCtrl && scope.adoCtrl.defaultSettings ? scope.adoCtrl.defaultSettings[key] : '';
        }

        // Max Activation Length:
        let maxActivationLengthField = document.getElementById("max_activation_length");
        let maxActivationLengthCheckbox = document.getElementById("use_system_default");
        let unlimitedCheckbox = document.getElementById("unlimited");

        function updateMaxActivationLength() {
            if (unlimitedCheckbox.checked) {
                maxActivationLengthField.value = getDefaultSetting('max_activation_length_unlimited');
                maxActivationLengthField.setAttribute("readonly", true);
                maxActivationLengthCheckbox.checked = false;
            } else if (maxActivationLengthCheckbox.checked) {
                maxActivationLengthField.value = getDefaultSetting('max_activation_length');
                maxActivationLengthField.setAttribute("readonly", true);
                unlimitedCheckbox.checked = false;
            } else {
                maxActivationLengthField.value = "";
                maxActivationLengthField.removeAttribute("readonly");
            }
        }
        maxActivationLengthCheckbox.addEventListener("change", updateMaxActivationLength);
        unlimitedCheckbox.addEventListener("change", updateMaxActivationLength);

        // Device Activation Limit:
        let deviceActivationLimitField = document.getElementById("device_activation_limit");
        let deviceactivationlimitcheckbox = document.getElementById("activation_limit_use_system_default");
        deviceactivationlimitcheckbox.addEventListener("change", function () {
            if (this.checked) {
                deviceActivationLimitField.value = getDefaultSetting('device_activation_limit');
                deviceActivationLimitField.setAttribute("readonly", true);
            } else {
                deviceActivationLimitField.value = "";
                deviceActivationLimitField.removeAttribute("readonly");
            }
        });

        // Void Payment In:
        let voidPaymentField = document.getElementById("void_payment_in");
        let voidpaymentcheckbox = document.getElementById("void_payment_use_system_default");
        let disallowcheckbox = document.getElementById("disallow_void");

        function updatevoidPayment() {
            if (disallowcheckbox.checked) {
                voidPaymentField.value = getDefaultSetting('disallow_void');
                voidPaymentField.setAttribute("readonly", true);
                voidpaymentcheckbox.checked = false;
            } else if (voidpaymentcheckbox.checked) {
                voidPaymentField.value = getDefaultSetting('void_payment_in');
                voidPaymentField.setAttribute("readonly", true);
                disallowcheckbox.checked = false;
            } else {
                voidPaymentField.value = "";
                voidPaymentField.removeAttribute("readonly");
            }
        }
        voidpaymentcheckbox.addEventListener("change", updatevoidPayment);
        disallowcheckbox.addEventListener("change", updatevoidPayment);

        // custom charges
        let customChangeField = document.getElementById("custom_charges_allow");
        let customchangecheckbox = document.getElementById("custom_change_use_system_default");
        customchangecheckbox.addEventListener("change", function () {
            if (this.checked) {
                let value = getDefaultSetting('custom_charges');
                customChangeField.checked = value === "true" || value === "1" ? true : true; // Keep old static behaviour or dynamic if preferred
            } else {
                customChangeField.checked = false;
            }
        });

        // Custom Subscription
        let customSubscriptionFiled = document.getElementById("custom_subscription_allow");
        let customSubscriptioncheckbox = document.getElementById("custom_subscription_use_system_default");
        customSubscriptioncheckbox.addEventListener("change", function () {
            if (this.checked) {
                customSubscriptionFiled.checked = true;
            } else {
                customSubscriptionFiled.checked = false;
            }
        });

        // Device Slots
        let DeviceSloteFiled = document.getElementById("device_slots_allow");
        let DeviceSlotecheckbox = document.getElementById("device_slot_use_system_default");
        DeviceSlotecheckbox.addEventListener("change", function () {
            if (this.checked) {
                DeviceSloteFiled.checked = true;
            } else {
                DeviceSloteFiled.checked = false;
            }
        });

        // Device Linking
        let DeviceLinkingFiled = document.getElementById("device_linking_allow");
        let DeviceLinkingcheckbox = document.getElementById("device_linking_use_system_default");
        DeviceLinkingcheckbox.addEventListener("change", function () {
            if (this.checked) {
                DeviceLinkingFiled.checked = true;
            } else {
                DeviceLinkingFiled.checked = false;
            }
        });

        // Link Code Expiration:
        let LinkCodeExpirationField = document.getElementById("link_code_expiration");
        let LinkCodeExpirationcheckbox = document.getElementById("link_code_use_system_default");
        LinkCodeExpirationcheckbox.addEventListener("change", function () {
            if (this.checked) {
                LinkCodeExpirationField.value = getDefaultSetting('link_code_expiration');
                LinkCodeExpirationField.setAttribute("readonly", true);
            } else {
                LinkCodeExpirationField.value = "";
                LinkCodeExpirationField.removeAttribute("readonly");
            }
        });

        // Active TOA:
        let AcceptanceToaFiled = document.getElementById("acceptance_toa");
        let AcceptanceToacheckbox = document.getElementById("active_toa_use_system_default");
        AcceptanceToacheckbox.addEventListener("change", function () {
            if (this.checked) {
                AcceptanceToaFiled.checked = true;
            } else {
                AcceptanceToaFiled.checked = false;
            }
        });

        // Subscription Activation:
        let SubscriptionActicationFiled = document.getElementById("subscription_activation_allow");
        let SubscriptionActicationcheckbox = document.getElementById("subscription_activation_use_system_default");
        SubscriptionActicationcheckbox.addEventListener("change", function () {
            if (this.checked) {
                SubscriptionActicationFiled.checked = true;
            } else {
                SubscriptionActicationFiled.checked = false;
            }
        });

        // Subscription Prorating:
        let SubscriptionProratingFiled = document.getElementById("subscription_prorating_allow");
        let SubscriptionProratingcheckbox = document.getElementById("prorating_use_system_default");
        SubscriptionProratingcheckbox.addEventListener("change", function () {
            if (this.checked) {
                SubscriptionProratingFiled.checked = true;
            } else {
                SubscriptionProratingFiled.checked = false;
            }
        });

        // Content Add_on Prorating:
        let ContentAddOnProratingFiled = document.getElementById("content_add_on_prorating_allow");
        let ContentAddOnProratingcheckbox = document.getElementById("content_add_use_system_default");
        ContentAddOnProratingcheckbox.addEventListener("change", function () {
            if (this.checked) {
                ContentAddOnProratingFiled.checked = true;
            } else {
                ContentAddOnProratingFiled.checked = false;
            }
        });

        // Voucher Subscribers:
        let VoucherSubscribersFiled = document.getElementById("voucher_subscribers_allow");
        let VoucherSubscriberscheckbox = document.getElementById("voucher_subscribers_use_system_default");
        if (VoucherSubscriberscheckbox) {
            VoucherSubscriberscheckbox.addEventListener("change", function () {
                if (this.checked) {
                    VoucherSubscribersFiled.checked = true;
                } else {
                    VoucherSubscribersFiled.checked = false;
                }
            });
        }

        // Expired Voucher Removel:
        let ExpiredVoucherField = document.getElementById("expired_voucher_removal");
        let ExpiredVouchercheckbox = document.getElementById("expired_voucher_use_system_default");
        ExpiredVouchercheckbox.addEventListener("change", function () {
            if (this.checked) {
                ExpiredVoucherField.value = getDefaultSetting('expired_voucher_removal');
                ExpiredVoucherField.setAttribute("readonly", true);
            } else {
                ExpiredVoucherField.value = "";
                ExpiredVoucherField.removeAttribute("readonly");
            }
        });

        // Voucher Slots:
        let VoucherSlotsField = document.getElementById("voucher_slots");
        let VoucherSlotscheckbox = document.getElementById("voucher_slot_use_system_default");
        VoucherSlotscheckbox.addEventListener("change", function () {
            if (this.checked) {
                VoucherSlotsField.value = getDefaultSetting('voucher_slots');
                VoucherSlotsField.setAttribute("readonly", true);
            } else {
                VoucherSlotsField.value = "";
                VoucherSlotsField.removeAttribute("readonly");
            }
        });
    });
</script>