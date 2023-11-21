document.addEventListener("DOMContentLoaded", function () {
    var carouselTrending = new Splide(".splide",{
        perPage: 5,
        rewind:true,
        width:'1280px',
        gap:'10px',
        breakpoints:{
            1280:{
                perPage:4,
                width:'900px'
            }
            
        },
    });
    carouselTrending.mount();
});
