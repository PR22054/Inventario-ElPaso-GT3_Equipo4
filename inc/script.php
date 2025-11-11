<script>
    document.addEventListener('DOMContentLoaded', () => {

        // elementos de navbar-burger
        const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

        // ver si hay un menu desplegado en la barra de navegacion
        if ($navbarBurgers.length > 0) {

            // evento clic
            $navbarBurgers.forEach( el => {
                el.addEventListener('click', () => {

                // traer atributo data-get
                const target = el.dataset.target;
                const $target = document.getElementById(target);

                // para alternar navbar-burger y navbar-menu
                el.classList.toggle('is-active');
                $target.classList.toggle('is-active');

                });
            });
        }
    });
</script>
<script src="./js/ajax.js"></script>