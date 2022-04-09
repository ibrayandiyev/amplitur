@extends('email.backoffice.template')

@section('content')

== CHANGE EVENT ==
 
{{ $provider->email }}
{{ $event->name }}

@endsection


