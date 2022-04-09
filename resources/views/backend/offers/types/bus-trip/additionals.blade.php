<table id="additionalsTable" class="col-sm-12 display nowrap table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 50%">{{ __('resources.additionals.model.add_available_link') }}</th>
            <th style="width: 50%">{{ __('resources.additionals.model.add_link') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($additionalables as $additionalable)
            <tr class="addtitionalable-form">
                <td colspan=2 class=" align-middle">{{ $additionalable->extendedName }}</td>
            </tr>
            <tr >
                <td colspan=2 class="align-middle">
                    <div class="form-group mb-0">
                        <select id="selection{{$loop->index}}"  class="multi-select form-control" name="additional_id[{{ $additionalable->id }}][]" style="width: 100%" multiple>

                            @php
                            $groupName = "";
                            $printFooter = 0;
                            $_sale_dates = $additionalable->bustripRoute->getSalesDateTimestamp();
                            @endphp
                            @foreach ($additionals as $additional)
                                @php
                                    $_additional_sale_dates = $additional->getSalesDateTimestamp();

                                    if(is_array($additional->type) &&
                                    !in_array($offer->type, $additional->type)){
                                        continue;
                                    }
                                    if(is_array($_additional_sale_dates)
                                    && is_array($_sale_dates)
                                    && !array_intersect($_sale_dates, $_additional_sale_dates)){
                                        continue;
                                    }
                                @endphp
                                @if($groupName != $additional->groupName)
                                    @php
                                        $printFooter    = 1;
                                        $groupName      = $additional->groupName;
                                    @endphp
                                    @if($printFooter)
                                        </optgroup>
                                        @php
                                        $printFooter = 0;
                                        @endphp
                                    @endif
                                    <optgroup label="{{ $additional->groupName }}">

                                @endif

                                <option value="{{ $additional->id }}" data-price="{{ $additional->price }}" @if ($additionalable->additionals->contains('id', $additional->id)) selected @endif>{{ $additional->completeName }}</option>

                            @endforeach
                        </select>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
