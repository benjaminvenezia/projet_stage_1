let observer = new IntersectionObserver(function(observables) {
    observables.forEach(function(observable){
        if(observable.intersectionRatio > 0.5) {
            observable.target.classList.remove('not-visible');
        } else {
            observable.target.classList.add('not-visible');
        }
    });
}, {
    threshold: [0.5]
});

let items = document.querySelectorAll('.card__presentation')
items.forEach(function(item){
    item.classList.add('not-visible');
    observer.observe(item);
});