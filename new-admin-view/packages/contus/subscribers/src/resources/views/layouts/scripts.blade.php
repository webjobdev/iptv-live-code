<script>
    // cancel organization
    function cancelOrgSub() {

        const cancel = window.cancelorgbtn;
        // if (confirm('Are you sure you want to cancel this process?')) {
            window.location.href = cancel;
        // }

    }

    // delete organization
    function deleteSub(organizationId) {
        const cancel = window.cancelorgbtn;
        const deleteBtn = window.deleteorgbtn;
        if (!confirm('Are you sure you want to delete this subscribers?')) {
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
</script>