@props(['disabled' => false, 'value'])

<div  x-data="{
    codigo: '{{ $value }}',
    copied: false,
    timeout: null,
    copy() {
        $clipboard(this.codigo)

        this.copied = true
        clearTimeout(this.timeout)

        this.timeout = setTimeout(() => {
            this.copied = false
        }, 3000)
    }}">

    <div class="relative w-full">
        <input @disabled($disabled)
            {{ $attributes->merge(['class' => 'block w-full pr-10 rounded-md text-primary-200 bg-tertiary-800 border-gray-300 shadow-sm focus:border-primary-200 focus:ring-primary-200 sm:text-sm']) }}
            value="{{ $value ?? '' }}" readonly>

        <!-- Ícone de Copiar -->
        <button type="button" title="Copiar"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-primary-200 hover:text-primary-300"
            x-on:click="copy">

            <!-- Ícone (FontAwesome ou Heroicons) -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5A3.375 3.375 0 0 0 6.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0 0 15 2.25h-1.5a2.251 2.251 0 0 0-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 0 0-9-9Z" />
            </svg>
        </button>
    </div>
    <small class="text-primary-200" x-text="copied ? 'Código copiado!' : ''"></small>
</div>
