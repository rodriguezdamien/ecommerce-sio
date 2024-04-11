const prenom = document.getElementById("prenom");
const nom = document.getElementById("nom");
const mail = document.getElementById("mail");
const password = document.getElementById("password");
const mailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

const requirements = document.getElementById("requirements");
const maj = document.getElementById("maj");
const min = document.getElementById("min");
const digit = document.getElementById("digit");
const characters = document.getElementById("characters");

document.addEventListener("submit", () => {
    let isValid = true;
    if (prenom.textContent.length) {
        isValid = false;
        prenom.classList.add("border-red-400");
        prenom.parentNode.appendChild(createAlertElement("Vous n'avez pas saisi de prénom."));
    }
    if (nom.textContent.length) {
        isValid = false;
        nom.classList.add("border-red-400");
        prenom.parentNode.appendChild(createAlertElement("Vous n'avez pas saisi de nom."));
    }
    if (mail.textContent.length) {
        isValid = false;
        mail.classList.add("border-red-400");
        mail.parentNode.appendChild(createAlertElement("Vous n'avez pas saisi de mail."));
    } else if (mailRegex.test(mail)) {
        isValid = false;
        mail.classList.add("border-red-400");
        mail.parentNode.appendChild(createAlertElement("L'adresse mail saisie n'est pas valide."));
    }
});

//TODO : Finir la vérification du mot de passe
password.addEventListener("focus", () => {
    password.addEventListener("input", () => {
        if (password.textContent.length < 8) {
            characters.classList.add("text-red-500");
        }
        if (/[A-Z]/.test(password.textContent)) {
            maj.classList.add("text-red-500");
        }

        if(/[a-z]/.test(password.textContent)){
            min.classList.add("text-red-500");
        }
        if
    });
});

function createAlertElement(text) {
    let alert = document.createElement("p");
    alert.textContent = text;
    alert.classList.add("text-sm text-red-500");
    return alert;
}
