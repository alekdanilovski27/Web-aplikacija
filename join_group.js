document.addEventListener('DOMContentLoaded', function () {
    const joinForms = document.querySelectorAll('.join-group-form');

    function handleFormSubmit(form, url, successMessage, errorMessage) {
        const formData = new FormData(form);
        const messageDiv = form.querySelector('.message');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);

                if (response.success) {
                    messageDiv.textContent = successMessage;
                    messageDiv.style.color = 'green';
                } else {
                    messageDiv.textContent = 'Error: ' + response.message;
                    messageDiv.style.color = 'red';
                }
            } else {
                messageDiv.textContent = errorMessage;
                messageDiv.style.color = 'red';
            }
        };

        xhr.onerror = function () {
            messageDiv.textContent = 'A network error occurred.';
            messageDiv.style.color = 'red';
        };

        xhr.send(formData);
    }

    joinForms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            handleFormSubmit(form, 'join_group.php', 'Successfully joined the group!', 'An error occurred while joining the group.');
        });
    });
});