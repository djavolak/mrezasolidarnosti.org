import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";

export default class Registersuccessbox extends Block {

    container;
    titleInput;
    descriptionInput;
    subtitleInput;
    secondDescriptionInput;
    buttonTextInput;
    buttonLinkInput;
    buttonSvgInput;
    blockBuilder;

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
            this.secondDescriptionInput,
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
            .buildTextArea({
                label: 'Second Description',
                inputName: `${this.getBaseInputName()}[${this.getName()}][secondDescription]`,
                value: this.data?.secondDescription ?? null,
                type: 'textarea'
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
    }

    getBlockInputData() {
        return {
            title: this.titleInput.value,
            description: this.descriptionInput.value,
            subtitle: this.subtitleInput.value,
            secondDescription: this.secondDescriptionInput.value,
            buttonText: this.buttonTextInput.value,
            buttonLink: this.buttonLinkInput.value,
            buttonSvg: this.buttonSvgInput.value,
        };
    }

    destroy() {
        super.destroyBase();
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
