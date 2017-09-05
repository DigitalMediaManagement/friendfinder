<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Who\'s your Paranormal Partner?') }}</title>

    <script>
      (function(d) {
        var config = {
          kitId: 'afr7yxr',
          scriptTimeout: 3000,
          async: true
        },
        h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
      })(document);
    </script>

    <link rel="shortcut icon" href="http://www.fox.com/sites/all/themes/fox/images/favicon.ico" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <meta property="fb:app_id" content="1420267864675222" />

    <meta property="og:type"               content="website" />

    @if ( isset($userid) && file_exists(public_path().'/storage/userimages/'.$userid.'.png') )

    <meta property="og:url"                content="http://ghostedonfox.com/share/{{ $userid }}" />
    <meta property="og:title"              content="I found my Partner in the Paranormal" />
    <meta property="og:description"        content="Find yours here!" />
    <meta property="og:image"              content="{{ asset('storage/userimages/'.$userid.'.png') }}" />

    @else

    <meta property="og:url"                content="http://ghostedonfox.com" />
    <meta property="og:title"              content="Who's your Partner in the Paranormal?" />
    <meta property="og:description"        content="Find out here!" />
    <meta property="og:image"              content="{{ url('images/ghosted-og-share.jpg') }}" />

    @endif

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <div id="app">
      
      <div class="container main-container">

        <div class="row main-row">
          <div class="col-md-6">
            <div class="friend-finder-container">
              <a class="show-logo" href="http://www.fox.com/ghosted" target="_blank"><img src="{{ url('images/ghosted-logo.png') }}" alt="Ghosted on Fox"></a>
              {{-- <h1 class="sr sr-only">Who’s Your Partner In the Paranormal?</h1> --}}
              {{-- <h2 class="sr sr-only">And Your go-to buddy for everything.</h2> --}}

              <h1 class="before"><span class="top">Who’s Your Partner</span> <span class="bottom">In the Paranormal?</span></h1>
              <h2 class="before">And Your go-to buddy for everything.</h2>

              {{-- <img class="header-text" src="{{ url( 'images/header-text.png' ) }}" alt="Who’s Your Partner
              In the Paranormal?"> --}}

              <div id="canvas">
                <div class="img-section">
                  <img class="boxes" src="{{ url( 'images/boxes.png' ) }}" alt="" width="1200" height="630">

                  <div class="left-image"><img src="{{ url( 'images/blank.jpg' ) }}" alt="Left Image"></div>
                  <div class="right-image"><img src="{{ url( 'images/blank.jpg' ) }}" alt="Right Image"></div>
                </div>
                <div class="button-container">
                  <button disabled id="loginbutton" class="btn btn-primary facebook"><i class="fa fa-facebook" aria-hidden="true"></i> LOG IN TO FACEBOOK TO FIND OUT</button>
                </div>
              </div>

              <p>By clicking [Log in to Facebook to Find Out], you agree to the <a target="_target" href="http://www.fox.com/policy">Fox Privacy Policy</a> and <a target="_target" href="http://www.fox.com/terms">Fox Terms Of Use</a></p>
            </div>
          </div>
        </div>

      </div>

      <img class="bottom-right hidden-xs hidden-sm" src="{{ url('images/guys.png') }}" alt="Adam and Craig" width="788" height="939">

    </div>

    <script src="{{ mix('js/app.js') }}"></script>
  </body>
</html>