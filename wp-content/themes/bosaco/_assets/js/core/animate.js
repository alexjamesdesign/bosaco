(function () {
    var elements;
    var windowHeight;

    function init() {
        elements = document.querySelectorAll('.animate, .animate-quick');
        windowHeight = window.innerHeight;
    }

    function checkPosition() {
        for (var i = 0; i < elements.length; i++) {
            var element = elements[i];
            var positionFromTop = elements[i].getBoundingClientRect().top;

            if (positionFromTop - windowHeight <= 0) {
                if(element.dataset.animate) {
                    element.classList.add(element.dataset.animate);
                }
            }
        }
    }

    window.addEventListener('scroll', checkPosition);
    window.addEventListener('resize', init);

    init();
    checkPosition();
})();