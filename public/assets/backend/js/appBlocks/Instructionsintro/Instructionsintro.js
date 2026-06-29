import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import TextEditor from "https://skeletor.greenfriends.systems/skeletorjs/src/TextEditor/TextEditor.js";

export default class Instructionsintro extends Block {

    container;
    titleInput;
    descriptionInput;
    buttonTextInput;
    buttonSvgInput;
    infoTitleInput;
    infoDescriptionInput;
    blockBuilder;
    #linkTextEditor;
    #linkTextInput;

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
            this.buttonTextInput,
            this.buttonSvgInput,
            this.infoTitleInput,
            this.infoDescriptionInput,
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
                label: 'Button Text',
                inputName: `${this.getBaseInputName()}[${this.getName()}][buttonText]`,
                value: this.data?.buttonText ?? null
            })
            .buildTextArea({
                label: 'Button SVG code',
                inputName: `${this.getBaseInputName()}[${this.getName()}][buttonSvg]`,
                value: this.data?.buttonSvg ?? null,
                type: 'textarea'
            })
            .buildInput({
                label: 'Info Title',
                inputName: `${this.getBaseInputName()}[${this.getName()}][infoTitle]`,
                value: this.data?.infoTitle ?? null
            })
            .buildTextArea({
                label: 'Info Description',
                inputName: `${this.getBaseInputName()}[${this.getName()}][infoDescription]`,
                value: this.data?.infoDescription ?? null,
                type: 'textarea'
            })
            .getSpread();

        this.#generateLinkTextEditor();
    }

    #generateLinkTextEditor() {
        const label = document.createElement('label');
        label.innerText = 'Link Text';
        this.container.appendChild(label);

        const editorContainer = document.createElement('div');
        this.#linkTextInput = document.createElement('input');
        this.#linkTextInput.type = 'hidden';
        this.#linkTextInput.name = `${this.getBaseInputName()}[${this.getName()}][linkText]`;
        this.#linkTextInput.value = this.data?.linkText ?? '';
        this.container.appendChild(editorContainer);
        this.container.appendChild(this.#linkTextInput);

        this.#linkTextEditor = new TextEditor(editorContainer, this.#linkTextInput, this.data?.linkText ?? '');
        this.#linkTextEditor.init();
    }

    getBlockInputData() {
        return {
            title: this.titleInput.value,
            description: this.descriptionInput.value,
            linkText: this.#linkTextInput.value,
            buttonText: this.buttonTextInput.value,
            buttonSvg: this.buttonSvgInput.value,
            infoTitle: this.infoTitleInput.value,
            infoDescription: this.infoDescriptionInput.value,
        };
    }

    destroy() {
        super.destroyBase();
        this.#linkTextEditor.destroy();
        this.#linkTextEditor = null;
        this.#linkTextInput = null;
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
