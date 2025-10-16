<x-guest-layout>
    <div class="flex min-h-screen">
        <!-- Image Section (Desktop Only) -->
        <div class=" hidden lg:flex lg:w-1/2 items-center justify-center relative overflow-hidden">
            <!-- Background Image -->
            <img src="https://i.pinimg.com/1200x/7d/f6/cb/7df6cb0c32171c137f3bd849b80ead17.jpg" alt="MyPost App Background" class="absolute inset-0 w-full h-full object-cover opacity-20">
            <div class="relative z-10 text-center text-white">
                <h1 class="animate__animated animate__headShake text-4xl font-bold mb-4">MyPost</h1>
                <p class="text-sm p-10 text-gray-200 mb-4 text-justify">Platform modern untuk berbagi konten dengan cepat dan mudah. MyPost memungkinkan pengguna untuk membuat, mengelola, dan membagikan postingan dalam format teks, gambar, atau media lainnya dengan tampilan yang bersih dan interaktif. Dengan fokus pada kemudahan penggunaan dan pengalaman pengguna yang menyenangkan, MyPost menghadirkan fitur-fitur seperti timeline personal, notifikasi real-time, dan opsi privasi yang dapat disesuaikan. Cocok untuk individu maupun komunitas yang ingin tetap terhubung dan mengekspresikan diri secara kreatif.</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Logo (Mobile Only) -->
                <div class="text-center mb-8 lg:hidden">
                    <!-- <x-application-logo class="w-16 h-16 mx-auto mb-4 fill-current text-gray-500" /> -->
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">MyPost</h1>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
                    @csrf

                    <!-- Email/Username -->
                    <div class="mb-4">
                        <x-input-label for="login" :value="__('Email or Username')" class="text-gray-700 dark:text-gray-300" />
                        <x-text-input id="login" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="login" :value="old('login')" required autofocus autocomplete="username"/>
                        <x-input-error :messages="$errors->get('login')" class="mt-2" />
                    
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-300" />

                        <x-text-input id="password" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                        @endif

                        <x-primary-button>
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>

                    <!-- Register Link -->
                    <div class="mb-1 mt-5 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                                Register here
                            </a>
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-guest-layout>