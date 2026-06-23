export default class LineGrid {

    #initComplete = false;

    container;
    progressLine;
    collapsibleHandles;
    constructor(container) {
        this.container = container;
    }

    init() {
        if (this.#initComplete) {
            throw new Error('GridLine is already initialized');
        }
        this.collapsibleHandles = this.container.querySelectorAll('.collapsibleHandle');
        this.progressLine = this.container.querySelector('.mainLine');
        this.#addListeners();
        this.#initComplete = true;
    }


    #addListeners() {
        this.#addCollapsibleListeners();
        this.#addScrollListener();
    }


    #addCollapsibleListeners() {
        this.collapsibleHandles.forEach((handle) => {
           handle.addEventListener('click', this.collapsibleHandleCallback)
        });
    }


    collapsibleHandleCallback = (e) => {
        const content = e.target.parentElement.querySelector('.collapsible');
        content.classList.toggle('active');
    };

    #addScrollListener() {
        window.addEventListener('scroll', this.onScroll, { passive: true });
        window.addEventListener('resize', this.onScroll);

        this.onScroll();
    }

    onScroll = () => {
        const rect = this.container.getBoundingClientRect();
        const viewportHeight = window.innerHeight;

        const start = viewportHeight * 0.55;

        const end = rect.height + viewportHeight * 0.15;

        const rawProgress = (start - rect.top) / end;

        const progress = Math.max(0, Math.min(1, rawProgress));

        this.progressLine.style.background = `
            linear-gradient(
                to bottom,
                rgba(38, 33, 133,1) 0%,
                rgba(38, 33, 133,1) ${progress * 100}%,
                rgba(38,33,133,0.2) ${progress * 100}%,
                rgba(38,33,133,0.2) 100%
            )
        `;
    };


    destroy() {
        if(this.collapsibleHandles) {
            this.collapsibleHandles.forEach((handle) => {
                handle.removeEventListener('click', this.collapsibleHandleCallback);
            });
        }
        window.removeEventListener('scroll', this.onScroll);
        window.removeEventListener('resize', this.onScroll);
    }
}