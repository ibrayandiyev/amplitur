@php
    $countries = $countries->sortBy(function($country) {
        return iconv('UTF-8', 'ASCII//TRANSLIT', $country->name);
    });
@endphp

@foreach ($countries as $country)
    <option value="{{ $country->iso2 }}" @if (isset($selectedValue) && $selectedValue == $country->iso2) selected @endif>{{ $country->name }}</option>
@endforeach