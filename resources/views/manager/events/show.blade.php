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
                  <form method="GET" action="{{ route('events.edit', ['event' => $event->id ]) }}">
                    <section class="text-gray-600 body-font">
                      <div class="container mx-auto flex px-5 py-12 items-center justify-center flex-col">
                          {{-- イベント画像 --}}
                        <img class="lg:w-2/6 md:w-3/6 w-5/6 mb-10 object-cover object-center rounded" alt="hero" src="https://dummyimage.com/720x600">
                        <div class="text-left lg:w-2/3 w-full">
                          <div>
                            <x-input-label for="content" value="イベント名" />
                            <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">{{ $event->title }}</h1>
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
                          <div class="my-4">
                            <x-input-label for="max_people" value="定員数" />
                            {{ $event->max_people }}
                          </div>
                          <div class="my-4">
                            @if($event->is_public)
                            <span class="text-green-500">表示中</span>
                            @else
                            <span class="text-red-500">非表示</span>
                            @endif
                          </div>
                          <div>
                            <x-input-label for="content" value="イベント詳細" />
                            <p class="mb-8 leading-relaxed"> {!! nl2br(e($event->content))  !!}</p>
                          </div>

                        </div>
                        {{-- 過去のイベントの場合非表示 --}}
                        @if($event->eventDate >= \Carbon\Carbon::today()->format('Y年m月d日'))
                          <x-primary-button class="ms-3">
                              イベント編集
                          </x-primary-button>
                        @endif
                      </div>
                    </section>
                 </form>
            </div>
        </div>
      </div>
      <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                  @if(!$users->isEmpty())
                   <div class="text-center py-2">参加状況</div>
                     <table class="table-auto w-full text-left whitespace-no-wrap">
                        <thead>
                          <tr>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">参加者名</th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">参加人数</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($participants as $participant)
                          @if(is_null($participant['canceled_at']))
                          <tr>
                            <td class="px-4 py-3"> {{ $participant['name'] }}</td>
                            <td class="px-4 py-3"> {{ $participant['number_of_people'] }}</td>
                          </tr>
                           @endif
                           @endforeach
                        </tbody>
                     </table>
                  @endif
                </div>
            </div>
        </div>
      </div>
</x-app-layout>