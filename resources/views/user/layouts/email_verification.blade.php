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
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
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
                                                                    <img src="{{URL::asset('build/images/logo-dark.png')}}" alt="" height="23">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 24px; vertical-align: top; margin: 0; padding: 0 0 10px;  text-align: center;" valign="top">
                                                                <h4 style="font-family: 'Roboto', sans-serif; font-weight: 500;">Please Verify your email</h5>
                                                            </td>
                                                            <td>
                                                                <form method="POST" action="{{ route('logout') }}">
                                                                    @csrf
                                                        
                                                                    <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                                        {{ __('Log Out') }}
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="font-family: 'Roboto', sans-serif; color: #878a99; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 0 0 26px; text-align: center;" valign="top">
                                                                Yes, we know
                                                                <p style="margin-bottom: 13px;">An email to verify an email.</p>
                                                                <p style="margin-bottom: 0;">Please validate your email address in order to get started using product.</p>
                                                            </td>
                                                        </tr>
                                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 22px; text-align: center;" valign="top">
                                                                <form method="POST" action="{{ route('verification.send') }}" style="margin: 0;">
                                                                    @csrf
                                                                    <button type="submit" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: .8125rem; color: #FFF; text-decoration: none; font-weight: 400; text-align: center; cursor: pointer; display: inline-block; border-radius: .25rem; text-transform: capitalize; background-color: #25a0e2; margin: 0; border-color: #25a0e2; border-style: solid; border-width: 1px; padding: .5rem .9rem;">
                                                                        Verify Your Email
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="color: #878a99; text-align: center;font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0; padding-top: 5px" valign="top">
                                                                <p style="margin-bottom: 10px;">Or verify using this link: </p>
                                                                <a href="https://themesbrand.com/velzon/" target="_blank">https://themesbrand.com/velzon/</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <div style="text-align: center; margin: 25px auto 0px auto;font-family: 'Roboto', sans-serif;">
                                            <h4 style="font-weight: 500; line-height: 1.5;font-family: 'Roboto', sans-serif;">Need Help ?</h4>
                                            <p style="color: #878a99; line-height: 1.5;">Please send and feedback or bug info to <a href="" style="font-weight: 500;">info@velzon.com</a></p>
                                            <p style="font-family: 'Roboto', sans-serif; font-size: 14px;color: #98a6ad; margin: 0px;">2022 Velzon. Design & Develop by Themesbrand</p>
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
                            <script>
                                document.write(new Date().getFullYear())
                            </script> Clever Creator. Crafted with <i
                                    class="mdi mdi-heart text-danger"></i> by Clever Creator</p>
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
