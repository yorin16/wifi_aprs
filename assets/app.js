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
        function setupForm(formName) {
            const $typeField = $(`select[name="${formName}[type]"]`);
            const $multiFields = $(`[name^="${formName}[multi"]`);
            const $openField = $(`input[name="${formName}[open]"]`);
            const $pointsField = $(`input[name="${formName}[points]"]`);

            function showFields() {
                const selectedType = $typeField.val();
                if (selectedType === '1') {
                    $multiFields.show().prev('label').show();
                    $openField.hide().prev('label').hide();
                    $pointsField.attr('required', 'required');
                    $pointsField.show().prev('label').show();
                } else if (selectedType === '2') {
                    $multiFields.hide().prev('label').hide();
                    $openField.show().prev('label').show();
                    $pointsField.attr('required', false);
                    $pointsField.hide().prev('label').hide();
                } else {
                    $multiFields.hide().prev('label').hide();
                    $openField.hide().prev('label').hide();
                    $pointsField.attr('required', false);
                    $pointsField.hide().prev('label').hide();
                }
            }

            showFields();
            $typeField.change(showFields);
        }

        const formNames = ['question_create', 'question_edit']; // Replace with the names of your Symfony forms
        formNames.forEach(function(formName) {
            setupForm(formName);
        });
    });

});