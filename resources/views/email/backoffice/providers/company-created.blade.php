@extends('email.providers.template')

@section('content')

== BACKOFFICE SYSTEM ==

<BR><BR>== CRIAÇÃO DE EMPRESA ==

    <BR><BR>Nome do Provider: {{ $company->provider->name}}
        <BR>Razão Social: {{ $company->legal_name}}
        <BR>Nome Fantasia: {{ $company->company_name}}
                    @php
                    $city = $address->city();
                    if(is_object($city)){
                        $city = $city->name;
                    }
                    @endphp
        <BR>Endereço:{{ $address->address}} - {{ $address->number}}, {{$city}} ,{{ $address->country()->name}}

<BR><BR>== DADOS DE REGISTRO ==

    <BR><BR>DATA e HORA ={{ $company->created_at->format("d/m/Y H:i:s")}}
        <BR>IP = {{ ip() }}

@endsection
