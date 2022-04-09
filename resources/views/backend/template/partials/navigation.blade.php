<aside class="left-sidebar">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-devider"></li>
                <li class="nav-small-cap">MENU</li>

                {{-- Dashboard --}}
                <li>
                    <a class="waves-effect waves-dark" href="{{ route('backend.index') }}">
                        <i class="fa fa-dashboard mr-10"></i>
                        <span>{{ __('navigation.dashboard') }}</span>
                    </a>
                </li>

                {{-- Registers --}}
                @if (user()->can('manage', \App\Models\Client::class) || user()->can('manage', \App\Models\Provider::class)
                 || user()->can('manage', \App\Models\Hotel::class))
                    <li>
                        <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                            <i class="fa fa-user-circle mr-10"></i>
                            <span class="hide-menu">{{ __('navigation.registers') }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            @if (user()->can('manage', \App\Models\Client::class))
                                <li><a href="{{ route('backend.clients.index') }}">{{ __('navigation.clients') }}</a></li>
                            @endif
                            @if (user()->can('manage', \App\Models\Provider::class))
                                <li><a href="{{ route('backend.providers.index') }}">{{ __('navigation.providers') }}</a></li>
                            @endif
                            @if (user()->can('onlyProvider', \App\Models\Provider::class))
                                <li><a href="{{ route('backend.providers.edit', auth()->user()->id) }}">{{ __("navigation.my_registry") }}</a></li>
                            @endif
                            @if (user()->can('onlyProvider', \App\Models\Provider::class))
                                <li><a href="{{ route('backend.providers.companies.index', auth()->user()->id) }}">{{ __("navigation.companies") }}</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- Packages --}}
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                        <i class="fa fa-cubes mr-10"></i>
                        <span class="hide-menu">{{ __('navigation.packages') }}</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('backend.packages.index') }}">{{ __('navigation.packages') }}</a></li>
                        <li><a href="{{ route('backend.offers.index') }}">{{ __('navigation.offers') }}</a></li>
                        <li><a href="{{ route('backend.offers.index') }}?analytic=1">{{ __('navigation.offer_filter') }}</a><li>

                        @if (user()->can('manage', \App\Models\Promocode::class))
                            <li><a href="{{ route('backend.promocodes.index') }}">{{ __('navigation.promocodes') }}</a></li>
                        @endif
                    </ul>
                </li>

                {{-- Bookings --}}
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="{{ route('backend.bookings.index') }}" aria-expanded="false">
                        <i class="fa fa-calendar mr-10"></i>
                        <span class="hide-menu">{{ __('navigation.bookings') }}</span>
                    </a>
                </li>

                {{-- Prebookings --}}
                @if (user()->can('manage', \App\Models\Prebooking::class))
                    <li>
                        <a class="waves-effect waves-dark" href="{{ route('backend.prebookings.index') }}" aria-expanded="false">
                            <i class="fa fa-ticket mr-10"></i>
                            <span class="hide-menu">{{ __('navigation.prebookings') }}</span>
                        </a>
                    </li>
                @endif

                {{-- Financial --}}

                <li>
                    <a class="waves-effect waves-dark" href="#" aria-expanded="false">
                        <i class="fa fa-money mr-10"></i>
                        <span class="hide-menu">{{ __('navigation.financial') }}</span>
                    </a>
                <ul aria-expanded="false" class="collapse">
                @if (user()->can('manage', \App\Models\BookingBill::class))
                        <li><a href="{{ route('backend.financial.decryptor') }}">{{ __('navigation.financial-decryptor') }}</a></li>
                        <li><a href="{{ route('backend.reports.report_refund.index') }}">{{ __("navigation.rel_refund_stock") }}</a></li>
                        <li><a href="{{ route('backend.reports.report_payments.index') }}">{{ __("navigation.return_gateway") }}</a></li>
                        <li><a href="{{ route('backend.reports.report_accountant.index') }}">{{ __("navigation.accountant_report") }}</a></li>
                        <li><a href="{{ route('backend.reports.report_bills.index') }}">{{ __("navigation.financial-bills") }}</a></li>
                @endif
                        <li><a href="{{ route('backend.reports.report_payment_providers.index') }}">{{ __("navigation.payment_future") }}</a></li>
                    </ul>
                </li>


                {{-- Reports --}}
                <li>
                    <a class="waves-effect waves-dark" href="#" aria-expanded="false">
                        <i class="fa fa-file mr-10"></i>
                        <span class="hide-menu">{{ __('navigation.reports') }}</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        @if (user()->canSeeBookingReport())
                        <li><a href="{{ route('backend.reports.report_detail_booking.index') }}">{{ __("navigation.rel_booking_details") }}</a></li>
                        @endif

                        @if (user()->canManageNewsletters())
                        <li><a href="{{ route('backend.reports.report_newsletter.index') }}">{{ __("navigation.newsletter") }}</a></li>
                        @endif
                        <li><a href="{{ route('backend.reports.report_event.index') }}">{{ __("navigation.rel_status_booking") }}</a></li>
                        <li><a href="{{ route('backend.reports.report_stock.index') }}">{{ __("navigation.rel_stock") }}</a></li>
                        <li><a href="{{ route('backend.reports.report_email.index') }}">{{ __("navigation.rel_email") }}</a></li>

                    </ul>
                </li>

                {{-- Settings --}}
                @if (user()->canManageSystemSettings())
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">
                        <i class="fa fa-cog mr-10"></i>
                        <span class="hide-menu">{{ __('navigation.settings') }}</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('backend.events.index') }}">{{ __('navigation.events') }}</a></li>
                        <li><a href="{{ route('backend.categories.index') }}">{{ __('navigation.categories') }}</a></li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false">{{ __('resources.providers.name') }}</a>
                            <ul aria-expanded="false" class="collapse">
                                <li>
                                    <a class="has-arrow" href="#" aria-expanded="false">{{ __('resources.offers.types.bustrip') }}</a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li><a href="{{ route('backend.inclusions.index', ['type' => \App\Enums\OfferType::BUSTRIP]) }}">{{ __('resources.offers.configs.fixed-inclusions') }}</a></li>
                                        <li><a href="{{ route('backend.exclusions.index', ['type' => \App\Enums\OfferType::BUSTRIP]) }}">{{ __('resources.offers.configs.fixed-exclusions') }}</a></li>
                                        <li><a href="{{ route('backend.observations.index', ['type' => \App\Enums\OfferType::BUSTRIP]) }}">{{ __('resources.offers.configs.fixed-observations') }}</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="has-arrow" href="#" aria-expanded="false">{{ __('resources.offers.types.shuttle') }}</a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li><a href="{{ route('backend.inclusions.index', ['type' => \App\Enums\OfferType::SHUTTLE]) }}">{{ __('resources.offers.configs.fixed-inclusions') }}</a></li>
                                        <li><a href="{{ route('backend.exclusions.index', ['type' => \App\Enums\OfferType::SHUTTLE]) }}">{{ __('resources.offers.configs.fixed-exclusions') }}</a></li>
                                        <li><a href="{{ route('backend.observations.index', ['type' => \App\Enums\OfferType::SHUTTLE]) }}">{{ __('resources.offers.configs.fixed-observations') }}</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="has-arrow" href="#" aria-expanded="false">{{ __('resources.offers.types.hospitality') }}</a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li>
                                            <a class="has-arrow" href="#" aria-expanded="false">{{ __('resources.offers.configs.service') }}</a>
                                            <ul aria-expanded="false" class="collapse">
                                                <li><a href="{{ route('backend.inclusions.index', ['type' => \App\Enums\OfferType::HOTEL]) }}">{{ __('resources.offers.configs.fixed-inclusions') }}</a></li>
                                                <li><a href="{{ route('backend.exclusions.index', ['type' => \App\Enums\OfferType::HOTEL]) }}">{{ __('resources.offers.configs.fixed-exclusions') }}</a></li>
                                                <li><a href="{{ route('backend.observations.index', ['type' => \App\Enums\OfferType::HOTEL]) }}">{{ __('resources.offers.configs.fixed-observations') }}</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="{{ route('backend.configs.providers.hotel.hotel-structure.index') }}">{{ __('resources.offers.configs.hotel-structure') }}</a></li>
                                        <li><a href="{{ route('backend.configs.providers.hotel.accommodation-structure.index') }}">{{ __('resources.offers.configs.accommodation-structure') }}</a></li>
                                        <li><a href="{{ route('backend.configs.providers.hotel.accommodation-types.index') }}">{{ __('resources.offers.configs.accommodation-types') }}</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="has-arrow" href="#" aria-expanded="false">{{ __('resources.offers.types.longtrip') }}</a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li>
                                            <a class="has-arrow" href="#" aria-expanded="false">{{ __('resources.offers.configs.service') }}</a>
                                            <ul aria-expanded="false" class="collapse">
                                                <li><a href="{{ route('backend.inclusions.index', ['type' => \App\Enums\OfferType::LONGTRIP]) }}">{{ __('resources.offers.configs.fixed-inclusions') }}</a></li>
                                                <li><a href="{{ route('backend.exclusions.index', ['type' => \App\Enums\OfferType::LONGTRIP]) }}">{{ __('resources.offers.configs.fixed-exclusions') }}</a></li>
                                                <li><a href="{{ route('backend.observations.index', ['type' => \App\Enums\OfferType::LONGTRIP]) }}">{{ __('resources.offers.configs.fixed-observations') }}</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="{{ route('backend.configs.providers.longtrip.accommodation-types.index') }}">{{ __('resources.offers.configs.accommodation-types') }}</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="{{ route('backend.invoiceInformation.index') }}">{{ __('navigation.invoiceInformation') }}</a></li>
                        <li><a href="{{ route('backend.saleCoefficients.index') }}">{{ __('navigation.saleCoefficients') }}</a></li>
                        <li><a href="{{ route('backend.images.index') }}">{{ __('navigation.images') }}</a></li>
                        <li><a href="{{ route('backend.paymentMethods.index') }}">{{ __('navigation.paymentMethods') }}</a></li>
                        <li><a href="{{ route('backend.currencies.index') }}">{{ __('navigation.currencies') }}</a></li>
                        <li><a href="{{ route('backend.pages.index') }}">{{ __('navigation.pages') }}</a></li>
                        <li><a href="{{ route('backend.configs.slideshow.index') }}">Slideshow</a></li>
                        <li><a href="{{ route('backend.configs.users.index') }}">{{ __('navigation.users') }}</a></li>
                    </ul>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
