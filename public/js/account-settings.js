const mailRegex =
    /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(?:\.[a-zA-Z]{2,})?$/;
const menu = document.getElementById("menu");
switch (menu.dataset.currentTab) {
    case "info":
        setInfoFormChecker();
        break;
    case "change-password":
        setPasswordFormChecker();
        break;
}
function setInfoFormChecker() {
    let form = document.getElementById("info");
    let firstName = document.getElementById("first-name");
    let lastName = document.getElementById("last-name");
    let mail = document.getElementById("mail");
    form.addEventListener("submit", function (event) {
        event.preventDefault();
        let isValid = true;
        removeInvalids();
        if (firstName.value.length == 0) {
            isValid = false;
            firstName.classList.add("invalid-border");
            firstName.parentNode.appendChild(
                createAlertElement("Vous n'avez pas saisi votre prénom.")
            );
        }
        if (lastName.value.length == 0) {
            isValid = false;
            lastName.classList.add("invalid-border");
            lastName.parentNode.appendChild(
                createAlertElement("Vous n'avez pas saisi votre nom.")
            );
        }
        if (mail.value.length == 0) {
            isValid = false;
            mail.classList.add("invalid-border");
            mail.parentNode.appendChild(
                createAlertElement("Vous n'avez pas saisi votre mail.")
            );
        } else if (!mailRegex.test(mail.value)) {
            isValid = false;
            mail.classList.add("invalid-border");
            mail.parentNode.appendChild(
                createAlertElement("L'mail saisi n'est pas valide.")
            );
        }
        if (isValid) {
            form.submit();
        }
    });
}
function setPasswordFormChecker() {
    const form = document.getElementById("change-password");
    const oldPassword = document.getElementById("old-password");
    const newPassword = document.getElementById("new-password");
    const confirmPassword = document.getElementById("confirm-password");
    form.addEventListener("submit", function (event) {
        let isValid = true;
        event.preventDefault();
        removeInvalids();
        if (oldPassword.value.length == 0) {
            isValid = false;
            oldPassword.classList.add("invalid-border");
            oldPassword.parentNode.appendChild(
                createAlertElement(
                    "Vous n'avez pas saisi votre mot de passe actuel."
                )
            );
        }
        if (!checkPassword()) {
            newPassword.addEventListener("input", checkPassword);
            newPassword.classList.add("invalid-border");
            confirmPassword.classList.add("invalid-border");
            isValid = false;
        } else if (newPassword.value != confirmPassword.value) {
            newPassword.classList.add("invalid-border");
            confirmPassword.classList.add("invalid-border");
            isValid = false;
            confirmPassword.parentNode.appendChild(
                createAlertElement("Les mots de passe ne correspondent pas.")
            );
        }
        if (isValid) {
            form.submit();
        }
    });
}
function createAlertElement(text) {
    let alert = document.createElement("p");
    alert.textContent = text;
    alert.classList.add("invalid");
    alert.classList.add("alert");
    return alert;
}
function checkPassword() {
    //On fait les vérifications des paramètres
    let newPassword = document.getElementById("new-password");
    let isValidLength = newPassword.value.length >= 8;
    let hasUpperCase = /[A-Z]/.test(newPassword.value);
    let hasLowerCase = /[a-z]/.test(newPassword.value);
    let hasDigit = /[0-9]/.test(newPassword.value);
    let maj = document.getElementById("maj");
    let min = document.getElementById("min");
    let digit = document.getElementById("digit");
    let characters = document.getElementById("characters");
    //classList.toggle => Active ou désactive la classe.
    //Si un deuxième paramètre est précisé, alors s'adapte en fonction de la valeur booléenne du paramètre.
    characters.classList.toggle("invalid", !isValidLength);
    maj.classList.toggle("invalid", !hasUpperCase);
    min.classList.toggle("invalid", !hasLowerCase);
    digit.classList.toggle("invalid", !hasDigit);
    return isValidLength && hasUpperCase && hasLowerCase && hasDigit;
}

function removeInvalids() {
    let invalidLabels = document.getElementsByClassName("alert");
    let invalidInputs = document.getElementsByClassName("invalid-border");
    for (let label of Array.from(invalidLabels)) {
        label.remove();
    }
    for (let input of Array.from(invalidInputs)) {
        input.classList.remove("invalid-border");
    }
}
