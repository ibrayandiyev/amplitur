<div id="importEventModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="importEventForm" action="{{ route('backend.events.import') }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">{{ __('messages.import') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <p class="mb-0"><b>{{ __('messages.warning') }}</b></p>
                        {{ __('resources.events.hints.import') }}
                    </div>
                    <div class="form-group">
                        <label>{{ __('resources.events.hints.import-file-label') }}</label>
                        <input type="file" name="events_file" class="form-control" aria-describedby="importFileHelp" required>
                        <span class="help-block">
                            <small>{{ __('resources.events.hints.import-file-types') }}</small>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect btn-sm save">{{ __('messages.import') }}</button>
                    <button type="button" class="btn btn-secondary waves-effect btn-sm" data-dismiss="modal">{{ __('messages.close') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>