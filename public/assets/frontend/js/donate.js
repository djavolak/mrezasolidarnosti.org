import Donate from "./donate/Donate.js";

document.addEventListener('DOMContentLoaded', () => {
    const profile = new Donate({
        container: document.getElementById('profileContent'),
        initiatorElements: document.querySelectorAll('.project')
    });
    profile.init();
});