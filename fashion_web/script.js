// Hero slider - autoplay with infinite loop
$('.hero-slider').slick({
    autoplay: true,
    autoplaySpeed: 4000,
    infinite: true,
    speed: 600,
    fade: false,
    nextArrow: $('.next'),
    prevArrow: $('.prev'),
});

// Product carousel on homepage
$('.popular-brands-content').slick({
    autoplay: true,
    autoplaySpeed: 3000,
    lazyLoad: 'ondemand',
    slidesToShow: 4,
    slidesToScroll: 1,
    draggable: true,
    swipe: true,
    touchThreshold: 10,
    nextArrow: $('.right'),
    prevArrow: $('.left'),
    responsive: [
        {
            breakpoint: 1200,
            settings: { slidesToShow: 3, slidesToScroll: 1, infinite: true }
        },
        {
            breakpoint: 1024,
            settings: { slidesToShow: 2, slidesToScroll: 1, infinite: true }
        },
        {
            breakpoint: 600,
            settings: { slidesToShow: 2, slidesToScroll: 1 }
        },
        {
            breakpoint: 480,
            settings: { slidesToShow: 1, slidesToScroll: 1, infinite: true }
        },
    ]
});

// Guarantee images carousel
$('.con').slick({
    autoplay: true,
    lazyLoad: 'ondemand',
    slidesToShow: 3,
    slidesToScroll: 1,
    draggable: true,
    swipe: true,
    touchThreshold: 10,
    responsive: [
        {
            breakpoint: 1024,
            settings: { slidesToShow: 2, slidesToScroll: 1, infinite: true }
        },
        {
            breakpoint: 600,
            settings: { slidesToShow: 2, slidesToScroll: 1 }
        },
        {
            breakpoint: 480,
            settings: { slidesToShow: 1, slidesToScroll: 1, infinite: true }
        },
    ]
});

// Handlers for profile, search, and menu toggle have been moved to user_script.js

// Countdown Timer — counts down to the next seasonal sale (Dec 31 each year)
(function () {
    const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;

    const today = new Date();
    let targetYear = today.getFullYear();
    // Target: 31 December at midnight
    let countDownDate = new Date(`December 31, ${targetYear} 23:59:59`).getTime();

    // If today is already past Dec 31, aim for next year
    if (new Date().getTime() > countDownDate) {
        countDownDate = new Date(`December 31, ${targetYear + 1} 23:59:59`).getTime();
    }

    const x = setInterval(function () {
        const now = new Date().getTime();
        const distance = countDownDate - now;

        const daysEl = document.getElementById("days");
        const hoursEl = document.getElementById("hours");
        const minutesEl = document.getElementById("minutes");
        const secondsEl = document.getElementById("seconds");

        if (daysEl) daysEl.innerText = Math.floor(distance / day);
        if (hoursEl) hoursEl.innerText = Math.floor((distance % day) / hour);
        if (minutesEl) minutesEl.innerText = Math.floor((distance % hour) / minute);
        if (secondsEl) secondsEl.innerText = Math.floor((distance % minute) / second);

        if (distance < 0) {
            clearInterval(x);
            if (daysEl) daysEl.innerText = "0";
            if (hoursEl) hoursEl.innerText = "0";
            if (minutesEl) minutesEl.innerText = "0";
            if (secondsEl) secondsEl.innerText = "0";
        }
    }, 1000);
}());
