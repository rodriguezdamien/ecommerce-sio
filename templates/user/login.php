<section id="looking-for" class="bg-gradient-sunset flex-1">
            <div class="flex flex-col justify-center items-center min-h-[500px] h-[77vh]">
           
                <div class="sm:mx-auto bg-white text-black font-extralight px-10 py-4 rounded-lg relative">
                
                    <h2 class="text-3xl font-extrabold mt-5 mb-4 text-center">Connexion</h2>
                    <?php if (isset($params['error']) && !empty($params['error'])): ?>
                        <div class="mb-5 border-2 border-red-500 bg-red-200 text-red-700 rounded-lg p-3 text-center"><p><?= $params['error'] ?></p></div>
                    <?php endif; ?>
                    <?php if (isset($params['success']) && !empty($params['success'])): ?>
                        <div class="mb-5 border-2 border-green-500 bg-green-200 text-green-700 rounded-lg p-3 max-w-[550px] text-center"><p><?= $params['success'] ?></p></div>
                    <?php endif; ?>
                    <form action="/login" method="post" class="flex flex-col text-left gap-5 items-center">
                        <div>
                            <label for="mail"><p class=" mt-1 mb-1">Adresse mail</p></label>
                            <input
                                name="mail"
                                id="mail"
                                type="text"
                                placeholder="Saisissez votre adresse mail..."
                                class="border px-5 py-2 w-80 sm:w-96 rounded-lg"
                            />
                        </div>
                        <div>
                            <label for="password"><p class="mb-1">Mot de passe</p></label>
                            <input
                                name="password"
                                id="password"
                                type="password"
                                placeholder="Saisissez votre mot de passe..."
                                class="border px-5 py-2 w-80 sm:w-96 rounded-lg"
                            />
                        </div>
                        <input
                            type="submit"
                            class="bg-black text-white py-3 text-lg font-bold rounded-md cursor-pointer mt-6 w-full"
                            value="Se connecter"
                        />
                    </form>
                    <p class="my-3 text-center">
                        Vous n'avez pas encore de compte ?
                        <a href="/register" class="text-blue-700 font-semibold"> Inscrivez-vous !</a>
                    </p>
                </div>
            </div>
        </section>
