<input id="has-payment" type="hidden"
       value="{{ (is_array($payment_methods) && count($payment_methods) == 0) ? 'no' : 'yes' }}">
@if(count($payment_methods))
    <div class="form-group">
        <div class="custom-controls-stacked">
            @foreach($payment_methods as $method)
                <label class="custom-control custom-radio">
                    <input id="payment_method" type="radio" class="custom-control-input" name="payment_method"
                           value="{{ $method['id'] }}" checked>
                    <span class="custom-control-label">
                        <b> **** **** **** {{ $method['last_four'] }}</b>
                    </span>
                </label>
            @endforeach
        </div>
    </div>
@else
    <div id="stripe_div">
        <small class="form-text text-muted" id="cardErrors" role="alert"></small>
        <input type="hidden" value="" name="payment_method">
        <div class="form-group">
            <label>Card Holder Name *</label>
            <input id="cardHolderName" type="text" class="form-control mb-2">
        </div>
        <div class="form-group">
            <label class="mb-10">Card Information *</label>
            <div id="card-element"></div>
            <div id="card-errors" role="alert"></div>
        </div>
        <div class="form-group text-right mt-5">
            <button id="submit" type="button" class="btn btn-primary">Add Card</button>
        </div>
    </div>
    @include('user.stripe-card.script')
@endif
