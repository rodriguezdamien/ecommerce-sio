document.addEventListener("DOMContentLoaded", function () {
    var carouselTrending = new Splide("#trending", {
        perPage: 7,
        rewind: false,
        width: "1300px",
        gap: "10px",
        perMove: 1,
        breakpoints: {
            2000: {
                perPage: 6,
                width: "1400px",
            },
            1470: {
                perPage: 5,
                width: "1000px",
            },
            1000: {
                perPage: 4,
                width: 900,
            },
            750: {
                perPage: 3,
            },
            500: {
                perPage: 2,
            },
        },
    });
    carouselTrending.mount();
    var carouselPreorder = new Splide("#preorder", {
        perPage: 7,
        rewind: false,
        width: "1300px",
        gap: "10px",
        perMove: 1,
        breakpoints: {
            2000: {
                perPage: 6,
                width: "1400px",
            },
            1470: {
                perPage: 5,
                width: "1000px",
            },
            1000: {
                perPage: 4,
                width: 900,
            },
            750: {
                perPage: 3,
            },
            500: {
                perPage: 2,
            },
        },
    });
    carouselPreorder.mount();
});

let acc = document.getElementsByClassName("accordion");

for (let i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
        this.classList.toggle("activated");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
        } else {
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    });
}
