//juste pour la maquette
var stockCount = 1;
const stockDisplay = document.getElementById("quantity");
const plus = document.getElementById("plus");
const minus = document.getElementById("minus");
const productInfo = document.getElementById("product-info");
const productId = productInfo.getAttribute("data-id");
const actionContainer = document.getElementById("action-container");
const cartInfo = document.getElementById("cart-info");
plus.addEventListener(
    "click",
    //add 1 to stockDisplay and stockCount
    () => {
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

document.addEventListener("DOMContentLoaded", async () => {
    let isInCart = false;
    let response = await fetch("/cart/content", {
        method: "GET",
    });
    if (response.ok) {
        let data = await response.json();
        //Check if current product is in the cart
        data.forEach((product) => {
            if (product.idAlbum == productId) {
                isInCart = true;
            }
        });
    }
    document.getElementById("action-loading").remove();
    if (isInCart) {
        setUpdateCartButton();
    }
    else {
        setAddToCartButton();
    }
});

async function addToCart(event) {
    event.currentTarget.setAttribute("disabled", "true");
    cartInfo.classList.add("visible");
    cartInfo.classList.add("opacity-1");
    cartInfo.classList.remove("invisible");
    cartInfo.classList.remove("opacity-0");
    cartInfo.style.transform = "translateY(-10px)";
    let response = await fetch("/cart/add-item", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            id: productId,
            qte: stockCount,
        }),
    });
    if (response.ok) {
        if (document.getElementById("cart-count") == null) {
            let cartCount = document.createElement("div");
            cartCount.classList.add("absolute", "font-bold", "flex", "items-center", "justify-center", "right-[-10px]", "top-[-3px]", "h-5", "w-5", "text-sm", "rounded-full", "text-center", "text-white", "bg-red-500");
            cartCount.setAttribute("id", "cart-count");
            cartCount.textContent = "1";
        }
        else {
            let cartCount = document.getElementById("cart-count");
            cartCount.textContent = parseInt(cartCount.textContent) + 1;
        }
        let cartInfoContent = document.getElementById("loading");
        cartInfoContent.removeAttribute("id");
        cartInfoContent.classList.add("text-black");
        cartInfoContent.textContent = "Le produit a été ajouté au panier. ";
        cartInfo.style.transform = "translateY(-12px)";
        let GotoCart = document.createElement("a");
        GotoCart.setAttribute("href", "/cart");
        GotoCart.textContent = "Voir le panier";
        GotoCart.classList.add("cursor-pointer", "text-blue-500", "hover:text-blue-700", "underline");
        cartInfoContent.appendChild(GotoCart);
        document.getElementById("add-to-cart").remove();
        setUpdateCartButton();
        setTimeout(() => {
            cartInfo.style.transform = "translateY(-10px)";
            cartInfo.classList.add("opacity-0");
            cartInfo.classList.add("invisible");
            cartInfo.classList.remove("visible");
            cartInfo.classList.remove("opacity-1");

            setTimeout(() => {
                cartInfoContent.textContent = "";
                GotoCart.remove();
                cartInfoContent.setAttribute("id", "loading");
            }, 300);
        }, 3000);

    } else if (response.status == 400) {
        let cartInfoContent = document.getElementById("loading");
        cartInfoContent.removeAttribute("id");
        cartInfoContent.classList.add("text-black");
        let data = await response.json();
        cartInfoContent.textContent = await data.error;
        event.currentTarget.removeAttribute("disabled");
        setTimeout(() => {
            cartInfo.style.transform = "translateY(-10px)";
            cartInfo.classList.add("opacity-0");
            cartInfo.classList.add("invisible");
            cartInfo.classList.remove("visible");
            cartInfo.classList.remove("opacity-1");
            setTimeout(() => {
                cartInfoContent.textContent = "";
                cartInfoContent.setAttribute("id", "loading");
            }, 300);
        }, 3000);
    }
}

async function updateCart(event) {
    event.target.setAttribute("disabled", "true");
    cartInfo.classList.add("visible");
    cartInfo.classList.add("opacity-1");
    cartInfo.classList.remove("invisible");
    cartInfo.classList.remove("opacity-0");
    cartInfo.style.transform = "translateY(0px)";
    let response = await fetch("/cart/update-item", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            id: productId,
            qte: stockCount,
        }),
    });
    if (response.ok) {
        let cartInfoContent = document.getElementById("loading");
        cartInfoContent.removeAttribute("id");
        cartInfoContent.classList.add("text-black");
        cartInfoContent.textContent = "Le panier a été mis à jour. ";
        cartInfo.style.transform = "translateY(-12px)";
        let GotoCart = document.createElement("a");
        GotoCart.setAttribute("href", "/cart");
        GotoCart.textContent = "Voir le panier";
        GotoCart.classList.add("cursor-pointer", "text-blue-500", "hover:text-blue-700", "underline");
        cartInfoContent.appendChild(GotoCart);
        event.target.removeAttribute("disabled");
        setTimeout(() => {
            cartInfo.style.transform = "translateY(-10px)";
            cartInfo.classList.add("opacity-0");
            cartInfo.classList.add("invisible");
            cartInfo.classList.remove("visible");
            cartInfo.classList.remove("opacity-1");

            setTimeout(() => {
                cartInfoContent.textContent = "";
                GotoCart.remove();
                cartInfoContent.setAttribute("id", "loading");
            }, 300);
        }, 3000);

    } else if (response.status == 400) {
        let cartInfoContent = document.getElementById("loading");
        cartInfoContent.removeAttribute("id");
        cartInfoContent.classList.add("text-black");
        let data = await response.json();
        cartInfoContent.textContent = await data.error;
        cartInfo.style.transform = "translateY(-12px)";
        setTimeout(() => {
            cartInfo.style.transform = "translateY(-10px)";
            cartInfo.classList.add("opacity-0");
            cartInfo.classList.add("invisible");
            cartInfo.classList.remove("visible");
            cartInfo.classList.remove("opacity-1");

            setTimeout(() => {
                cartInfoContent.textContent = "";
                cartInfoContent.setAttribute("id", "loading");
            }, 300);
        }, 3000);
    }

}

async function removeFromCart(event) {
    event.currentTarget.setAttribute("disabled", "true");
    cartInfo.classList.add("visible");
    cartInfo.classList.add("opacity-1");
    cartInfo.classList.remove("invisible");
    cartInfo.classList.remove("opacity-0");
    cartInfo.style.transform = "translateY(0px)";
    let response = await fetch("/cart/remove-item", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            id: productId,
        }),
    });
    if (response.ok) {
        let cartInfoContent = document.getElementById("loading");
        cartInfoContent.removeAttribute("id");
        cartInfoContent.classList.add("text-black");
        cartInfoContent.textContent = "Le produit a été retiré du panier. ";
        cartInfo.style.transform = "translateY(-12px)";
        let GotoCart = document.createElement("a");
        GotoCart.setAttribute("href", "/cart");
        GotoCart.textContent = "Voir le panier";
        GotoCart.classList.add("cursor-pointer", "text-blue-500", "hover:text-blue-700", "underline");
        document.getElementById("update-cart").remove();
        document.getElementById("remove-from-cart").remove();
        setAddToCartButton();
        cartInfoContent.appendChild(GotoCart);
        setTimeout(() => {
            cartInfo.style.transform = "translateY(-10px)";
            cartInfo.classList.add("opacity-0");
            cartInfo.classList.add("invisible");
            cartInfo.classList.remove("visible");
            cartInfo.classList.remove("opacity-1");

            setTimeout(() => {
                cartInfoContent.textContent = "";
                GotoCart.remove();
                cartInfoContent.setAttribute("id", "loading");
            }, 300);
        }, 3000);
    }

}

function setUpdateCartButton() {
    let updateCartButton = document.createElement("button");
    updateCartButton.setAttribute("id", "update-cart");
    updateCartButton.classList.add("bg-white", "text-black", "py-5", "w-[70vw]", "md:w-80", "rounded-md", "mt-7", "animate-button");
    updateCartButton.textContent = "Mettre à jour le panier";
    updateCartButton.addEventListener("click", updateCart);
    actionContainer.appendChild(updateCartButton);
    let removeFromCartButton = document.createElement("button");
    removeFromCartButton.setAttribute("id", "remove-from-cart");
    removeFromCartButton.classList.add("flex", "bg-white", "text-black", "py-5", "rounded-md", "h-16", "w-16", "justify-center", "items-center", "mt-7", "animate-button");
    let icon = document.createElement("i");
    icon.classList.add("ri-delete-bin-line");
    removeFromCartButton.appendChild(icon);
    removeFromCartButton.addEventListener("click", removeFromCart);
    actionContainer.appendChild(removeFromCartButton);
}

function setAddToCartButton() {
    let addToCartButton = document.createElement("button");
    addToCartButton.setAttribute("id", "add-to-cart");
    addToCartButton.classList.add("bg-white", "text-black", "py-5", "w-[80vw]", "md:w-96", "rounded-md", "mt-7", "animate-button");
    addToCartButton.textContent = "Ajouter au panier";
    addToCartButton.addEventListener("click", addToCart);
    actionContainer.appendChild(addToCartButton);
}


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
