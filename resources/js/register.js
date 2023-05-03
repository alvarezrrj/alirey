$(document).ready(() => {
    $('.flexselect').flexselect();

    $('#code_flexselect').on('focusout', formatCCode);

    // If there's a selected item, assign it to input box
    selected = Array.from($('#code')[0].children).find(option => option.selected);
    if(selected) {
        $('#code_flexselect').val(selected.innerText).trigger('focusout');
    }

    let placeholder = document.querySelector('#flexselect-classes');
    $('#code_flexselect_dropdown').addClass(placeholder.classList.value);
    placeholder.remove();
});

function formatCCode(e) {
    if (e.target.value == 'Código de pais') return;
    let content = e.target.value.split(' ').filter(v => v != '');
	e.target.value = content.length > 1 ? (content[0] + content[1]) : e.target.value;
}
