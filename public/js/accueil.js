document.addEventListener("DOMContentLoaded", function () {
    var carouselTrending = new Splide("#trending", {
        perPage: 7,
        rewind: false,
        width: "1800px",
        gap: "6px",
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
                width: "900px",
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
        width: "1800px",
        gap: "6px",
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
var opened = null;
var click = 0;

for (let i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", openAccordion);
}

function openAccordion(event) {
    if (opened != event.currentTarget.children[1]) {
        if (opened){ 
            opened.style.maxHeight = null;
            opened.parentNode.classList.remove("activated");
        }
        opened = event.currentTarget.children[1];
        opened.style.maxHeight = opened.scrollHeight + "px";
        event.currentTarget.classList.add("activated");
    } else {
        opened.parentNode.classList.remove("activated");
        opened.style.maxHeight = null;
        opened = null;
    }
}
