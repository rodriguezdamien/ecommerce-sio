const productsElements = document.getElementsByClassName("cart-product");
const cart = [];
let isTextRemoved = false;
const needUpdate = document.getElementById("need-update");
const confirmUpdate = document.getElementById("confirm-update");

for (product of Array.from(productsElements)) {
    //dataset = attribut data-*
    let id = product.dataset.id;
    let qteStock = parseInt(
        product.getElementsByClassName("quantity-picker")[0].dataset.quantity
    );
    console.log(qteStock);
    let plus = product.getElementsByClassName("plus")[0];
    let minus = product.getElementsByClassName("minus")[0];
    let remove = product.getElementsByClassName("remove")[0];
    let cancel = product.getElementsByClassName("cancel")[0];
    let qte = parseInt(product.getElementsByClassName("qte")[0].innerText);
    cart.push({
        idAlbum: parseInt(id),
        qte: qte,
        isDeleted: false,
    });
    plus.addEventListener("click", function (event) {
        qte = qte < qteStock ? qte + 1 : qte;
        event.currentTarget.parentNode.getElementsByClassName(
            "qte"
        )[0].innerText = qte;
        cart.find((product) => product.idAlbum == id).qte = qte;
        showNeedUpdate();
    });
    minus.addEventListener("click", function (event) {
        if (qte == 1) {
            removeFromCart(id, event);
        } else {
            qte--;
            event.currentTarget.parentNode.getElementsByClassName(
                "qte"
            )[0].innerText = qte;
            cart.find((product) => product.idAlbum == id).qte = qte;
        }
        showNeedUpdate();
    });
    remove.addEventListener("click", function (event) {
        removeFromCart(id, event);
        showNeedUpdate();
    });
    cancel.addEventListener("click", function (event) {
        cart.find((product) => product.idAlbum == id).isDeleted = false;
        let removing = event.currentTarget
            .closest(".cart-product")
            .getElementsByClassName("removing")[0];
        removing.classList.remove("flex");
        removing.classList.add("hidden");
    });
}
function removeFromCart(id, event) {
    let removing = event.currentTarget
        .closest(".cart-product")
        .getElementsByClassName("removing")[0];
    removing.classList.remove("hidden");
    removing.classList.add("flex");
    cart.find((product) => product.idAlbum == id).isDeleted = true;
}

function showNeedUpdate() {
    if (!isTextRemoved) {
        needUpdate.classList.remove("hidden");
        needUpdate.classList.add("flex");
        isTextRemoved = true;
    }
}

confirmUpdate.addEventListener("click", function (event) {
    let newCart = cart.filter((product) => !product.isDeleted);
    let newCartJson = JSON.stringify(newCart);
    fetch("/cart/update", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: newCartJson,
    }).then(async (response) => {
        if (response.ok) {
            window.location.reload();
        } else {
            let data = await response.json();
            document.getElementById("update-error").classList.remove("hidden");
            console.log(data);
            document.getElementById("update-error-message").textContent =
                data.error;
        }
    });
});
