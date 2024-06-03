@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => '  w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 h-96 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out']) !!}>{{ $slot }}