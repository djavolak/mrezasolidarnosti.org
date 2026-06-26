import Project from "./Project.js";
import EventEmitter from "../EventEmitter/EventEmitter.js";
import Form from "./Form.js";

export default class Donate {
    #setupComplete = false;
    #initiatorElements;
    #container;
    #projects = [];
    #form;
    eventEmitter = new EventEmitter();

    constructor({initiatorElements, container}) {
        this.#initiatorElements = initiatorElements;
        this.#container = container;
    }
    init() {
        if(this.#setupComplete) {
            return;
        }
        this.#setProjects();
        this.#setForm();
        this.#listenToEvents();
        this.#setupComplete = true;
    }

    #setProjects() {
        this.#initiatorElements.forEach((initiatorElement) => {
           const project = new Project({
               container:initiatorElement,
               thankYouTitle: initiatorElement.getAttribute('data-title') ?? 'Hvala što ste izabrali da donirate za Mrežu solidarnosti',
               eventEmitter: this.eventEmitter
           });
           project.init();
           this.#projects.push(project);
        });
    }


    #setForm() {
        this.#form = new Form({form: document.getElementById('donationForm'), eventEmitter: this.eventEmitter});
        this.#form.init();
    }

    #listenToEvents() {
        this.eventEmitter.on('projectHovered', (hoveredProject) => {
            this.#projects.forEach((project) => {
                if(project === hoveredProject) {
                    project.focusVisual();
                } else {
                    project.disableVisual();
                }
            });
        });
        this.eventEmitter.on('projectUnhovered', (unhoveredProject) => {
            this.#projects.forEach((project) => {
                project.defaultVisual();
            });
        });

        this.eventEmitter.on('showForm', (project) => {
            this.#container.classList.add('hidden');
        });
        this.eventEmitter.on('formClosed', () => {
            this.#container.classList.remove('hidden');
        });
    }

}