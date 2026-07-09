import Translator from "./Translator/Translator.js";

document.addEventListener('DOMContentLoaded', () => {
   const forms = document.querySelectorAll('.registrationForm');
   if(forms) {
       forms.forEach((form) => {
           const messagesContainer = form.querySelector('.messagesContainer');
           const firstNameInput = form.querySelector('input[name="firstName"]');
           const lastNameInput = form.querySelector('input[name="lastName"]');
           const emailInput = form.querySelector('input[name="email"]');
           form.addEventListener('submit', async (e) => {
               e.preventDefault();
               let valid = true;
               messagesContainer.innerHTML = '';
               if(firstNameInput.value.trim() === '') {
                   messagesContainer.appendChild(getMessageElement(Translator.translate('Name is required.'), 'error'));
                   valid = false;
               }
               if(lastNameInput.value.trim() === '') {
                   messagesContainer.appendChild(getMessageElement(Translator.translate('Surname is required.'), 'error'));
                   valid = false;
               }
               if(emailInput.value.trim() === '') {
                   messagesContainer.appendChild(getMessageElement(Translator.translate('Email is required.'), 'error'));
                   valid = false;
               } else if(!validateEmail(emailInput.value)) {
                   messagesContainer.appendChild(getMessageElement(Translator.translate('Email is not valid.'), 'error'));
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
                       if(resData.data.token) {
                           replaceCsrf(form, resData.data.token);
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

   function replaceCsrf(form, val) {
       form.querySelector('input[name^="_csrf"]').value = val;
   }
});