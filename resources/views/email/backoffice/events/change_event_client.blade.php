@extends('email.backoffice.template')

@section('content')

== CHANGE EVENT - CLIENT ==
 
{{ $client->email }}
{{ $event->name }}

@endsection


