        let currentPostId = null;

        window.openModal = function(id) {
            const post = postsData.find(p => p.id === id);
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
                // buat elemen video baru biar mute-nya benar-benar reset
                const newVideo = document.createElement('video');
                newVideo.id = 'modal-video';
                newVideo.src = post.file_path;
                newVideo.className = 'w-full h-full rounded-lg';
                newVideo.autoplay = true;
                newVideo.controls = true;
                newVideo.playsInline = true;
                newVideo.loop = true; // tambahkan loop
                newVideo.muted = false; // pastikan tidak mute
                newVideo.currentTime = 0;

                // ganti elemen lama dengan yang baru
                vid.parentNode.replaceChild(newVideo, vid);
                vid = newVideo;

                // mainkan video
                vid.play().catch(err => console.log('Autoplay gagal:', err));
            }

            // isi data user
            document.getElementById('modal-user-photo').src = post.user_photo;
            document.getElementById('modal-username').textContent = post.user_name;
            document.getElementById('modal-date').textContent = post.created_at;
            document.getElementById('modal-caption').textContent = post.caption || '';

            // isi likes count
            document.getElementById('modal-likes-count').textContent = post.likes_count + ' suka';

            // isi comments
            const commentsContainer = document.getElementById('modal-comments');
            commentsContainer.innerHTML = '<p class="text-gray-400 text-sm">Loading comments...</p>';

            // Fetch comments
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

            // reset form komentar
            document.getElementById('comment-input').value = '';
        }

        window.toggleLike = function(postId, button) {
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

        window.closeModal = function() {
            const modal = document.getElementById('post-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            currentPostId = null; // reset current post id

            // stop video  modal
            document.querySelectorAll('#post-modal video').forEach(v => v.pause());

            // aktifkan lagi semua video di feed dan reset ikon speaker ke mute
            document.querySelectorAll('#posts-content video').forEach(v => {
                v.muted = true; // kembali mute
                v.currentTime = 0; // reset mulai dari awal
                v.play().catch(err => console.log('Autoplay gagal setelah modal ditutup:', err));

                // // reset ikon speaker ke mute
                // const speakerBtn = v.nextElementSibling;
                // if (speakerBtn) {
                //     speakerBtn.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.617.816L4.414 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.414l3.969-3.816a1 1 0 011.616 0zM12.293 7.293a1 1 0 011.414 0L15 8.586l1.293-1.293a1 1 0 111.414 1.414L16.414 10l1.293 1.293a1 1 0 01-1.414 1.414L15 11.414l-1.293 1.293a1 1 0 01-1.414-1.414L13.586 10l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>';
                // }
            });
        }

        // Handle comment form submission
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
                            // Add new comment to the modal
                            const commentsContainer = document.getElementById('modal-comments');
                            const commentDiv = document.createElement('div');
                            commentDiv.className = 'text-sm';
                            commentDiv.innerHTML = `
                        <strong>${data.comment.user_name}</strong> ${data.comment.content}
                        <span class="text-gray-400 text-xs">${data.comment.created_at}</span>
                    `;
                            commentsContainer.appendChild(commentDiv);

                            // Clear input
                            input.value = '';

                            // Scroll to bottom
                            commentsContainer.scrollTop = commentsContainer.scrollHeight;
                        })
                        .catch(error => console.error('Error:', error));
                });
            }
        });
