document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.create-group form');

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(form);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'create_groups.php', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert('Group created successfully');
                    window.location.href = 'groups.php';
                } else {
                    alert('Error: ' + response.message);
                }
            } else {
                alert('An error occurred while creating the group.');
            }
        };

        xhr.send(formData);
    });
});