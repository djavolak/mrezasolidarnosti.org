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

export default class Valuecards extends Block {

    container;
    blockBuilder;
    #tabbedContent;
    #images = [];
    #cardEditors = [];
    #nextAvailableCardId = 0;

    constructor({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions}) {
        super({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions});
        this.blockBuilder = new BlockBuilder({containerType: BlockBuilder.CONTAINER_TYPES.ONE_COLUMN});
        this.#generateView();
    }

    #generateView() {
        [this.container] = this.blockBuilder.getSpread();
        this.#generateCards();
    }

    #generateCards() {
        const label = document.createElement('label');
        label.classList.add('valueCardsLabel');
        label.innerText = 'Cards';
        this.container.appendChild(label);

        const tabs = document.createElement('div');
        tabs.classList.add(tabbedContentSelectors.classes.tabs);
        this.container.appendChild(tabs);

        this.#tabbedContent = new TabbedContent(this.container, {
            tabText: 'Card',
            appendNumberToTabText: true,
            tabContent: null,
        });
        this.#tabbedContent.init();
        this.#listenToTabbedEvents();

        if (this.data?.cards) {
            this.data.cards.forEach((cardData) => {
                const tab = this.#tabbedContent.addTab(false);
                this.#populateTabContent(tab, cardData);
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

    #populateTabContent(data, cardData = null) {
        const container = data.tabContent;
        container.classList.add(blockSelectors.classes.blockContentContainer, BlockBuilder.CONTAINER_TYPES.ONE_COLUMN);

        const imageContainer = ImageInForm.generateHTML({
            inputName: `${this.getBaseInputName()}[${this.getName()}][cards][${this.#nextAvailableCardId}][imageId]`,
            label: 'Image',
            chooseImageText: 'Choose image',
            imageId: cardData?.imageId ?? null,
            src: cardData?.filename ?? null,
        });
        const image = new ImageInForm(imageContainer);
        container.appendChild(imageContainer);
        image.init();
        this.#images.push(image);

        const titleInput = BlockBuilder.getInput({
            label: 'Title',
            value: cardData?.title ?? '',
            placeholder: 'Title',
            inputName: `${this.getBaseInputName()}[${this.getName()}][cards][${this.#nextAvailableCardId}][title]`,
        });
        container.appendChild(titleInput);

        const editorLabel = document.createElement('label');
        editorLabel.innerText = 'Description';
        container.appendChild(editorLabel);

        const editorContainer = document.createElement('div');
        const editorValueInput = document.createElement('input');
        editorValueInput.type = 'hidden';
        editorValueInput.name = `${this.getBaseInputName()}[${this.getName()}][cards][${this.#nextAvailableCardId}][description]`;
        editorValueInput.value = cardData?.description ?? '';
        container.appendChild(editorContainer);
        container.appendChild(editorValueInput);

        const editor = new TextEditor(editorContainer, editorValueInput, cardData?.description ?? '');
        editor.init();
        this.#cardEditors.push({tabContent: container, editor});

        this.#nextAvailableCardId++;
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
        this.#cardEditors.forEach((entry, index) => {
            if (entry.tabContent === tabContent) {
                entry.editor.destroy();
                this.#cardEditors.splice(index, 1);
            }
        });
    }

    getBlockInputData() {
        const cards = [];
        this.#tabbedContent.getTabContents().forEach((tabContent) => {
            let filename = null;
            const image = tabContent.querySelector('img');
            if (image) {
                const url = new URL(image.src.replace(MediaLibrary.imagePath, ''));
                filename = url.pathname;
            }
            cards.push({
                imageId: tabContent.querySelector('input[name*="[imageId]"]').value,
                filename: filename,
                title: tabContent.querySelector('input[name*="[title]"]').value,
                description: tabContent.querySelector('input[type="hidden"][name*="[description]"]').value,
            });
        });

        return {
            cards: cards,
        };
    }

    destroy() {
        super.destroyBase();
        this.#cardEditors.forEach((entry) => entry.editor.destroy());
        this.#cardEditors = [];
        this.#tabbedContent.destroy();
        this.#tabbedContent = null;
        this.#images.forEach((image) => image.destroy());
        this.#images = [];
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
