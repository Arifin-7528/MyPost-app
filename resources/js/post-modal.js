let currentPostId = null;

function openModal(id) {
    if (typeof postsData === 'undefined') {
        console.error('postsData is not defined');
        return;
    }

    const post = postsData.find(p => p.id === id);
    if (!post) {
        console.error('Post not found:', id);
        return;
    }

    const modal = document.getElementById('post-modal');
    const img = document.getElementById('modal-image');
    let vid = document.getElementById('modal-video');

    currentPostId = id; // set current post id

    // reset
    img.classList.add('hidden');
    vid.classList.add('hidden');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // stop semua video di background
    document.querySelectorAll('video').forEach(v => {
        v.pause();
        v.currentTime = 0;
    });

    // tampilkan media
    if (post.type === 'image') {
        img.src = post.file_path;
        img.classList.remove('hidden');
    } else {
        // buat elemen video baru untuk menghindari masalah autoplay
        const newVideo = document.createElement('video');
        newVideo.id = 'modal-video';
        newVideo.src = post.file_path;
        newVideo.className = 'w-full h-full rounded-lg';
        newVideo.autoplay = true;
        newVideo.controls = true;
        newVideo.playsInline = true;
        newVideo.loop = true;
        newVideo.muted = false;
        newVideo.currentTime = 0;

        // ganti elemen lama dengan yang baru
        vid.parentNode.replaceChild(newVideo, vid);
        vid = newVideo;

        // play video
        vid.play().catch(err => console.log('Autoplay gagal:', err));
    }

    // isi data user
    document.getElementById('modal-user-photo').src = post.user_photo;
    document.getElementById('modal-username').textContent = post.user_name;
    document.getElementById('modal-date').textContent = post.created_at;
    document.getElementById('modal-caption').textContent = post.caption || '';

    // likes
    document.getElementById('modal-likes-count').textContent = post.likes_count + ' suka';

    // comments
    const commentsContainer = document.getElementById('modal-comments');
    commentsContainer.innerHTML = '<p class="text-gray-400 text-sm">Loading comments...</p>';

    // fetch comments
    fetch(`/posts/${id}/comments`)
        .then(response => response.json())
        .then(data => {
            commentsContainer.innerHTML = '';
            if (data.comments && data.comments.length > 0) {
                data.comments.forEach(comment => {
                    const commentDiv = document.createElement('div');
                    commentDiv.className = 'text-sm';
                    commentDiv.innerHTML = `
                <strong>${comment.user_name}</strong> ${comment.content}
                <span class="text-gray-400 text-xs">${comment.created_at}</span>
            `;
                    commentsContainer.appendChild(commentDiv);
                });
            } else {
                commentsContainer.innerHTML = '<p class="text-gray-400 text-sm">Belum ada komentar.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching comments:', error);
            commentsContainer.innerHTML = '<p class="text-gray-400 text-sm">Error loading comments.</p>';
        });

    // reset form coments
    document.getElementById('comment-input').value = '';
}

function toggleLike(postId, button) {
    fetch(`/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const countSpan = button.querySelector('span');
            const icon = button.querySelector('svg');
            countSpan.textContent = data.likes_count;
            if (data.liked) {
                icon.classList.add('fill-current');
                icon.setAttribute('fill', 'currentColor');
            } else {
                icon.classList.remove('fill-current');
                icon.setAttribute('fill', 'none');
            }
        })
        .catch(error => console.error('Error:', error));
}

function closeModal() {
    const modal = document.getElementById('post-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');

    currentPostId = null; // reset current post id

    // stop video  modal
    document.querySelectorAll('#post-modal video').forEach(v => v.pause());

    // mengaktifkan semua video di feed
    document.querySelectorAll('#posts-content video').forEach(v => {
        v.muted = true; // kembali mute
        v.currentTime = 0; // reset mulai dari awal
        v.play().catch(err => console.log('Autoplay gagal setelah modal ditutup:', err));

    });
}

// handle comment form submission
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('comment-input');
            const content = input.value.trim();

            if (!content || !currentPostId) return;

            fetch(`/posts/${currentPostId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        content: content
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // menambahkan komentar baru ke dalam modal
                    const commentsContainer = document.getElementById('modal-comments');
                    const commentDiv = document.createElement('div');
                    commentDiv.className = 'text-sm';
                    commentDiv.innerHTML = `
                <strong>${data.comment.user_name}</strong> ${data.comment.content}
                <span class="text-gray-400 text-xs">${data.comment.created_at}</span>
            `;
                    commentsContainer.appendChild(commentDiv);

                    // clear input
                    input.value = '';

                    // scroll to bottom
                    commentsContainer.scrollTop = commentsContainer.scrollHeight;
                })
                .catch(error => console.error('Error:', error));
        });
    }
});

// Export functions to window
window.openModal = openModal;
window.toggleLike = toggleLike;
window.closeModal = closeModal;
