<section id="looking-for" class="bg-gradient-sunset">
            <div class="h-[77vh] min-h-[620px] text-white flex justify-center">
                <div class="m-auto bg-white text-black font-extralight px-10 py-4 rounded-lg">
                    <h2 class="text-3xl font-extrabold my-5 text-center">Créer un compte</h2>
                    <form action="/register/" enctype="multipart/form-data" method="post" class="flex flex-col text-left gap-4">
                        <div>
                            <label for="prenom"><p class="mb-1">Prénom</p></label>
                            <input
                                name="prenom"
                                id="prenom"
                                type="text"
                                placeholder="Saisissez votre prénom"
                                class="border px-5 py-2 w-80 sm:w-96 rounded-lg"
                                required
                            />
                        </div>
                        <div>
                            <label for="nom"><p class="mb-1">Nom</p></label>
                            <input
                                name="nom"
                                id="nom"
                                type="text"
                                placeholder="Saisissez votre nom"
                                class="border px-5 py-2 w-80 sm:w-96 rounded-lg"
                                required
                            />
                        </div>
                        <div>
                            <label for="mail"><p class="mb-1">Adresse mail</p></label>
                            <input
                                name="mail"
                                id="mail"
                                type="text"
                                placeholder="Saisissez votre adresse mail"
                                class="border px-5 py-2 w-80 sm:w-96 rounded-lg"
                                required
                            />
                        </div>
                        <div>
                            <label for="password"><p class="mb-1">Mot de passe</p></label>
                            <input
                                name="password"
                                id="password"
                                type="password"
                                placeholder="Saisissez votre mot de passe"
                                class="border  px-5 py-2 w-80 sm:w-96 rounded-lg"
                                required
                            />
                            <p class="text-sm" id="requirements"><span id="maj">1 majuscule,</span> <span id="min">1 minuscule,</span> <span id="digit">1 chiffre,</span> <span id="characters">8 caractères</span></p>
                        </div>
                        <input type="submit" class="bg-black text-white py-3 text-lg rounded-md cursor-pointer my-5 font-bold" value="S'inscrire" />
                    </form>
                </div>
            </div>
        </section>