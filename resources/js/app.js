import ScrollReveal from "scrollreveal";

const sr = ScrollReveal({
    origin: 'top',
    distance: '30px',
    duration: 4000,
    delay: 400,
});

sr.reveal('.qr-code', {origin: 'top', distance: '30px', duration: 4000, delay: 400,});
sr.reveal('.logo', {origin: 'top', duration: 1500});
sr.reveal('.button', {origin: 'right'});
sr.reveal('.description', {origin: 'bottom'});
sr.reveal('.footer', {origin: 'bottom'});
