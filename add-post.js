document.addEventListener('DOMContentLoaded', function () {
    // Ensure both sections are hidden by default
    document.getElementById('main-text-box').classList.add('hidden');
    document.getElementById('media-upload').classList.add('hidden');
    document.getElementById('description-field').classList.add('hidden');

    // Handle "Text" icon click
    document.getElementById('text-icon').addEventListener('click', function () {
        document.getElementById('main-text-box').classList.remove('hidden');
        document.getElementById('media-upload').classList.add('hidden');
        document.getElementById('description-field').classList.add('hidden');
    });

    // Handle "Media" icon click
    document.getElementById('media-icon').addEventListener('click', function () {
        document.getElementById('main-text-box').classList.add('hidden');
        document.getElementById('media-upload').classList.remove('hidden');
        document.getElementById('description-field').classList.remove('hidden');
    });

    // Handle form submission
    document.querySelector('form').addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(this);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'save_post.php', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Add the new post to the profile and homepage
                    addPostToProfile(response.post);
                    addPostToHomepage(response.post);
                    // Reset the form
                    document.querySelector('form').reset();
                    document.getElementById('main-text-box').classList.add('hidden');
                    document.getElementById('media-upload').classList.add('hidden');
                    document.getElementById('description-field').classList.add('hidden');
                } else {
                    alert('Error: ' + response.message);
                }
            } else {
                alert('An error occurred while submitting the form.');
            }
        };

        xhr.send(formData);
    });

    function addPostToProfile(post) {
        const postsContainer = document.querySelector('.posts-container .posts-column:first-child');
        const postElement = document.createElement('div');
        postElement.classList.add('post');
        if (post.image) {
            postElement.innerHTML = `
                <img src="${post.image}" alt="Post Image">
                <small>Posted on: ${post.created_at}</small>
            `;
        } else if (post.content) {
            postElement.innerHTML = `
                <p>${post.content}</p>
                <small>Posted on: ${post.created_at}</small>
            `;
        }
        postsContainer.prepend(postElement);
    }

    function addPostToHomepage(post) {
        const homepagePostsContainer = document.querySelector('.homepage-posts');
        const postElement = document.createElement('div');
        postElement.classList.add('post');
        if (post.image) {
            postElement.innerHTML = `
                <img src="${post.image}" alt="Post Image">
                <small>Posted on: ${post.created_at}</small>
            `;
        } else if (post.content) {
            postElement.innerHTML = `
                <p>${post.content}</p>
                <small>Posted on: ${post.created_at}</small>
            `;
        }
        homepagePostsContainer.prepend(postElement);
    }
});