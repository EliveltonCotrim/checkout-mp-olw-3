<div class="bg-tertiary-900 min-h-screen w-full flex flex-col items-center justify-center">
    <div class="flex flex-col rounded-md border border-primary-200 px-16 py-10">
        <div class="mt-6">
            <x-input-label for="email" value="Email" />
            <div class="mt-1">
                <x-text-input type="email" id="email" name="email" autocomplete="email"
                    placeholder="Digite seu email" wire:model="email" />
            </div>

            <div>
                @error('email')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <x-primary-button class="mt-6 w-full" wire:click="login()">
                {{ __('Solicitar email') }}
            </x-primary-button>
        </div>
    </div>
</div>
