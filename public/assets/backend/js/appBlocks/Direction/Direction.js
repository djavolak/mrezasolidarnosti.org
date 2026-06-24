import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import TabbedContent from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/TabbedContent.js";
import {blockSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/blockSelectors.js";
import {events} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/events.js";
import {tabbedContentSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/tabbedContentSelectors.js";
import TextEditor from "https://skeletor.greenfriends.systems/skeletorjs/src/TextEditor/TextEditor.js";

export default class Direction extends Block {

    container;
    titleInput;
    descriptionInput;
    footerTextInput;
    blockBuilder;
    #tabbedContent;
    #projectEditors = [];
    #nextAvailableProjectId = 0;

    constructor({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions}) {
        super({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions});
        this.blockBuilder = new BlockBuilder({containerType: BlockBuilder.CONTAINER_TYPES.ONE_COLUMN});
        this.#generateView();
    }

    #generateView() {
        [
            this.container,
            this.titleInput,
            this.descriptionInput,
            this.footerTextInput,
        ] = this.blockBuilder
            .buildInput({
                label: 'Title',
                inputName: `${this.getBaseInputName()}[${this.getName()}][title]`,
                value: this.data?.title ?? null
            })
            .buildTextArea({
                label: 'Description',
                inputName: `${this.getBaseInputName()}[${this.getName()}][description]`,
                value: this.data?.description ?? null,
                type: 'textarea'
            })
            .buildInput({
                label: 'Footer Text',
                inputName: `${this.getBaseInputName()}[${this.getName()}][footerText]`,
                value: this.data?.footerText ?? null
            })
            .getSpread();

        this.#generateProjects();
    }

    #generateProjects() {
        const label = document.createElement('label');
        label.classList.add('directionProjectsLabel');
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
            this.#destroyEditorInTab(data.tabContent);
        });
    }

    #populateTabContent(data, projectData = null) {
        const container = data.tabContent;
        container.classList.add(blockSelectors.classes.blockContentContainer, BlockBuilder.CONTAINER_TYPES.ONE_COLUMN);

        const projectHTMLIdInput = BlockBuilder.getInput({
            label: 'HTML ID',
            value: projectData?.projectHTMLId ?? '',
            placeholder: 'HTML ID',
            inputName: `${this.getBaseInputName()}[${this.getName()}][projects][${this.#nextAvailableProjectId}][projectHTMLId]`,
        });
        container.appendChild(projectHTMLIdInput);

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

        const linkTextInput = BlockBuilder.getInput({
            label: 'Link Text',
            value: projectData?.linkText ?? '',
            placeholder: 'Link Text',
            inputName: `${this.getBaseInputName()}[${this.getName()}][projects][${this.#nextAvailableProjectId}][linkText]`,
        });
        container.appendChild(linkTextInput);

        const linkUrlInput = BlockBuilder.getInput({
            label: 'Link URL',
            value: projectData?.linkUrl ?? '',
            placeholder: 'Link URL',
            inputName: `${this.getBaseInputName()}[${this.getName()}][projects][${this.#nextAvailableProjectId}][linkUrl]`,
        });
        container.appendChild(linkUrlInput);

        this.#nextAvailableProjectId++;
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
            projects.push({
                projectHTMLId: tabContent.querySelector('input[name*="[projectHTMLId]"]').value,
                title: tabContent.querySelector('input[name*="[title]"]').value,
                description: tabContent.querySelector('input[type="hidden"][name*="[description]"]').value,
                linkText: tabContent.querySelector('input[name*="[linkText]"]').value,
                linkUrl: tabContent.querySelector('input[name*="[linkUrl]"]').value,
            });
        });

        return {
            title: this.titleInput.value,
            description: this.descriptionInput.value,
            footerText: this.footerTextInput.value,
            projects: projects,
        };
    }

    destroy() {
        super.destroyBase();
        this.#projectEditors.forEach((entry) => entry.editor.destroy());
        this.#projectEditors = [];
        this.#tabbedContent.destroy();
        this.#tabbedContent = null;
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
