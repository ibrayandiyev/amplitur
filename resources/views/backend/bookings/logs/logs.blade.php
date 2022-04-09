<div class="tab-pane {{ $activeLogs }}" id="logs" role="tabpanel">

    <div class="row">
        @if (user()->canManageBookingDetails())
            <div class="col-md-12 mb-20">
                <a href="{{ route('backend.bookings.bookingNotification', $booking) }}?type=client" class="btn btn-primary btn-sm">
                    <i class="fa fa-user"></i>
                    {{ __('messages.send-customer-information') }}
                </a>
                <a href="{{ route('backend.bookings.bookingNotification', $booking) }}?type=provider" class="btn btn-primary btn-sm">
                    <i class="fa fa-building"></i>
                    {{ __('messages.send-provider-information') }}
                </a>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="form-group col-md-7">
            <label>
                <strong>{{ __('resources.logs.message') }}</strong>
            </label>
            <input type="text" name="log[message]" class="form-control" />
        </div>
        <div class="form-group col-md-3">
            <label>
                <strong>{{ __('resources.logs.level') }}</strong>
            </label>
            <select name="log[level]" class="form-control">
                @if (user()->canDeleteBookingLog())
                <option value="1">{{ __('resources.logs.levels.1') }}</option>
                <option value="2">{{ __('resources.logs.levels.2') }}</option>
                <option value="16">{{ __('resources.logs.levels.16') }}</option>
                @endif
                <option value="4">{{ __('resources.logs.levels.4') }}</option>
            </select>
        </div>
        <div class="form-group col-md-2 mt-30 align-middle">
            <button type="button" class="btn btn-block btn-sm btn-primary" style="padding: 8px 0px;" id="submitLogButton">
                <i class="fa fa-plus-circle"></i>
                {{ __('resources.logs.create') }}
            </button>
        </div>
    </div>

    <table class="table full-color-table full-primary-table hover-table">
        <thead>
            <th width="5%"></th>
            <th width="20%"></th>
            <th class="align-middle text-center">{{__('resources.hist_msg')}}</th>
        </thead>
        <tbody>
            @foreach ($bookingLogs as $key => $bookingLog)
                <tr>
                    <td class="align-middle text-center">
                        @if (user()->canDeleteBookingLog())
                            <div class="form-group mt-5">
                                <input type="checkbox" class="deleteLogs" name="deleteLogs[{{$key}}]" value="{{ $bookingLog->id }}" style="padding: 10px;" />
                            </div>
                        @endif
                    </td>
                    <td>
                            {{ $bookingLog->createdAtLabel }} <i class="fa fa-clock-o"></i> {{ $bookingLog->createdAtTimeLabel }} <br />
                            {!! $bookingLog->originLabel !!}
                            {!! $bookingLog->levelLabel !!}
                            @if ($bookingLog->ip && user()->canSeeIpAddresses())
                        {{ $bookingLog->ip }}
                            @endif

                    </td>
                    <td>
                        <p>{!! $bookingLog->message !!}</p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <button type="button" class="btn btn-sm btn-danger mt-10" onclick="deleteLogs()">
        <i class="fa fa-trash"></i> {{ __('resources.excluir_select') }}
    </button>
</div>
