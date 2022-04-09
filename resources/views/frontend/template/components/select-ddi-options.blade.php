@php
    $countries = $countries->sortBy(function($country) {
        return iconv('UTF-8', 'ASCII//TRANSLIT', $country->name);
    });
@endphp

@foreach ($countries as $country)
    <option value="{{ $country->phonecode }}" @if (isset($selectedValue) && $selectedValue == $country->phonecode) selected @endif>{{ $country->name }} {{ $country->phonecode }}</option>
@endforeach