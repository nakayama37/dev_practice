@props(['message'])
 
@if ($message)
    <div class="my-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">エラー: </strong>
        <span class="block sm:inline">{{ $message }}</span>
    </div>
@endif