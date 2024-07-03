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
                    <x-error-message />
                    <section class="text-gray-600 body-font">
                        <div class="container mx-auto flex px-5 py-12 items-center justify-center flex-col">
                            @if (is_null($event->image))
                                <img class="lg:w-2/6 md:w-3/6 w-5/6 mb-10 object-cover object-center rounded"
                                    alt="hero" src="{{ asset('storage/events/No_Image.png') }}">
                            @else
                                <img class="lg:w-2/6 md:w-3/6 w-5/6 mb-10 object-cover object-center rounded"
                                    alt="hero" src="{{ asset('storage/events/' . $event->image) }}">
                            @endif
                            <div class="text-left lg:w-2/3 w-full">
                                <div>
                                    <x-input-label for="content" value="イベント名" />
                                    <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">
                                        {{ $event->title }}</h1>
                                </div>
                                <div>
                                        <x-input-label for="event_date" value="カテゴリー" />
                                        @foreach ($event->categories as $category)
                                            {{ $category->name }}
                                        @endforeach
                                  </div>
                                <div class="md:flex justify-start mt-4">
                                    <div>
                                        <x-input-label for="event_date" value="イベント日付" />
                                        {{ $event->eventDate }}/
                                    </div>
                                    <div class="mx-2">
                                        <x-input-label for="start_at" value="開始時間" />
                                        {{ $event->startTime }}/
                                    </div>
                                    <div>
                                        <x-input-label for="end_at" value="終了時間" />
                                        {{ $event->endTime }}
                                    </div>
                                </div>
                                 <div class="md:flex justify-start mt-4">
                                        <div>
                                            <x-input-label for="venue" value="イベント場所" />
                                            {{ $event->location->venue }}
                                        </div>
                                        <div class="mx-2">
                                            <x-input-label for="full_address" value="住所" />
                                            〒{{ $event->location->full_address }}
                                        </div>
                                    </div>
                                <div class="my-4">
                                    <x-input-label for="max_people" value="参加者数" />
                                    @if ($reservation)
                                        {{ $reservation->number_of_people }}
                                    @else
                                        0
                                    @endif
                                </div>
                                <div class="my-4">
                                        <x-input-label for="price" value="イベント価格" />
                                        <p id="price">
                                            {{ $event->formatted_price == 0 ? '無料' : $event->formatted_price . '円' }}
                                        </p>

                                    </div>

                                <div>
                                    <x-input-label for="content" value="イベント詳細" />
                                    <p class="mb-8 leading-relaxed"> {!! nl2br(e($event->content)) !!}</p>
                                </div>

                            </div>
                            <form id="cancel_{{ $event->id }}" method="post"
                                action="{{ route('mypage.cancel', ['id' => $event->id]) }}">
                                @csrf
                                {{-- 過去のイベントの場合非表示 --}}
                                @if ($event->eventDate >= \Carbon\Carbon::today()->format('Y年m月d日'))
                                    <a href="#"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                        data-id="{{ $event->id }}" onclick="cancelPost(this)">
                                        イベントキャンセル
                                    </a>
                            </form>
                        </div>
                        @endif
                    </section>
                </div>
            </div>
        </div>
        <x-cancel-js />

</x-app-layout>
