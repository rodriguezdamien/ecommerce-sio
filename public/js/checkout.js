const mailRegex =
    /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(?:\.[a-zA-Z]{2,})?$/;
const expDateRegex = /^\d{2}\/\d{2}$/;
document.addEventListener("DOMContentLoaded", function () {
    let mail = document.getElementById("mail");
    let adress = document.getElementById("adress");
    let adress2 = document.getElementById("adress-2");
    let cp = document.getElementById("postal");
    let city = document.getElementById("city");
    let cardNumber = document.getElementById("card-number");
    let expirationDate = document.getElementById("exp-date");
    let securityCode = document.getElementById("security-code");
    let form = document.getElementById("customer-form");
    let cardName = document.getElementById("card-name");
    let firstName = document.getElementById("first-name");
    let lastName = document.getElementById("last-name");
    let phone = document.getElementById("phone");

    phone.addEventListener("keydown", function (event) {
        if (
            (event.key.match(/[0-9]/) === null &&
                event.key !== "Backspace" &&
                event.key !== "Tab") ||
            (phone.value.length == 14 &&
                event.key !== "Backspace" &&
                event.key !== "Tab")
        ) {
            event.preventDefault();
        }
        if (
            (phone.value.length == 2 ||
                phone.value.length == 5 ||
                phone.value.length == 8 ||
                phone.value.length == 11) &&
            event.key !== "Backspace"
        ) {
            phone.value += " ";
        }
        if (event.key === "Backspace") {
            if (
                phone.value.length == 4 ||
                phone.value.length == 7 ||
                phone.value.length == 10 ||
                phone.value.length == 13
            ) {
                phone.value = phone.value.slice(0, -1);
            }
        }
    });
    // Gestionnaires d'événements pour éviter des saisies incorrectes, contient quelques petites lacunes...
    expirationDate.addEventListener("paste", function (event) {
        event.preventDefault();
        const pasteData = (event.clipboardData || window.clipboardData).getData(
            "text"
        );
        if (expDateRegex.test(pasteData)) {
            expirationDate.value = pasteData;
        }
    });
    expirationDate.addEventListener("keydown", function (event) {
        if (
            (event.key.match(/[0-9]/) === null &&
                event.key !== "Backspace" &&
                event.key !== "/" &&
                event.key !== "Tab") ||
            (event.key === "/" && expirationDate.value.length != 2) ||
            (expirationDate.value.length == 5 &&
                event.key !== "Backspace" &&
                event.key !== "Tab")
        ) {
            event.preventDefault();
        } else {
            if (event.key === "Backspace") {
                if (expirationDate.value.length == 4) {
                    expirationDate.value = expirationDate.value.slice(0, 3);
                }
            } else if (expirationDate.value.length == 2 && event.key !== "/") {
                expirationDate.value += "/";
            }
        }
    });

    cardNumber.addEventListener("keydown", function (event) {
        if (
            (event.key.match(/[0-9]/) === null &&
                event.key !== "Backspace" &&
                event.key !== "Tab") ||
            (cardNumber.value.length == 19 &&
                event.key !== "Backspace" &&
                event.key !== "Tab")
        ) {
            event.preventDefault();
        }
        if (
            (cardNumber.value.length == 4 ||
                cardNumber.value.length == 9 ||
                cardNumber.value.length == 14) &&
            event.key !== "Backspace"
        ) {
            cardNumber.value += " ";
        }
        if (event.key === "Backspace") {
            if (
                cardNumber.value.length == 6 ||
                cardNumber.value.length == 11 ||
                cardNumber.value.length == 16
            ) {
                cardNumber.value = cardNumber.value.slice(0, -1);
            }
        }
    });

    cp.addEventListener("keydown", function (event) {
        if (
            (event.key.match(/[0-9]/) === null &&
                event.key !== "Backspace" &&
                event.key !== "Tab") ||
            (cp.value.length == 5 &&
                event.key !== "Backspace" &&
                event.key !== "Tab")
        ) {
            event.preventDefault();
        }
    });

    securityCode.addEventListener("keydown", function (event) {
        if (
            (event.key.match(/[0-9]/) === null &&
                event.key !== "Backspace" &&
                event.key !== "Tab") ||
            (securityCode.value.length == 3 &&
                event.key !== "Backspace" &&
                event.key !== "Tab")
        ) {
            event.preventDefault();
        }
    });

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        let isValid = true;
        let invalidLabels = document.getElementsByClassName("alert");
        let invalidInputs = document.getElementsByClassName("invalid-border");
        for (let label of Array.from(invalidLabels)) {
            label.remove();
        }
        for (let input of Array.from(invalidInputs)) {
            input.classList.remove("invalid-border");
        }
        if (mail.value.length == 0) {
            isValid = false;
            mail.classList.add("invalid-border");
            mail.parentNode.appendChild(
                createAlertElement("Vous n'avez pas saisi de mail.")
            );
        } else if (!mailRegex.test(mail.value)) {
            isValid = false;
            mail.classList.add("invalid-border");
            mail.parentNode.appendChild(
                createAlertElement("L'adresse mail saisie n'est pas valide.")
            );
        }
        if (adress.value.length == 0) {
            isValid = false;
            adress.classList.add("invalid-border");
            adress.parentNode.appendChild(
                createAlertElement("Vous n'avez pas saisi d'adresse.")
            );
        }
        if (cp.value.length == 0) {
            isValid = false;
            cp.classList.add("invalid-border");
            cp.parentNode.appendChild(
                createAlertElement("Vous n'avez pas saisi de code postal.")
            );
        } else if (cp.value.length != 5) {
            isValid = false;
            cp.classList.add("invalid-border");
            cp.parentNode.appendChild(
                createAlertElement("Le code postal doit contenir 5 chiffres.")
            );
        }
        if (city.value.length == 0) {
            isValid = false;
            city.classList.add("invalid-border");
            city.parentNode.appendChild(
                createAlertElement("Vous n'avez pas saisi de ville.")
            );
        }
        if (cardNumber.value.length == 0) {
            isValid = false;
            cardNumber.classList.add("invalid-border");
            cardNumber.parentNode.appendChild(
                createAlertElement("Vous n'avez pas saisi de numéro de carte.")
            );
        } else if (
            cardNumber.value.length != 19 &&
            cardNumber.value.length != 16
        ) {
            //16 chiffres avec ou sans les espaces automatiques, de toutes façons ces informations ne sont pas stockées
            isValid = false;
            cardNumber.classList.add("invalid-border");
            cardNumber.parentNode.appendChild(
                createAlertElement(
                    "Le numéro de carte doit contenir 16 chiffres."
                )
            );
        }
        if (expirationDate.value.length == 0) {
            isValid = false;
            expirationDate.classList.add("invalid-border");
            expirationDate.parentNode.appendChild(
                createAlertElement(
                    "Vous n'avez pas saisi de date d'expiration."
                )
            );
        } else if (!expDateRegex.test(expirationDate.value)) {
            isValid = false;
            expirationDate.classList.add("invalid-border");
            expirationDate.parentNode.appendChild(
                createAlertElement(
                    "La date d'expiration doit être au format MM/AA."
                )
            );
        }
        if (securityCode.value.length == 0) {
            isValid = false;
            securityCode.classList.add("invalid-border");
            securityCode.parentNode.appendChild(
                createAlertElement("Vous n'avez pas saisi de code de sécurité.")
            );
        } else if (securityCode.value.length != 3) {
            isValid = false;
            securityCode.classList.add("invalid-border");
            securityCode.parentNode.appendChild(
                createAlertElement(
                    "Le code de sécurité doit contenir 3 chiffres."
                )
            );
        }
        if (cardName.value.length == 0) {
            isValid = false;
            cardName.classList.add("invalid-border");
            cardName.parentNode.appendChild(
                createAlertElement(
                    "Vous n'avez pas saisi le nom du possesseur de la carte."
                )
            );
        }
        if (firstName.value.length == 0) {
            isValid = false;
            firstName.classList.add("invalid-border");
            firstName.parentNode.appendChild(
                createAlertElement("Vous n'avez pas saisi de prénom.")
            );
        }
        if (lastName.value.length == 0) {
            isValid = false;
            lastName.classList.add("invalid-border");
            lastName.parentNode.appendChild(
                createAlertElement("Vous n'avez pas saisi de nom.")
            );
        }

        if (phone.value.length == 0) {
            isValid = false;
            phone.classList.add("invalid-border");
            phone.parentNode.appendChild(
                createAlertElement(
                    "Vous n'avez pas saisi de numéro de téléphone."
                )
            );
        } else if (phone.value.length != 14) {
            isValid = false;
            phone.classList.add("invalid-border");
            phone.parentNode.appendChild(
                createAlertElement(
                    "Le numéro de téléphone doit contenir 10 chiffres."
                )
            );
        }
        if (isValid) {
            form.submit();
        }
    });
});

function createAlertElement(text) {
    let alert = document.createElement("p");
    alert.textContent = text;
    alert.classList.add("invalid");
    alert.classList.add("alert");
    return alert;
}
