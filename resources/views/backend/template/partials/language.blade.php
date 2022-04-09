@php
    $language = app()->getLocale();
@endphp

@if ($language == 'pt-br')
<a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="flag-icon flag-icon-br"></i>
</a>
@elseif ($language == 'en')
<a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="flag-icon flag-icon-gb"></i>
</a>
@elseif ($language == 'es')
<a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="flag-icon flag-icon-es"></i>
</a>
@endif

<div class="dropdown-menu dropdown-menu-right">
    @if ($language != 'pt-br')
    <a class="dropdown-item" href="{{ route('backend.language.change', 'pt-br') }}" data-language-change>
        <i class="flag-icon flag-icon-br"></i> Portugês
    </a>
    @endif
    @if ($language != 'en')
    <a class="dropdown-item" href="{{ route('backend.language.change', 'en') }}" data-language-change>
        <i class="flag-icon flag-icon-gb"></i> English
    </a>
    @endif
    @if ($language != 'es')
    <a class="dropdown-item" href="{{ route('backend.language.change', 'es') }}" data-language-change>
        <i class="flag-icon flag-icon-es"></i> Español
    </a>
    @endif
</div>