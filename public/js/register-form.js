const form = document.getElementById("register");
const prenom = document.getElementById("prenom");
const nom = document.getElementById("nom");
const mail = document.getElementById("mail");
const password = document.getElementById("password");
const mailRegex =
    /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(?:\.[a-zA-Z]{2,})?$/;

const requirements = document.getElementById("requirements");
const maj = document.getElementById("maj");
const min = document.getElementById("min");
const digit = document.getElementById("digit");
const characters = document.getElementById("characters");

document.addEventListener("submit", (event) => {
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
    if (prenom.value.length == 0) {
        isValid = false;
        prenom.classList.add("invalid-border");
        prenom.parentNode.appendChild(
            createAlertElement("Vous n'avez pas saisi de prénom.")
        );
    }
    if (nom.value.length == 0) {
        isValid = false;
        nom.classList.add("invalid-border");
        nom.parentNode.appendChild(
            createAlertElement("Vous n'avez pas saisi de nom.")
        );
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
    if (!checkPassword()) {
        password.classList.add("invalid-border");
        isValid = false;
    }
    if (isValid) {
        form.submit();
    }
});

password.addEventListener("input", checkPassword);

function createAlertElement(text) {
    let alert = document.createElement("p");
    alert.textContent = text;
    alert.classList.add("invalid");
    alert.classList.add("alert");
    return alert;
}

function checkPassword() {
    //On fait les vérifications des paramètres
    let isValidLength = password.value.length >= 8;
    let hasUpperCase = /[A-Z]/.test(password.value);
    let hasLowerCase = /[a-z]/.test(password.value);
    let hasDigit = /[0-9]/.test(password.value);

    //classList.toggle => Active ou désactive la classe.
    //Si un deuxième paramètre est précisé, alors s'adapte en fonction de la valeur booléenne du paramètre.
    characters.classList.toggle("invalid", !isValidLength);
    maj.classList.toggle("invalid", !hasUpperCase);
    min.classList.toggle("invalid", !hasLowerCase);
    digit.classList.toggle("invalid", !hasDigit);

    return isValidLength && hasUpperCase && hasLowerCase && hasDigit;
}
