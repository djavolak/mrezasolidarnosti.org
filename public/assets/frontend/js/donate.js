import Donate from "./donate/Donate.js";

document.addEventListener('DOMContentLoaded', () => {
    const donate = new Donate({
        container: document.getElementById('profileContent'),
        initiatorElements: document.querySelectorAll('.project')
    });
    donate.init();
});