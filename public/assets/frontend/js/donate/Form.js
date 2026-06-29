export default class Form {

    #setupComplete = false;
    #form;
    eventEmitter;
    #logoElement;
    #titleElement;
    #backToProfileButton;
    #closeFormButton;
    #frequencyTriggers;
    #frequencyInput;
    #frequencyActiveTrigger;
    #paymentMethodsTriggers;
    #paymentTemplate;
    #formFieldsContainer;
    #projectInput;
    #messagesContainer;
    #changeDonationButton;
    constructor({form, eventEmitter}) {
        this.#form = form;
        this.eventEmitter = eventEmitter;
    }

    init() {
        if(this.#setupComplete) {
            return;
        }
        this.#setElements();
        this.#listenToEvents();
        this.#addListeners();
        this.#setupComplete = true;
    }

    #setElements() {
        this.#logoElement = this.#form.querySelector('#donationFormLogo');
        this.#titleElement = this.#form.querySelector('h2');
        this.#backToProfileButton = this.#form.querySelector('#backToProfile');
        this.#closeFormButton = this.#form.querySelector('#closeForm');
        this.#frequencyTriggers = this.#form.querySelectorAll('#frequencyFields .trigger');
        this.#frequencyInput = this.#form.querySelector('#frequencyInput');
        this.#frequencyActiveTrigger = this.#form.querySelector('#frequencyFields .trigger.active');
        this.#paymentMethodsTriggers = this.#form.querySelectorAll('#paymentMethodFields .trigger');
        this.#paymentTemplate = document.getElementById('paymentTemplate');
        this.#formFieldsContainer = document.getElementById('donationFormFields');
        this.#projectInput = document.getElementById('projectInput');
        this.#messagesContainer = document.querySelector('.messagesContainer');
        this.#changeDonationButton = document.getElementById('changeDonation');

    }

    #listenToEvents() {
        this.eventEmitter.on('showForm', (project) => {
            window.scrollTo({
                top: 0,
                left: 0,
            });
            this.#form.className = '';
            this.#form.classList.add('active');
            this.#form.classList.add(project.id);
            this.#logoElement.innerHTML = project.logo;
            this.#titleElement.textContent = project.thankYouTitle;
            this.#projectInput.value = project.projectId;
        });
        this.eventEmitter.on('prefillForm', (data) => {
            this.#prefill(data);
        });
    }

    // Pre-fills the form with the donor's existing donation data: frequency and,
    // for each saved payment method, its amount (currency is derived from the method).
    #prefill(data) {
        if (data.frequency !== null && data.frequency !== undefined) {
            const frequencyTrigger = Array.from(this.#frequencyTriggers)
                .find((trigger) => trigger.getAttribute('data-value') === String(data.frequency));
            if (frequencyTrigger) {
                this.#frequencyActiveTrigger?.classList.remove('active');
                frequencyTrigger.classList.add('active');
                this.#frequencyActiveTrigger = frequencyTrigger;
                this.#frequencyInput.value = frequencyTrigger.getAttribute('data-value');
            }
        }

        // Clear any previously built payment fields / active triggers first.
        this.#paymentMethodsTriggers.forEach((trigger) => trigger.classList.remove('active'));
        this.#formFieldsContainer.querySelectorAll('.formFields[data-method]').forEach((fields) => fields.remove());

        (data.paymentMethods ?? []).forEach((paymentMethod) => {
            const trigger = Array.from(this.#paymentMethodsTriggers)
                .find((trigger) => trigger.getAttribute('data-method') === String(paymentMethod.type));
            if (!trigger) {
                return;
            }
            // Reuse the trigger's own handler to build the amount/currency fields.
            trigger.click();
            const fields = this.#formFieldsContainer.querySelector(`.formFields[data-method="${paymentMethod.type}"]`);
            const amountInput = fields?.querySelector('input[type="number"]');
            if (amountInput) {
                amountInput.value = paymentMethod.amount;
            }
        });
    }


    #addListeners() {
        this.#backToProfileButton.addEventListener('click', this.#close);
        this.#changeDonationButton.addEventListener('click', this.#close);
        this.#closeFormButton.addEventListener('click', this.#close);
        this.#frequencyTriggers.forEach((trigger) => {
            trigger.addEventListener('click', () => {
                this.#frequencyActiveTrigger.classList.remove('active');
                this.#frequencyInput.value = trigger.getAttribute('data-value');
                trigger.classList.add('active');
                this.#frequencyActiveTrigger = trigger;
            });
        });
        this.#paymentMethodsTriggers.forEach((trigger) => {
           trigger.addEventListener('click', () => {
                const method = trigger.getAttribute('data-method');
                if(trigger.classList.contains('active')) {
                    trigger.classList.remove('active');
                    const existing = document.querySelector(`.formFields[data-method="${method}"]`);
                    existing.remove();
                } else {
                    trigger.classList.add('active');
                    const template = this.#paymentTemplate.content.cloneNode(true);
                    template.querySelector('h3').textContent = 'IZNOS ' + trigger.parentElement.querySelector(':scope >span').textContent;
                    template.querySelector('.formFields').setAttribute('data-method', method);
                    template.querySelector('input[type="number"]').name = `payment[${method}][value]`;
                    // template.querySelectorAll('input[type="radio"]').forEach((radio) => {
                    //    radio.name = `payment[${method}][currency]`;
                    // });
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `payment[${method}][currency]`;
                    const button = document.createElement('span');
                    button.classList.add('currency', 'active');
                    const amountInput = template.querySelector('.amountInput');
                    switch(method) {
                        case '1':
                            input.value = '1';
                            button.setAttribute('data-currency', 'RSD');
                            button.textContent = 'RSD';
                            amountInput.placeholder = 'min 500 RSD';
                            break;
                        case '2':
                        case '3':
                        case '4':
                            input.value = '2';
                            button.setAttribute('data-currency', 'EUR');
                            button.textContent = 'EUR';
                            amountInput.placeholder = 'min 10 EUR';
                            break;
                    }
                    const currenciesElement = template.querySelector('.currencies');
                    currenciesElement.appendChild(input);
                    currenciesElement.appendChild(button);
                    // template.querySelectorAll('.currency').forEach((currencyButton) => {
                    //     currencyButton.addEventListener('click', () => {
                    //        const currency = currencyButton.getAttribute('data-currency');
                    //        const inputs = currencyButton.parentElement.querySelectorAll('input');
                    //        inputs.forEach((input) => {
                    //           input.checked = currency === input.getAttribute('data-currency');
                    //        });
                    //        currencyButton.parentElement.querySelector('.currency.active').classList.remove('active');
                    //        currencyButton.classList.add('active');
                    //     });
                    // });
                    this.#formFieldsContainer.appendChild(template);
                }
           });
        });

        this.#form.addEventListener('submit', async (e) => {
            e.preventDefault();
            this.#messagesContainer.innerHTML = '';
            const res = await fetch(this.#form.action, {
                method: 'POST',
                body: new FormData(this.#form)
            });
            const resData = await res.json();
            if(!resData.success) {
                if(resData.data.errors.length) {
                    resData.data.errors.forEach((error) => {
                        if (Array.isArray(error)) {
                            error.forEach((errorEntry) => {
                                this.#messagesContainer.appendChild(this.#getMessageElement(errorEntry, 'error'));
                            });
                            this.#scrollToMessagesContainer();
                        } else {
                            this.#messagesContainer.appendChild(this.#getMessageElement(error, 'error'));
                            this.#scrollToMessagesContainer();
                        }
                    });
                }
            } else {
                this.#messagesContainer.appendChild(this.#getMessageElement('Uspešno ste sačuvali izmene.', 'success'))
                this.#scrollToMessagesContainer();
            }
            if(resData.data.token) {
                this.#replaceCsrf(resData.data.token);
            }
        });
    }

    #scrollToMessagesContainer() {
        const top = this.#messagesContainer.getBoundingClientRect().top + window.scrollY - 100;

        window.scrollTo({
            top,
            behavior: 'smooth'
        });
    }

    #getMessageElement(message, type) {
        const messageElement = document.createElement('span');
        messageElement.textContent = message;
        messageElement.classList.add(type);
        return messageElement;
    }

    #replaceCsrf(val) {
        this.#form.querySelector('input[name^="_csrf"]').value = val;
    }

    #close = (e) => {
        e.preventDefault();
        this.#form.className = '';
        this.#form.classList.remove('active');
        this.eventEmitter.emit('formClosed');
    }
}