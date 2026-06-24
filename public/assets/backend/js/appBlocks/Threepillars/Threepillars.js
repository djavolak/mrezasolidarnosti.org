import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import MediaLibrary from "https://skeletor.greenfriends.systems/skeletorjs/src/MediaLibrary/MediaLibrary.js";
import ImageInForm from "https://skeletor.greenfriends.systems/skeletorjs/src/MediaLibrary/ImageInForm/ImageInForm.js";
import TextEditor from "https://skeletor.greenfriends.systems/skeletorjs/src/TextEditor/TextEditor.js";

export default class Threepillars extends Block {

    static PILLAR_COUNT = 3;

    container;
    titleInput;
    descriptionInput;
    blockBuilder;
    #imageRefs = [];
    #pillars = [];

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
            .getSpread();

        this.#generateImage('imageDesktop', 'Desktop Illustration');
        this.#generateImage('imageMobile', 'Mobile Illustration');
        this.#generatePillars();
    }

    #generateImage(key, labelText) {
        const imageContainer = ImageInForm.generateHTML({
            inputName: `${this.getBaseInputName()}[${this.getName()}][${key}Id]`,
            label: labelText,
            chooseImageText: 'Choose image',
            imageId: this.data?.[`${key}Id`] ?? null,
            src: this.data?.[`${key}Filename`] ?? null,
        });
        const image = new ImageInForm(imageContainer);
        this.container.appendChild(imageContainer);
        image.init();

        const svgContainer = BlockBuilder.getTextArea({
            label: `${labelText} SVG code (overrides image)`,
            value: this.data?.[`${key}Svg`] ?? '',
            placeholder: '<svg ...></svg>',
            inputName: `${this.getBaseInputName()}[${this.getName()}][${key}Svg]`,
        });
        this.container.appendChild(svgContainer);

        this.#imageRefs.push({key, imageContainer, image, svgContainer});
    }

    #generatePillars() {
        for (let i = 0; i < Threepillars.PILLAR_COUNT; i++) {
            this.#generatePillar(i, this.data?.pillars?.[i] ?? null);
        }
    }

    #generatePillar(index, pillarData) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('pillarEditor');

        const heading = document.createElement('label');
        heading.classList.add('pillarLabel');
        heading.innerText = `Pillar ${index + 1}`;
        wrapper.appendChild(heading);

        const titleInput = BlockBuilder.getInput({
            label: 'Title',
            value: pillarData?.title ?? '',
            placeholder: 'Title',
            inputName: `${this.getBaseInputName()}[${this.getName()}][pillars][${index}][title]`,
        });
        wrapper.appendChild(titleInput);

        const editorLabel = document.createElement('label');
        editorLabel.innerText = 'Description';
        wrapper.appendChild(editorLabel);

        const editorContainer = document.createElement('div');
        const editorValueInput = document.createElement('input');
        editorValueInput.type = 'hidden';
        editorValueInput.name = `${this.getBaseInputName()}[${this.getName()}][pillars][${index}][description]`;
        editorValueInput.value = pillarData?.description ?? '';
        wrapper.appendChild(editorContainer);
        wrapper.appendChild(editorValueInput);

        const buttonTextInput = BlockBuilder.getInput({
            label: 'Button Text',
            value: pillarData?.buttonText ?? '',
            placeholder: 'Button Text',
            inputName: `${this.getBaseInputName()}[${this.getName()}][pillars][${index}][buttonText]`,
        });
        wrapper.appendChild(buttonTextInput);

        const buttonLinkInput = BlockBuilder.getInput({
            label: 'Button Link',
            value: pillarData?.buttonLink ?? '',
            placeholder: 'Button Link',
            inputName: `${this.getBaseInputName()}[${this.getName()}][pillars][${index}][buttonLink]`,
        });
        wrapper.appendChild(buttonLinkInput);

        this.container.appendChild(wrapper);

        const editor = new TextEditor(editorContainer, editorValueInput, pillarData?.description ?? '');
        editor.init();

        this.#pillars.push({titleInput, editorValueInput, buttonTextInput, buttonLinkInput, editor});
    }

    getBlockInputData() {
        const data = {
            title: this.titleInput.value,
            description: this.descriptionInput.value,
            pillars: this.#pillars.map((pillar) => ({
                title: pillar.titleInput.value,
                description: pillar.editorValueInput.value,
                buttonText: pillar.buttonTextInput.value,
                buttonLink: pillar.buttonLinkInput.value,
            })),
        };

        this.#imageRefs.forEach((ref) => {
            let filename = null;
            const image = ref.imageContainer.querySelector('img');
            if (image) {
                const url = new URL(image.src.replace(MediaLibrary.imagePath, ''));
                filename = url.pathname;
            }
            data[`${ref.key}Id`] = ref.imageContainer.querySelector(`input[name*="[${ref.key}Id]"]`).value;
            data[`${ref.key}Filename`] = filename;
            data[`${ref.key}Svg`] = ref.svgContainer.querySelector('textarea').value;
        });

        return data;
    }

    destroy() {
        super.destroyBase();
        this.#pillars.forEach((pillar) => pillar.editor.destroy());
        this.#pillars = [];
        this.#imageRefs.forEach((ref) => ref.image.destroy());
        this.#imageRefs = [];
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
