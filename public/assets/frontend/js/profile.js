import Profile from "./profile/Profile.js";

document.addEventListener('DOMContentLoaded', () => {
    const profile = new Profile({
        container: document.getElementById('profileContent'),
        initiatorElements: document.querySelectorAll('.project')
    });
    profile.init();
});