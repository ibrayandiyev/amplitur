@extends('email.backoffice.template')

@section('content')

== BACKOFFICE SYSTEM ==

<BR><BR>== REGISTRO DE CLIENTE ==

    @if(App\Enums\PersonType::FISICAL)
        <BR>Tipo: {{ __('frontend.forms.tipo_pf') }}
        <BR>Nome: {{ $client->getNameByType() }}
    @else
        <BR>Tipo: {{ __('frontend.forms.tipo_pj') }}
        <BR>Nome: {{ $client->getNameByType() }}
    @endif

    <BR>E-mail: {{ $client->email }}
    <BR>User Name: {{ $client->username }}

<BR><BR>== DADOS DE REGISTRO ==

    <BR><BR>DATA e HORA = {{ $client->created_at->format("d/m/Y H:i:s")}}
        <BR>IP = {{ ip() }}


@endsection


