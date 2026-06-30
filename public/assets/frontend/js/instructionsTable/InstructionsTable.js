export default class InstructionsTable {
    #setupComplete = false;
    mobileBreakpoint = window.matchMedia('(max-width: 768px)');
    #currentView;
    container;
    desktopTemplate;
    mobileTemplate;
    VIEW_DESKTOP = 'desktop';
    VIEW_MOBILE = 'mobile';
    #getInstructionsEndpoint = '/donor/getInstructions';
    #csrfInput;
    #data = [];
    constructor({container}) {
        this.container = container;
    }


    init() {
        if(this.#setupComplete) {
            return;
        }
        this.#setElements();
        this.#addListeners();
        this.#fetchData().then(() => {
           this.#render();
           this.#setupComplete = true;
        });
    }

    #setElements() {
        this.desktopTemplate = document.getElementById('desktopTemplateInstructionsTable');
        this.mobileTemplate = document.getElementById('mobileTemplateInstructionsTable');
        this.#csrfInput = document.querySelector('input[name^="_csrf"]');
    }

    #setCurrentView() {
        this.mobileBreakpoint.matches ? this.#currentView = this.VIEW_MOBILE : this.#currentView = this.VIEW_DESKTOP;
    }

    #addListeners() {
        this.mobileBreakpoint.addEventListener('change', () => {
            this.#render();
        });
    }

    #render() {
        this.#setCurrentView();
        switch(this.#currentView) {
            case this.VIEW_DESKTOP:
                this.#renderDesktop();
                break;
            case this.VIEW_MOBILE:
                this.#renderMobile();
                break;
        }
    }

    #renderDesktop() {
        const template = this.desktopTemplate.content.cloneNode(true);
        const table = template.querySelector('table');
        const tbody = table.querySelector('tbody');
        this.#data.forEach((instruction) => {
            const tr = document.createElement('tr');
            Object.keys(instruction).forEach((instructionKey) => {
                if (instructionKey === 'expiresAt') {
                    return;
                }
                const td = document.createElement('td');
                if (instructionKey === 'status') {
                    td.classList.add('status');
                    if (instruction[instructionKey].value === 1) {
                        tr.classList.add('new');
                        td.appendChild(this.#createDonateButton());
                    } else {
                        const button = document.createElement('div');
                        button.classList.add(`status${instruction[instructionKey].value}`);
                        button.textContent = instruction[instructionKey]['label'];
                        td.appendChild(button);
                    }
                } else if(instructionKey === 'createdAt') {
                    if(instruction['expiresAt']) {
                        td.classList.add('expires');
                        td.innerHTML = `<div><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M5 22H19" stroke="#CF3443" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M5 2H19" stroke="#CF3443" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M17 22V17.828C16.9999 17.2976 16.7891 16.789 16.414 16.414L12 12L7.586 16.414C7.2109 16.789 7.00011 17.2976 7 17.828V22" stroke="#CF3443" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M7 2V6.172C7.00011 6.70239 7.2109 7.21101 7.586 7.586L12 12L16.414 7.586C16.7891 7.21101 16.9999 6.70239 17 6.172V2" stroke="#CF3443" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<span>Rok za uplatu ističe ${instruction['expiresAt']}</span></div>`; //@TODO TRANSLATE
                    } else {
                        td.textContent = instruction[instructionKey];
                    }
                } else {
                    td.textContent = instruction[instructionKey];
                }
                tr.appendChild(td);
            });
            tbody.appendChild(tr);
        });
        this.container.appendChild(table);
    }

    #createDonateButton() {
        const button = document.createElement('div');
        button.classList.add('donateButton');
        button.innerHTML = `<svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.2479 1.62712C10.9699 1.34905 10.6399 1.12846 10.2767 0.977961C9.9135 0.827462 9.52419 0.75 9.13103 0.75C8.73787 0.75 8.34856 0.827462 7.98535 0.977961C7.62213 1.12846 7.29212 1.34905 7.01418 1.62712L6.43735 2.20395L5.86053 1.62712C5.2991 1.0657 4.53765 0.750291 3.74368 0.750291C2.9497 0.750291 2.18825 1.0657 1.62683 1.62712C1.0654 2.18854 0.75 2.95 0.75 3.74397C0.75 4.53794 1.0654 5.29939 1.62683 5.86082L6.43735 10.6713L11.2479 5.86082C11.526 5.58288 11.7465 5.25287 11.897 4.88965C12.0475 4.52644 12.125 4.13713 12.125 3.74397C12.125 3.35081 12.0475 2.9615 11.897 2.59828C11.7465 2.23507 11.526 1.90506 11.2479 1.62712Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg> DONIRAJ`; //@TODO translate
        return button;
    }

    #renderMobile() {

    }

    async #fetchData() {
        const formData = new FormData();
        formData.append(this.#csrfInput.name, this.#csrfInput.value);
        const res = await fetch(this.#getInstructionsEndpoint, {
            method: 'POST',
            page: 1,
            body: formData
        });
        const resData = await res.json();
        if(resData?.data?.token) {
            this.#replaceCsrf(resData.data.token);
        }
        if(resData?.success && resData?.data?.instructions?.items) {
            this.#data.push(...resData.data.instructions.items);
        }
    }

    #replaceCsrf(val) {
        this.#csrfInput.value = val;
    }
}