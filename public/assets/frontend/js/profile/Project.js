export default class Project {

    #setupComplete = false;
    container;
    logo;
    thankYouTitle;
    eventEmitter;
    showFormButton;
    id;
    projectId;
    constructor({container, thankYouTitle, eventEmitter}) {
        this.container = container;
        this.thankYouTitle = thankYouTitle;
        this.eventEmitter = eventEmitter;
    }

    init() {
        if(this.#setupComplete) {
            return;
        }
        this.showFormButton = this.container.querySelector('.showForm');
        this.logo = this.container.querySelector('.projectLogo').innerHTML;
        this.id = this.container.id;
        this.projectId = this.container.getAttribute('data-id');
        this.#addListeners();
        this.#setupComplete = true;
    }

    #addListeners() {
        this.container.addEventListener('mouseenter', () => {
            this.eventEmitter.emit('projectHovered', this);
        });
        this.container.addEventListener('mouseleave', () => {
            this.eventEmitter.emit('projectUnhovered', this);
        });
        this.showFormButton.addEventListener('click', () => {
            this.eventEmitter.emit('showForm', this);
        });
    }

    focusVisual() {
        this.container.classList.add('active');
        this.container.classList.remove('disabled');
    }

    disableVisual() {
        this.container.classList.remove('active');
        this.container.classList.add('disabled');
    }

    defaultVisual() {
        this.container.classList.remove('active');
        this.container.classList.remove('disabled');
    }
}