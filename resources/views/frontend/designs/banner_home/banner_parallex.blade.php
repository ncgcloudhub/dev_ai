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

#text {
    font-size: 7em;
    color: #fff;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    z-index: 3;
    position: relative;
    display: inline-block;
    text-shadow: 7px 7px 5px rgba(0, 0, 0, 0.7);
}

.clever {
    position: relative;
    display: inline-block;
}

.creator {
    position: relative;
    display: inline-block;
    left: 130px; /* Moves "Creator" right */
    /* transition: left 0.5s ease-out; */
}


#bg{
    z-index: 1;
}
#mountain{
    z-index: 2;
}
#astronaut{
    z-index: 4;
}
</style>


<section class=" pb-0" id="a">

            <img src="{{ asset('frontend/parallex_images/background_moon.png') }}" id="bg">
            <img src="{{ asset('frontend/parallex_images/mountain.png') }}" id="mountain">
            <h3 id="text">
              <span class="clever">Clever</span>
              <span class="creator">Creator</span>
          </h3>
          
          
            <img src="{{ asset('frontend/parallex_images/astronaut.png') }}" id="astronaut">     
   
           
</section>

<script>
    let bg = document.getElementById("bg");
    let astronaut = document.getElementById("astronaut");
    let mountain = document.getElementById("mountain");
    let text = document.getElementById("text");
    
    window.addEventListener('scroll', function(){
        var value = window.scrollY;

        bg.style.top = value * 1.5 + 'px';
        // astronaut.style.top = value * 1.5 + 'px';
        mountain.style.top = value * 1.5 + 'px';
        // text.style.top = value * 2 + 'px';
        let zoomFactor = 1 + (value * 0.002); // Adjust zoom speed as needed
        text.style.transform = `scale(${zoomFactor})`;





    })
</script>