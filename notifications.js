document.addEventListener('DOMContentLoaded', function () {
    function fetchNotifications() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_notifications.php', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    const notificationsContainer = document.getElementById('notifications');
                    notificationsContainer.innerHTML = '';
                    response.notifications.forEach(notification => {
                        const notificationElement = document.createElement('div');
                        notificationElement.classList.add('notification');
                        notificationElement.innerHTML = `
                            <p><strong>${notification.firstname} ${notification.lastname}:</strong> ${notification.content}</p>
                            <small>${notification.created_at}</small>
                        `;
                        notificationsContainer.appendChild(notificationElement);
                    });
                } else {
                    alert('Error: ' + response.message);
                }
            } else {
                alert('An error occurred while fetching notifications.');
            }
        };

        xhr.send();
    }

    fetchNotifications();
});