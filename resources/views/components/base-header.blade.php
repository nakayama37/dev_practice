 <header class="text-gray-600 body-font">
      <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
          <a href="{{ route('welcome') }}" class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-10 h-10 text-white p-2 bg-indigo-500 rounded-full" viewBox="0 0 24 24">
              <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
          </svg>
          <span class="ml-3 text-xl">イベントアプリ</span>
          </a>
          <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center">
              <a  href="{{ route('search.index') }}" class="mr-5 hover:text-gray-900">イベントを探す</a>
          @if (Route::has('login'))
          @auth
              <a  href="{{ url('/home') }}" class="mr-5 hover:text-gray-900">ホーム</a>
          @else
              <a  href="{{ route('login') }}" class="mr-5 hover:text-gray-900">ログイン</a>
          @if (Route::has('register'))
              <a  href="{{ route('register') }}" class="mr-5 hover:text-gray-900">登録</a>
          @endif
          @endauth
          </nav>
        @endif
      </div>
</header>      