@props(['errors'])
 
@if ($errors->any())
    <div class="text-sm text-red-600 space-y-1 py-4">
      <span class="text-lg pt-2">問題が発生しました</span>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif