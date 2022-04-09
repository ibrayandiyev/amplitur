@extends('email.backoffice.template')

@section('content')

== BACKOFFICE SYSTEM ==

<BR><BR>== CADASTRO DE PROVIDER ==

    <BR><BR>PROVIDER: {{ $provider->name}}
        <BR>E-MAIL: {{ $provider->email}}
        <BR>LOGIN: {{ $provider->username}}

<BR><BR>== DADOS DE REGISTRO ==

    <BR><BR>DATA e HORA = {{ $provider->created_at->format("d/m/Y H:i:s")}}
        <BR>IP = {{ ip() }}

@endsection
