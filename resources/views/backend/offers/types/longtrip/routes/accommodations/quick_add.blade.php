<div class="row">
    <div class="form-group col-md-3 @if($errors->has('longtrip_accommodation_type_id')) has-danger @endif">
        <label class="form-control-label">
            <strong>{{ __('resources.longtrip-accommodations.model.type') }}</strong>
            <span class="text-danger">*</span>
        </label>
        <select name="longtrip_accommodation_type_id" id="longtrip_accommodation_type_id" class="select2 m-b-10" style="width: 100%">
            @foreach ($types as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label class="form-control-label">
            <strong> </strong><BR>
            <span class="text-danger"></span>
        </label>
        <button type="button" href-longtrip-accommodation="{{ route('backend.providers.companies.offers.longtrip.accommodation-type.storeLongtripAccommodation', [$provider, $company, $offer, $longtripRoute])}}" class="btn btn-rounded btn-block btn-outline-primary save-accommodation form-control">
            <i class="fa fa-plus"></i> {{ __('resources.longtrip-accommodations.model.add_room') }}
        </button>
    </div>
    <div class="form-group col-md-3"><BR><BR>
         <a href="{{ route('backend.hotels.index', ['lt_referer' => $longtripRoute->id])}}" class="btn btn-rounded btn-block btn-outline-primary form-control">
            <i class="fa fa-plus"></i> {{ __('resources.longtrip-accommodations.model.manage_hotels') }}
        </a>
    </div>
</div>

@push('scripts')
<script src="/backend/js/offers.js?v=0.0.1"></script>

@endpush
