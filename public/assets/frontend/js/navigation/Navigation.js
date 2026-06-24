export default class Navigation {

    #initComplete = false;
    constructor({container, hamburger, closeNav}) {
        this.container = container;
        this.hamburger = hamburger;
        this.closeNav = closeNav;
    }

    init() {
        if(this.#initComplete) {
            throw new Error('Navigation is already initialized');
        }
        this.#addListeners();
        this.#initComplete = true;
    }

    #addListeners() {
        this.hamburger.addEventListener('click', this.#toggleCallback);
        this.closeNav.addEventListener('click', this.#closeNavCallback);
        window.addEventListener('scroll', this.#addScrollListener);
    }

    #toggleCallback = () => {
        this.container.classList.toggle('active');
        document.body.classList.toggle('freeze');
    }

    #closeNavCallback = () => {
        this.container.classList.remove('active');
        document.body.classList.remove('freeze');
    }

    #addScrollListener = () => {
        if(window.scrollY > 0) {
            this.container.parentElement.classList.add('scrolled');
        } else {
            this.container.parentElement.classList.remove('scrolled');
        }
    }

    destroy() {
        if(!this.#initComplete) {
            throw new Error('Navigation is not initialized');
        }
        this.hamburger.removeEventListener('click', this.#toggleCallback);
        this.closeNav.removeEventListener('click', this.#closeNavCallback);
    }
}