<x-app-layout>
  <!-- Session Status -->
  <x-success-session-status class="mb-4" :status="session('status')" />
    <div class="py-16 text-center">
      <h2 class="text-2xl font-semibold text-gray-800">今月の話題のイベント</h2>
    </div>  
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="overflow-x-scroll scrollbar-hide mb-4 relative px-0.5" style="overflow-y: hidden;">
      <div class="flex snap-x snap-mandatory gap-4" style="width: max-content;"> 
        @foreach ($events as $event) 
        <div class="cursor-pointer flex-none w-64 snap-center post" onclick="location.href='{{ route('reservations.detail', [ 'event' => $event->id ]) }}'" >
          <div class="bg-white border-1 border border-gray-200 rounded-lg overflow-hidden mb-4">
            @if(is_null($event->image))   
            <img src="{{ asset('storage/events/No_Image.png') }}" alt="" class="w-full h-40 object-cover">
            @else  
            <img src="{{ asset('storage/events/' . $event->image) }}" alt="" class="w-full h-40 object-cover">
            @endif   
            <div class="p-4">
              <h3 class="text-lg leading-6 font-bold text-gray-900">{{ $event->title }}</h3>
              <h4 class="text-sm leading-6 text-gray-900">{{ $event->event_date }}</h4>
              <div class="flex justify-between items-center mt-4">
                <span class="text-2xl font-extrabold text-gray-900">1000円</span>
                <span class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
                    <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                    </svg>1.2K
                </span>
                <span class="text-gray-400 inline-flex items-center leading-none text-sm">
                    <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                    </svg>6
                </span>
              </div>
            </div>
          </div>
        </div>
        @endforeach 
        </div>
      </div>
    </div> 
	 @foreach ($categories as $category)  
    <div class="my-12 text-center">
      <h2 class="text-2xl font-semibold text-gray-800">{{ $category->name }}</h2>
    </div>
    <div class="pb-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-x-scroll scrollbar-hide mb-4 relative px-0.5" style="overflow-y: hidden;">
	      <div class="flex snap-x snap-mandatory gap-4" style="width: max-content;">

		   @foreach ($category->events as $event) 
			<div class="cursor-pointer flex-none w-64 snap-center" onclick="location.href='{{ route('reservations.detail', [ 'event' => $event->id ]) }}'" >
				<div class="bg-white border-1 border border-gray-200 rounded-lg overflow-hidden mb-4">
          @if(is_null($event->image))   
				  <img src="{{ asset('storage/events/No_Image.png') }}" alt="" class="w-full h-40 object-cover">
          @else  
				  <img src="{{ asset('storage/events/' . $event->image) }}" alt="" class="w-full h-40 object-cover">
          @endif   
					<div class="p-4">
						<h3 class="text-lg leading-6 font-bold text-gray-900">{{ $event->title }}</h3>
						<h4 class="text-sm leading-6 text-gray-900">{{ $event->event_date }}</h4>
						<div class="flex justify-between items-center mt-4">
							<span class="text-2xl font-extrabold text-gray-900">1000円</span>
							<span class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
                  <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                  </svg>1.2K
              </span>
              <span class="text-gray-400 inline-flex items-center leading-none text-sm">
                  <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                  </svg>6
              </span>
						</div>
					</div>
				</div>
			</div>
		  @endforeach
      
	    </div>
      </div>
     </div>
    </div>
  @endforeach
</x-app-layout>