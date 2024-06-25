<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <x-base-head />
    <body class="font-sans antialiased">
       @auth
         @include('layouts.navigation')
       @else
         <x-base-header />
       @endauth

        <section class="text-gray-600 body-font min-h-screen bg-orange-50">
          <div class="container px-5 py-24 mx-auto">
            <h2 id="category-title" class="font-semibold text-xl text-gray-800 leading-tight">
                全てのカテゴリーの検索結果
            </h2>
            <form id="search-form">
              <div class="flex justify-start items-center mx-2">
                <div  class="p-4">
                  <label for="category_id">カテゴリー:</label>
                  <select id="category-select" class="pr-8 border border-gray-300 p-4 rounded" name="category_id">
                      <option value="">お選びください</option>
                      @foreach($categories as $category)
                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                      @endforeach
                  </select>
                </div>
                <div class="mx-2">
                    <label for="event_date">イベント日付:</label>
                    <x-text-input type="date" id="event_date" name="event_date"/>
                </div>
                 <div class="mx-2">
                    <label for="keyword">キーワード:</label>
                    <x-text-input type="text" id="keyword" name="keyword"/>
                </div>
                <div>
                   <button type="submit" class="p-2.5 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                      <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                      </svg>
                      <span class="sr-only">Search</span>
                  </button>
                </div>
              </form>
              </div>
            <div id="event-list" class="flex flex-wrap mt-4">
              @foreach($events as $event)
              <div id="event-item" class="cursor-pointer p-4 md:w-1/3" onclick="location.href='{{ route('reservations.detail', [ 'event' => $event->id ]) }}'">
                <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
                  <img class="lg:h-48 md:h-36 w-full object-cover object-center" src="https://dummyimage.com/720x400" alt="blog">
                  <div class="p-6">
                    <div class="flex flex-wrap justify-start mx-2">
                      @foreach($event->categories as $category)
                      <h2 class="tracking-widest text-xs title-font font-medium text-gray-400 mb-1">{{ $category->name }}</h2>
                      @endforeach
                    </div>
                    <h1 class="title-font text-lg font-medium text-gray-900 mb-3">{{ $event->title }}</h1>
                    <p class="leading-relaxed mb-3">{{ $event->event_date }}</p>
                    <p class="leading-relaxed mb-3">場所：{{ $event->location->venue }}</p>
                    <p class="leading-relaxed mb-3">{{ $event->formatted_price == 0 ? '無料' : $event->formatted_price . '円' }}</p>
                    <div class="flex items-center flex-wrap ">
                      <span class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
                        <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                          <circle cx="12" cy="12" r="3"></circle>
                        </svg>{{ $event->like_count }}
                      </span>
                      <span class="text-gray-400 inline-flex items-center leading-none text-sm">
                        <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                          <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                        </svg>{{ $event->comment_count }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
               {{ $events->links() }}
            </div>
          </div>
        </section>
         <x-search-js />
    </body>
</html>