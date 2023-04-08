/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
// start the Stimulus application
// import './bootstrap';
const savedTheme = localStorage.getItem('theme') || 'light';

// set the data-bs-theme attribute on the document element
document.documentElement.setAttribute('data-bs-theme', savedTheme);

// add an event listener to the button to toggle the theme
document.getElementById('btnSwitch').addEventListener('click',()=>{
    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    localStorage.setItem('theme', newTheme);
    document.documentElement.setAttribute('data-bs-theme', newTheme);
});
// any CSS you import will output into a single css file (app.css in this case)
import './styles/global.scss';

const $ = require('jquery');
require('bootstrap');
$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
})
// document.getElementById('btnSwitch').addEventListener('click',()=>{
//     if (document.documentElement.getAttribute('data-bs-theme') === 'dark') {
//         document.documentElement.setAttribute('data-bs-theme','light')
//     }
//     else {
//         document.documentElement.setAttribute('data-bs-theme','dark')
//     }
// })

// const switchTheme = () => {
//     const html = document.documentElement;
//     if (html.getAttribute('data-bs-theme') === 'dark') {
//         html.setAttribute('data-bs-theme', 'light');
//         localStorage.setItem('theme', 'light');
//     } else {
//         html.setAttribute('data-bs-theme', 'dark');
//         localStorage.setItem('theme', 'dark');
//     }
// };
//
// // Set initial theme based on stored value
// const storedTheme = localStorage.getItem('theme');
// if (storedTheme) {
//     document.documentElement.setAttribute('data-bs-theme', storedTheme);
// }
//
// document.getElementById('btnSwitch').addEventListener('click', switchTheme);

// get the saved theme from localStorage or set to 'light' as default
