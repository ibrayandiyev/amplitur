<div class="form-group  @if($errors->has('currency')) has-danger @endif">
    <label class="form-control-label">
        <strong>{{ __('resources.bus-trip.model.sales-currency') }}</strong>
        <span class="text-danger">*</span>
    </label>
    <select class="form-control" name="currency">
        <option value>{{ __('messages.select') }}</option>
        <option value="{{ \App\Enums\Currency::REAL }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::REAL) selected @endif>{{ __('resources.financial.currencies.real') }}</option>
        <option value="{{ \App\Enums\Currency::DOLLAR }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::DOLLAR) selected @endif>{{ __('resources.financial.currencies.dollar') }}</option>
        <option value="{{ \App\Enums\Currency::EURO }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::EURO) selected @endif>{{ __('resources.financial.currencies.euro') }}</option>
        <option value="{{ \App\Enums\Currency::LIBRA }}" @if (old('currency', $offer->currency) == \App\Enums\Currency::LIBRA) selected @endif>{{ __('resources.financial.currencies.pound') }}</option>
    </select>
</div>