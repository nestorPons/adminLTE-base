import "./bootstrap";
import '@fortawesome/fontawesome-free/js/all';

const app = {
    isLoading: false, // Indicador de carga
    load: function() {
        const container = document.getElementsByClassName("sidebar")[0];
        const navLinks = container.getElementsByClassName("nav-bar-link");
        const navLinksAsync = document.getElementsByClassName("nav-link-async");

        // Convertir HTMLCollection o NodeList a arrays
        const navLinksArray = Array.from(navLinks);
        const navLinksAsyncArray = Array.from(navLinksAsync);
        
        // Combinar los dos arrays
        const combinedArray = navLinksArray.concat(navLinksAsyncArray);
        for (const element of combinedArray) {
            this.reactiveNavLinks(element)
        } 
    },
    reactiveNavLinks: function(link){
        link.addEventListener("click", event => {
            console.log("reactiveNavLinks")
            event.preventDefault();
            if (this.isLoading) {
                return; // Evitar peticiones adicionales si ya hay una en curso
            }

            const url = link.getAttribute("href");
            if (url) {
                const contentSection = document.querySelector(".content");
                this.isLoading = true; // Marcar que una petición está en curso

                axios
                .get(url)
                .then((response) => {
                    contentSection.innerHTML = response.data;
                    //if (window.Livewire && typeof window.Livewire.restart === "function") {
                    //    window.Livewire.restart();
                    //    this.load()
                    //}
                })
                .catch((error) => {
                    contentSection.innerHTML = error;
                    
                    console.error("Error al cargar el contenido:", error);
                })
                .finally(() => {
                    this.isLoading = false; // Restablecer el indicador después de la petición
                });
            }
            history.pushState({ url }, "", "");
        });
    }
}

document.addEventListener("DOMContentLoaded", function() {
    app.load();
    // Colorea las filas de las tablas seleccionadas
    // Manejar el clic del botón que agrega la clase
    $('.content').on('click','.row .show-selected', function () {
        // Seleccionar todos los elementos padre con la clase .row y agregar la clase row-selected
        $('.row-selected').removeClass('row-selected')
        $(this).closest('.row').addClass('row-selected');
    });
        
    Livewire.on('showMessage', ({ title, message, type }) => {
        console.log(title, message, type)
        Swal.fire(title , message, type);
    });

});

