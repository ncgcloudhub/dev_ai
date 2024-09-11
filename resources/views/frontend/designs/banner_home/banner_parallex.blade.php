<style>

body
{
  background: #0a2a43;
  min-height: 1500px;
}
#a
{
  position: relative;
  width: 100%;
  height: 100vh;
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* section:before
{
  content: '';
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 100px;
  background: linear-gradient(to top, #ffffff, transparent);
  z-index: 10000;
} */

/* section:after
{
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #0a2a43;
  z-index: 10000;
  mix-blend-mode: color;
} */

#a img
{
  position: absolute;
  top: 0;
  Left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  pointer-events: none;
}

#text{
    position: relative;
    color: #fff;
    font-size: 10em;
    z-index: 1;
    font-family: Verdana, Geneva, Tahoma, sans-serif
}
#road{
    z-index: 2;
}
#robot{
    z-index: 3;
}
</style>


<section class=" pb-0" id="a">

  
            <img src="{{ asset('frontend/bg.png') }}" id="bg">
            {{-- <img src="{{ asset('frontend/moon.png') }}" id="moon"> --}}
            <h1 id="text">Clever Creator</h1>
            <img src="{{ asset('frontend/robo.png') }}" id="robot">
            {{-- <img src="{{ asset('frontend/mountain.png') }}" id="mountain">
            <img src="{{ asset('frontend/road.png') }}" id="road"> --}}
        
   
           
</section>

<script>
    let bg = document.getElementById("bg");
    // let moon = document.getElementById("moon");
    let robot = document.getElementById("robot");
    let road = document.getElementById("road");
    let text = document.getElementById("text");
    
    window.addEventListener('scroll', function(){
        var value = window.scrollY;

        bg.style.top = value * 1.5 + 'px';
        // moon.style.left = -value * 0.5 + 'px';
        robot.style.bottom = -value * 1.5 + 'px';
        road.style.top = value * 0.15 + 'px';
        text.style.left = value * 1 + 'px';
    })
</script>