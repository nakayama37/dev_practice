<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <script src="https://cdn.jsdelivr.net/npm/tailwindcss-cdn@3.4.1/tailwindcss.js"></script>
        <script src="//unpkg.com/alpinejs" defer></script>
        @vite(['resources/css/app.css',
                'resources/js/app.js',
                'resources/js/flatpickr.js'
                ])
    </head>
    <body class="font-sans antialiased">
        <header class="text-gray-600 body-font">
            <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
                <a class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-10 h-10 text-white p-2 bg-indigo-500 rounded-full" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
                <span class="ml-3 text-xl">イベントアプリ</span>
                </a>
             @if (Route::has('login'))
                <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center">
                @auth
                    <a  href="{{ url('/home') }}" class="mr-5 hover:text-gray-900">ホーム</a>
                @else
                    <a  href="{{ route('login') }}" class="mr-5 hover:text-gray-900">ログイン</a>
                @if (Route::has('register'))
                    <a  href="{{ route('register') }}" class="mr-5 hover:text-gray-900">登録</a>
                @endif
                @endauth
                </nav>
             @endif
            </div>
        </header>         
           <div class="min-h-screen bg-orange-50">
            <!-- Session Status -->
            <x-success-session-status class="mb-4" :status="session('status')" />

            @foreach ($categories as $category)  
              <div class="py-16 text-center">
                <h2 class="text-2xl font-semibold text-gray-800">{{ $category->name }}</h2>
              </div>
              <div class="pb-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                  <div class="overflow-x-scroll scrollbar-hide mb-4 relative px-0.5" style="overflow-y: hidden;">
                  <div class="flex snap-x snap-mandatory gap-4" style="width: max-content;">

                @foreach ($category->events as $event) 
                <div class="cursor-pointer flex-none w-64 snap-center" onclick="location.href='{{ route('reservations.detail', [ 'event' => $event->id ]) }}'" >
                  <div class="bg-white border-1 border border-gray-200 rounded-lg overflow-hidden mb-4">
                    @if(is_null($event->image))   
                    <img src="{{ asset('storage/events/No_Image.png') }}" alt="" class="w-full h-40 object-cover">
                    @else  
                    <img src="{{ asset('storage/events/' . $event->image) }}" alt="" class="w-full h-40 object-cover">
                    @endif   
                    <div class="p-4">
                      <h3 class="text-lg leading-6 font-bold text-gray-900">{{ $event->title }}</h3>
                      <h4 class="text-sm leading-6 text-gray-900">{{ $event->event_date }}</h4>
                      <div class="flex justify-between items-center mt-4">
                        <span class="text-2xl font-extrabold text-gray-900">1000円</span>
                        <span class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
                            <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                            </svg>1.2K
                        </span>
                        <span class="text-gray-400 inline-flex items-center leading-none text-sm">
                            <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                            </svg>6
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
                
                </div>
                </div>
              </div>
              </div>
            @endforeach 
        </div>
    </body>
</html>