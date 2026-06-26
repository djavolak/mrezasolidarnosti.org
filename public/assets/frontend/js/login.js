document.addEventListener('DOMContentLoaded', (e) => {
    const forms = document.querySelectorAll('.loginActionContainer');
    if(forms) {
        forms.forEach((form) => {
            const messagesContainer = form.querySelector('.messagesContainer');
            const emailInput = form.querySelector('input[name="email"]');
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                let valid = true;
                messagesContainer.innerHTML = '';
                if(emailInput.value.trim() === '') {
                    //@TODO TRANSLATE
                    messagesContainer.appendChild(getMessageElement('Email je obavezan.', 'error'));
                    valid = false;
                } else if(!validateEmail(emailInput.value)) {
                    //@TODO TRANSLATE
                    messagesContainer.appendChild(getMessageElement('Email nije validan.', 'error'));
                    valid = false;
                }
                if(!valid) {
                    return;
                }
                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form)
                    });
                    const resData = await res.json();
                    if(!resData.success) {
                        if(resData.data.errors.length) {
                            resData.data.errors.forEach((error) => {
                                if (Array.isArray(error)) {
                                    error.forEach((errorEntry) => {
                                        messagesContainer.appendChild(getMessageElement(errorEntry, 'error'));
                                    });
                                } else {
                                    messagesContainer.appendChild(getMessageElement(error, 'error'));
                                }
                            });
                        }
                    } else {
                        if (resData.data.redirect) {
                            window.location.href = resData.data.redirect;
                        }
                    }
                } catch (e) {
                }
            });
        });
    }

    function getMessageElement(message, type) {
        const messageElement = document.createElement('span');
        messageElement.textContent = message;
        messageElement.classList.add(type);
        return messageElement;
    }

    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
});