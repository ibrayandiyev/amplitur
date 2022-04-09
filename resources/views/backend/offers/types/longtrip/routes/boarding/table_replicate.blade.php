@php
    $longtripBoardingLocations = $longtripRoute->longtripBoardingLocations;
@endphp

@if (count($longtripBoardingLocations) > 0)
<div class="block-{{$longtripRoute->id}}">
    <label>{{ __('resources.offers.model.block')}}</label>

    <table class="table table-bordered table-striped table-hover ">
        <thead>
            <th width="30%">{{ __('resources.longtrip-routes-boarding.model.address') }}</th>
            <th width="15%" class="text-center">{{ __('resources.longtrip-routes-boarding.model.boarding_at') }}</th>
            <th width="15%" class="text-center">{{ __('resources.longtrip-routes-boarding.model.travel_time') }}</th>
            <th width="10%">{{ __('resources.longtrip-routes-boarding.model.net_sale') }}</th>
            <th width="10%" class="text-center">
                <button class="btn btn-danger btn-sm delete-line" target='block-{{$longtripRoute->id}}' data-toggle="tooltip" data-placement="top" title="{{ __('messages.del_route') }}">
                    <i class="fa fa-trash"></i>
                </button>
                <input type="hidden" class="form-control" required name="route[{{$longtripRoute->id}}]" value="{{$longtripRoute->id}}" />
            </th>
        </thead>
        <tbody>
            @foreach ($longtripBoardingLocations as $boardingLocation)
            @php
                $address = $boardingLocation->address;
            @endphp
            <tr  class="line-{{$boardingLocation->id}}">
                <td>{{ city($address->city) }} - {{ state($address->country, $address->country) }} - {{ country($address->country) }}
                ({{$boardingLocation->boarding_at->format("H:i")}}) <BR>
                {{ $boardingLocation->boardingAtLabel }} , {{ $boardingLocation->boardingAtTimeLabel }}
                </td>

            </td>
                <td class="text-center">
                    <input type="text" class="form-control datetimepicker" required name="boarding_at[{{$boardingLocation->id}}]" value="{{ $offer->package->starts_at }}" />
                </td>
                <td class="text-center">
                    <input type="text" class="form-control datetimepicker-ends" required name="ends_at[{{$boardingLocation->id}}]" value="{{ $offer->package->ends_at }}" />
                </td>
                <td>{{ $boardingLocation->extendedPrice }}</td>
                <td class="text-center skip">
                    <button class="btn btn-danger btn-sm delete-line" target='line-{{$boardingLocation->id}}' data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
    <div class="row m-b-10">
        <div class="col-md-12">
           {{ __('resources.longtrip-routes-boarding.empty') }}
        </div>
    </div>
@endif
