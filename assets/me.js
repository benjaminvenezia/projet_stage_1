const debounce = (callback, wait) => {
    let timeoutId = null;
    return (...args) => {
      window.clearTimeout(timeoutId);
      timeoutId = window.setTimeout(() => {
        callback.apply(null, args);
      }, wait);
    };
  }

  const me = document.getElementById('me');
  const bigC = document.querySelector('.outer-wrapper');
  let legleft = document.querySelector('.me .leg-left');

  const moveLegLeft = debounce(() => {
    legleft.classList.toggle("move-leg-left");
  }, 1);

  const moveCharacter = debounce(ev => {
        console.log(bigC.scrollTop);
        me.style.left = `${bigC.scrollTop}px`;
  }, 8);

  bigC.addEventListener('scroll', moveCharacter);
  bigC.addEventListener('scroll', moveLegLeft);




//const bigC = document.querySelector('.outer-wrapper');






