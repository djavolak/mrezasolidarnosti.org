import LanguageSwitcher from "./languageSwitcher/LanguageSwitcher.js";
import Navigation from "./navigation/Navigation.js";
import Faq from "./faq/faq.js";
import LineGrid from "./lineGrid/lineGrid.js";

document.addEventListener('DOMContentLoaded', () => {
    const languageSwitcherContainers = document.querySelectorAll('.languageSwitcher');
    languageSwitcherContainers.forEach((container) => {
        const languageSwitcher = new LanguageSwitcher(container);
        languageSwitcher.init();
    });

    const headerContent = document.getElementById('headerContent');
    const hamburger = document.getElementById('hamburger');
    const closeNav = document.getElementById('closeNavigation');
    if(headerContent && hamburger && closeNav) {
        const navigation = new Navigation({
            container: headerContent,
            hamburger,
            closeNav
        });
        navigation.init();
    }

    const faqContainers = document.querySelectorAll('.faq');
    faqContainers.forEach((faqContainer) => {
        const faq = new Faq(faqContainer);
        faq.init();
    });

    const testimonialSliders = document.querySelectorAll('.testimonialSlider');
    testimonialSliders.forEach((testimonialSlider) => {
        new Swiper(testimonialSlider, {
            loop:true,
            navigation:{
                nextEl:'.swiperNext',
                prevEl:'.swiperPrev',
            },
            pagination:{
                el:'.swiperPagination',
                clickable:true,
            },
        });
    });

    const lineGridDesktop = document.querySelector('.lineGrid');
    const lineGridMobile = document.querySelector('.lineGridWrapperMobile');
    if(lineGridDesktop && lineGridMobile) {
        const lineGrid = new LineGrid(lineGridDesktop, lineGridMobile);
        lineGrid.init();
    }
});