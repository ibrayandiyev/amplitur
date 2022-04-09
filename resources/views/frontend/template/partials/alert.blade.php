<div class="largura-site ma">
    @if(session('success'))
        <div class="mensagem mensagem--sucesso" style="margin-top: 20px">
            <h4 class="text-success"><i class="fa fa-check-circle"></i> <strong>{{ __('messages.success')  }}</strong></h4>
            {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="mensagem mensagem--aviso" style="margin-top: 20px">
            <h4 class="text-warning"><i class="fa fa-check-circle"></i> <strong>{{ __('messages.warning')  }}</strong></h4>
            {{ session('warning') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mensagem mensagem--erro" style="margin-top: 20px">
            <h4 class="text-danger"><i class="fa fa-check-circle"></i> <strong>{{ __('messages.error')  }}</strong></h4>
            {!! session('error') !!}
        </div>
    @endif
    @if(session('info'))
        <div class="mensagem mensagem--info" style="margin-top: 20px">
            <h4 class="text-info"><i class="fa fa-check-circle"></i> <strong>{{ __('messages.info')  }}</strong></h4>
            {{ session('info') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mensagem mensagem--erro" style="margin-top: 20px">
            <h4 class="text-danger"><i class="fa fa-check-circle"></i> <strong>{{ __('messages.error')  }}</strong></h4>
            @foreach ($errors->all() as $error)
                {!! $error !!} <br />
            @endforeach
        </div>
    @endif
</div>