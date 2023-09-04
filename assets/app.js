/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import 'bootstrap';
import AOS from 'aos';
import './styles/app.scss';
import './styles/nav.scss';
import './styles/footer.scss';
import 'aos/dist/aos.css';

AOS.init({
    duration: 1000
});

window.addEventListener("DOMContentLoaded", () => {
    if( document.body.scrollHeight < window.innerHeight ) {
        const footer = document.querySelector('footer');
        if( footer ){
            footer.classList.add('fixed-bottom');
        }
    }
})

// start the Stimulus application
// import './bootstrap';
