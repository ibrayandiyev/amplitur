<div class="pop-ups">
    <div class="pop-up pop-up--visitando">
        <i class="fas fa-users"></i>
        Visitando agora: 1
    </div>

    @if($package->bookings()->where("created_at", ">=", Carbon\Carbon::now()->subDays(7)->format("Y-m-d H:i:s"))->count())
    <div class="pop-up pop-up--quente">
        <i class="fas fa-fire"></i>
    </div>
    @endif

    @if($package->visits >1)
        <div class="pop-up pop-up--visitaram">
            <i class="fas fa-users"></i>
            {{$package->visits}} {{__('frontend.reservas.ja_visitaram_pacote')}}
        </div>
    @endif
</div>
