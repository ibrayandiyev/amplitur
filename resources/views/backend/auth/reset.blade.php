@extends('backend.template.auth')
@extends('backend.auth.header')

@section('content')
<form id="resetform" class="form-horizontal" method="POST" action="{{ route('backend.password.update') }}" autocomplete="off">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}" />
    <input type="hidden" name="email" value="{{ $email }}" />

    <h3 class="box-title m-b-20">{{__('auth.change_pass')}}</h3>
    @if(session('success'))
    <div class="alert alert-success">
        <h4 class="text-success"><i class="fa fa-check-circle"></i>{{__('auth.success')}}</h4>
        {{ session('success') }}
    </div>
    @endif
    @if(session('warning'))
    <div class="alert alert-warning">
        <h4 class="text-warning"><i class="fa fa-check-circle"></i> {{__('auth.warning')}}</h4>
        {{ session('warning') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">
        <h4 class="text-danger"><i class="fa fa-check-circle"></i> {{__('auth.error')}}</h4>
        {{ session('error') }}
    </div>
    @endif
    @if(session('info'))
    <div class="alert alert-info">
        <h4 class="text-info"><i class="fa fa-check-circle"></i> {{__('auth.info')}}</h4>
        {{ session('info') }}
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <h4 class="text-danger"><i class="fa fa-check-circle"></i> {{__('auth.error')}}</h4>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="form-group">
        <div class="col-xs-12">
            <input class="form-control" type="password" name="password" placeholder="Nova senha" required />
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12">
            <input class="form-control" type="password" name="password_confirmation" placeholder="Repetir nova senha" required />
        </div>
    </div>
    <div class="form-group text-center">
        <div class="col-xs-12 p-b-20">
            <button class="btn btn-block btn-lg btn-info btn-rounded" type="submit">{{__('auth.change')}}</button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(() => {
        $(".preloader").fadeOut();
    });
</script>
@endpush
