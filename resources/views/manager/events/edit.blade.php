<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            イベント編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="max-w-2xl mx-auto">

                        <!-- エラーメッセージ -->
                        <x-error-message />

                        <form method="POST" action="{{ route('events.update', ['event' => $event->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div>
                                <x-input-label for="image" value="イベント画像" />
                                <x-text-input id="image" class="block mt-1 w-full" type="file"
                                    accept="image/png, image/jpeg, image/jpg" name="image" />
                            </div>
                            <div class="mt-4">
                                <x-input-label for="categories" value="カテゴリー" />
                                <select id="categories" name="categories[]"
                                    class="block w-full sm:w-2/3 bg-gray-200 py-2 px-3 text-gray-700 border border-gray-200 rounded focus:outline-none focus:bg-white"
                                    multiple>
                                    @foreach ($categories as $category)
                                        <option name="category" value="{{ $category->id }}"
                                            {{ $event->categories->contains($category->id) ? 'selected' : '' }}>
                                            {{ $category->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4">
                                <x-input-label for="title" value="イベント名" />
                                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                    value="{{ $event->title }}" required autofocus />
                            </div>

                            <div class="md:flex justify-between mt-4">
                                <div>
                                    <x-input-label for="event_date" value="イベント日付" />
                                    <x-text-input id="event_date" class="block mt-1 w-full" type="text"
                                        name="event_date" value="{{ $event->eventDate }}" required />
                                </div>

                                <div>
                                    <x-input-label for="start_at" value="開始時間" />
                                    <x-text-input id="start_at" class="block mt-1 w-full" type="text"
                                        name="start_at" value="{{ $event->startTime }}" required />
                                </div>

                                <div>
                                    <x-input-label for="end_at" value="終了時間" />
                                    <x-text-input id="end_at" class="block mt-1 w-full" type="text" name="end_at"
                                        value="{{ $event->endTime }}" required />
                                </div>
                            </div>
                            <div class="md:flex justify-between items-end">
                                <div class="mt-4">
                                    <x-input-label for="max_people" value="定員数" />
                                    <x-text-input id="max_people" class="block mt-1 w-full" type="number"
                                        name="max_people" value="{{ $event->max_people }}" required min="1" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="price" value="価格（円）" />
                                    <x-text-input id="price" class="block mt-1 w-full" type="number" name="price"
                                        value="{{ $event->price }}" min="0" />
                                </div>
                                <div class="flex space-x-4 justify-around">
                                    <input type="radio" name="is_public" value="1"
                                        @if ($event->is_public === 1) { checked } @endif>表示
                                    <input type="radio" name="is_public" value="0"
                                        @if ($event->is_public === 0) { checked } @endif>非表示
                                </div>
                            </div>

                            <x-edit-address-form :event="$event" />

                            <div class="mt-4">
                                <x-input-label for="content" value="イベント詳細" />
                                <x-textarea id="content" class="block mt-1 w-full" type="text"
                                    name="content" />{{ $event->content }}</textarea>
                            </div>
                            <div class="mt-4 flex justify-center">
                                <x-primary-button class="ms-3">
                                    イベント更新
                                </x-primary-button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-auto-address-input-js />
</x-app-layout>
