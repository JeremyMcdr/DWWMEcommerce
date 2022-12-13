(function(){

    var doc = document.documentElement;
    var w = window;
    console.log("COUCOU")
  
    var prevScroll = w.scrollY || doc.scrollTop;
    var curScroll;
    var direction = 0;
    var prevDirection = 0;
  
    var header = document.getElementsByClassName('header');
    // var header = document.getElementById('site-header');
  
    var checkScroll = function() {
    console.log("On ajoute hide")
  
      /*
      ** Find the direction of scroll
      ** 0 - initial, 1 - up, 2 - down
      */
  
      curScroll = w.scrollY || doc.scrollTop;
      if (curScroll > prevScroll) { 
        //scrolled up
        direction = 2;
      }
      else if (curScroll < prevScroll) { 
        //scrolled down
        direction = 1;
      }
  
      if (direction !== prevDirection) {
        toggleHeader(direction, curScroll);
      }
  
      prevScroll = curScroll;
    };
  
    var toggleHeader = function(direction, curScroll) {
      if (direction === 2 && curScroll > 115) { 
  
        //replace 52 with the height of your header in px
        console.log("On ajoute hide")
        header[0].classList.add('hide');
        prevDirection = direction;
      }
      else if (direction === 1) {
        header[0].classList.remove('hide');
        prevDirection = direction;
      }
    };
  
    window.addEventListener('scroll', checkScroll);
  
  })();
