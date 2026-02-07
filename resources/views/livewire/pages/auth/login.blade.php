<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();

        $this->redirectIntended(default: route('events.index', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen relative overflow-hidden bg-stone-950">
    {{-- soft “olive paper” background --}}
    <div class="absolute inset-0">
        <div class="restore-pointer-events-none absolute inset-0 bg-gradient-to-b from-stone-950 via-stone-950 to-stone-900"></div>

        {{-- glow blobs --}}
        <div class="absolute -top-24 -left-24 h-96 w-96 rounded-full bg-emerald-900/25 blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 h-[32rem] w-[32rem] rounded-full bg-lime-900/20 blur-3xl"></div>

        {{-- subtle grain-ish overlay --}}
        <div class="absolute inset-0 opacity-[0.06] [background-image:radial-gradient(#fff_1px,transparent_1px)] [background-size:18px_18px]"></div>
    </div>

    <div class="relative mx-auto flex min-h-screen max-w-6xl items-center justify-center px-6 py-12">
        <div class="grid w-full max-w-5xl grid-cols-1 gap-10 lg:grid-cols-2">
            {{-- Left “brand” panel --}}
            <div class="hidden lg:flex flex-col justify-center">
                <div class="flex items-center gap-4">
    {{-- LOGO --}}
    <img
        src="{{ asset('images/logo.png') }}"
        alt="Diana’s Garden"
        class="h-14 w-14 object-contain rounded-full bg-stone-900/60 p-2 ring-1 ring-emerald-300/20"
    >

    <div>
        <div class="text-sm uppercase tracking-[0.35em] text-emerald-200/70">
            Diana’s Garden
        </div>
        <div class="text-3xl font-serif font-semibold text-stone-100">
            Digital Invitations
        </div>
    </div>
</div>


                <p class="mt-6 max-w-md text-stone-300/80 leading-relaxed">
    Elegantne digitalne pozivnice, dizajnirane sa pažnjom za detalj,
    inspirisane prirodom, teksturom i bezvremenskim stilom.
</p>


                <div class="mt-8 flex items-center gap-3 text-xs text-stone-400/80">
                    <span class="inline-flex items-center gap-2 rounded-full bg-stone-900/60 px-3 py-1 ring-1 ring-stone-800">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400/80"></span>
                        Siguran pristup
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-stone-900/60 px-3 py-1 ring-1 ring-stone-800">
                        <span class="h-1.5 w-1.5 rounded-full bg-lime-300/70"></span>
                        Brzo i jednostavno
                    </span>
                </div>
            </div>

            {{-- Right login card --}}
            <div class="flex items-center justify-center">
                <div class="w-full max-w-md">
                    <div class="rounded-3xl bg-gradient-to-b from-stone-900/75 to-stone-950/75 p-8 shadow-2xl ring-1 ring-stone-800 backdrop-blur">
                        {{-- top label like “foil tag” --}}
                        <div class="mb-6 flex items-center justify-between">
                            <div class="inline-flex items-center gap-2 rounded-full bg-emerald-900/30 px-3 py-1 text-xs uppercase tracking-[0.25em] text-emerald-100/80 ring-1 ring-emerald-300/15">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-300/80"></span>
                                Prijava
                            </div>
                        </div>

                        <h1 class="text-2xl font-semibold text-stone-100">Dobrodošli nazad</h1>
                        <p class="mt-2 text-sm text-stone-300/80">
                            Ulogujte se da nastavite sa uređivanjem događaja.
                        </p>

                        <div class="mt-6">
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form wire:submit="login" class="space-y-4">
                                <div>
                                    <x-input-label for="email" :value="__('Email')" class="text-stone-200/80" />
                                    <x-text-input
                                        wire:model="form.email"
                                        id="email"
                                        class="mt-1 block w-full !bg-stone-900/60 !text-stone-100 ring-1 ring-stone-800 focus:!ring-emerald-400/50 focus:!border-emerald-400/50 placeholder:text-stone-500"
                                        type="email"
                                        name="email"
                                        required
                                        autofocus
                                        autocomplete="username"
                                    />
                                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="password" :value="__('Password')" class="text-stone-200/80" />
                                    <x-text-input
                                        wire:model="form.password"
                                        id="password"
                                        class="mt-1 block w-full !bg-stone-900/60 !text-stone-100 ring-1 ring-stone-800 focus:!ring-emerald-400/50 focus:!border-emerald-400/50 placeholder:text-stone-500"
                                        type="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                    />
                                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-between pt-1">
                                    <label for="remember" class="inline-flex items-center gap-2 text-sm text-stone-300/80">
                                        <input
                                            wire:model="form.remember"
                                            id="remember"
                                            type="checkbox"
                                            class="rounded border-stone-700 bg-stone-900/60 text-emerald-500 focus:ring-emerald-500"
                                            name="remember"
                                        >
                                        {{ __('Remember me') }}
                                    </label>

                                    @if (Route::has('password.request'))
                                        <a
                                            class="text-sm text-emerald-200/80 hover:text-emerald-200 underline underline-offset-4"
                                            href="{{ route('password.request') }}"
                                            wire:navigate
                                        >
                                            {{ __('Forgot your password?') }}
                                        </a>
                                    @endif
                                </div>

                                <div class="pt-2">
                                    <button
                                        type="submit"
                                        class="w-full rounded-2xl bg-gradient-to-r from-emerald-700/80 to-lime-700/60 px-4 py-3 text-sm font-semibold text-stone-50 shadow-lg ring-1 ring-emerald-300/20 hover:from-emerald-600/85 hover:to-lime-600/65 focus:outline-none focus:ring-2 focus:ring-emerald-400/40"
                                    >
                                        {{ __('Log in') }}
                                    </button>

                                    <p class="mt-4 text-center text-xs text-stone-400/80">
                                        © {{ date('Y') }} • <span class="text-emerald-200/70 uppercase tracking-[0.25em]">Diana’s Garden</span>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- mobile brand hint --}}
                    <div class="mt-6 lg:hidden text-center">
                        <div class="mx-auto mb-3 inline-flex items-center justify-center">
                            <img
                                src="{{ asset('images/logo.png') }}"
                                alt="Diana’s Garden"
                                class="h-12 w-12 object-contain rounded-full bg-stone-900/60 p-2 ring-1 ring-emerald-300/20"
                            >
                        </div>

                        <div class="text-xs uppercase tracking-[0.25em] text-emerald-200/70">
                            Diana’s Garden
                        </div>
                        <div class="mt-1 text-stone-200 font-serif font-semibold">
                            Digital Invitations
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
