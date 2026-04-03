<script>
    // cancel organization
    function cancelOrganization() {

        const cancel = window.cancelbtn;
        if (confirm('Are you sure you want to cancel this process?')) {
            window.location.href = cancel;
        }

    }

    // delete organization
    function deleteOrganization(organizationId) {
        const cancel = window.cancelbtn;
        const deleteBtn = window.deletebtn;
        if (!confirm('Are you sure you want to delete this organization?')) {
            return;
        }

        fetch(deleteBtn, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: organizationId
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

    // clone organization
    function cloneOrganization(orgId) {

        const cancel = window.cancelbtn;

        const cloneBtn = window.clonebtn.replace('__ID__', orgId);

        if (!confirm("Are you sure you want to clone this organization?")) return;

        fetch(cloneBtn, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect or notify
                    window.location.href = cancel;
                } else {
                    alert('Failed to clone organization.');
                }
            })
            .catch(error => {
                console.error('Clone error:', error);
                alert('An error occurred.');
            });
    }

    // image code
    function displaySelectedImage(event, elementId) {
        const selectedImage = document.getElementById(elementId);
        const fileInput = event.target;

        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                selectedImage.src = e.target.result;
            };

            reader.readAsDataURL(fileInput.files[0]);
        }
    }

    // prifix name
    document.addEventListener("DOMContentLoaded", function() {
        // console.log("DOM fully loaded.");

        const autoCheckbox = document.getElementById("prefix-auto");
        const prefixInput = document.getElementById("prefix");
        const orgIdInput = document.getElementById("org-id");

        if (!autoCheckbox || !prefixInput || !orgIdInput) {
            // console.error("One or more required elements not found.");
            return;
        }

        // console.log("All required input elements found.");

        autoCheckbox.addEventListener("change", function() {
            const orgId = orgIdInput.value;
            // console.log("Auto checkbox changed:", this.checked);
            // console.log("Organization ID:", orgId);

            if (this.checked) {
                fetchOrganizationName(orgId);
            } else {
                prefixInput.value = "";
            }
        });
    });

    function generateShortName(name) {
        // console.log("Generating short name from:", name);
        const short = name
            .split(" ")
            .map(word => word.substring(0, 2))
            .join("")
            .toLowerCase();
        // console.log("Generated short name:", short);
        return short;
    }

    function fetchOrganizationName(orgId) {
        if (!orgId) {
            // console.error("Organization ID is missing.");
            return;
        }

        const url = `${apiUrl}/organizations/general/settingrecords/records`;
        const payload = {
            organization_id: orgId
        };

        // console.log("Sending request to:", url);
        // console.log("Payload:", payload);

        fetch(url, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    "Authorization": "Bearer " + localStorage.getItem("access_token")
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                // console.log("Response received:", data);

                const orgList = data?.data?.data || [];

                const matchedOrg = orgList.find(org => String(org.organization_id) === String(orgId));

                if (matchedOrg && matchedOrg.organization_name) {
                    // console.log("Matched Organization:", matchedOrg.organization_name);
                    const shortName = generateShortName(matchedOrg.organization_name);
                    document.getElementById('prefix').value = shortName;
                } else {
                    console.warn("No matching organization or name not found.");
                }
            })

            .catch(error => {
                console.error("Error during fetch:", error);
            });
    }

    // cancel cancelOrgSub
    function cancelOrgSub() {
        const cancel = window.cancelorgbtn;
        // if (confirm('Are you sure you want to cancel this process?')) {
        window.location.href = cancel;
        // }
    }

    // delete deleteOrgSub
    function deleteOrgSub() {
        const cancel = window.deleteorhbtn;
        // if (confirm('Are you sure you want to cancel this process?')) {
        window.location.href = cancel;
        // }
    }
</script>