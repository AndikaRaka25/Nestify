<x-action-section>
    <x-slot name="title">
        {{ __('Hapus Akun') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Pastikan Anda telah mencadangkan semua data yang ingin disimpan sebelum menghapus akun Anda.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('Akun Anda akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi yang ingin Anda simpan.') }}
        </div>

        <div class="mt-5">
            <x-filament::button color="danger" wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                {{ __('Hapus Akun') }}
            </x-filament::button>

            <x-action-message class="ms-3" on="userDeleted">
                {{ __('Akun Anda telah dihapus.') }}
            </x-action-message>
        </div>

        <!-- Delete User Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmingUserDeletion">
            <x-slot name="title">
                {{ __('Delete Account') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}

                <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-input type="password" class="mt-1 block w-3/4"
                                autocomplete="current-password"
                                placeholder="{{ __('Password') }}"
                                x-ref="password"
                                wire:model="password"
                                wire:keydown.enter="deleteUser" />

                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                    {{-- ✨ PERBAIKAN: Menggunakan Tombol Filament (Secondary) ✨ --}}
                    <x-filament::button color="gray" wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                        {{ __('Batal') }}
                    </x-filament::button>

                    {{-- ✨ PERBAIKAN: Menggunakan Tombol Filament (Danger) ✨ --}}
                    <x-filament::button color="danger" class="ms-3" wire:click="deleteUser" wire:loading.attr="disabled">
                        {{ __('Hapus Akun') }}
                    </x-filament::button>
                </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>
