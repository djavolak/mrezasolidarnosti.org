import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import TabbedContent from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/TabbedContent.js";
import MediaLibrary from "https://skeletor.greenfriends.systems/skeletorjs/src/MediaLibrary/MediaLibrary.js";
import {blockSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/blockSelectors.js";
import {mediaLibrarySelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/MediaLibrary/mediaLibrarySelectors.js";
import ImageInForm from "https://skeletor.greenfriends.systems/skeletorjs/src/MediaLibrary/ImageInForm/ImageInForm.js";
import {events} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/events.js";
import {tabbedContentSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/tabbedContentSelectors.js";
import TextEditor from "https://skeletor.greenfriends.systems/skeletorjs/src/TextEditor/TextEditor.js";

export default class Projectsdisplay extends Block {

    container;
    blockBuilder;
    #tabbedContent;
    #images = [];
    #projectEditors = [];
    #nextAvailableProjectId = 0;

    constructor({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions}) {
        super({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions});
        this.blockBuilder = new BlockBuilder({containerType: BlockBuilder.CONTAINER_TYPES.ONE_COLUMN});
        this.#generateView();
    }

    #generateView() {
        [this.container] = this.blockBuilder.getSpread();
        this.#generateProjects();
    }

    #generateProjects() {
        const label = document.createElement('label');
        label.classList.add('projectsDisplayLabel');
        label.innerText = 'Projects';
        this.container.appendChild(label);

        const tabs = document.createElement('div');
        tabs.classList.add(tabbedContentSelectors.classes.tabs);
        this.container.appendChild(tabs);

        this.#tabbedContent = new TabbedContent(this.container, {
            tabText: 'Project',
            appendNumberToTabText: true,
            tabContent: null,
        });
        this.#tabbedContent.init();
        this.#listenToTabbedEvents();

        if (this.data?.projects) {
            this.data.projects.forEach((projectData) => {
                const tab = this.#tabbedContent.addTab(false);
                this.#populateTabContent(tab, projectData);
            });
        }
    }

    #listenToTabbedEvents() {
        this.#tabbedContent.eventEmitter.on(events.tabAdded, (data) => {
            this.#populateTabContent(data);
        });
        this.#tabbedContent.eventEmitter.on(events.beforeTabRemoved, (data) => {
            this.#destroyImagesInTabContent(data.tabContent);
            this.#destroyEditorInTab(data.tabContent);
        });
    }

    #populateTabContent(data, projectData = null) {
        const container = data.tabContent;
        container.classList.add(blockSelectors.classes.blockContentContainer, BlockBuilder.CONTAINER_TYPES.ONE_COLUMN);

        const imageContainer = ImageInForm.generateHTML({
            inputName: `${this.getBaseInputName()}[${this.getName()}][projects][${this.#nextAvailableProjectId}][imageId]`,
            label: 'Image',
            chooseImageText: 'Choose image',
            imageId: projectData?.imageId ?? null,
            src: projectData?.filename ?? null,
        });
        const image = new ImageInForm(imageContainer);
        container.appendChild(imageContainer);
        image.init();
        this.#images.push(image);

        const classNameInput = BlockBuilder.getInput({
            label: 'Class Name',
            value: projectData?.className ?? '',
            placeholder: 'Class Name (e.g. education)',
            inputName: `${this.getBaseInputName()}[${this.getName()}][projects][${this.#nextAvailableProjectId}][className]`,
        });
        container.appendChild(classNameInput);

        const titleInput = BlockBuilder.getInput({
            label: 'Title',
            value: projectData?.title ?? '',
            placeholder: 'Title',
            inputName: `${this.getBaseInputName()}[${this.getName()}][projects][${this.#nextAvailableProjectId}][title]`,
        });
        container.appendChild(titleInput);

        const editorLabel = document.createElement('label');
        editorLabel.innerText = 'Description';
        container.appendChild(editorLabel);

        const editorContainer = document.createElement('div');
        const editorValueInput = document.createElement('input');
        editorValueInput.type = 'hidden';
        editorValueInput.name = `${this.getBaseInputName()}[${this.getName()}][projects][${this.#nextAvailableProjectId}][description]`;
        editorValueInput.value = projectData?.description ?? '';
        container.appendChild(editorContainer);
        container.appendChild(editorValueInput);

        const editor = new TextEditor(editorContainer, editorValueInput, projectData?.description ?? '');
        editor.init();
        this.#projectEditors.push({tabContent: container, editor});

        this.#nextAvailableProjectId++;
    }

    #destroyImagesInTabContent(tabContent) {
        const imageContainers = tabContent.querySelectorAll(`.${mediaLibrarySelectors.classes.initiator}`);
        imageContainers.forEach((imageContainer) => {
            this.#images.forEach((image, index) => {
                if (image.getContainer() === imageContainer) {
                    image.destroy();
                    this.#images.splice(index, 1);
                }
            });
        });
    }

    #destroyEditorInTab(tabContent) {
        this.#projectEditors.forEach((entry, index) => {
            if (entry.tabContent === tabContent) {
                entry.editor.destroy();
                this.#projectEditors.splice(index, 1);
            }
        });
    }

    getBlockInputData() {
        const projects = [];
        this.#tabbedContent.getTabContents().forEach((tabContent) => {
            let filename = null;
            const image = tabContent.querySelector('img');
            if (image) {
                const url = new URL(image.src.replace(MediaLibrary.imagePath, ''));
                filename = url.pathname;
            }
            projects.push({
                imageId: tabContent.querySelector('input[name*="[imageId]"]').value,
                filename: filename,
                className: tabContent.querySelector('input[name*="[className]"]').value,
                title: tabContent.querySelector('input[name*="[title]"]').value,
                description: tabContent.querySelector('input[type="hidden"][name*="[description]"]').value,
            });
        });

        return {
            projects: projects,
        };
    }

    destroy() {
        super.destroyBase();
        this.#projectEditors.forEach((entry) => entry.editor.destroy());
        this.#projectEditors = [];
        this.#tabbedContent.destroy();
        this.#tabbedContent = null;
        this.#images.forEach((image) => image.destroy());
        this.#images = [];
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
