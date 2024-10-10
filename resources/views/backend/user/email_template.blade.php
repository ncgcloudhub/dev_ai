{{-- <!DOCTYPE html>
<html>
<head>
    <title>{{ $details['subject'] }}</title>
</head>
<body>
    <h1>{{ $details['subject'] }}</h1>
    <p>{!! $details['body'] !!}</p>

    <p>Thank you!</p>
</body>
</html> --}}

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- CSS Reset : BEGIN -->
    <style>

        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
body {
    margin: 0 auto !important;
    padding: 0 !important;
    height: 100% !important;
    width: 100% !important;
    background: #f1f1f1;
}

/* What it does: Stops email clients resizing small text. */
* {
    -ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%;
}

/* What it does: Centers email on Android 4.4 */
div[style*="margin: 16px 0"] {
    margin: 0 !important;
}

/* What it does: Stops Outlook from adding extra spacing to tables. */
table,
td {
    mso-table-lspace: 0pt !important;
    mso-table-rspace: 0pt !important;
}

/* What it does: Fixes webkit padding issue. */
table {
    border-spacing: 0 !important;
    border-collapse: collapse !important;
    table-layout: fixed !important;
    margin: 0 auto !important;
}

/* What it does: Uses a better rendering method when resizing images in IE. */
img {
    -ms-interpolation-mode:bicubic;
}

/* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
a {
    text-decoration: none;
}

/* What it does: A work-around for email clients meddling in triggered links. */
*[x-apple-data-detectors],  /* iOS */
.unstyle-auto-detected-links *,
.aBn {
    border-bottom: 0 !important;
    cursor: default !important;
    color: inherit !important;
    text-decoration: none !important;
    font-size: inherit !important;
    font-family: inherit !important;
    font-weight: inherit !important;
    line-height: inherit !important;
}

/* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
.a6S {
    display: none !important;
    opacity: 0.01 !important;
}

/* What it does: Prevents Gmail from changing the text color in conversation threads. */
.im {
    color: inherit !important;
}

/* If the above doesn't work, add a .g-img class to any image in question. */
img.g-img + div {
    display: none !important;
}

/* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
/* Create one of these media queries for each additional viewport size you'd like to fix */

/* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
@media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
    u ~ div .email-container {
        min-width: 320px !important;
    }
}
/* iPhone 6, 6S, 7, 8, and X */
@media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
    u ~ div .email-container {
        min-width: 375px !important;
    }
}
/* iPhone 6+, 7+, and 8+ */
@media only screen and (min-device-width: 414px) {
    u ~ div .email-container {
        min-width: 414px !important;
    }
}


    </style>

    <!-- CSS Reset : END -->

    <!-- Progressive Enhancements : BEGIN -->
    <style>

	    .primary{
	background: #f5564e;
}
.bg_white{
	background: #ffffff;
}
.bg_light{
	background: #fafafa;
}
.bg_black{
	background: #000000;
}
.bg_dark{
	background: rgba(0,0,0,.8);
}
.email-section{
	padding:2.5em;
}

/*BUTTON*/
.btn{
	padding: 5px 15px;
	display: inline-block;
}
.btn.btn-primary{
	border-radius: 5px;
	background: #f5564e;
	color: #ffffff;
}
.btn.btn-white{
	border-radius: 5px;
	background: #ffffff;
	color: #000000;
}
.btn.btn-white-outline{
	border-radius: 5px;
	background: transparent;
	border: 1px solid #fff;
	color: #fff;
}

h1,h2,h3,h4,h5,h6{
	font-family: 'Nunito Sans', sans-serif;
	color: #000000;
	margin-top: 0;
}

body{
	font-family: 'Nunito Sans', sans-serif;
	font-weight: 400;
	font-size: 15px;
	line-height: 1.8;
	color: rgba(0,0,0,.4);
}

a{
	color: #f5564e;
}

table{
}
/*LOGO*/

.logo h1{
	margin: 0;
}
.logo h1 a{
	color: #000;
	font-size: 20px;
	font-weight: 700;
	text-transform: uppercase;
	font-family: 'Nunito Sans', sans-serif;
}

.navigation{
	padding: 0;
}
.navigation li{
	list-style: none;
	display: inline-block;;
	margin-left: 5px;
	font-size: 12px;
	font-weight: 700;
	text-transform: uppercase;
}
.navigation li a{
	color: rgba(0,0,0,.6);
}

/*HERO*/
.hero{
	position: relative;
	z-index: 0;
}
.hero .overlay{
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	content: '';
	width: 100%;
	background: #000000;
	z-index: -1;
	opacity: .3;
}
.hero .icon{
}
.hero .icon a{
	display: block;
	width: 60px;
	margin: 0 auto;
}
.hero .text{
	color: rgba(255,255,255,.8);
	padding: 0 4em;
}
.hero .text h2{
	color: #ffffff;
	font-size: 40px;
	margin-bottom: 0;
	line-height: 1.2;
	font-weight: 900;
}


/*HEADING SECTION*/
.heading-section{
}
.heading-section h2{
	color: #000000;
	font-size: 24px;
	margin-top: 0;
	line-height: 1.4;
	font-weight: 700;
}
.heading-section .subheading{
	margin-bottom: 20px !important;
	display: inline-block;
	font-size: 13px;
	text-transform: uppercase;
	letter-spacing: 2px;
	color: rgba(0,0,0,.4);
	position: relative;
}
.heading-section .subheading::after{
	position: absolute;
	left: 0;
	right: 0;
	bottom: -10px;
	content: '';
	width: 100%;
	height: 2px;
	background: #f5564e;
	margin: 0 auto;
}

.heading-section-white{
	color: rgba(255,255,255,.8);
}
.heading-section-white h2{
	font-family: 
	line-height: 1;
	padding-bottom: 0;
}
.heading-section-white h2{
	color: #ffffff;
}
.heading-section-white .subheading{
	margin-bottom: 0;
	display: inline-block;
	font-size: 13px;
	text-transform: uppercase;
	letter-spacing: 2px;
	color: rgba(255,255,255,.4);
}


.icon{
	text-align: center;
}
.icon img{
}


/*SERVICES*/
.services{
	background: rgba(0,0,0,.03);
}
.text-services{
	padding: 10px 10px 0; 
	text-align: center;
}
.text-services h3{
	font-size: 16px;
	font-weight: 600;
}

.services-list{
	padding: 0;
	margin: 0 0 10px 0;
	width: 100%;
	float: left;
}

.services-list .text{
	width: 100%;
	float: right;
}
.services-list h3{
	margin-top: 0;
	margin-bottom: 0;
	font-size: 18px;
}
.services-list p{
	margin: 0;
}


/*DESTINATION*/
.text-tour{
	padding-top: 10px;
}
.text-tour h3{
	margin-bottom: 0;
}
.text-tour h3 a{
	color: #000;
}

/*BLOG*/
.text-services .meta{
	text-transform: uppercase;
	font-size: 14px;
}

/*TESTIMONY*/
.text-testimony .name{
	margin: 0;
}
.text-testimony .position{
	color: rgba(0,0,0,.3);

}


/*COUNTER*/
.counter{
	width: 100%;
	position: relative;
	z-index: 0;
}
.counter .overlay{
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	content: '';
	width: 100%;
	background: #000000;
	z-index: -1;
	opacity: .3;
}
.counter-text{
	text-align: center;
}
.counter-text .num{
	display: block;
	color: #ffffff;
	font-size: 34px;
	font-weight: 700;
}
.counter-text .name{
	display: block;
	color: rgba(255,255,255,.9);
	font-size: 13px;
}


ul.social{
	padding: 0;
}
ul.social li{
	display: inline-block;
}

/*FOOTER*/

.footer{
	color: rgba(255,255,255,.5);

}
.footer .heading{
	color: #ffffff;
	font-size: 20px;
}
.footer ul{
	margin: 0;
	padding: 0;
}
.footer ul li{
	list-style: none;
	margin-bottom: 10px;
}
.footer ul li a{
	color: rgba(255,255,255,1);
}


@media screen and (max-width: 500px) {

	.icon{
		text-align: left;
	}

	.text-services{
		padding-left: 0;
		padding-right: 20px;
		text-align: left;
	}

}


    </style>


<meta name="robots" content="noindex, follow">
<script nonce="90d58fe6-0079-49e8-b156-56df57d59009">try{(function(w,d){!function(j,k,l,m){if(j.zaraz)console.error("zaraz is loaded twice");else{j[l]=j[l]||{};j[l].executed=[];j.zaraz={deferred:[],listeners:[]};j.zaraz._v="5808";j.zaraz._n="90d58fe6-0079-49e8-b156-56df57d59009";j.zaraz.q=[];j.zaraz._f=function(n){return async function(){var o=Array.prototype.slice.call(arguments);j.zaraz.q.push({m:n,a:o})}};for(const p of["track","set","debug"])j.zaraz[p]=j.zaraz._f(p);j.zaraz.init=()=>{var q=k.getElementsByTagName(m)[0],r=k.createElement(m),s=k.getElementsByTagName("title")[0];s&&(j[l].t=k.getElementsByTagName("title")[0].text);j[l].x=Math.random();j[l].w=j.screen.width;j[l].h=j.screen.height;j[l].j=j.innerHeight;j[l].e=j.innerWidth;j[l].l=j.location.href;j[l].r=k.referrer;j[l].k=j.screen.colorDepth;j[l].n=k.characterSet;j[l].o=(new Date).getTimezoneOffset();if(j.dataLayer)for(const t of Object.entries(Object.entries(dataLayer).reduce(((u,v)=>({...u[1],...v[1]})),{})))zaraz.set(t[0],t[1],{scope:"page"});j[l].q=[];for(;j.zaraz.q.length;){const w=j.zaraz.q.shift();j[l].q.push(w)}r.defer=!0;for(const x of[localStorage,sessionStorage])Object.keys(x||{}).filter((z=>z.startsWith("_zaraz_"))).forEach((y=>{try{j[l]["z_"+y.slice(7)]=JSON.parse(x.getItem(y))}catch{j[l]["z_"+y.slice(7)]=x.getItem(y)}}));r.referrerPolicy="origin";r.src="/cdn-cgi/zaraz/s.js?z="+btoa(encodeURIComponent(JSON.stringify(j[l])));q.parentNode.insertBefore(r,q)};["complete","interactive"].includes(k.readyState)?zaraz.init():j.addEventListener("DOMContentLoaded",zaraz.init)}}(w,d,"zarazData","script");window.zaraz._p=async mP=>new Promise((mQ=>{if(mP){mP.e&&mP.e.forEach((mR=>{try{const mS=d.querySelector("script[nonce]"),mT=mS?.nonce||mS?.getAttribute("nonce"),mU=d.createElement("script");mT&&(mU.nonce=mT);mU.innerHTML=mR;mU.onload=()=>{d.head.removeChild(mU)};d.head.appendChild(mU)}catch(mV){console.error(`Error executing script: ${mR}\n`,mV)}}));Promise.allSettled((mP.f||[]).map((mW=>fetch(mW[0],mW[1]))))}mQ()}));zaraz._p({"e":["(function(w,d){})(window,document)"]});})(window,document)}catch(e){throw fetch("/cdn-cgi/zaraz/t"),e;};</script></head>

<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #222222;">
	<center style="width: 100%; background-color: #f1f1f1;">
    <div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
      &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
    </div>
    <div style="max-width: 600px; margin: 0 auto;" class="email-container">
    	<!-- BEGIN BODY -->
      <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
       
      	<tr>
          <td valign="top" class="bg_white" style="padding: 1em 2.5em;">
          	<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
          		<tr>
          			<td width="40%" class="logo" style="text-align: left;">
			            <h1><a href="#">Clever Creator AI</a></h1>
			          </td>
                      <td width="60%" class="logo" style="text-align: right;">
			         
			          </td>
          		</tr>
          	</table>
          </td>
	      </tr><!-- end tr -->
          <tr>
	        <td class="bg_dark email-section" style="text-align:center;">
	        	<div class="heading-section heading-section-white">
	          	<h2>{{ $details['subject'] }}</h2>
	        	</div>
	        </td>
	      </tr><!-- end: tr -->
				<tr>
          <td valign="middle" class="hero bg_white">
          	<div class="overlay"></div>
            <table>
            	<tr>
            		<td>
            			<div class="text" style="text-align: center;">
                            {!! $details['body'] !!}
            			</div>
            		</td>
            	</tr>
            </table>
          </td>
	      </tr><!-- end tr -->
	      
	      <tr>
		      <td class="bg_white">
		        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
		        	<tr>
					      <td class="bg_white">
					        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
					          <tr>
					            <td class="bg_white email-section">
					            	<div class="heading-section" style="text-align: center; padding: 0 30px;">
					              	<h2>Best Tools</h2>
					              	<p>Can build any type of web application like eCommerce, Image Generation, Content Creation, and Intelligent Chat Assistants</p>
					            	</div>
					            	<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
					            		<tr>
			                      <td valign="top" width="50%">
			                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
			                          <tr>
			                            <td style="padding-top: 20px; padding-right: 10px;">
			                             
			                              	<h3><a href="#">AI Content Generator </a></h3>
			                              	
			                              </div>
			                            </td>
			                          </tr>
			                          <tr>
			                            <td style="padding-top: 20px; padding-right: 10px;">
			                            
			                              	<h3><a href="#">Gurtnellen, Swetzerland</a></h3>
			                              	
			                              </div>
			                            </td>
			                          </tr>
			                          <tr>
			                            <td style="padding-top: 20px; padding-right: 10px;">
			                            
			                              	<h3><a href="#">Gurtnellen, Swetzerland</a></h3>
			                              	
			                              </div>
			                            </td>
			                          </tr>
			                          <tr>
			                            <td style="padding-top: 20px; padding-right: 10px;">
			                             
			                              <div class="text-tour" style="text-align: center;">
			                              	<h3><a href="#">Gurtnellen, Swetzerland</a></h3>
			                              
			                              </div>
			                            </td>
			                          </tr>
			                        </table>
			                      </td>



			                      <td valign="top" width="50%">
			                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
			                          <tr>
			                            <td style="padding-top: 20px; padding-left: 10px;">
			                             
			                              <div class="text-tour" style="text-align: center;">
			                              	<h3><a href="#">Gurtnellen, Swetzerland</a></h3>
			                              
			                              </div>
			                            </td>
			                          </tr>
			                         
			                         
			                        </table>
			                      </td>
			                    </tr>
					            	</table>
					            </td>
					          </tr><!-- end: tr -->

					        </table>

					      </td>
					    </tr><!-- end:tr -->
		          <tr>
                    <td valign="middle" class="counter" style="background-image: url('https://i.pinimg.com/736x/c7/09/99/c709991e964bfe5df4e1edf6c5ee5f4e.jpg'); background-size: cover; padding: 4em 0;">
			          	<div class="overlay"></div>
			            <table>
			            	<tr>
			            		<td valign="middle" width="33.333%">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                          <tr>
                            <td class="counter-text">
                            	<span class="num">70+</span>
                            	<span class="name">Templates</span>
                            </td>
                          </tr>
                        </table>
                      </td>
                      <td valign="middle" width="33.333%">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                          <tr>
                            <td class="counter-text">
                            	<span class="num">3200+</span>
                            	<span class="name">Prompts</span>
                            </td>
                          </tr>
                        </table>
                      </td>
                      <td valign="middle" width="33.333%">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                          <tr>
                            <td class="counter-text">
                            	<span class="num">1000+</span>
                            	<span class="name">Satisfied Users</span>
                            </td>
                          </tr>
                        </table>
                      </td>
			            	</tr>
			            </table>
			          </td>
				      </tr><!-- end tr -->
				      <tr>
		            <td class="bg_white email-section">
		            	<div class="heading-section" style="text-align: center; padding: 0 30px;">
		              	<h2>Our Blog</h2>
		              	<p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
		            	</div>
		            	<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
		            		<tr>
                      <td valign="top" width="50%" style="padding-top: 20px;">
                        <table role="presentation" cellspacing="0" cellpadding="10" border="0" width="100%">
                          <tr>
                            <td>
                              <img src="images/blog-1.jpg" alt="" style="width: 100%; max-width: 600px; height: auto; margin: auto; display: block;">
                            </td>
                          </tr>
                          <tr>
                            <td class="text-services" style="text-align: left;">
                            	<p class="meta"><span>Posted on Feb 18, 2019</span> <span>Food</span></p>
                            	<h3>Business Key to Success</h3>
                             	<p>Far far away, behind the word mountains, far from the countries</p>
                             	<p><a href="#" class="btn btn-primary">Read more</a></p>
                            </td>
                          </tr>
                        </table>
                      </td>
                      <td valign="top" width="50%" style="padding-top: 20px;">
                        <table role="presentation" cellspacing="0" cellpadding="10" border="0" width="100%">
                          <tr>
                            <td>
                              <img src="images/blog-2.jpg" alt="" style="width: 100%; max-width: 600px; height: auto; margin: auto; display: block;">
                            </td>
                          </tr>
                          <tr>
                            <td class="text-services" style="text-align: left;">
                            	<p class="meta"><span>Posted on Feb 18, 2019</span> <span>Food</span></p>
                            	<h3>Web Design Technique</h3>
                              <p>Far far away, behind the word mountains, far from the countries</p>
                              <p><a href="#" class="btn btn-primary">Read more</a></p>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
		            	</table>
		            </td>
		          </tr><!-- end: tr -->
		          
		          <tr>
		            <td class="bg_white email-section" style="width: 100%;">
		            	<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
		            		<tr>
		            			<td valign="middle" width="50%">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                          <tr>
                            <td>
                              <img src="https://images.nightcafe.studio/jobs/hNyLOTbreyw1LrYkV8WZ/hNyLOTbreyw1LrYkV8WZ--2--vz5me_7.8125x.jpg?tr=w-1600,c-at_max" alt="" style="width: 100%; max-width: 600px; height: auto; margin: auto; display: block;">
                            </td>
                          </tr>
                        </table>
                      </td>
                      <td valign="middle" width="50%">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                          <tr>
                            <td class="text-services" style="text-align: left; padding-left:25px;">
                            	<div class="heading-section">
								              	<h2>Things to know before traveling</h2>
								            	</div>
								            	<div class="services-list">
								            		<div class="text">
								            			<h3>1. Pack up your things</h3>
								            			<p>A small river named Duden flows by their place and supplies</p>
								            		</div>
								            	</div>
								            	<div class="services-list">
								            		<div class="text">
								            			<h3>2. Search for Destination</h3>
								            			<p>A small river named Duden flows by their place and supplies</p>
								            		</div>
								            	</div>
								            	<div class="services-list">
								            		<div class="text">
								            			<h3>3. Be Responsible</h3>
								            			<p>A small river named Duden flows by their place and supplies</p>
								            		</div>
								            	</div>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
		            	</table>
		            </td>
		          </tr><!-- end: tr -->
		        </table>

		      </td>
		    </tr><!-- end:tr -->
      <!-- 1 Column Text + Button : END -->
      </table>
      <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
      	<tr>
          <td valign="middle" class="bg_black footer email-section">
            <table>
            	<tr>
                <td valign="top" width="60%" style="padding-top: 20px;">
                  <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                      <td style="text-align: left; padding-right: 10px;">
                      	<h3 class="heading">About</h3>
                      	<p>Empower Your Creativity with Our AI: Generate Images, Craft Content, and Chat Seamlessly with Our OpenAI-Powered Assistant!</p>
                      </td>
                    </tr>
                  </table>
                </td>
                <td valign="top" width="40%" style="padding-top: 20px;">
                  <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                      <td style="text-align: left; padding-left: 5px; padding-right: 5px;">
                      	<h3 class="heading">Contact Info</h3>
                      	<ul>
                        <li><span class="text">203 Fake St. Mountain View, San Francisco, California, USA</span></li>
                        <li><span class="text">+2 392 3929 210</span></a></li>
					              </ul>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr><!-- end: tr -->
        <tr>
        	<td valign="middle" class="bg_black footer email-section">
        		<table>
            	<tr>
                <td valign="top" width="33.333%">
                  <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                      <td style="text-align: left; padding-right: 10px;">
                      	<p>&copy; 2024 Clever Creator AI</p>
                      </td>
                    </tr>
                  </table>
                </td>
                {{-- <td valign="top" width="33.333%">
                  <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                      <td style="text-align: right; padding-left: 5px; padding-right: 5px;">
                      	<p><a href="#" style="color: rgba(255,255,255,.4);">Unsubcribe</a></p>
                      </td>
                    </tr>
                  </table>
                </td> --}}
              </tr>
            </table>
        	</td>
        </tr>
      </table>

    </div>
  </center>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');
</script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"8d056cc3bdd4ce52","serverTiming":{"name":{"cfExtPri":true,"cfL4":true}},"version":"2024.8.0","token":"cd0b4b3a733644fc843ef0b185f98241"}' crossorigin="anonymous"></script>
</body>
</html>