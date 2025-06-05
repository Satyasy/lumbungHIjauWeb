<!-- resources/views/auth/register.blade.php -->
<x-guest-layout> {{-- Atau layout custom Anda yang mirip --}}
    <div class="flex items-center justify-center min-h-screen bg-gray-100 text-gray-900">
        <div class="p-2 max-w-md space-y-8 w-full">
            <div class="bg-white shadow-xl rounded-2xl p-8 md:p-12 space-y-8">
                <div class="flex flex-col items-center space-y-4">
                    {{-- Logo Anda --}}
                    <a href="{{ route('home') }}"> {{-- Ganti route('home') jika perlu --}}
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                    <h2 class="text-2xl font-bold tracking-tight text-center">
                        Buat Akun Baru
                    </h2>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="text-sm font-medium leading-6 text-gray-900 sr-only">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Nama Lengkap"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                               value="{{ old('name') }}" required autofocus autocomplete="name">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                         <label for="email" class="text-sm font-medium leading-6 text-gray-900 sr-only">Email</label>
                        <input type="email" name="email" id="email" placeholder="Alamat Email"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                               value="{{ old('email') }}" required autocomplete="email">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Pilihan Role (Jika Perlu) -->
                    <div>
                        <label for="role" class="text-sm font-medium leading-6 text-gray-900 sr-only">Daftar Sebagai</label>
                        <select name="role" id="role"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" required>
                            <option value="user">Pengguna Biasa</option>
                            <option value="collector">Kolektor</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>


                    <!-- Password -->
                    <div class="mt-4">
                         <label for="password" class="text-sm font-medium leading-6 text-gray-900 sr-only">Password</label>
                        <input type="password" name="password" id="password" placeholder="Password"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                               required autocomplete="new-password">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <label for="password_confirmation" class="text-sm font-medium leading-6 text-gray-900 sr-only">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                               required autocomplete="new-password">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                           href="{{ route('login') }}">
                            {{ __('Sudah punya akun?') }}
                        </a>

                        <x-primary-button class="ms-4 bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-primary-500">
                            {{ __('Daftar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
             <p class="text-xs text-gray-600 text-center">
                Kembali ke <a href="{{ route('home') }}" class="font-medium text-primary-600 hover:text-primary-500">Beranda</a>
            </p>
        </div>
    </div>
</x-guest-layout>