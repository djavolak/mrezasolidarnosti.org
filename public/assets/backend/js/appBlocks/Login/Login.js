import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import TextEditor from "https://skeletor.greenfriends.systems/skeletorjs/src/TextEditor/TextEditor.js";

export default class Login extends Block {

    container;
    titleInput;
    descriptionInput;
    subtitleInput;
    buttonTextInput;
    buttonLinkInput;
    buttonSvgInput;
    blockBuilder;
    #footerEditor;
    #footerInput;

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
            this.subtitleInput,
            this.buttonTextInput,
            this.buttonLinkInput,
            this.buttonSvgInput,
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
                label: 'Subtitle',
                inputName: `${this.getBaseInputName()}[${this.getName()}][subtitle]`,
                value: this.data?.subtitle ?? null
            })
            .buildInput({
                label: 'Button Text',
                inputName: `${this.getBaseInputName()}[${this.getName()}][buttonText]`,
                value: this.data?.buttonText ?? null
            })
            .buildInput({
                label: 'Button Link',
                inputName: `${this.getBaseInputName()}[${this.getName()}][buttonLink]`,
                value: this.data?.buttonLink ?? null
            })
            .buildTextArea({
                label: 'Button SVG code',
                inputName: `${this.getBaseInputName()}[${this.getName()}][buttonSvg]`,
                value: this.data?.buttonSvg ?? null,
                type: 'textarea'
            })
            .getSpread();

        this.#generateFooterEditor();
    }

    #generateFooterEditor() {
        const label = document.createElement('label');
        label.innerText = 'Footer Text';
        this.container.appendChild(label);

        const editorContainer = document.createElement('div');
        this.#footerInput = document.createElement('input');
        this.#footerInput.type = 'hidden';
        this.#footerInput.name = `${this.getBaseInputName()}[${this.getName()}][footerText]`;
        this.#footerInput.value = this.data?.footerText ?? '';
        this.container.appendChild(editorContainer);
        this.container.appendChild(this.#footerInput);

        this.#footerEditor = new TextEditor(editorContainer, this.#footerInput, this.data?.footerText ?? '');
        this.#footerEditor.init();
    }

    getBlockInputData() {
        return {
            title: this.titleInput.value,
            description: this.descriptionInput.value,
            subtitle: this.subtitleInput.value,
            buttonText: this.buttonTextInput.value,
            buttonLink: this.buttonLinkInput.value,
            buttonSvg: this.buttonSvgInput.value,
            footerText: this.#footerInput.value,
        };
    }

    destroy() {
        super.destroyBase();
        this.#footerEditor.destroy();
        this.#footerEditor = null;
        this.#footerInput = null;
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
