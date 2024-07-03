<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            カテゴリー編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="max-w-2xl mx-auto">

                        <!-- エラーメッセージ -->
                        <x-error-message />

                        <form method="POST" action="{{ route('categories.update', ['category' => $category->id]) }}">
                            @csrf
                            {{-- @method('put') --}}
                            <div class="md:flex justify-between items-end">
                                <div>
                                    <x-input-label for="name" value="カテゴリー名" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                        value="{{ $category->name }}" required autofocus />
                                </div>
                                <x-primary-button class="ms-3">
                                    カテゴリー更新
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
