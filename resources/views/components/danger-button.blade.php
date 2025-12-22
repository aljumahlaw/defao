<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-danger border border-transparent rounded-lg font-cairo font-semibold text-xs text-white uppercase tracking-widest hover:bg-danger/90 active:bg-danger focus:outline-none focus:ring-2 focus:ring-danger focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm']) }}>
    {{ $slot }}
</button>
