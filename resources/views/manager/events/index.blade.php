<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            イベント管理
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <section class="text-gray-600 body-font">
                      <div class="container px-5 py-4 mx-auto">
                        <!-- Session Status -->
                        <x-success-session-status class="mb-4" :status="session('status')" />
                        <button onclick="location.href='{{ route('events.create') }}'" class="flex ml-auto mb-4 text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">イベント作成</button>
                        <div class="w-full mx-auto overflow-auto">
                          <table class="table-auto w-full text-left whitespace-no-wrap">
                            <thead>
                              <tr>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">イベント名</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">開始日時</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">終了日時</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">参加人数</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">定員</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">表示・非表示</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($events as $event)           
                              <tr>
                                <td class="px-4 py-3">{{ $event->title }}</td>              
                                <td class="px-4 py-3">{{ $event->start_at }}</td>              
                                <td class="px-4 py-3">{{ $event->end_at }}</td>              
                                <td class="px-4 py-3">後程</td>              
                                <td class="px-4 py-3">{{ $event->max_people }}</td>          
                                <td class="px-4 py-3">{{ $event->is_public }}</td>          
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                          {{ $events->links() }}
                        </div>
                       >
                      </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>