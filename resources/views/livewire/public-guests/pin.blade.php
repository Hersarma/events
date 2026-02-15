<div class="min-h-screen bg-neutral-100 flex items-center justify-center px-6">
  <div class="w-full max-w-sm rounded-[32px] bg-white border border-gray-200 p-6">
    <div class="text-center">
      <div class="text-xl font-bold">Lista gostiju</div>
      <div class="text-sm text-gray-600 mt-1">Unesite PIN (4 cifre)</div>
    </div>

    <form wire:submit.prevent="submit" class="mt-6 space-y-4">
      <div>
        <input wire:model.defer="pin"
               inputmode="numeric" maxlength="4"
               class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-center tracking-[0.35em] text-lg outline-none focus:ring-2 focus:ring-gray-200"
               placeholder="••••" />
        @error('pin') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
      </div>

      <button class="w-full rounded-2xl bg-gray-900 px-5 py-3 text-sm font-semibold text-white">
        Prikaži listu
      </button>
    </form>
  </div>
</div>
