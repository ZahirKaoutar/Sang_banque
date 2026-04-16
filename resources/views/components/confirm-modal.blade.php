@props(['id' => 'confirmModal', 'title' => 'Confirmation', 'message' => 'Êtes-vous sûr?', 'confirmText' => 'Confirmer', 'cancelText' => 'Annuler'])

<div x-show="data.{{ $id }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl">
        <h3 class="text-lg font-bold text-dark mb-2">{{ $title }}</h3>
        <p class="text-gray-600 mb-6">{{ $message }}</p>

        <div class="flex gap-4 justify-end">
            <button @click="data.{{ $id }} = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                {{ $cancelText }}
            </button>
            <button onclick="submitted = true; this.closest('form').submit()" class="px-4 py-2 bg-red-mid text-white rounded-lg hover:bg-red-deep transition">
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>
