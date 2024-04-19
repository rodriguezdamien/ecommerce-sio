//juste pour la maquette
var stockCount = 1;
const stockDisplay = document.getElementById("quantity");
const plus = document.getElementById("plus");
const minus = document.getElementById("minus");
plus.addEventListener(
    "click",
    //add 1 to stockDisplay and stockCount
    () => {
        console.log(stockDisplay.getAttribute("data-quantity"));
        if (stockCount < stockDisplay.getAttribute("data-quantity")) {
            stockCount++;
            stockDisplay.innerHTML = stockCount;
        }
    }
);
minus.addEventListener(
    "click",
    //remove 1 to stockDisplay and stockCount
    () => {
        if (stockCount > 1) {
            stockCount--;
            stockDisplay.innerHTML = stockCount;
        }
    }
);

var carouselRecommend = new Splide("#recommend", {
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
carouselRecommend.mount();
