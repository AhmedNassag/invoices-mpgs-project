@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-cogs"></i> {{ __('setting.setting') }}</h1>

			<span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            	<a class="text-white"><i class="fas fa-cogs fa-sm text-white-50"></i> {{ __('setting.setting') }}</a>
			</span>
        </div>

        <!-- Content Row -->
        <div class="card shadow mb-3">
            <div class="card-body">
                <div class="row">
    				<div class="col-xl-12 col-md-12">
  						<ul class="nav nav-pills" id="setting" role="tablist">
						    <li class="nav-item">
						        <a class="nav-link {{ active_setting('generalsetting') ? 'active' : '' }}" data-toggle="pill" href="#generalsetting" role="tab" aria-controls="generalsetting" aria-selected="true">{{ __('setting.general_setting') }}</a>
						    </li>
						    <li class="nav-item">
						        <a class="nav-link {{ active_setting('emailsetting') ? 'active' : '' }}" data-toggle="pill" href="#emailsetting" role="tab" aria-controls="emailsetting" aria-selected="false">{{ __('setting.email_setting') }}</a>
						    </li>
						    <li class="nav-item">
						        <a class="nav-link {{ active_setting('invoicesetting') ? 'active' : '' }}" data-toggle="pill" href="#invoicesetting" role="tab" aria-controls="invoicesetting" aria-selected="false">{{ __('setting.invoice_setting') }}</a>
						    </li>
						    <li class="nav-item">
						        <a class="nav-link {{ active_setting('paymentsetting') ? 'active' : '' }}" data-toggle="pill" href="#paymentsetting" role="tab" aria-controls="paymentsetting" aria-selected="false">{{ __('setting.payment_setting') }}</a>
						    </li>
						</ul>
                	</div>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="row">
    				<div class="col-xl-12 col-md-12">
						<div class="tab-content" id="settingContent">
						    <div class="tab-pane fade {{ active_setting('generalsetting') ? 'show active' : '' }}" id="generalsetting" role="tabpanel" aria-labelledby="generalsetting">
						        <form action="{{ route('admin.setting.store') }}" method="POST" enctype="multipart/form-data">
						        	@csrf
						        	<input type="hidden" name="settingtype" value="generalsetting">
									<fieldset class="setting-fieldset">
										<legend class="setting-legend">{{ __('setting.general') }}</legend>
						                <div class="row">
						                	<div class="col-sm-6">
						                		<div class="form-group">
													<label for="site_name">{{ __('Site Name') }}</label> <span class="text-danger">*</span>
													<input name="site_name" id="site_name" type="text" class="form-control @error('site_name') is-invalid @enderror" value="{{ old('site_name', setting('site_name')) }}">
													@error('site_name')
														<div class="invalid-feedback">
															<strong>{{ $message }}</strong>
														</div>
													@enderror
												</div>

												<div class="form-group">
													<label for="phone">{{ __('Phone') }}</label> <span class="text-danger">*</span>
													<input name="phone" id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', setting('phone')) }}">
													@error('phone')
														<div class="invalid-feedback">
															<strong>{{ $message }}</strong>
														</div>
													@enderror
												</div>

											  	<div class="form-group">
											    	<label for="site_logo">{{ __('Logo') }} <span class="text-danger">*</span></label>
											  		<div class="custom-file">
													    <input type="file" name="site_logo" class="custom-file-input upload-file-input @error('site_logo') is-invalid @enderror" id="uploadLogo">
													    <label class="custom-file-label" for="site_logo">{{ __('Choose file') }}</label>
													</div>
													@error('site_logo')
														<div class="invalid-feedback">
															<strong>{{ $message }}</strong>
														</div>
													@enderror
													@if(setting('site_logo'))
														<img id="prevLogo" class="img-thumbnail image-width mt-2 mb-2 setting-logo" src="{{ asset('img/'.setting('site_logo')) }}" alt="{{ setting('site_name') }} Logo">
													@endif
											  	</div>

											  	<div class="form-group">
												    <label for="timezone">{{ __('Timezone') }} <span class="text-danger">*</span></label>
												    <select class="form-control @error('timezone') is-invalid @enderror" id="timezone" name="timezone">
														<?php if(!blank($timezones)) { foreach($timezones as $timezoneKey => $timezone) { ?>
															<option value="{{ $timezoneKey }}" {{ $timezoneKey == setting('timezone') ? 'selected' : ''}}>{{ $timezone }}</option>
														<?php } } ?>
												    </select>
												    @error('timezone')
														<div class="invalid-feedback">
															<strong>{{ $message }}</strong>
														</div>
													@enderror
												</div>

												<div class="form-group">
													<label for="currency_code">{{ __('Currency Code') }}</label> <span class="text-danger">*</span>
													<input name="currency_code" id="currency_code" type="text" class="form-control @error('currency_code') is-invalid @enderror" value="{{ old('currency_code', setting('currency_code')) }}">
													@error('currency_code')
														<div class="invalid-feedback">
															<strong>{{ $message }}</strong>
														</div>
													@enderror
												</div>

						                	</div>
						                	<div class="col-sm-6">
						                		<div class="form-group">
													<label for="email">{{ __('Email') }}</label> <span class="text-danger">*</span>
													<input name="email" id="email" type="text" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', setting('email')) }}">
													@error('email')
														<div class="invalid-feedback">
															<strong>{{ $message }}</strong>
														</div>
													@enderror
												</div>
												<div class="form-group">
													<label for="copyright_by">{{ __('Copyright By') }}</label> <span class="text-danger">*</span>
													<input name="copyright_by" id="copyright_by" type="text" class="form-control @error('copyright_by') is-invalid @enderror" value="{{ old('copyright_by', setting('copyright_by')) }}">
													@error('copyright_by')
														<div class="invalid-feedback">
															<strong>{{ $message }}</strong>
														</div>
													@enderror
												</div>
											  	<div class="form-group">
											    	<label for="address">{{ __('Address') }} <span class="text-danger">*</span></label>
											    	<textarea name="address" class="form-control @error('address') is-invalid @enderror" cols="30" rows="4" placeholder="Enter Your Address">{{ old('address', setting('address')) }}</textarea>
											    	@error('address')
														<div class="invalid-feedback">
															<strong>{{ $message }}</strong>
														</div>
													@enderror
											  	</div>
											  	<div class="form-group">
													<label for="currency_symbol">{{ __('Currency Symbol') }}</label> <span class="text-danger">*</span>
													<input name="currency_symbol" id="currency_symbol" type="text" class="form-control @error('currency_symbol') is-invalid @enderror" value="{{ old('currency_symbol', setting('currency_symbol')) }}">
													@error('currency_symbol')
														<div class="invalid-feedback">
															<strong>{{ $message }}</strong>
														</div>
													@enderror
												</div>

												<div class="form-group">
												    <label for="site_sidebar">{{ __('Site Sidebar') }} <span class="text-danger">*</span></label>
												    <select class="form-control @error('site_sidebar') is-invalid @enderror" id="site_sidebar" name="site_sidebar">
															<option value="1" {{ (old('site_sidebar', setting('site_sidebar')) == 1) ? 'selected' : ''}}>{{ __('Extend ') }}</option>
															<option value="0" {{ (old('site_sidebar', setting('site_sidebar')) == 0) ? 'selected' : ''}}>{{ __('Minimize ') }}</option>
												    </select>
												    @error('site_sidebar')
														<div class="invalid-feedback">
															<strong>{{ $message }}</strong>
														</div>
													@enderror
												</div>
						                	</div>
						                </div>
						            </fieldset>
									<button type="submit" class="btn btn-primary">{{ __('setting.save_changes') }}</button>
					        	</form>
						    </div>

						    <div class="tab-pane fade {{ active_setting('emailsetting') ? 'show active' : '' }}" id="emailsetting" role="tabpanel" aria-labelledby="emailsetting">
						        <form action="{{ route('admin.setting.store') }}" method="POST" enctype="multipart/form-data">
						        	@csrf
						        	<input type="hidden" name="settingtype" value="emailsetting">
									<fieldset class="setting-fieldset">
										<legend class="setting-legend">{{ __('Email Setting') }}</legend>
						                <div class="row">

											<div class="form-group col-sm-4">
												<label for="mail_host">{{ __('Mail Host') }}</label> <span class="text-danger">*</span>
												<input name="mail_host" id="mail_host" type="text" class="form-control @error('mail_host') is-invalid @enderror" value="{{ old('mail_host', setting('mail_host')) }}">
												@error('mail_host')
													<div class="invalid-feedback">
														<strong>{{ $message }}</strong>
													</div>
												@enderror
											</div>

											<div class="form-group col-sm-4">
												<label for="mail_port">{{ __('Mail Port') }}</label> <span class="text-danger">*</span>
												<input name="mail_port" id="mail_port" class="form-control @error('mail_port') is-invalid @enderror" value="{{ old('mail_port', setting('mail_port')) }}">
												@error('mail_port')
												<div class="invalid-feedback">
													<strong>{{ $message }}</strong>
												</div>
												@enderror
											</div>

											<div class="form-group col-sm-4">
												<label for="mail_username">{{ __('Mail Username') }}</label> <span class="text-danger">*</span>
												<input name="mail_username" id="mail_username" type="text" class="form-control @error('mail_username') is-invalid @enderror" value="{{ old('mail_username', setting('mail_username')) }}">
												@error('mail_username')
												<div class="invalid-feedback">
													<strong>{{ $message }}</strong>
												</div>
												@enderror
											</div>

											<div class="form-group col-sm-4">
												<label for="mail_password">{{ __('Mail Password') }}</label> <span class="text-danger">*</span>
												<input name="mail_password" id="mail_password" type="text" class="form-control @error('mail_password') is-invalid @enderror" value="{{ old('mail_password', setting('mail_password')) }}">
												@error('mail_password')
												<div class="invalid-feedback">
													<strong>{{ $message }}</strong>
												</div>
												@enderror
											</div>

											<div class="form-group col-sm-4">
												<label for="mail_encryption">{{ __('Mail Encryption') }}</label> <span class="text-danger">*</span>
												<input name="mail_encryption" id="mail_encryption" type="text" class="form-control @error('mail_encryption') is-invalid @enderror" value="{{ old('mail_encryption', setting('mail_encryption')) }}">
												@error('mail_encryption')
												<div class="invalid-feedback">
													<strong>{{ $message }}</strong>
												</div>
												@enderror
											</div>

											<div class="form-group col-sm-4">
												<label for="mail_from_address">{{ __('Mail From Address') }}</label> <span class="text-danger">*</span>
												<input name="mail_from_address" id="mail_from_address" type="text" class="form-control @error('mail_from_address') is-invalid @enderror" value="{{ old('mail_from_address', setting('mail_from_address')) }}">
												@error('mail_from_address')
												<div class="invalid-feedback">
													<strong>{{ $message }}</strong>
												</div>
												@enderror
											</div>

										</div>
						            </fieldset>
									<button type="submit" class="btn btn-primary">{{ __('setting.save_changes') }}</button>
					        	</form>
						    </div>

						    <div class="tab-pane fade {{ active_setting('invoicesetting') ? 'show active' : '' }}" id="invoicesetting" role="tabpanel" aria-labelledby="invoicesetting">
								<fieldset class="setting-fieldset">
									<legend class="setting-legend">{{ __('Theme Setting') }}</legend>
					                <div class="row">
									    <div class="col-md-4 col-sm-6 col-xs-12">
									        <div class="single-theme {{ setting('invoicetheme') == 'invoice1' ? 'single-theme-active' : '' }}" data-theme="invoice1">
									            <div class="theme-thumnail">
									                <img src="{{ asset('img/invoice1.png') }}" alt="" class="img-thumbnail" />
									            </div>
									            @if(setting('invoicetheme') == 'invoice1')
									        		<div class="theme-active"><span class="fa fa-check fa-2x"></span></div>
									        	@endif
									        </div>
									    </div>
									    <div class="col-md-4 col-sm-6 col-xs-12">
									        <div class="single-theme {{ setting('invoicetheme') == 'invoice2' ? 'single-theme-active' : '' }}" data-theme="invoice2">
									            <div class="theme-thumnail">
									                <img src="{{ asset('img/invoice2.png') }}" alt="" class="img-thumbnail" />
									            </div>
									            @if(setting('invoicetheme') == 'invoice2')
									        		<div class="theme-active"><span class="fa fa-check fa-2x"></span></div>
									        	@endif
									        </div>
									    </div>
									    <div class="col-md-4 col-sm-6 col-xs-12">
									        <div class="border text-theme">
									            <h4>{{ __('More (10+) Colorful Invoice Theme Coming...') }}</h4>
									        </div>
									    </div>
									</div>
					            </fieldset>
						    </div>

						    <div class="tab-pane fade {{ active_setting('paymentsetting') ? 'show active' : '' }}" id="paymentsetting" role="tabpanel" aria-labelledby="paymentsetting">
						        <form action="{{ route('admin.setting.store') }}" method="POST" enctype="multipart/form-data">
						        	@csrf
									<input type="hidden" name="settingtype" value="paymentsetting">
									<input type="hidden" name="paymentgateway" value="stripe">
									<fieldset class="setting-fieldset">
										<legend class="setting-legend">{{ __('setting.stripe_setting') }}</legend>
						                <div class="row">
						                	<div class="col-sm-4">
						                		<div class="form-group">
													<label for="stripe_key">{{ __('Stripe key') }}</label>
													<input name="stripe_key" id="stripe_key" type="text" class="form-control @error('stripe_key') is-invalid @enderror" value="{{ old('stripe_key', setting('stripe_key')) }}">
													@error('stripe_key')
														<div class="invalid-feedback">
															<strong>{{ $message }}</strong>
														</div>
													@enderror
												</div>
						                	</div>
						                	<div class="col-sm-4">
						                		<div class="form-group">
													<label for="stripe_secret">{{ __('Stripe Secret') }}</label>
													<input name="stripe_secret" id="stripe_secret" type="text" class="form-control @error('stripe_secret') is-invalid @enderror" value="{{ old('stripe_secret', setting('stripe_secret')) }}">
													@error('stripe_secret')
														<div class="invalid-feedback">
															<strong>{{ $message }}</strong>
														</div>
													@enderror
												</div>
						                	</div>
						                	<div class="col-sm-4">
						                		<div class="form-group">
													<label for="stripe_status">{{ __('Stripe Status') }}</label>
													<select class="form-control @error('stripe_status') is-invalid @enderror" id="stripe_status" name="stripe_status">
														<option value="5" {{ 5 == old('stripe_status', setting('stripe_status')) ? 'selected' : ''}}>{{ __('Active ') }}</option>
														<option value="10" {{ 10 == old('stripe_status', setting('stripe_status')) ? 'selected' : ''}}>{{ __('Inactive ') }}</option>
												    </select>
													@error('stripe_status')
														<div class="invalid-feedback">
															<strong>{{ $message }}</strong>
														</div>
													@enderror
												</div>
						                	</div>
						                </div>
						            </fieldset>

									<button type="submit" class="btn btn-primary">{{ __('setting.save_changes') }}</button>
					        	</form>

								<hr class="my-4">

								<form action="{{ route('admin.setting.store') }}" method="POST" enctype="multipart/form-data">
									@csrf
									<input type="hidden" name="settingtype" value="paymentsetting">
									<input type="hidden" name="paymentgateway" value="razorpay">
									<fieldset class="setting-fieldset">
										<legend class="setting-legend">{{ __('setting.razorpay_setting') }}</legend>
										<div class="row">
											<div class="col-sm-4">
												<div class="form-group">
													<label for="razorpay_key">{{ __('Razorpay key') }}</label>
													<input name="razorpay_key" id="razorpay_key" type="text" class="form-control @error('razorpay_key') is-invalid @enderror" value="{{ old('razorpay_key', setting('razorpay_key')) }}">
													@error('razorpay_key')
													<div class="invalid-feedback">
														<strong>{{ $message }}</strong>
													</div>
													@enderror
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<label for="razorpay_secret">{{ __('Razorpay Secret') }}</label>
													<input name="razorpay_secret" id="razorpay_secret" type="text" class="form-control @error('razorpay_secret') is-invalid @enderror" value="{{ old('razorpay_secret', setting('razorpay_secret')) }}">
													@error('razorpay_secret')
													<div class="invalid-feedback">
														<strong>{{ $message }}</strong>
													</div>
													@enderror
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<label for="razorpay_status">{{ __('Razorpay Status') }}</label>
													<select class="form-control @error('razorpay_status') is-invalid @enderror" id="razorpay_status" name="razorpay_status">
														<option value="5" {{ 5 == old('razorpay_status', setting('razorpay_status')) ? 'selected' : ''}}>{{ __('Active ') }}</option>
														<option value="10" {{ 10 == old('razorpay_status', setting('razorpay_status')) ? 'selected' : ''}}>{{ __('Inactive ') }}</option>
													</select>
													@error('razorpay_status')
													<div class="invalid-feedback">
														<strong>{{ $message }}</strong>
													</div>
													@enderror
												</div>
											</div>
										</div>
									</fieldset>
									<button type="submit" class="btn btn-primary">{{ __('setting.save_changes') }}</button>
								</form>

								<hr class="my-4">

								<form action="{{ route('admin.setting.store') }}" method="POST" enctype="multipart/form-data">
									@csrf
									<input type="hidden" name="settingtype" value="paymentsetting">
									<input type="hidden" name="paymentgateway" value="mpgs">
									<fieldset class="setting-fieldset">
										<legend class="setting-legend">{{ __('setting.mpgs_setting') }}</legend>
										<div class="row">
											<div class="col-sm-4">
												<div class="form-group">
													<label for="mpgs_key">{{ __('MERCHANT_ID') }}</label>
													<input name="MERCHANT_ID" id="MERCHANT_ID" type="text" class="form-control @error('MERCHANT_ID') is-invalid @enderror" value="{{ old('MERCHANT_ID', setting('MERCHANT_ID')) }}">
													@error('MERCHANT_ID')
													<div class="invalid-feedback">
														<strong>{{ $message }}</strong>
													</div>
													@enderror
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<label for="API_PASSWORD">{{ __('API_PASSWORD') }}</label>
													<input name="API_PASSWORD" id="API_PASSWORD" type="text" class="form-control @error('API_PASSWORD') is-invalid @enderror" value="{{ old('API_PASSWORD', setting('API_PASSWORD')) }}">
													@error('API_PASSWORD')
													<div class="invalid-feedback">
														<strong>{{ $message }}</strong>
													</div>
													@enderror
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<label for="mpgs_status">{{ __('mpgs Status') }}</label>
													<select class="form-control @error('mpgs_status') is-invalid @enderror" id="mpgs_status" name="mpgs_status">
														<option value="5" {{ 5 == old('mpgs_status', setting('mpgs_status')) ? 'selected' : ''}}>{{ __('Active ') }}</option>
														<option value="10" {{ 10 == old('mpgs_status', setting('mpgs_status')) ? 'selected' : ''}}>{{ __('Inactive ') }}</option>
													</select>
													@error('mpgs_status')
													<div class="invalid-feedback">
														<strong>{{ $message }}</strong>
													</div>
													@enderror
												</div>
											</div>
										</div>
									</fieldset>
									<button type="submit" class="btn btn-primary">{{ __('setting.save_changes') }}</button>
								</form>
						    </div>

						</div>
					</div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection
