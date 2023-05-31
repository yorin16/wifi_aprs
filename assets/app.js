/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
// start the Stimulus application
// import './bootstrap';



/*
    Darkmode code
 */
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
window.jQuery = $;
window.$ = $;

require('bootstrap');
$(document).ready(function() {
    $('[data-toggle="popover"]').popover();

    $(function() {
        const $typeField = $('select[name="question_create[type]"]');
        const $multiFields = $('[name^="question_create[multi"]'); // find all inputs starting with "question_create[Multi"
        const $openField = $('input[name="question_create[open]"]');
        const $pointsField = $('input[name="question_create[points]"]');

        function showFields() {
            const selectedType = $typeField.val();
            console.log(selectedType);
            if (selectedType === '1') {
                $multiFields.show().prev('label').show(); // show the inputs and their labels
                $openField.hide().prev('label').hide(); // hide the input and its label
            } else if (selectedType === '2') {
                $multiFields.hide().prev('label').hide(); // hide the inputs and their labels
                $openField.show().prev('label').show(); // show the input and its label
            } else {
                console.log('else');
                $multiFields.hide().prev('label').hide(); // hide the inputs and their labels
                $openField.hide().prev('label').hide(); // hide the input and its label
                $pointsField.hide().prev('label').hide(); // hide the input and its label
            }
        }

        showFields();
        $typeField.change(showFields);
    });
});
