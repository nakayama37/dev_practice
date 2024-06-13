<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            イベント詳細
        </h2>
    </x-slot>

    <div class="pt-4 pb-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                   <!-- エラーメッセージ -->
                  <x-error-message/>
                  <form method="POST" action="{{ route('reservations.join') }}">
                     @csrf
                    <section class="text-gray-600 body-font">
                      <div class="container mx-auto flex px-5 py-12 items-center justify-center flex-col">
                        @if(is_null($event->image))           
                          <img class="lg:w-2/6 md:w-3/6 w-5/6 mb-10 object-cover object-center rounded" alt="hero" src="{{ asset('storage/events/No_Image.png') }}">
                        @else      
                          <img class="lg:w-2/6 md:w-3/6 w-5/6 mb-10 object-cover object-center rounded" alt="hero" src="{{ asset('storage/events/' . $event->image) }}">
                        @endif
                        <div class="text-left lg:w-2/3 w-full">
                          <div>
                            <x-input-label for="content" value="イベント名" />
                            <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">{{ $event->title }}</h1>
                          </div>
                          <div class="md:flex justify-start mt-4">
                            <div>
                              <x-input-label for="event_date" value="イベント日付" />
                              {{ $eventDate }}/
                            </div>
                            <div class="mx-2">
                              <x-input-label for="start_at" value="開始時間" />
                              {{ $startTime }}/
                            </div>
                            <div>
                              <x-input-label for="end_at" value="終了時間" />
                              {{ $endTime }}
                            </div>
                          </div>
                          <div class="md:flex justify-start mt-4">
                            <div>
                              <x-input-label for="max_people" value="定員数" />
                              {{ $event->max_people }}
                            </div>
                            <div class="mx-4">
                              <x-input-label for="number_of_people" value="現在のイベント参加者数" />
                              {{ $participantCount }}
                              </div>
                             <div>
                                <x-input-label for="reservablePeople" value="参加可能人数" />
                                {{ $reservablePeople }}
                             </div>
                          </div>
                          <div class="mt-4">
                            <x-input-label for="content" value="イベント詳細" />
                            <p class="mb-8 leading-relaxed"> {!! nl2br(e($event->content))  !!}</p>
                          </div>
                          @if($reservablePeople <= 0)
                            <span class="text-lg text-red-500">このイベントは満員です</span>
                            @else
                            <div class="mt-4">
                              <x-input-label for="number_of_people" value="参加人数" />
                              <x-text-input id="number_of_people"  class="block mt-1" type="number" name="number_of_people" required min="1"/>
                            </div>
                            </div>
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            @if($isReserved === null)
                            {{-- 過去のイベントの場合非表示 --}}
                              @if($event->eventDate >= \Carbon\Carbon::today()->format('Y年m月d日') || $event->max_people > $participantCount)
                              <x-primary-button class="ms-3">
                                イベント参加
                                </x-primary-button>
                              @endif
                            @else
                            <span class="text-lg text-red-500">このイベントは既に予約済みです</span>
                            @endif
                          @endif
                      </div>
                    </section>
                 </form>
            </div>
        </div>
      </div>
</x-app-layout>