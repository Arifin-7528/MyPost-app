<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="loading-skeleton" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @for($i = 0; $i < 6; $i++)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow animate-pulse">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full mr-3"></div>
                                <div>
                                    <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-20 mb-2"></div>
                                    <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-16"></div>
                                </div>
                            </div>
                            <div class="w-full h-64 bg-gray-300 dark:bg-gray-600 rounded-lg mb-4"></div>
                            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4 mb-4"></div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-16 h-4 bg-gray-300 dark:bg-gray-600 rounded mr-4"></div>
                                    <div class="w-20 h-4 bg-gray-300 dark:bg-gray-600 rounded"></div>
                                </div>
                            </div>
                    </div>
                    @endfor
                </div>

                <div id="posts-content">
                    @if($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($posts as $post)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow">
                            <div class="flex items-center mb-4">
                                <img src="{{ $post->user->profile_photo_url }}" alt="User Avatar" class="w-10 h-10 rounded-full mr-3">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $post->user->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            @if($post->isImage())
                            <img src="{{ asset($post->file_path) }}" alt="Post Image" class="w-full h-64 object-cover rounded-lg mb-4 cursor-pointer" onclick="openModal({{ $post->id }})">
                            @elseif($post->isVideo())
                            <div class="relative">
                                <video autoplay muted loop playsinline class="w-full h-64 object-cover rounded-lg mb-4 cursor-pointer" onclick="this.muted = false; openModal({{ $post->id }})">
                                    <source src="{{ asset($post->file_path) }}" type="video/mp4">
                                    Browser Anda tidak mendukung tag video.
                                </video>
                                <button class="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white p-2 rounded-full" onclick="event.stopPropagation(); toggleMute(this.previousElementSibling)">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.617.816L4.414 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.414l3.969-3.816a1 1 0 011.616 0zM12.293 7.293a1 1 0 011.414 0L15 8.586l1.293-1.293a1 1 0 111.414 1.414L16.414 10l1.293 1.293a1 1 0 01-1.414 1.414L15 11.414l-1.293 1.293a1 1 0 01-1.414-1.414L13.586 10l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            @endif

                            @if($post->caption)
                            <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $post->caption }}</p>
                            @endif

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <button class="flex items-center text-red-500 hover:text-red-700 mr-4">
                                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $post->likes_count }}
                                    </button>
                                    <button class="flex items-center text-gray-500 hover:text-gray-700">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-center text-gray-500 dark:text-gray-400">Belum ada postingan. Jadilah yang pertama memposting!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const skeleton = document.getElementById('loading-skeleton');
            const content = document.getElementById('posts-content');

            // Show skeleton initially
            skeleton.style.display = 'grid';
            content.style.display = 'none';

            // Set a maximum loading time (e.g., 5 seconds) to prevent infinite loading
            const maxLoadingTime = 5000; // 5 seconds
            let loadingTimeout = setTimeout(function() {
                skeleton.style.display = 'none';
                content.style.display = 'block';
            }, maxLoadingTime);

            // Function to check if images and videos are loaded
            function checkMediaLoaded() {
                const mediaElements = content.querySelectorAll('img, video');
                let loadedCount = 0;
                const totalMedia = mediaElements.length;

                if (totalMedia === 0) {
                    // No media, show content immediately
                    clearTimeout(loadingTimeout);
                    skeleton.style.display = 'none';
                    content.style.display = 'block';
                    return;
                }

                mediaElements.forEach(function(media) {
                    if (media.complete || media.readyState >= 3) {
                        loadedCount++;
                    } else {
                        media.addEventListener('load', function() {
                            loadedCount++;
                            if (loadedCount === totalMedia) {
                                clearTimeout(loadingTimeout);
                                skeleton.style.display = 'none';
                                content.style.display = 'block';
                            }
                        });
                        media.addEventListener('error', function() {
                            loadedCount++;
                            if (loadedCount === totalMedia) {
                                clearTimeout(loadingTimeout);
                                skeleton.style.display = 'none';
                                content.style.display = 'block';
                            }
                        });
                    }
                });

                // If all media are already loaded
                if (loadedCount === totalMedia) {
                    clearTimeout(loadingTimeout);
                    skeleton.style.display = 'none';
                    content.style.display = 'block';
                }
            }

            // Check media loading
            checkMediaLoaded();

            // Auto-play videos when content is loaded
            setTimeout(function() {
                document.querySelectorAll('video').forEach(v => {
                    v.play().catch(err => console.log('Autoplay gagal:', err));
                });
            }, 100);
        });
    </script>

    <script>
    document.querySelectorAll('video').forEach(v => {
        v.addEventListener('play', function() {
            document.querySelectorAll('video').forEach(other => {
                if (other !== v) {
                    other.pause();
                }
            });
        });
    });

    function toggleMute(video) {
        if (video.muted) {
            video.muted = false;
            video.nextElementSibling.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.617.816L4.414 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.414l3.969-3.816a1 1 0 011.616 0z" clip-rule="evenodd"></path></svg>';
        } else {
            video.muted = true;
            video.nextElementSibling.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.617.816L4.414 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.414l3.969-3.816a1 1 0 011.616 0zM12.293 7.293a1 1 0 011.414 0L15 8.586l1.293-1.293a1 1 0 111.414 1.414L16.414 10l1.293 1.293a1 1 0 01-1.414 1.414L15 11.414l-1.293 1.293a1 1 0 01-1.414-1.414L13.586 10l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>';
        }
    }
    </script>

    <!-- Modal untuk Gambar/Video -->
    <div id="post-modal" class="fixed inset-0 bg-black bg-opacity-70 hidden justify-center items-center z-50">
        <div class="flex flex-col lg:flex-row bg-gray-900 rounded-2xl w-full max-w-5xl overflow-hidden shadow-2xl">
            <!-- Bagian Media -->
            <div class="w-full lg:w-3/5 bg-black flex justify-center items-center">
                <img id="modal-image" class="w-full h-full object-contain hidden">
                <video id="modal-video" class="w-full h-full hidden" autoplay muted loop playsinline></video>
            </div>

            <!-- Bagian Detail -->
            <div class="w-full lg:w-2/5 bg-gray-800 text-white flex flex-col relative">
                <button class="absolute top-2 right-2 text-gray-400 hover:text-red-500" onclick="closeModal()">✖</button>

                <div id="modal-header" class="flex items-center gap-3 border-b border-gray-700 p-4">
                    <img id="modal-user-photo" src="" class="w-10 h-10 rounded-full border border-gray-700">
                    <div>
                        <p id="modal-username" class="font-semibold text-sm"></p>
                        <p id="modal-date" class="text-xs text-gray-400"></p>
                    </div>
                </div>

                <div id="modal-caption" class="p-4 text-sm text-gray-200 border-b border-gray-700"></div>

                <div id="modal-comments" class="flex-1 overflow-y-auto p-4 space-y-2 text-sm"></div>
            </div>
        </div>
    </div>

    @php
    $postsData = $posts->map(function($p) {
        return [
            'id' => $p->id,
            'type' => $p->isImage() ? 'image' : 'video',
            'file_path' => asset($p->file_path),
            'caption' => $p->caption,
            'user_name' => $p->user->name,
            'user_photo' => $p->user->profile_photo_url,
            'created_at' => $p->created_at->format('d/m/Y'),
        ];
    });
    @endphp

    <script>
    const postsData = @json($postsData);

    function openModal(id) {
        const post = postsData.find(p => p.id === id);
        const modal = document.getElementById('post-modal');
        const img = document.getElementById('modal-image');
        let vid = document.getElementById('modal-video');

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
    }

    function closeModal() {
        const modal = document.getElementById('post-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        // stop video  modal
        document.querySelectorAll('#post-modal video').forEach(v => v.pause());

        // aktifkan lagi semua video di feed dan reset ikon speaker ke mute
        document.querySelectorAll('#posts-content video').forEach(v => {
            v.muted = true; // pastikan kembali mute
            v.currentTime = 0; // reset biar mulai dari awal
            v.play().catch(err => console.log('Autoplay gagal setelah modal ditutup:', err));

            // reset ikon speaker ke mute
            const speakerBtn = v.nextElementSibling;
            if (speakerBtn) {
                speakerBtn.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.617.816L4.414 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.414l3.969-3.816a1 1 0 011.616 0zM12.293 7.293a1 1 0 011.414 0L15 8.586l1.293-1.293a1 1 0 111.414 1.414L16.414 10l1.293 1.293a1 1 0 01-1.414 1.414L15 11.414l-1.293 1.293a1 1 0 01-1.414-1.414L13.586 10l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>';
            }
        });
    }

    </script>
</x-app-layout>
