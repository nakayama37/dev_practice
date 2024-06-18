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

                          <div class="py-4 my-4 flex justify-between items-center bg-gray-100" id="like-button" data-event-id="{{ $event->id }}" data-liked="{{ $liked }}" data-like-count="{{ $likeCount }}">
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
                            <form method="POST" action="{{ route('reservations.join') }}">
                              @csrf
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
      <script>
        'use strict';
        // DOMが完全に読み込まれた後に実行される処理
        document.addEventListener('DOMContentLoaded', function() {
            const likeButton = document.getElementById('like-button');
            const likeToggle = document.getElementById('like-toggle');
            const likeIcon = document.getElementById('like-icon');
            const likeText = document.getElementById('like-text');
            const likeCount = document.getElementById('like-count');

            let isRequestInProgress = false;
            
            likeToggle.addEventListener('click', function(event) {
              event.preventDefault();
              
              // リクエスト中は他のリクエストができないようにする
                  if (isRequestInProgress) return;
                  isRequestInProgress = true;

                  likeToggle.classList.add('text-gray-200');  // クリックを無効化するためのクラスを追加

                const eventId = likeButton.getAttribute('data-event-id');

                fetch(`/events/${eventId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                
                    likeButton.setAttribute('data-liked', data.liked);
                    likeCount.innerText = data.likeCount;
                    if (data.liked) {
                        likeIcon.innerText = '❤️';
                        likeText.innerText = 'いいねを取り消す';
                    } else {
                        likeIcon.innerText = '♡';
                        likeText.innerText = 'いいねを押してイベントを応援する';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                 .finally(() => {
                    isRequestInProgress = false;
                    likeToggle.classList.remove('text-gray-200'); 
                });
            });
        });
      
      
      </script>
</x-app-layout>