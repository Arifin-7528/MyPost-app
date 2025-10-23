<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- File Upload -->
                        <div class="mb-4">
                            <x-input-label for="file" :value="__('File (Image or Video)')" />
                            <input id="file" type="file" name="file" accept="image/*,video/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>

                        <!-- Caption -->
                        <div class="mb-4">
                            <x-input-label for="caption" :value="__('Caption')" />
                            <x-text-input id="caption" name="caption" type="text" class="mt-1 block w-full" placeholder="Write a caption..." />
                            <x-input-error :messages="$errors->get('caption')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Post') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
