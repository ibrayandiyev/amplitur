


<input type='hidden' name='quick[{{ $longtripAccommodationHotel->id }}][id]' value='{{ $longtripAccommodationHotel->id }}' />
<input type='hidden' name='quick[{{ $longtripAccommodationHotel->id }}][hotel_id]' value='{{ $longtripAccommodationHotel->hotel_id }}' />
<input type='hidden' name='quick[{{ $longtripAccommodationHotel->id }}][name]' value='{{ ($longtripAccommodationHotel->hotel != null)?$longtripAccommodationHotel->hotel->name:null }}' />
<input type='hidden' name='quick[{{ $longtripAccommodationHotel->id }}][longtrip_accommodation_type_id]' value='{{ $longtripAccommodation->type->id  }}' />
<select name="quick[{{ $longtripAccommodationHotel->id }}][select_hotel_id]" {{$disabled}}
    class="select2 m-b-10 longtrip_accommodation_hotel_id"
    target="quick[{{ $longtripAccommodationHotel->id }}][hotel_id]"
    style="width: 100%">
    @php
        $hotel_id = (old('quick.'. $longtripAccommodationHotel->id .'.select_hotel_id'))?old('quick.'. $longtripAccommodationHotel->id .'.select_hotel_id'):$longtripAccommodationHotel->hotel_id;
  @endphp
    @if(!$longtripAccommodationHotel->hotel)
        <option>{{ __('resources.longtrip-accommodations.model.please_choose_hotel') }}
    @endif
    @foreach ($hotels as $hotel)
    @php
        $hotelCity = "-";
        if($hotel->address->city()){
            $hotelCity = $hotel->address->city()->name;
        }
    @endphp
        <option value="{{ $hotel->id }}" @if($hotel_id == $hotel->id) selected="selected" @endif>{{ $hotel->name }} - {{ $hotelCity }} - {{ $hotel->address->country }} </option>
    @endforeach
</select>

@push('scripts')
<script>
$('.longtrip_accommodation_hotel_id').on('select2:select', function (e) {
    var data = e.params.data;
    var name = (e.target.attributes.target.value);
    $("input[name='"+name+"']").val(data.id);
});
</script>
@endpush
