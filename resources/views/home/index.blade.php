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
                            <img src="{{ asset($post->file_path) }}" alt="Post Image" class="w-full h-64 object-cover rounded-lg mb-4">
                            @elseif($post->isVideo())
                            <video controls class="w-full h-64 object-cover rounded-lg mb-4">
                                <source src="{{ asset($post->file_path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
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
                    <p class="text-center text-gray-500 dark:text-gray-400">No posts yet. Be the first to post!</p>
                    @endif
                </div>
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
        });
    </script>
</x-app-layout>