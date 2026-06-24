import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import TextEditor from "https://skeletor.greenfriends.systems/skeletorjs/src/TextEditor/TextEditor.js";
import {blockSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/blockSelectors.js";

export default class Sidebyside extends Block {

    static PADDING_TYPES = {big: 'Big', small: 'Small'};

    container;
    titleInput;
    blockBuilder;
    #descriptionEditor;
    #descriptionInput;
    #linkTextContainer;
    #linkUrlContainer;
    #topPaddingContainer;
    #bottomPaddingContainer;

    constructor({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions}) {
        super({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions});
        this.blockBuilder = new BlockBuilder({containerType: BlockBuilder.CONTAINER_TYPES.ONE_COLUMN});
        this.#generateView();
    }

    #generateView() {
        [
            this.container,
            this.titleInput,
        ] = this.blockBuilder
            .buildTextArea({
                label: 'Title',
                inputName: `${this.getBaseInputName()}[${this.getName()}][title]`,
                value: this.data?.title ?? null,
                type: 'textarea'
            })
            .getSpread();

        this.#generateDescriptionEditor();
        this.#generateLinkInputs();

        this.#topPaddingContainer = this.#buildSelect({
            label: 'Top Padding',
            inputName: `${this.getBaseInputName()}[${this.getName()}][topPadding]`,
            options: Sidebyside.PADDING_TYPES,
            value: this.data?.topPadding ?? 'big',
        });
        this.container.appendChild(this.#topPaddingContainer);

        this.#bottomPaddingContainer = this.#buildSelect({
            label: 'Bottom Padding',
            inputName: `${this.getBaseInputName()}[${this.getName()}][bottomPadding]`,
            options: Sidebyside.PADDING_TYPES,
            value: this.data?.bottomPadding ?? 'big',
        });
        this.container.appendChild(this.#bottomPaddingContainer);
    }

    #buildSelect({label, inputName, options, value}) {
        const container = document.createElement('div');
        container.classList.add(blockSelectors.classes.inputContainer);

        const labelEl = document.createElement('label');
        labelEl.innerText = label;
        container.appendChild(labelEl);

        const select = document.createElement('select');
        select.name = inputName;
        select.classList.add(blockSelectors.classes.input);
        Object.keys(options).forEach((key) => {
            const option = document.createElement('option');
            option.value = key;
            option.innerText = options[key];
            if (value && value === key) {
                option.selected = true;
            }
            select.appendChild(option);
        });
        container.appendChild(select);

        return container;
    }

    #generateLinkInputs() {
        this.#linkTextContainer = BlockBuilder.getInput({
            label: 'Link Text (optional)',
            value: this.data?.linkText ?? '',
            placeholder: 'Link Text',
            inputName: `${this.getBaseInputName()}[${this.getName()}][linkText]`,
        });
        this.container.appendChild(this.#linkTextContainer);

        this.#linkUrlContainer = BlockBuilder.getInput({
            label: 'Link URL (optional)',
            value: this.data?.linkUrl ?? '',
            placeholder: 'Link URL',
            inputName: `${this.getBaseInputName()}[${this.getName()}][linkUrl]`,
        });
        this.container.appendChild(this.#linkUrlContainer);
    }

    #generateDescriptionEditor() {
        const label = document.createElement('label');
        label.innerText = 'Description';
        this.container.appendChild(label);

        const editorContainer = document.createElement('div');
        this.#descriptionInput = document.createElement('input');
        this.#descriptionInput.type = 'hidden';
        this.#descriptionInput.name = `${this.getBaseInputName()}[${this.getName()}][description]`;
        this.#descriptionInput.value = this.data?.description ?? '';
        this.container.appendChild(editorContainer);
        this.container.appendChild(this.#descriptionInput);

        this.#descriptionEditor = new TextEditor(editorContainer, this.#descriptionInput, this.data?.description ?? '');
        this.#descriptionEditor.init();
    }

    getBlockInputData() {
        return {
            title: this.titleInput.value,
            description: this.#descriptionInput.value,
            linkText: this.#linkTextContainer.querySelector('input').value,
            linkUrl: this.#linkUrlContainer.querySelector('input').value,
            topPadding: this.#topPaddingContainer.querySelector('select').value,
            bottomPadding: this.#bottomPaddingContainer.querySelector('select').value,
        };
    }

    destroy() {
        super.destroyBase();
        this.#descriptionEditor.destroy();
        this.#descriptionEditor = null;
        this.#descriptionInput = null;
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
