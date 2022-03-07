<html>
<head>
	<meta charset="utf-8">
	<title>Paypal Payment</title>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
    <div class="row">    	
        <div class="col-md-8 col-md-offset-2">        	
        	<h3 class="text-center" style="margin-top: 30px;">Paypal Payment</h3>
            <div class="panel panel-default" style="margin-top: 60px;">

                @if ($message = Session::get('success'))
                <div class="custom-alerts alert alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    {!! $message !!}
                </div>
                <?php Session::forget('success');?>
                @endif

                @if ($message = Session::get('error'))
                <div class="custom-alerts alert alert-danger fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    {!! $message !!}
                </div>
                <?php Session::forget('error');?>
                @endif
                <div class="panel-heading"><b>Paywith Paypal</b></div>
                <div class="panel-body">
                <form class="form-horizontal" method="POST" id="payment-form" role="form" action="{!! URL::route('paypal') !!}" >
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                          <label for="amount" class="col-md-4 control-label">Total Amount</label>

                          <div class="col-md-6">
                            <input id="selected_plan" type="hidden" class="form-control" name="selected_plan" value="{{ Session::get('plan') }}">
                            <input id="payment_name" type="hidden" class="form-control" name="payment_name" value="Paypal">
                            <input id="cal_discount" type="hidden" class="form-control" name="cal_discount" value="{{ Session::get('cal_discount') }}">
                            <input id="final_price" type="text" class="form-control" name="final_price" value="{{ Session::get('final_price') }}" readonly>

                              <!-- @if ($errors->has('amount'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('amount') }}</strong>
                                  </span>
                              @endif -->
                          </div>
                          
                          <div class="form-group">
                              <div class="col-md-6 col-md-offset-4">
                                  <button type="submit" class="btn btn-primary">
                                      Paywith Paypal
                                  </button>
                              </div>
                          </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>