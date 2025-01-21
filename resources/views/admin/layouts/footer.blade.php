<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <!-- First Section (Left) -->
            <div class="col-3">
                <script>document.write(new Date().getFullYear())</script> © {{ $siteSettings->footer_text }}.
            </div>

            <!-- Second Section (Center) - Social Media Links -->
            <div class="col-6 text-center">
                <a href="{{$siteSettings->facebook}}" target="_blank" class="mx-1">
                    <img src="{{ asset('/backend/uploads/site/fb.png') }}" width="20px" alt="">
                </a>
                <a href="{{$siteSettings->instagram}}" target="_blank" class="mx-1">
                    <img src="{{ asset('backend/uploads/site/insta.png') }}" width="20px" alt="">
                </a>
                <a href="{{$siteSettings->linkedin}}" target="_blank" class="mx-1">
                    <img src="{{ asset('backend/uploads/site/In.png') }}" width="20px" alt="">
                </a>
                <a href="{{$siteSettings->youtube}}" target="_blank" class="mx-1">
                    <img src="{{ asset('backend/uploads/site/Youtube.png') }}" width="20px" alt="">
                </a>
            </div>
            
            <!-- Third Section (Right) -->
            <div class="col-3">
                <div class="text-sm-end d-none d-sm-block">
                    Design & Develop by {{ $siteSettings->footer_text }}
                </div>
            </div>
        </div>
    </div>
</footer>
