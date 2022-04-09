@extends('backend.template.default')
@section('content')
<div class="row page-titles">
    <div class="col-md-8">
        <h3 class="text-themecolor">{{ __('resources.offers.create') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('backend.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.offers.index') }}">{{ __('resources.offers.name-plural') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('backend.providers.companies.offers.edit', [$offer->provider, $offer->company, $offer]) }}">{{ $offer->package->name }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('resources.offers.replicate') }}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="labelx label-service">
                {{ __('resources.offers.info') }}
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <strong><i class="fa fa-info-circle"></i> {{__('messages.info')}}</strong><br />
                            {!!__('messages.select_pack')!!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12" style="display: none;" data-package-empty>
                        <div class="alert alert-primary mt-10">
                            <strong><i class="fa fa-warning"></i> {{__('messages.sorry')}}</strong><br />
                            {{__('messages.event_no_pack')}} <a href="#" class="btn btn-sm btn-primary">{{ __('messages.created_new') }}</a> {{__('messages.before_move')}}
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="form-control-label">
                            <strong>{{ __('resources.events.name') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control select2" name="event_id">
                            @csrf
                        </select>
                    </div>

                    <div class="form-group col-md-12" style="display: none;" data-package-selection>
                        <label class="form-control-label">
                            <strong>{{ __('resources.packages.name') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="package_id">
                            <option value="">{{ __('messages.select') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12  @if($errors->has('currency')) has-danger @endif" style="display: none;" data-package-selection>
                        <label class="form-control-label">
                            <strong>{{ __('resources.bus-trip.model.sales-currency') }}</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" name="currency_id">
                            <option value>{{ __('messages.select') }}</option>
                            <option value="{{ \App\Enums\Currency::REAL }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::REAL) selected @endif>{{ __('resources.financial.currencies.real') }}</option>
                            <option value="{{ \App\Enums\Currency::DOLLAR }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::DOLLAR) selected @endif>{{ __('resources.financial.currencies.dollar') }}</option>
                            <option value="{{ \App\Enums\Currency::EURO }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::EURO) selected @endif>{{ __('resources.financial.currencies.euro') }}</option>
                            <option value="{{ \App\Enums\Currency::LIBRA }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::LIBRA) selected @endif>{{ __('resources.financial.currencies.pound') }}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12" >

                        <form id="replicateForm" action="{{ route('backend.offers.storeReplicate', $offer) }}" method="post">
                            @csrf
                            <input type="hidden" name="package_id" />
                            <input type="hidden" name="provider_id" />
                            <input type="hidden" name="company_id" />
                            <input type="hidden" name="offer_type" value="{{ $offer->type}}" />
                            <input type="hidden" name="currency" value="{{ $offer->currency}}" />

                            <div class="tab-pane" id="additionals" role="tabpanel">

                                <div id="type-{{ $offer->type}}" class="additionals-type">
                                    @switch($offer->type)
                                        @case('bus-trip')
                                            @php
                                            $bustripRoutes = $offer->bustripRoutes
                                            @endphp
                                            @foreach ($bustripRoutes as $route)
                                                @php
                                                    $bustripRoute = $route;
                                                @endphp
                                                @include('backend.offers.types.bus-trip.routes.boarding.table_replicate')
                                            @endforeach
                                            @break;
                                        @case('longtrip')
                                            @php
                                            $longtripRoutes = $offer->longtripRoutes
                                            @endphp
                                            @foreach ($longtripRoutes as $route)
                                                @php
                                                    $longtripRoute = $route;
                                                @endphp
                                                @include('backend.offers.types.longtrip.routes.boarding.table_replicate')
                                            @endforeach
                                            @break;
                                        @case('shuttle')
                                            @php
                                                $shuttleRoutes = $offer->shuttleRoutes
                                            @endphp
                                            @foreach ($shuttleRoutes as $route)
                                                @php
                                                    $shuttleRoute = $route;
                                                @endphp
                                                @include('backend.offers.types.shuttle.routes.boarding.table_replicate')
                                            @endforeach
                                            @break;
                                        @case('additional')
                                            @include('backend.offers.types.additional.table_replicate')
                                            @break;
                                    @endswitch
                                </div>

                            </div>

                            <button type="button" class="btn btn-primary" id="nextStep">
                                <i class="fa fa-arrow-right"></i> {{ __('messages.continue') }}
                            </button>
                            <a href="{{ route('backend.providers.companies.offers.index', [$provider, $company]) }}" class="btn btn-secondary">
                                <i class="fa fa-times-circle-o"></i> {{ __('resources.voltar') }}
                            </a>

                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="/backend/vendors/datetimepicker/jquery.datetimepicker.min.css" />
@endpush

@push('scripts')
<script src="/backend/vendors/datetimepicker/jquery.datetimepicker.full.min.js"></script>
<script src="/backend/vendors/moment/moment.js"></script>
<script src="/backend/vendors/moment/min/moment-timezone.min.js"></script>
<script type="text/javascript">
    var validateMaxDate = '2100-08-01';
    var validateMinDate = '2020-08-01';
    window.addEventListener( "pageshow", function ( event ) {
        var historyTraversal = event.persisted ||
                                ( window.performance.getEntriesByType("navigation")[0].type === "back_forward" );
        if ( historyTraversal ) {
            // Handle page restore.
            window.location.reload();
        }
    });
    $(document).ready(function () {
        let eventSelect = $('[name="event_id"]');
        let packageSelect = $('[name="package_id"]');
        let currencySelect = $('[name="currency_id"]');
        let currencyInput = $('[name="currency"]');
        let nextStepButton = $('#nextStep');
        let route = "{{ route('backend.offers.replicate', [$provider, $company]) }}";
        let divPackageSelection = $('[data-package-selection]');
        let divPackageEmpty = $('[data-package-empty]');
        let preloader = $('.preloader');
        let replicateForm = $('#replicateForm');
        let company = '{{ $company->id }}';
        let provider = '{{ $provider->id }}';
        let currency = '{{ $offer->currency }}';

        eventSelect.change(function () {
            let eventId = eventSelect.val();
            preloader.show();
            divPackageSelection.hide();
            divPackageEmpty.hide();

            packageSelect.find('option').remove();
            packageSelect.append('<option value="">{{ __('messages.select') }}</option>');

            $.ajax({
                url: '/api/events/' + eventId + '/packages',
            }).done((response) => {

                if (response.length == 0) {
                    divPackageSelection.hide();
                    divPackageEmpty.show();
                    return;
                }

                response.map((package) => {
                    var duration    = "";
                    var startDate   = package.starts_at;
                    var endDate     = package.ends_at;
                    if(package.event.category.flags.DURATION != undefined){
                        duration = package.event.category.flags.DURATION
                    }
                    packageSelect.append('<option value="' + package.id + '" startDate="'+startDate+'" endDate="'+endDate+'" duration="'+duration+'">' + package.extendedName + '</option>');
                });

                divPackageSelection.show();
                divPackageEmpty.hide();
            }).always(() => {
                preloader.hide();
            });

            changeCreateUrl(eventId);
        });

        currencySelect.change(function () {
            currency = currencySelect.find("option:selected").val();
            currencyInput.val(currency);
        });

        nextStepButton.click(function(){
            let hasError = 0;
            $('.datetimepicker,.datetimepicker-ends').each(function (index, elem){
                $(this).parent().removeClass("has-danger");
                if(elem.value == ""){
                    $(this).parent().addClass("has-danger");
                    hasError = 1;
                }
            });
            if(!hasError){
                replicateForm.submit();
                return true;
            }
            return false;
        });

        packageSelect.change(function () {
            var valSelected = packageSelect.find('option:selected').val();
            changeCreateUrl(eventSelect.val(), valSelected);
            $("#type-longtrip, #type-bus-trip, #type-shuttle, #type-additional").css("display","none");
            if(valSelected == ""){
                return;
            }
            var elementType = $("input[name=offer_type");
            var subDay      = undefined;
            var addEndDay   = 0;
            var startDate   = packageSelect.find('option:selected').attr("startDate");
            var endDate     = packageSelect.find('option:selected').attr("endDate");
            var maxDate     = startDate;
            var duration    = packageSelect.find('option:selected').attr("duration");
            let date        = "2017-02-01 15:20:00.00";
            let pattern     = "YYYY-MM-DD";
            var startAt     = moment(startDate); // July 23rd 2021, 3:13:34 pm
            $("#type-longtrip").css("display","");
            $("#type-bus-trip").css("display","");
            $("#type-shuttle").css("display","");
            switch(elementType.val()){
                case "additional":
                    $("#type-additional").css("display","");
                    break;
                case "longtrip":
                    /**
                     * Longtrip Rules:
                     * Starts At - need to be two days before the event inclusive the start date
                     * Ends at- need to be two days after the last day of event.
                     */
                    subDay      = 1;
                    addEndDay   = 2;
                    var endAt   = moment(maxDate, pattern);
                    refreshDatetimePick(startAt.subtract(subDay, 'days').format('YYYY-MM-DD')+" 00:00:00", endAt.format('YYYY-MM-DD')+" 23:59:59");
                    maxDate     = endDate;
                    endAt       = moment(maxDate, pattern);
                    // For LP
                    refreshDatetimePickExclusive(startAt.format('YYYY-MM-DD')+" 00:00:00", endAt.add(addEndDay, 'days').format('YYYY-MM-DD')+" 23:59:59");
                    break;
                case "bus-trip":
                    if(subDay == undefined){
                        subDay = 2;
                    }
                    if(duration != "one-day"){
                        maxDate = endDate;
                    }
                case "shuttle":
                    if(subDay == undefined){
                        subDay = 0;
                        // Rule for shuttle only
                        $('.datetimepicker').each(function (index, elem){
                            elem.value = moment(startDate).format('DD/MM/YYYY, HH:mm');
                        });
                    }
                    var endAt       = moment(maxDate, pattern); // July 23rd 2021, 3:13:34 pm
                    refreshDatetimePick(startAt.subtract(subDay, 'days').format('YYYY-MM-DD')+" 00:00:00", endAt.format('YYYY-MM-DD')+" 23:59:59");
                    break;
            }

        });

        function changeCreateUrl(event, package) {
            replicateForm.find('input[name="package_id"]').val(package);
            replicateForm.find('input[name="provider_id"]').val(provider);
            replicateForm.find('input[name="company_id"]').val(company);
            replicateForm.find('input[name="currency_id"]').val(currency);

            if (event && package) {
                nextStepButton.show();
            } else {
                nextStepButton.hide();
            }
        }

        /**
         * This is used for common offers
         */
        function refreshDatetimePick(packageMinDate, packageMaxDate){

            $('.datetimepicker').datetimepicker('destroy');
            if(packageMaxDate != undefined){
                validateDatetimePick(packageMinDate, packageMaxDate)
                validateMaxDate = packageMaxDate;
                validateMinDate = packageMinDate;
            }
            $('.datetimepicker').datetimepicker({
                format: 'd/m/Y, H:i',
                mask: true,
                maxDate: validateMaxDate,
                minDate: validateMinDate,
                closeOnDateSelect:true,
                validateOnBlur: true

            }).on('blur', function(ct,$i){
                validateDatetimePick(validateMinDate, validateMaxDate);
            });
        }
        /**
         * This is used for longtrip exclusive rules
         */
        function refreshDatetimePickExclusive(packageMinDate, packageMaxDate){

            $('.datetimepicker-ends').datetimepicker('destroy');
            if(packageMaxDate != undefined){
                validateDatetimePick(packageMinDate, packageMaxDate)
                validateMaxDate = packageMaxDate;
                validateMinDate = packageMinDate;
            }
            $('.datetimepicker-ends').datetimepicker({
                format: 'd/m/Y, H:i',
                mask: true,
                maxDate: validateMaxDate,
                minDate: validateMinDate,
                closeOnDateSelect:true,
                validateOnBlur: true

            }).on('blur', function(ct,$i){
                validateDatetimePick(validateMinDate, validateMaxDate);
            });
        }

        function validateDatetimePick(packageMinDate, packageMaxDate){

            var calcMinDate = moment(packageMinDate, "YYYY-MM-DD hh:mm");
            var calcMaxDate = moment(packageMaxDate, "YYYY-MM-DD hh:mm");
            $('.datetimepicker,.datetimepicker-ends').each(function (index, elem){
                var fieldDate = moment(elem.value,"DD/MM/YYYY, hh:mm"); // July 23rd 2021, 3:13:34 pm
                //console.log(fieldDate.format("YYYY-MM-DD hh:mm") + "   -    "+ packageMinDate);
                //console.log("Min:"+ fieldDate.diff(calcMinDate, "minutes"));
                //console.log("Max:"+ fieldDate.diff(calcMaxDate, "minutes"));
                if(fieldDate.diff(calcMinDate, "minutes") <0){
                    elem.value = "";
                };
                if(fieldDate.diff(calcMaxDate, "minutes") >0){
                    elem.value = "";
                };
                if(isNaN(fieldDate.diff(calcMaxDate, "minutes"))){
                    elem.value = "";
                }
            });
        }

        function additionalStart(){
            $(".additionals-type").css("display","none");
        }

        nextStepButton.hide();
        refreshDatetimePick();
        additionalStart();

    });
</script>
@include('backend.template.scripts.select-events')
@endpush
