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
    }


    #addListeners() {
        this.#backToProfileButton.addEventListener('click', this.#close);
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
                    template.querySelectorAll('input[type="radio"]').forEach((radio) => {
                       radio.name = `payment[${method}][currency]`;
                    });
                    template.querySelectorAll('.currency').forEach((currencyButton) => {
                        currencyButton.addEventListener('click', () => {
                           const currency = currencyButton.getAttribute('data-currency');
                           const inputs = currencyButton.parentElement.querySelectorAll('input');
                           inputs.forEach((input) => {
                              input.checked = currency === input.getAttribute('data-currency');
                           });
                           currencyButton.parentElement.querySelector('.currency.active').classList.remove('active');
                           currencyButton.classList.add('active');
                        });
                    });
                    this.#formFieldsContainer.appendChild(template);
                }
           });
        });
    }


    #close = () => {
        this.#form.className = '';
        this.#form.classList.remove('active');
        this.eventEmitter.emit('formClosed');
    }
}