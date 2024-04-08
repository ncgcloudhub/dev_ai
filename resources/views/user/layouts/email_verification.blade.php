@extends('admin.layouts.master-without-nav')
@section('title')
    @lang('translation.signin')
@endsection
@section('content')


    <div class="auth-page-wrapper pt-5">
       
        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">

                    @if (session('status') == 'verification-link-sent')
                <div class="d-flex justify-content-center py-5">
                  <!-- Success Alert -->
                    <div class="alert alert-success alert-dismissible alert-additional fade show" role="alert">
                        <div class="alert-body">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <i class="ri-check-double-line fs-16 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="alert-heading">Success!</h5>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="alert-content">
                            <p class="mb-0">A new verification link has been sent to your email address.</p>
                        </div>
                    </div>
                </div>
            @endif

                    <!--end col-->
                    <div class="col-12">
                        <table class="body-wrap" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: transparent; margin: 0;">
                            <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <td style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
                                <td class="container" width="600" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                                    <div class="content" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                                        <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border: none;">
                                            <tr style="font-family: 'Roboto', sans-serif; font-size: 14px; margin: 0;">
                                                <td class="content-wrap" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; color: #495057; font-size: 14px; vertical-align: top; margin: 0;padding: 30px; border: 1px solid #e9ebec;border-radius: 7px; background-color: #fff;" valign="top">
                                                    <meta itemprop="name" content="Confirm Email" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
                                                    <table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                                <div style="text-align: center;margin-bottom: 15px;">
                                                                    <img src="{{URL::asset('build/images/logo-dark1.png')}}" alt="" height="23">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 24px; vertical-align: top; margin: 0; padding: 0 0 10px;  text-align: center;" valign="top">
                                                                <h4 style="font-family: 'Roboto', sans-serif; font-weight: 500;">Please Verify your email</h5>
                                                            </td>
                                                            
                                                        </tr>
                                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="font-family: 'Roboto', sans-serif; color: #878a99; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 0 0 26px; text-align: center;" valign="top">
                                                                You're Almost There!
                                                                <p style="margin-bottom: 13px;"></p>
                                                                <p style="margin-bottom: 0;">Please click on the <strong>button</strong> to get <b>E-Mail Verification</b></p>
                                                            </td>
                                                        </tr>
                                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 22px; text-align: center;" valign="top">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <form method="POST" action="{{ route('verification.send') }}" style="margin: 0;">
                                                                            @csrf
                                                                            <button type="submit" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: .8125rem; color: #FFF; text-decoration: none; font-weight: 400; text-align: center; cursor: pointer; display: inline-block; border-radius: .25rem; text-transform: capitalize; background-color: #25a0e2; margin: 0; border-color: #25a0e2; border-style: solid; border-width: 1px; padding: .5rem .9rem;">
                                                                                Verify Your Email
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                    <div class="col">
                                                                        <form method="POST" action="{{ route('logout') }}">
                                                                            @csrf
                                                                            <button type="submit" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: .8125rem; color: #FFF; text-decoration: none; font-weight: 400; text-align: center; cursor: pointer; display: inline-block; border-radius: .25rem; text-transform: capitalize; background-color: #25a0e2; margin: 0; border-color: #25a0e2; border-style: solid; border-width: 1px; padding: .5rem .9rem;">
                                                                               Log Out
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        
                                                      
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <div style="text-align: center; margin: 25px auto 0px auto;font-family: 'Roboto', sans-serif;">
                                            <h4 style="font-weight: 500; line-height: 1.5;font-family: 'Roboto', sans-serif;">Need Help ?</h4>
                                            <p style="color: #878a99; line-height: 1.5;">Please send and feedback or bug info to <a href="" style="font-weight: 500;">clevercreatorai@gmail.com</a></p>
                                            
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <!-- end table -->
                    </div>
                    <!--end col-->
                </div><!-- end row -->
                
        
            
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <script>document.write(new Date().getFullYear())</script> © {{ $siteSettings->title }}. Design & Develop by {{ $siteSettings->title }}
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>
@endsection
