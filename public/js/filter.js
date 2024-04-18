const eventFilter = document.getElementById("event");
const editionSelect = document.getElementById("edition_select");
const editionFilter = document.getElementById("edition");
const editions = Array.from(editionFilter.options);
document.addEventListener("DOMContentLoaded", () => {
    if (eventFilter.selectedIndex == 0) {
        editionSelect.setAttribute("hidden", "true");
    } else {
        filterEditions();
    }
});

eventFilter.addEventListener("change", filterEditions);

function filterEditions() {
    if (eventFilter.selectedIndex == 1 || eventFilter.selectedIndex == 5) {
        editionSelect.setAttribute("hidden", "true");
        editionFilter.selectedIndex = 0;
    } else {
        editionSelect.removeAttribute("hidden");
        for (let i = 1; i < editions.length; i++) {
            editions[i].style.display = "none";
            editions[i].setAttribute("disabled", "true")
        }
        let selectedEvent = eventFilter.options[eventFilter.selectedIndex].value;
        let eventEditions = Array.from(document.getElementsByClassName(selectedEvent));
        for (let edition of eventEditions) {
            edition.style.display = "block";
            edition.removeAttribute("disabled");
        }
        editionFilter.selectedIndex = 0;
    }
}