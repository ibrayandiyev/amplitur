@extends('backend.template.auth')
@extends('backend.auth.header')

@section('content')
<form id="loginform" class="form-horizontal" method="POST" action="{{ route('backend.authenticate') }}" autocomplete="off">
    @csrf

    <h3 class="box-title m-b-20">Login</h3>
    @if(session('success'))
    <div class="alert alert-success">
        <h4 class="text-success"><i class="fa fa-check-circle"></i> {{ __('auth.success')  }}</h4>
        {{ session('success') }}
    </div>
    @endif
    @if(session('warning'))
    <div class="alert alert-warning">
        <h4 class="text-warning"><i class="fa fa-check-circle"></i> {{ __('auth.warning')  }}</h4>
        {{ session('warning') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">
        <h4 class="text-danger"><i class="fa fa-check-circle"></i> {{ __('auth.error')  }}</h4>
        {{ session('error') }}
    </div>
    @endif
    @if(session('info'))
    <div class="alert alert-info">
        <h4 class="text-info"><i class="fa fa-check-circle"></i> {{ __('auth.info')  }}</h4>
        {{ session('info') }}
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <h4 class="text-danger"><i class="fa fa-check-circle"></i> {{ __('auth.error')  }}</h4>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="form-group m-b-10">
        <div class="col-xs-12">
            <input class="form-control" type="text" name="username" value="{{ old('username') }}" placeholder="Usuário" required />
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12">
            <input class="form-control" type="password" name="password" placeholder="Senha" required />
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <div class="checkbox checkbox-info pull-left p-t-0">
                <input id="checkbox-signup" type="checkbox" name="remember" class="filled-in chk-col-light-blue">
                <label for="checkbox-signup"> {{ __('auth.remember')  }}</label>
            </div>
        </div>
    </div>
    <div class="form-group text-center">
        <div class="col-xs-12 p-b-20">
            <button class="btn btn-block btn-lg btn-primary btn-rounded" type="submit">{{ __('auth.access')  }}</button>
        </div>
        <a href="#" id="to-recover" class="text-dark text-center">
            <i class="fa fa-lock m-r-5"></i> {{ __('auth.forget-password')  }}
        </a>
    </div>
</form>
<form id="recoverform" class="form-horizontal" method="POST" action="{{ route('backend.password.email') }}" autocomplete="off">
    @csrf

    <div class="form-group ">
        <div class="col-xs-12">
            <h3>{{ __('auth.password-recovery')  }}</h3>
            <p>{{ __('auth.descriptions.password-recovery')  }}</p>
        </div>
    </div>
    <div class="form-group ">
        <div class="col-xs-12">
            <input class="form-control" type="text" name="email" placeholder="Email" required />
        </div>
    </div>
    <div class="form-group text-center m-t-20">
        <div class="col-xs-12 p-b-20">
            <button class="btn btn-primary btn-lg btn-block btn-rounded waves-effect waves-light" type="submit">{{ __('auth.recover') }}</button>
        </div>
        <a href="#" id="to-login" class="text-dark text-center">
            <i class="fa fa-arrow-left m-r-5"></i> {{ __('auth.back')  }}
        </a>
    </div>
</form>

        <div class="form-group text-center">
            <a href="#" id="to-recover" class="text-dark text-center">
                <i class="fa m-r-5"></i> {{ __('auth.descriptions.ainda_nao_cadastro')  }}
            </a>
            <div class="col-xs-12 p-b-20">
                <button class="btn btn-block btn-primary" onclick="window.location.href='./providers/cadastro';">
                    {{ __('auth.descriptions.cadastro_titulo')  }}
                </button>
            </div>
        </div>

@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(() => {
        $(".preloader").fadeOut();
    });

    $('#to-recover').on('click', function() {
        $('#loginform').slideUp();
        $('#recoverform').fadeIn();
    });

    $('#to-login').on("click", function () {
        $('#loginform').slideDown();
        $('#recoverform').fadeOut('fast');
    });

    $('form').on('submit', function (e) {
        $(".preloader").fadeIn();
        $('.btn').prop('disabled', true);
        this.submit();
    });
</script>
@endpush

