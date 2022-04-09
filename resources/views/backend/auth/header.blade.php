<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <div>
            <a href="{{ route('backend.index') }}">
                <b>
                    <img src="/frontend/images/estrutura/amp-travel-front-bgblue.png" alt="homepage" style="padding: 0.3em" />
                </b>
            </a>
        </div>
        <div class="navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item hidden-sm-down"><span></span></li>
            </ul>
            <ul class="navbar-nav my-lg-0">
                <li class="nav-item dropdown">
                    @include('backend.template.partials.language')
                </li>
                <li class="nav-item dropdown">
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="dropdown-user">
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="#" id="btn-logout"><i class="fa fa-power-off"></i> {{ __('auth.logout') }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#btn-logout').on('click', (e) => {
                e.preventDefault();
                App.logout("{{ route('backend.logout') }}", "{{ csrf_token() }}");
            });
        });
    </script>
@endpush
