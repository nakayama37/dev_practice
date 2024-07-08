<x-app-layout>
    <script src="https://js.stripe.com/v3/"></script>
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
                                    alt="hero" src="{{ asset('storage/No_Image.png') }}">
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

                                <div class="py-4 my-4 flex justify-between items-center bg-gray-100" id="like-button"
                                    data-event-id="{{ $event->id }}" data-liked="{{ $liked }}"
                                    data-like-count="{{ $likeCount }}">
                                    <div class="mx-auto">
                                        <span id="like-count">{{ $likeCount }}</span> いいね
                                    </div>
                                    <div class="mx-auto">
                                        <a href="#" id="like-toggle">
                                            <span id="like-icon">
                                                {{ $liked ? '❤️' : '♡' }}
                                            </span>
                                            <span id="like-text">
                                                {{ $liked ? 'いいねを取り消す' : 'いいねを押してイベントを応援する' }}
                                            </span>
                                        </a>
                                    </div>
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
                                    <div class="mx-4">
                                        <x-input-label for="price" value="イベント価格" />
                                        <p id="price">
                                            {{ $event->formatted_price == 0 ? '無料' : $event->formatted_price . '円' }}
                                        </p>

                                    </div>
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="content" value="イベント詳細" />
                                    <p class="mb-8 leading-relaxed"> {!! nl2br(e($event->content)) !!}</p>
                                </div>
                                @if ($reservablePeople <= 0)
                                    <span class="text-lg text-red-500">このイベントは満員です</span>
                                @else
                                <form id="payment-form">
                                @csrf
                                <input id="event_id" type="hidden" name="event_id"
                                    value="{{ $event->id }}">
                                <div class="mt-4">
                                    <x-input-label for="number_of_people" value="参加人数" />
                                    <x-text-input id="number_of_people" class="block mt-1" type="number"
                                        name="number_of_people" required min="1" />
                                </div>
                            </div>
                            <div class="text-left lg:w-2/3 mt-4">
                                <div id="payment-status" class="hidden text-center mt-4">
                                    <p id="payment-message"></p>
                                </div>
                                <label for="card-element"
                                    class="pr-32 block text-sm font-medium text-gray-700">クレジットカード情報</label>
                                <div id="card-element" class="mt-1 p-2 border rounded-md"></div>
                            </div>
                            @if ($isReserved === null)
                                {{-- 過去のイベントの場合非表示 --}}
                                @if ($event->eventDate >= \Carbon\Carbon::today()->format('Y年m月d日') || $event->max_people > $participantCount)
                                    <x-primary-button id="payment-button" class="mt-4">
                                        イベント参加
                                    </x-primary-button>
                                @endif
                            @else
                                <span class="text-lg text-red-500">このイベントは既に予約済みです</span>
                            @endif
                            @endif
                                </form>
                        </div>
                    </section>

                    <section class="bg-white dark:bg-gray-900 py-8 lg:py-16 antialiased">
                        <div class="max-w-2xl mx-auto px-4">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">コメント</h2>
                            </div>
                            <form id="comment-form" class="mb-6">
                                @csrf
                                <div
                                    class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                    <label for="comment" class="sr-only">Your comment</label>
                                    <textarea id="comment-content" rows="6" name="content"
                                        class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
                                        placeholder="Write a comment..." required></textarea>
                                </div>
                                <x-primary-button class="ms-3">
                                    コメントを投稿する
                                </x-primary-button>
                            </form>
                            <div id="comments-list" class="p-6 text-base bg-white rounded-lg dark:bg-gray-900"></div>
                        </div>

                    </section>
                </div>
            </div>
        </div>
        <x-cancel-js />
        <x-reservations-js />
</x-app-layout>
