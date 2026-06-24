document.addEventListener('DOMContentLoaded', () => {
    const submitButton = document.getElementById('formSubmit');
    const formEmailNotice = document.querySelector('#profileDataInfo p');
    const inputs = document.querySelectorAll('#profileDataInfo input');
    let isEdit = false;
    submitButton.addEventListener('click', (e) => {
        if(!isEdit) {
            e.preventDefault();
        }
        submitButton.textContent = 'Sačuvaj';
        formEmailNotice.textContent = 'U slučaju da želite da promenite e-mail stići će Vam verifikacioni e-mail koji ćete morati potvrditi.';
        inputs.forEach((input) => {
           input.disabled = false;
        });
        isEdit = true;
    });
});