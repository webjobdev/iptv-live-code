<script>
    // cancel drm
    function cancelDrm() {

        const cancel = window.cancelbtn;
        if (confirm('Are you sure you want to cancel this process?')) {
            window.location.href = cancel;
        }

    }

    // delete drm
    function deleteDrm(drmId) {
        const cancel = window.cancelbtn;
        const deleteBtn = window.deletebtn;
        if (!confirm('Are you sure you want to delete this drm?')) {
            return;
        }

        fetch(deleteBtn, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: drmId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = cancel;
                    // window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Something went wrong.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
            });
    }

    // ================================================************************************=================================================

    document.addEventListener("DOMContentLoaded", function() {
        const drmProviderSelect = document.getElementById("drm_provider");

        drmProviderSelect?.addEventListener("change", function() {
            const selectedProvider = this.value;
            if (selectedProvider === "Pallycon") {
                console.log("Generating values for Pallycon...");
                window.generateAccesskey();
                window.generateSitekey();
                window.generateaccount_id();
            } else if (selectedProvider === "EZDRM") {
                window.generatepx_value();
            } else {
                console.log("No special generation required for selected provider.");
            }
        });
    });

    // Generate random Access Key 
    window.generateAccesskey = function() {
        const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        const tokenLength = 25;
        const token = Array.from({
                length: tokenLength
            }, () =>
            characters.charAt(Math.floor(Math.random() * characters.length))
        ).join('');

        const input = document.getElementById('accessKeyInput');
        if (input) {
            input.value = token;
        } else {
            console.warn("Access key input not found in DOM.");
        }
    };

    // Generate random Site Key 
    window.generateSitekey = function() {
        const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        const tokenLength = 20;
        const token = Array.from({
                length: tokenLength
            }, () =>
            characters.charAt(Math.floor(Math.random() * characters.length))
        ).join('');

        const input = document.getElementById('siteKeyInput');
        if (input) {
            input.value = token;
        } else {
            console.warn("Site key input not found in DOM.");
        }
    };

    // Generate account ID based on drm_provider name + drm-id
    window.generateaccount_id = function() {
        const drmName = document.getElementById("drm_name")?.value;
        const drmId = document.getElementById("drm-id")?.value;
        const drmaccId = (drmName + drmId).toLowerCase();

        setTimeout(() => {
            const input = document.getElementById("account_id");
            if (input) {
                input.value = drmaccId;
            } else {
                console.warn("Account ID input not found in DOM.");
            }
        }, 900);
    };

    // Generate random PX Value 
    window.generatepx_value = function() {
        const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        const tokenLength = 15;
        const token = Array.from({
                length: tokenLength
            }, () =>
            characters.charAt(Math.floor(Math.random() * characters.length))
        ).join('');

        const input = document.getElementById('px_value');
        if (input) {
            input.value = token;
            console.log("Generated PX Value:", token);
        } else {
            console.warn("PX Value input not found in DOM.");
        }
    };


    // access key code
    document.addEventListener("DOMContentLoaded", function() {
        // console.log("DOMContentLoaded fired.....");

        const targetDrmId = document.getElementById("drm-id")?.value;

        if (targetDrmId) {
            // console.log("Drm id found:", targetDrmId);
            window.fetchaccessKey(targetDrmId);
        } else {
            console.warn("Drm id not found");
        }
    })

    window.fetchaccessKey = function(drmID) {
        if (!drmID) {
            console.error("Drm id missing");
            return;
        }

        // console.log("Fetching access token for drm id:", drmID);

        const url = `${apiUrl}/drm/records`;
        const payload = {
            drm_id: drmID
        };

        // console.log("Sending request to:", url);
        // console.log("Payload:", payload);

        fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + localStorage.getItem("access_token")
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(response => {
                // console.log("Response received:", response);

                if (response && response.data && Array.isArray(response.data.data)) {
                    const drmItem = response.data.data.find(item => item.drm_id == drmID);
                    if (drmItem && drmItem.access_key) {
                        // console.log("Access key found:", drmItem.access_key);
                        document.getElementById('accessKeyInput').value = drmItem.access_key;
                    } else {
                        // console.log("Access key not found. Generating a new key.");
                        window.generateAccesskey();
                    }
                } else {
                    console.error("Unexpected response format.");
                }
            })
            .catch(err => {
                console.error("Fetch error:", err);
            });
    };

    window.generateAccesskey = function() {
        // console.log("Generating a new Access key...");

        const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        const tokenLength = 25;

        const token = Array.from({
                length: tokenLength
            }, () =>
            characters.charAt(Math.floor(Math.random() * characters.length))
        ).join('');

        // console.log("Generated key:", token);

        setTimeout(() => {
            const input = document.getElementById('accessKeyInput');
            if (input) {
                input.value = token;
            } else {
                console.warn("Access key input not found in DOM.");
            }
        }, 900);
    };

    window.toggleAccessKey = function() {
        const accessinput = document.getElementById("accessKeyInput");
        const icon = document.getElementById("toggleaccessIcon");

        if (accessinput.type === "password") {
            accessinput.type = "text";
            icon.classList.remove("glyphicon-eye-open");
            icon.classList.add("glyphicon-eye-close");
        } else {
            accessinput.type = "password";
            icon.classList.remove("glyphicon-eye-close");
            icon.classList.add("glyphicon-eye-open");
        }
    }


    // site key code
    document.addEventListener("DOMContentLoaded", function() {
        // console.log("DOMContentLoaded fired.......");

        const targetdrmId = document.getElementById("drm-id")?.value;

        if (targetdrmId) {
            // console.log("Drm id load:", targetdrmId);
            window.fetchsiteKey(targetdrmId);
        } else {
            console.log("Drm id not found");
        }
    });

    window.fetchsiteKey = function(drmID) {
        if (!drmID) {
            console.error("Drm id missing");
            return;
        }

        // console.log("Fetching access token for drm id:", drmID);

        const url = `${apiUrl}/drm/records`;
        const payload = {
            drm_id: drmID
        };

        // console.log("Sending request to:", url);
        // console.log("Payload:", payload);

        fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + localStorage.getItem("access_token")
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(response => {
                // console.log("Response received:", response);

                if (response && response.data && Array.isArray(response.data.data)) {
                    const drmItem = response.data.data.find(item => item.drm_id == drmID);
                    if (drmItem && drmItem.site_key) {
                        // console.log("Access key found:", drmItem.site_key);
                        document.getElementById('siteKeyInput').value = drmItem.site_key;
                    } else {
                        // console.log("Access key not found. Generating a new key.");
                        window.generateSitekey();
                    }
                } else {
                    console.error("Unexpected response format.");
                }
            })
            .catch(err => {
                console.error("Fetch error:", err);
            });
    };

    window.generateSitekey = function() {
        // console.log("Generating a new Site key...");

        const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        const tokenLength = 20;

        const token = Array.from({
                length: tokenLength
            }, () =>
            characters.charAt(Math.floor(Math.random() * characters.length))
        ).join('');

        // console.log("Generated key:", token);

        setTimeout(() => {
            const input = document.getElementById('siteKeyInput');
            if (input) {
                input.value = token;
            } else {
                console.warn("Access key input not found in DOM.");
            }
        }, 900);
    }

    window.toggleSiteKey = function() {
        const siteinput = document.getElementById("siteKeyInput");
        const icon = document.getElementById("togglesiteIcon");

        if (siteinput.type === "password") {
            siteinput.type = "text";
            icon.classList.remove("glyphicon-eye-open");
            icon.classList.add("glyphicon-eye-close");
        } else {
            siteinput.type = "password";
            icon.classList.remove("glyphicon-eye-close");
            icon.classList.add("glyphicon-eye-open");
        }
    }


    // account_id code
    document.addEventListener("DOMContentLoaded", function() {
        const targetdrmId = document.getElementById("drm-id")?.value;

        if (targetdrmId) {
            window.fetchaccount_id(targetdrmId);
        } else {
            console.log("Drm id not found");
        }
    });

    window.fetchaccount_id = function(drmID) {
        if (!drmID) {
            console.error("Drm id missing");
            return;
        }
        setTimeout(function() {
            const url = `${apiUrl}/drm/records`;
            const payload = {
                drm_id: drmID
            };

            fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": "Bearer " + localStorage.getItem("access_token")
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(response => {
                    if (response && response.data && Array.isArray(response.data.data)) {
                        const drmItem = response.data.data.find(item => item.drm_id == drmID);

                        if (drmItem) {
                            if (drmItem.account_id) {
                                document.getElementById('account_id').value = drmItem.account_id;
                            } else {
                                const generatedAccountID = `${drmItem.drm_name}${drmItem.drm_id}`.toLowerCase();
                                // console.log("Generated account ID:", generatedAccountID);
                                document.getElementById('account_id').value = generatedAccountID;
                            }
                        } else {
                            console.error("DRM item not found for drm_id:", drmID);
                        }
                    } else {
                        console.error("Unexpected response format.");
                    }
                })
                .catch(err => {
                    console.error("Fetch error:", err);
                });
        }, 900);
    };

    // px value
    document.addEventListener("DOMContentLoaded", function() {
        // console.log("DOMContentLoaded fired.......");

        const targetdrmId = document.getElementById("drm-id")?.value;

        if (targetdrmId) {
            // console.log("Drm id load:", targetdrmId);
            window.fetchpxvalue(targetdrmId);
        } else {
            console.log("Drm id not found");
        }
    });

    window.fetchpxvalue = function(drmID) {
        if (!drmID) {
            console.error("Drm id missing");
            return;
        }

        // console.log("Fetching access token for drm id:", drmID);

        const url = `${apiUrl}/drm/records`;
        const payload = {
            drm_id: drmID
        };

        // console.log("Sending request to:", url);
        // console.log("Payload:", payload);

        fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + localStorage.getItem("access_token")
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(response => {
                // console.log("Response received:", response);

                if (response && response.data && Array.isArray(response.data.data)) {
                    const drmItem = response.data.data.find(item => item.drm_id == drmID);
                    if (drmItem && drmItem.px_value) {
                        // console.log("px_value found:", drmItem.px_value);
                        document.getElementById('px_value').value = drmItem.px_value;
                    } else {
                        // console.log("px_value not found. Generating a new key.");
                        window.generatepx_value();
                    }
                } else {
                    console.error("Unexpected response format.");
                }
            })
        // .catch(err => {
        // console.error("Fetch error:", err);
        // });
    };

    window.generatepx_value = function() {
        // console.log("Generating a new px_value...");

        const characters = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        const tokenLength = 6;

        const token = Array.from({
                length: tokenLength
            }, () =>
            characters.charAt(Math.floor(Math.random() * characters.length))
        ).join('');

        // console.log("Generated key:", token);

        setTimeout(() => {
            const input = document.getElementById('px_value');
            if (input) {
                input.value = token;
            } else {
                console.warn("px_value input not found in DOM.");
            }
        }, 900);
    };
</script>