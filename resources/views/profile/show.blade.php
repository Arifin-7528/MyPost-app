<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Profile Header -->
                    <div class="flex items-center mb-8">
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile Photo" class="w-20 h-20 rounded-full mr-6 border-4 border-gray-300 dark:border-gray-600">
                        <div class="flex items-center gap-6">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h1>
                            <p class="text-gray-600 dark:text-gray-400">{{ $posts->count() }} Posts</p>
                            <p class="text-gray-600 dark:text-gray-400">{{ $posts->sum('likes_count') }} Likes</p>
                        </div>
                    </div>

                    <!-- Posts Section -->
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

                                <div class="relative">
                            @if($post->isImage())
                            <img src="{{ asset($post->file_path) }}" alt="Post Image" class="w-full h-64 object-cover rounded-lg mb-4 cursor-pointer" onclick="openModal({{ $post->id }})">
                            @elseif($post->isVideo())
                            <div class="relative">
                                <video loop playsinline class="w-full h-64 object-cover rounded-lg mb-4 cursor-pointer" onclick="openModal({{ $post->id }})">
                                    <source src="{{ asset($post->file_path) }}" type="video/mp4">
                                    Browser Anda tidak mendukung tag video.
                                </video>
                            </div>
                            @endif
                                </div>

                                @if($post->caption)
                                <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $post->caption }}</p>
                                @endif

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <button class="flex items-center text-red-500 hover:text-red-700 mr-4" onclick="toggleLike({{ $post->id }}, this)">
                                            <svg class="w-5 h-5 mr-1 {{ $post->isLikedByUser() ? 'fill-current' : '' }}" fill="{{ $post->isLikedByUser() ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            <span>{{ $post->likes_count }}</span>
                                        </button>
                                        <button class="flex items-center text-gray-500 hover:text-gray-700" onclick="openModal({{ $post->id }})">
                                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            Komentar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-center text-gray-500 dark:text-gray-400">belum ada post. jadilah yang pertama memposting!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal untuk post -->
    <div id="post-modal" class="fixed inset-0 bg-black bg-opacity-70 hidden justify-center items-center z-50">
        <div class="flex flex-col lg:flex-row bg-gray-900 rounded-2xl w-full max-w-5xl h-[90vh] overflow-hidden shadow-2xl">
            <!-- media -->
            <div class="w-full lg:w-3/5 bg-black flex justify-center items-center">
                <img id="modal-image" class="max-h-full max-w-full object-contain hidden">
                <video id="modal-video" class="max-h-full max-w-full object-contain hidden" autoplay muted loop playsinline></video>
            </div>

            <!-- detail -->
            <div class="w-full lg:w-2/5 bg-gray-800 text-white flex flex-col relative h-full">
                <button class="absolute top-2 right-2 text-gray-400 hover:text-red-500" onclick="closeModal()">✖</button>

                <div id="modal-header" class="flex items-center gap-3 border-b border-gray-700 p-4">
                    <img id="modal-user-photo" src="" class="w-10 h-10 rounded-full border border-gray-700">
                    <div>
                        <p id="modal-username" class="font-semibold text-sm"></p>
                        <p id="modal-date" class="text-xs text-gray-400"></p>
                    </div>
                </div>

                <div id="modal-caption" class="p-4 text-sm text-gray-200 border-b border-gray-700"></div>

                <div id="modal-likes-count" class="p-4 text-sm text-gray-200 border-b border-gray-700"></div>

                <div id="modal-comments" class="flex-1 overflow-y-auto p-4 space-y-2 text-sm"></div>

                <!-- form komentar -->
                <div class="p-4 border-t border-gray-700">
                    <form id="comment-form" class="flex gap-2">
                        <input type="text" id="comment-input" placeholder="Tambahkan komentar..." class="flex-1 bg-gray-700 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" maxlength="255">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">Kirim</button>
                    </form>
                </div>
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
    'likes_count' => $p->likes_count,
    ];
    });
    @endphp
    <script>
        const postsData = @json($postsData);
    </script>
</x-app-layout>
