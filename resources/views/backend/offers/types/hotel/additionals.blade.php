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
                <td class="">{{ $additionalable->typeLabel }}</td>
            </tr>
            <tr>
                <td colspan=2 class="align-middle">
                    <div class="form-group mb-0">
                        <select id="selection{{$loop->index}}"  class="multi-select  form-control" name="additional_id[{{ $additionalable->id }}][]" style="width: 100%" multiple {{ $readonly }}>
                            <option value=""></option>
                            @php
                            $groupName = "";
                            $printFooter = 0;
                            @endphp
                            @foreach ($additionals as $additional)
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
