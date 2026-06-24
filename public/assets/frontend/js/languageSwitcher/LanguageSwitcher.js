export default class LanguageSwitcher {

    #initComplete = false;

    constructor(container) {
        this.container = container;
        this.languageOptions = this.container.querySelector('.languageOptions');
        this.currentLanguage = this.container.querySelector('.currentLanguage');

        this.supportsHover = window.matchMedia('(hover: hover)').matches;
    }

    init() {
        if (this.#initComplete) {
            throw new Error('LanguageSwitcher is already initialized');
        }

        this.#addListeners();
        this.#initComplete = true;
    }

    #addListeners() {
        if (this.supportsHover) {
            this.container.addEventListener('mouseenter', this.#mouseEnterCallback);
            this.container.addEventListener('mouseleave', this.#mouseLeaveCallback);
        } else {
            this.currentLanguage.addEventListener('click', this.#toggleCallback);
            document.addEventListener('click', this.#outsideClickCallback);
        }
    }

    #mouseEnterCallback = () => {
        this.languageOptions.classList.add('active');
    }

    #mouseLeaveCallback = () => {
        this.languageOptions.classList.remove('active');
    }

    #toggleCallback = (e) => {
        this.languageOptions.classList.toggle('active');
    }

    #outsideClickCallback = (e) => {
        if (!this.container.contains(e.target)) {
            this.languageOptions.classList.remove('active');
        }
    }

    destroy() {
        if (!this.#initComplete) {
            throw new Error('LanguageSwitcher is not initialized');
        }

        if (this.supportsHover) {
            this.container.removeEventListener('mouseenter', this.#mouseEnterCallback);
            this.container.removeEventListener('mouseleave', this.#mouseLeaveCallback);
        } else {
            this.currentLanguage.removeEventListener('click', this.#toggleCallback);
            document.removeEventListener('click', this.#outsideClickCallback);
        }

        this.#initComplete = false;
    }
}