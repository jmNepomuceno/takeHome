
const menubut = document.getElementById('menu');

const home = document.getElementById('home');
const about = document.getElementById('about');
const portfolio = document.getElementById('portfolio');
const feature = document.getElementById('feature');




function menus(){

    var aside = document.getElementById('aside');

    aside.classList.toggle('hidden');
    aside.classList.add ('fixed');
    

}


   function scrollToContent() {
      const element = document.getElementById('homescroll');
      element.scrollIntoView({ behavior: 'smooth' });
   }



   function isInViewport(element) {
      const rect = element.getBoundingClientRect();
      return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
      );
    }



menubut.addEventListener('click', menus);


