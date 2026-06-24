import Block from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/Block.js";
import BlockBuilder from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/BlockBuilder.js";
import TabbedContent from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/TabbedContent.js";
import {blockSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/ContentEditor/Blocks/blockSelectors.js";
import {events} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/events.js";
import {tabbedContentSelectors} from "https://skeletor.greenfriends.systems/skeletorjs/src/TabbedContent/tabbedContentSelectors.js";

export default class Faq extends Block {

    container;
    titleInput;
    buttonTextInput;
    buttonLinkInput;
    blockBuilder;
    #tabbedContent;
    #nextAvailableSectionId = 0;

    constructor({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions}) {
        super({contentEditorId, id, data, label, eventEmitter, lockBlockActions, customBlockActions});
        this.blockBuilder = new BlockBuilder({containerType: BlockBuilder.CONTAINER_TYPES.ONE_COLUMN});
        this.#generateView();
    }

    #generateView() {
        [
            this.container,
            this.titleInput,
            this.buttonTextInput,
            this.buttonLinkInput,
        ] = this.blockBuilder
            .buildInput({
                label: 'Title',
                inputName: `${this.getBaseInputName()}[${this.getName()}][title]`,
                value: this.data?.title ?? null
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
            .getSpread();

        this.#generateSections();
    }

    #generateSections() {
        const label = document.createElement('label');
        label.classList.add('faqSectionsLabel');
        label.innerText = 'Questions';
        this.container.appendChild(label);

        const tabs = document.createElement('div');
        tabs.classList.add(tabbedContentSelectors.classes.tabs);
        this.container.appendChild(tabs);

        this.#tabbedContent = new TabbedContent(this.container, {
            tabText: 'Question',
            appendNumberToTabText: true,
            tabContent: null,
        });
        this.#tabbedContent.init();
        this.#listenToTabbedEvents();

        if (this.data?.sections) {
            this.data.sections.forEach((sectionData) => {
                const tab = this.#tabbedContent.addTab(false);
                this.#populateTabContent(tab, sectionData);
            });
        }
    }

    #listenToTabbedEvents() {
        this.#tabbedContent.eventEmitter.on(events.tabAdded, (data) => {
            this.#populateTabContent(data);
        });
    }

    #populateTabContent(data, sectionData = null) {
        const container = data.tabContent;
        container.classList.add(blockSelectors.classes.blockContentContainer, BlockBuilder.CONTAINER_TYPES.ONE_COLUMN);

        const questionInput = BlockBuilder.getInput({
            label: 'Question',
            value: sectionData?.question ?? '',
            placeholder: 'Question',
            inputName: `${this.getBaseInputName()}[${this.getName()}][sections][${this.#nextAvailableSectionId}][question]`,
        });
        container.appendChild(questionInput);

        const answerInput = BlockBuilder.getTextArea({
            label: 'Answer',
            value: sectionData?.answer ?? '',
            placeholder: 'Answer',
            inputName: `${this.getBaseInputName()}[${this.getName()}][sections][${this.#nextAvailableSectionId}][answer]`,
        });
        container.appendChild(answerInput);

        this.#nextAvailableSectionId++;
    }

    getBlockInputData() {
        const sections = [];
        this.#tabbedContent.getTabContents().forEach((tabContent) => {
            sections.push({
                question: tabContent.querySelector('input[name*="[question]"]').value,
                answer: tabContent.querySelector('textarea').value,
            });
        });

        return {
            title: this.titleInput.value,
            buttonText: this.buttonTextInput.value,
            buttonLink: this.buttonLinkInput.value,
            sections: sections,
        };
    }

    destroy() {
        super.destroyBase();
        this.#tabbedContent.destroy();
        this.#tabbedContent = null;
        this.blockBuilder.destroy();
        this.blockBuilder = null;
        this.container = null;
    }
}
