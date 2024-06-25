<div id="address-form">
  <div class="md:flex justify-between mt-4">
    <div>
      <div id="loading-message" class="text-gray-500"></div>
      <x-input-label for="postcode" value="郵便番号" />
      <x-text-input type="text" id="postcode" name="postcode" class="block mt-2" value="{{ $event->location->postcode }}" required autofocus />
    </div>
    <div>
      <x-input-label for="prefecture" value="都道府県" />
      <x-text-input id="prefecture" class="block mt-1" type="text" name="prefecture" value="{{ $event->location->prefecture }}" required readonly/>
    </div>
    <div>
      <x-input-label for="city" value="市町村区" />
      <x-text-input id="city" class="block mt-1" type="text" name="city" value="{{ $event->location->city }}" required readonly/>
    </div>
  </div>
  <div class="mt-4">
    <x-input-label for="street" value="以降の住所" />
    <x-text-input id="street" class="block mt-1 w-full" type="text" name="street" value="{{ $event->location->street }}" required autofocus/>
  </div>
  <div class="mt-4">
    <x-input-label for="venue" value="開催場所名称" />
    <x-text-input id="venue" class="block mt-1" type="text" name="venue" value="{{ $event->location->venue }}" required autofocus/>
  </div>
</div>