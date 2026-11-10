
function followUser(userId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'follow_user.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert('Followed successfully');
                // Optionally, update the UI to reflect the follow status
                document.querySelector(`button[data-user-id="${userId}"]`).textContent = 'Following';
                document.querySelector(`button[data-user-id="${userId}"]`).disabled = true;
            } else {
                alert('Error: ' + response.message);
            }
        } else {
            alert('An error occurred while following the user.');
        }
    };
    xhr.send('user_id=' + userId);
}
