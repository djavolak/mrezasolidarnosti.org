import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import TextEditor from "https://skeletor.greenfriends.systems/skeletorjs/src/TextEditor/TextEditor.js";

export default class About extends Block {

    static PROJECT_COUNT = 2;

    container;
    blockBuilder;
    #editors = [];
    #projects = [];
    #firstTitleContainer;
    #firstDescriptionInput;
    #firstFooterInput;
    #secondTitleContainer;
    #secondDescriptionInput;
    #secondFooterInput;

    constructor({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions}) {
        super({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions});
        this.blockBuilder = new BlockBuilder({containerType: BlockBuilder.CONTAINER_TYPES.ONE_COLUMN});
        this.#generateView();
    }

    #generateView() {
        [this.container] = this.blockBuilder.getSpread();

        // First part
        this.#firstTitleContainer = BlockBuilder.getInput({
            label: 'First Title',
            value: this.data?.firstTitle ?? '',
            placeholder: 'Title',
            inputName: `${this.getBaseInputName()}[${this.getName()}][firstTitle]`,
        });
        this.container.appendChild(this.#firstTitleContainer);
        this.#firstDescriptionInput = this.#generateEditor('First Description', 'firstDescription');
        this.#firstFooterInput = this.#generateEditor('First Footer Text', 'firstFooterText');

        // Second part
        this.#secondTitleContainer = BlockBuilder.getInput({
            label: 'Second Title',
            value: this.data?.secondTitle ?? '',
            placeholder: 'Title',
            inputName: `${this.getBaseInputName()}[${this.getName()}][secondTitle]`,
        });
        this.container.appendChild(this.#secondTitleContainer);
        this.#secondDescriptionInput = this.#generateEditor('Second Description', 'secondDescription');

        for (let i = 0; i < About.PROJECT_COUNT; i++) {
            this.#generateProject(i, this.data?.projects?.[i] ?? null);
        }

        this.#secondFooterInput = this.#generateEditor('Second Footer Text', 'secondFooterText');
    }

    #generateEditor(labelText, key) {
        const label = document.createElement('label');
        label.innerText = labelText;
        this.container.appendChild(label);

        const editorContainer = document.createElement('div');
        const valueInput = document.createElement('input');
        valueInput.type = 'hidden';
        valueInput.name = `${this.getBaseInputName()}[${this.getName()}][${key}]`;
        valueInput.value = this.data?.[key] ?? '';
        this.container.appendChild(editorContainer);
        this.container.appendChild(valueInput);

        const editor = new TextEditor(editorContainer, valueInput, this.data?.[key] ?? '');
        editor.init();
        this.#editors.push(editor);

        return valueInput;
    }

    #generateProject(index, projectData) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('whatWeDoProjectEditor');

        const heading = document.createElement('label');
        heading.classList.add('whatWeDoProjectLabel');
        heading.innerText = `Project ${index + 1}`;
        wrapper.appendChild(heading);

        const svgContainer = BlockBuilder.getTextArea({
            label: 'SVG code',
            value: projectData?.svg ?? '',
            placeholder: '<svg ...></svg>',
            inputName: `${this.getBaseInputName()}[${this.getName()}][projects][${index}][svg]`,
        });
        wrapper.appendChild(svgContainer);

        const titleContainer = BlockBuilder.getInput({
            label: 'Title',
            value: projectData?.title ?? '',
            placeholder: 'Title',
            inputName: `${this.getBaseInputName()}[${this.getName()}][projects][${index}][title]`,
        });
        wrapper.appendChild(titleContainer);

        const descContainer = BlockBuilder.getTextArea({
            label: 'Description',
            value: projectData?.description ?? '',
            placeholder: 'Description',
            inputName: `${this.getBaseInputName()}[${this.getName()}][projects][${index}][description]`,
        });
        wrapper.appendChild(descContainer);

        this.container.appendChild(wrapper);

        this.#projects.push({svgContainer, titleContainer, descContainer});
    }

    getBlockInputData() {
        return {
            firstTitle: this.#firstTitleContainer.querySelector('input').value,
            firstDescription: this.#firstDescriptionInput.value,
            firstFooterText: this.#firstFooterInput.value,
            secondTitle: this.#secondTitleContainer.querySelector('input').value,
            secondDescription: this.#secondDescriptionInput.value,
            secondFooterText: this.#secondFooterInput.value,
            projects: this.#projects.map((project) => ({
                svg: project.svgContainer.querySelector('textarea').value,
                title: project.titleContainer.querySelector('input').value,
                description: project.descContainer.querySelector('textarea').value,
            })),
        };
    }

    destroy() {
        super.destroyBase();
        this.#editors.forEach((editor) => editor.destroy());
        this.#editors = [];
        this.#projects = [];
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
