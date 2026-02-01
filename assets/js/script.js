// 1. PRELOADER LOGIC (Updated for 5-second delay)
window.addEventListener('load', () => {
    const preloader = document.getElementById('preloader');
    if (preloader) {
        // This sets a timer for 5000ms (5 seconds)
        setTimeout(() => {
            // Start the fade-out animation
            preloader.classList.add('fade-out');
            
            // Wait for the animation to finish (0.6s) then delete the element
            setTimeout(() => {
                preloader.remove();
            }, 600); 
            
        }, 5000); // <--- CHANGE THIS NUMBER to change the seconds (e.g., 3000 for 3s)
    }
});

// 2. MOBILE MENU TOGGLE
function toggleMenu() {
    const nav = document.getElementById('navLinks');
    const burger = document.querySelector('.hamburger');
    
    nav.classList.toggle('active');
    burger.classList.toggle('active');
}

// 3. SCROLL REVEAL ANIMATION
window.addEventListener('scroll', reveal);

function reveal() {
    var reveals = document.querySelectorAll('.reveal');
    for (var i = 0; i < reveals.length; i++) {
        var windowheight = window.innerHeight;
        var revealtop = reveals[i].getBoundingClientRect().top;
        var revealpoint = 150;
        if (revealtop < windowheight - revealpoint) {
            reveals[i].classList.add('active');
        }
    }
}
reveal(); // Trigger once on load

// 4. FAQ ACCORDION LOGIC
const faqs = document.querySelectorAll(".faq-question");

faqs.forEach(faq => {
    faq.addEventListener("click", () => {
        faqs.forEach(otherFaq => {
            if (otherFaq !== faq) {
                otherFaq.parentElement.classList.remove("active");
            }
        });
        const item = faq.parentElement;
        item.classList.toggle("active");
    });
});