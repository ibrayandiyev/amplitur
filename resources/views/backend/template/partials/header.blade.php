<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <div>
            <a href="{{ route('backend.index') }}">
                <b>
                   <img src="/frontend/images/estrutura/amp-travel-front-bgblue2.png" alt="homepage" style="padding: 0.3em" />
                </b>
            </a>
        </div>
        <div class="navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                <li class="nav-item hidden-sm-down"><span></span></li>
            </ul>
            <ul class="navbar-nav my-lg-0">
                <li class="nav-item dropdown">
                    @include('backend.template.partials.language')
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="/backend/images/users/1.jpg" alt="user" class="profile-pic" /></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="dropdown-user">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img"><img src="/backend/images/users/1.jpg" alt="user"></div>
                                    <div class="u-text">
                                        <h4>{{ auth()->user()->name  }}</h4>
                                        <p class="text-muted">{{ auth()->user()->email }}</p>
                                    </div>


                                </div>
                            </li>
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
