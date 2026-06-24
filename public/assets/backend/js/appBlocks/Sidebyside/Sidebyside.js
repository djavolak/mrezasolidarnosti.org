import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import TextEditor from "https://skeletor.greenfriends.systems/skeletorjs/src/TextEditor/TextEditor.js";

export default class Sidebyside extends Block {

    container;
    titleInput;
    blockBuilder;
    #descriptionEditor;
    #descriptionInput;

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
