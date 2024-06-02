const menu = document.getElementById("menu");
switch (menu.dataset.currentTab) {
    case "product-form":
        setProductFormChecker();
        setCoverImageUploader();
        break;
}

function setProductFormChecker() {
    const addSongButton = document.getElementById("add-song");
    const confirmNewSongButton = document.getElementById("confirm-new-song");
    const newSongForm = document.getElementById("new-song-form");
    addSongButton.addEventListener("click", function (event) {
        event.preventDefault();
        newSongForm.classList.remove("hidden");
    });
    confirmNewSongButton.addEventListener("click", function (event) {
        event.preventDefault();
        let isValid = true;
        let newSong = {};
        const titleNew = document.getElementById("title-new");
        const artistNew = document.getElementById("artist-new");
        const songList = document.getElementById("song-list");
        const songContainer = document.getElementById("song-container");
        titleNew.classList.remove("invalid-border");
        artistNew.classList.remove("invalid-border");
        if (titleNew.value == "") {
            titleNew.classList.add("invalid-border");
            isValid = false;
        }
        if (artistNew.value == "") {
            artistNew.classList.add("invalid-border");
            isValid = false;
        }
        if (isValid) {
            newSong = {
                title: titleNew.value,
                artist: artistNew.value,
                albumId: parseInt(document.getElementById("form-product").dataset.productId),
            };
        }
        fetch("/back-office/api/add-song/", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(newSong),
        }).then(async (response) => {
            if (response.ok) {
                let data = await response.json();
                songContainer.appendChild(
                    newSongRow(data.id, newSong.title, newSong.artist, parseInt(songList.dataset.songsCount) + 1)
                );
                songList.dataset.songsCount = parseInt(songList.dataset.songsCount) + 1;
                titleNew.value = "";
                artistNew.value = "";
                newSongForm.classList.add("hidden");
            }
        });
    });
}

function setCoverImageUploader() {
    document.getElementById("album-cover").addEventListener("change", function () {
        const file = this.files[0];
        if (file.type == "image/jpeg") {
            newCover = new FormData();
            newCover.append("cover", file);
            newCover.append("albumId", document.getElementById("form-product").dataset.productId);
            fetch("/back-office/api/upload-cover/", {
                method: "POST",
                body: newCover,
            }).then(async (response) => {
                if (response.ok) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById("cover-preview").src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
}

function newSongRow(idSong, title, artist, nb) {
    let newSongRow = document.createElement("div");
    newSongRow.dataset.songId = idSong;
    newSongRow.classList.add("list-row", "flex", "justify-between", "border-b", "px-5", "py-3", "w-full", "bg-white");
    let songInfo = document.createElement("div");
    songInfo.classList.add("flex", "gap-3", "text-center");
    let songNumber = document.createElement("p");
    songNumber.classList.add("w-2");
    songNumber.innerText = nb;
    songInfo.appendChild(songNumber);
    let dash1 = document.createElement("p");
    dash1.classList.add("w-1");
    dash1.innerText = "-";
    songInfo.appendChild(dash1);
    let songTitle = document.createElement("p");
    songTitle.classList.add("font-bold");
    songTitle.innerText = title;
    songInfo.appendChild(songTitle);
    let dash2 = document.createElement("p");
    dash2.classList.add("w-1");
    dash2.innerText = "-";
    songInfo.appendChild(dash2);
    let songArtist = document.createElement("p");
    songArtist.innerText = artist;
    songInfo.appendChild(songArtist);
    newSongRow.appendChild(songInfo);
    let songDuration = document.createElement("p");
    songDuration.innerText = Math.floor(Math.random() * 10) + 1 + ":30";
    newSongRow.appendChild(songDuration);
    return newSongRow;
}
