<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            イベント作成
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                  <div class="max-w-2xl mx-auto">

                  <!-- エラーメッセージ -->
                   <x-error-message/>

                    <form method="POST" action="{{ route('events.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="title" value="イベント名" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="content" value="イベント詳細" />
                            <x-textarea id="content" class="block mt-1 w-full" type="text" name="content" :value="old('content')" />
                          
                        </div>

                        <div class="md:flex justify-between mt-4">
                          <div>
                            <x-input-label for="event_date" value="イベント日付" />
                            <x-text-input id="event_date" class="block mt-1 w-full" type="text" name="event_date" :value="old('event_date')" required/>
                          </div>
                              
                          <div>
                                <x-input-label for="start_at" value="開始時間" />
                                <x-text-input id="start_at" class="block mt-1 w-full" type="text" name="start_at" :value="old('start_at')" required />
                          </div>
                          
                          <div>
                            <x-input-label for="end_at" value="終了時間" />
                            <x-text-input id="end_at" class="block mt-1 w-full" type="text" name="end_at" :value="old('end_at')" required />
                          </div>
                        </div>
                        <div class="md:flex justify-between items-end">
                          <div class="mt-4">
                            <x-input-label for="max_people" value="定員数" />
                            <x-text-input id="max_people" class="block mt-1 w-full" type="number" name="max_people" required />
                          </div>
                          <div class="flex space-x-4 justify-around">
                            <input type="radio" name="is_public" value="1" checked >表示
                            <input type="radio" name="is_public" value="0" checked >非表示
                          </div>
                            <x-primary-button class="ms-3">
                                イベント作成
                            </x-primary-button>
                        </div>
                      </form>
                    </div>
                </div>
            </div>
        </div>
      </div>
</x-app-layout>