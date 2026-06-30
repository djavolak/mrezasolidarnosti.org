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
    #data;
    constructor({container}) {
        this.container = container;
    }


    init() {
        if(this.#setupComplete) {
            return;
        }
        this.#setElements();
        this.#addListeners();
        this.#render();
        this.#setupComplete = true;
    }

    #setElements() {
        this.desktopTemplate = document.getElementById('desktopTemplateInstructionsTable');
        this.mobileTemplate = document.getElementById('mobileTemplateInstructionsTable');
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
        this.container.appendChild(table);
    }

    #renderMobile() {

    }
}