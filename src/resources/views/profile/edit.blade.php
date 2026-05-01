<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900">アカウント退会</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        退会するとアカウントは利用停止となり、{{ \App\Services\AccountDeletionService::GRACE_PERIOD_DAYS }}日後に完全に削除されます。
                    </p>
                    <a href="{{ route('account.delete.show') }}" class="btn-danger mt-4 inline-flex">
                        退会手続きへ進む
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
