<x-guest-layout>
    <div class="flex min-h-screen">
        <!-- Image Section (Desktop Only) -->
        <div class="hidden lg:flex lg:w-1/2 items-center justify-center relative overflow-hidden">
            <!-- Background Image -->
            <img src="https://i.pinimg.com/1200x/7d/f6/cb/7df6cb0c32171c137f3bd849b80ead17.jpg" alt="MyPost App Background" class="absolute inset-0 w-full h-full object-cover opacity-20">
            <!-- Dark overlay for dark mode -->
            <div class="absolute inset-0 bg-black opacity-50 dark:opacity-70"></div>
            <div class="relative z-10 text-center text-white">
                <p class="text-sm p-10 text-gray-200 mb-4 text-justify">Platform modern untuk berbagi konten dengan cepat dan mudah. MyPost memungkinkan pengguna untuk membuat, mengelola, dan membagikan postingan dalam format teks, gambar, atau media lainnya dengan tampilan yang bersih dan interaktif. Dengan fokus pada kemudahan penggunaan dan pengalaman pengguna yang menyenangkan, MyPost menghadirkan fitur-fitur seperti timeline personal, notifikasi real-time, dan opsi privasi yang dapat disesuaikan. Cocok untuk individu maupun komunitas yang ingin tetap terhubung dan mengekspresikan diri secara kreatif.</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <form method="POST" action="{{ route('register') }}" class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
                    <!-- Logo -->
                    <div class="text-center p-10">
                        <img src="/logo/logo-lightmode.png" alt="MyPost Logo" class="w-40 h-auto mx-auto dark:hidden" />
                        <img src="/logo/logo-darkmode.png" alt="MyPost Logo" class="w-40 h-auto mx-auto hidden dark:block" />
                    </div>

                    @csrf
                    <!-- Name -->
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Name')" class="text-gray-700 dark:text-gray-300" />
                        <x-text-input id="name" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300" />
                        <x-text-input id="email" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-300" />

                        <x-text-input id="password" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 dark:text-gray-300" />

                        <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Login Link -->
                    <div class="mb-4 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                                Login here
                            </a>
                        </p>
                    </div>

                    <div class="flex items-center justify-end">
                        <x-primary-button>
                            {{ __('Register') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>