<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            カテゴリー一覧
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
                        <div class="flex justify-between">
                          <button onclick="location.href='{{ route('categories.create') }}'" class="flex ml-auto mb-4 text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">カテゴリー作成</button>
                        </div>

                        <div class="w-full mx-auto overflow-auto">
                          <table class="table-auto w-full text-left whitespace-no-wrap">
                            <thead>
                              <tr>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">カテゴリー名</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">公開・非公開</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($categories as $category)           
                              <tr>
                                <td class="text-blue-500 px-4 py-3">
                                  <a href="{{ route('categories.edit', [ 'category' => $category->id ]) }}">{{ $category->name }}</a>
                                </td>  
                                <td class="text-blue-500 px-4 py-3">
                                   @if($category->is_public)
                                    <span class="text-green-500">表示中</span>
                                   @else
                                    <span class="text-red-500">非表示</span>
                                   @endif
                                </td>  
                                <form  method="post" action="{{ route('categories.public.toggle', ['category' => $category->id ]) }}">   
                                  @csrf
                                  @method('put')
                                @if($category->is_public)                
                                <td class="text-blue-500 px-4 py-3">
                                   <x-primary-button class="ms-3">
                                      非公開にする
                                   </x-primary-button>
                                </td>  
                                @else
                                <td class="text-blue-500 px-4 py-3">
                                   <x-primary-button class="ms-3">
                                      公開にする
                                  </x-primary-button>
                                </td>     
                                @endif       
                                </form>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                          {{ $categories->links() }}
                        </div>
                      </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>