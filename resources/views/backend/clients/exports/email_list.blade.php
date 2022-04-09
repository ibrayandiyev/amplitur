email;primeiro nome;nome;data_nascimento;cidade;estado;pais;idioma;cadastro;<br/>

@foreach($clients as $client)
@php
    $address    = $client->address;
    $city       = ($address) ? $address->city() : null;
    $state      = ($address) ? $address->state() : null;
    $country    = ($address) ? $address->country() : null;
    $name       = ($client->type=="fisical")?name($client):$client->company_name;
    $_name      = explode(" ", $client->name);
    $birthdate  = ($client->birthdate != null)?$client->birthdate->format("d/m/Y"):null;
@endphp
{{ $client->email }};{{ isset($_name[0])?$_name[0]:null}};{{$name}};{{ $birthdate }};{{ city($city) }};{{ state($country, $state) }};{{ country($country) }};{{ App\Enums\Language::getLabel($client->language) }};{{ $client->createdAtLabel }};<br/>
@endforeach
