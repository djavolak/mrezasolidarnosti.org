export default class Faq {

    #initComplete = false;
    constructor(container) {
        this.container = container;
        this.sections = this.container.querySelectorAll('.faqSection');
    }


    init() {
        if (this.#initComplete) {
            throw new Error('Faq is already initialized');
        }
        this.#addListeners();
        this.#initComplete = true;
    }

    #addListeners() {
        if(this.sections) {
            this.sections.forEach(section => {
                const handle = section.querySelector('.faqQuestion');
                if(handle) {
                    handle.addEventListener('click', this.#handleCallback)
                }
            });
        }
    }

    #handleCallback(e) {
        e.stopImmediatePropagation();
        const container = e.target.closest('.faqSection');
        if(container) {
            const answer = container.querySelector('.faqAnswer');
            const icon = container.querySelector('.faqQuestion svg');
            if(answer){
                answer.classList.toggle('active');
            }
            if(icon) {
                icon.classList.toggle('active');
            }
        }
    }

    destroy() {
        if (!this.#initComplete) {
            throw new Error('Faq is not initialized');
        }
        if(this.sections) {
            this.sections.forEach(section => {
                const handle = section.querySelector('.faqQuestion');
                if(handle) {
                    handle.removeEventListener('click', this.#handleCallback)
                }
            });
        }
    }
}