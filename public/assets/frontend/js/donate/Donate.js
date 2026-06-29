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
        this.#openExistingProject();
        this.#setupComplete = true;
    }

    // When the donor already has donation data, open the matching project's popup
    // (their single project, or the "all projects" -1 one) and pre-fill the form.
    #openExistingProject() {
        const existingData = this.#getExistingData();
        if (!existingData || existingData.projectId === null || existingData.projectId === undefined) {
            return;
        }
        const project = this.#projects.find((project) => project.projectId === String(existingData.projectId));
        if (!project) {
            return;
        }
        this.eventEmitter.emit('showForm', project);
        this.eventEmitter.emit('prefillForm', existingData);
    }

    #getExistingData() {
        const element = document.getElementById('donationExistingData');
        if (!element) {
            return null;
        }
        try {
            return JSON.parse(element.textContent);
        } catch (e) {
            return null;
        }
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