document.addEventListener('DOMContentLoaded', function () {
    const groupId = new URLSearchParams(window.location.search).get('group_id');
    const postsContainer = document.getElementById('posts-container');

    function fetchPosts() {
        fetch(`fetch_group_posts.php?group_id=${groupId}`)
            .then(response => response.json())
            .then(posts => {
                postsContainer.innerHTML = '';
                posts.forEach(post => {
                    const postElement = document.createElement('div');
                    postElement.classList.add('post');
                    postElement.innerHTML = `
                        <div class="post-header">
                            <img src="${post.pp}" alt="Profile Picture" class="post-profile-img">
                            <div class="post-info">
                                <span class="username">${post.firstname} ${post.lastname}</span>
                                <span class="post-date">${post.created_at}</span>
                            </div>
                        </div>
                        <div class="post-content">
                            <p>${post.content}</p>
                            ${post.image ? `<img src="${post.image}" alt="Post Image" class="post-image">` : ''}
                        </div>
                    `;
                    postsContainer.appendChild(postElement);
                });
            });
    }

    fetchPosts();

    const createPostForm = document.getElementById('create-post-form');
    createPostForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(createPostForm);

        fetch('create_post.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                fetchPosts();
                createPostForm.reset();
            } else {
                alert('Error: ' + result.message);
            }
        });
    });
});