<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen antialiased bg-[#ffffff] dark:bg-[#0a0a0a]">
        <div class="min-h-svh flex items-center justify-center p-6 md:p-10">
            <div class="w-full max-w-sm">
                <div class="rounded-xl overflow-hidden border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_1px_2px_0_rgba(0,0,0,0.06)]">
                    <div class="bg-[#F53003] text-white px-5 py-4 flex items-center gap-2">
                        <x-app-logo-icon class="size-6 fill-current text-white" />
                        <span class="font-semibold">Sign in</span>
                    </div>
                    <div class="bg-white dark:bg-[#161615] px-5 py-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
