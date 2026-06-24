import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import TabbedContent from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/TabbedContent.js";
import {blockSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/blockSelectors.js";
import {events} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/events.js";
import {tabbedContentSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/tabbedContentSelectors.js";
import TextEditor from "https://skeletor.greenfriends.systems/skeletorjs/src/TextEditor/TextEditor.js";

export default class Testimonials extends Block {

    container;
    descriptionInput;
    blockBuilder;
    #titleEditor;
    #titleEditorInput;
    #tabbedContent;
    #nextAvailableTestimonialId = 0;

    constructor({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions}) {
        super({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions});
        this.blockBuilder = new BlockBuilder({containerType: BlockBuilder.CONTAINER_TYPES.ONE_COLUMN});
        this.#generateView();
    }

    #generateView() {
        [
            this.container,
            this.descriptionInput,
        ] = this.blockBuilder
            .buildTextArea({
                label: 'Description',
                inputName: `${this.getBaseInputName()}[${this.getName()}][description]`,
                value: this.data?.description ?? null,
                type: 'textarea'
            })
            .getSpread();

        this.#generateTitleEditor();
        this.#generateTestimonials();
    }

    #generateTitleEditor() {
        const wrapper = document.createElement('div');

        const label = document.createElement('label');
        label.innerText = 'Title';
        wrapper.appendChild(label);

        const editorContainer = document.createElement('div');
        this.#titleEditorInput = document.createElement('input');
        this.#titleEditorInput.type = 'hidden';
        this.#titleEditorInput.name = `${this.getBaseInputName()}[${this.getName()}][title]`;
        this.#titleEditorInput.value = this.data?.title ?? '';
        wrapper.appendChild(editorContainer);
        wrapper.appendChild(this.#titleEditorInput);

        this.container.insertBefore(wrapper, this.container.firstChild);

        this.#titleEditor = new TextEditor(editorContainer, this.#titleEditorInput, this.data?.title ?? '');
        this.#titleEditor.init();
    }

    #generateTestimonials() {
        const label = document.createElement('label');
        label.classList.add('testimonialsLabel');
        label.innerText = 'Testimonials';
        this.container.appendChild(label);

        const tabs = document.createElement('div');
        tabs.classList.add(tabbedContentSelectors.classes.tabs);
        this.container.appendChild(tabs);

        this.#tabbedContent = new TabbedContent(this.container, {
            tabText: 'Testimonial',
            appendNumberToTabText: true,
            tabContent: null,
        });
        this.#tabbedContent.init();
        this.#listenToTabbedEvents();

        if (this.data?.testimonials) {
            this.data.testimonials.forEach((testimonialData) => {
                const tab = this.#tabbedContent.addTab(false);
                this.#populateTabContent(tab, testimonialData);
            });
        }
    }

    #listenToTabbedEvents() {
        this.#tabbedContent.eventEmitter.on(events.tabAdded, (data) => {
            this.#populateTabContent(data);
        });
    }

    #populateTabContent(data, testimonialData = null) {
        const container = data.tabContent;
        container.classList.add(blockSelectors.classes.blockContentContainer, BlockBuilder.CONTAINER_TYPES.ONE_COLUMN);

        const textInput = BlockBuilder.getTextArea({
            label: 'Text',
            value: testimonialData?.text ?? '',
            placeholder: 'Text',
            inputName: `${this.getBaseInputName()}[${this.getName()}][testimonials][${this.#nextAvailableTestimonialId}][text]`,
        });
        container.appendChild(textInput);

        this.#nextAvailableTestimonialId++;
    }

    getBlockInputData() {
        const testimonials = [];
        this.#tabbedContent.getTabContents().forEach((tabContent) => {
            testimonials.push({
                text: tabContent.querySelector('textarea').value,
            });
        });

        return {
            title: this.#titleEditorInput.value,
            description: this.descriptionInput.value,
            testimonials: testimonials,
        };
    }

    destroy() {
        super.destroyBase();
        this.#titleEditor.destroy();
        this.#titleEditor = null;
        this.#titleEditorInput = null;
        this.#tabbedContent.destroy();
        this.#tabbedContent = null;
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
