<section id="payment" class="bg-gradient-sunset flex flex-col lg:grid lg:grid-cols-2">
            <div class="flex flex-col items-center lg:items-end px-[5vw] gap-5 lg:max-h-[60rem] overflow-scroll py-10">
                <div class="relative w-full lg:w-[500px]">
                    <a href="/cart">    
                        <button class="bg-white py-3 px-5 rounded-full my-4 flex items-center gap-2">
                           <i class="ri-arrow-left-line text-xl"></i>
                            <p>Retourner au panier</p>
                        </button>
                    </a>
                    <div class="flex flex-col gap-3 bg-white rounded-md p-3">
                        <h2 class="font-bold text-[21px]">Récapitulatif de la commande</h2>
                        <div class="flex flex-col gap-3 py-2">
                            <?php foreach ($params['cart'] as $item): ?>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-5">
                                    <div class="relative aspect-square">
                                        <div
                                            class="absolute font-bold flex items-center justify-center right-[-5px] top-[-5px] h-7 w-7 rounded-full text-center text-white bg-black opacity-90"
                                        >
                                            <?= $item['qte'] ?>
                                        </div>
                                        <img
                                            src="<?= CDN_URL . '/images/album/' . htmlspecialchars($item['album']->GetUriImage()) ?>"
                                            alt=""
                                            class="h-24 w-24 rounded-md aspect-square max-w-none"
                                        />
                                    </div>
                                    <div>
                                        <p><?= htmlspecialchars($item['album']->GetNom()) ?></p>
                                        <p class="font-semibold"><?= !empty($item['album']->GetArtiste()) ? $item['album']->GetArtiste() : $item['album']->GetLabel() ?></p>
                                    </div>
                                </div>
                                <p class="font-semibold text-xl whitespace-nowrap"><?= $item['album']->GetPrix() * $item['qte'] ?> €</p>
                            </div>
                            <?php endforeach; ?>
                            <span id="recap-separator" class="h-[1px] bg-slate-300 w-full mt-2"></span>
                            <div>
                                <div class="flex justify-between items-center">
                                    <p>Sous-total</p>
                                    <p class="font-bold"><?= $params['total'] ?> €</p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p>Livraison</p>
                                    <p class="font-bold">Offerte</p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-2xl">Total</p>
                                    <p class="text-2xl font-bold"><?= $params['total'] ?> €</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white border-l border-l-slate-300 flex xl:pl-10 pt-5 pb-16">
        <?php if (!isset($_SESSION['checkout-confirming'])): ?>
            
                <form id="customer-form" action="/checkout" method="post" class="text-sm flex flex-col gap-1 top-[100px] w-full xl:w-auto px-4 py-8 lg:py-0">
                <?php if (isset($params['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <p><?= $params['error'] ?></p>
                </div>
            <?php endif; ?>    
                <h2 class="text-xl font-bold">Contact</h2>
                    <div class="mb-3">
                        <label for="mail"><p class="mb-1">Adresse e-mail</p></label>
                        <input
                            name="mail"
                            id="mail"
                            type="mail"
                            placeholder="exemple@gaku.fr"
                            class="border px-5 py-2 rounded-md w-full "
                        />
                    </div>
                    <div class="mb-3">
                        <label for="phone"><p class="mb-1">Numéro de téléphone</p></label>
                        <input
                            name="phone"
                            id="phone"
                            type="text"
                            placeholder="06 12 34 56 78"
                            class="border px-5 py-2 rounded-md w-full "
                        />
                    </div>
                    <h2 class="text-xl font-bold">Adresse de livraison</h2>
                    <div class="flex gap-3 w-full mb-3">
                        <div class="w-full">
                            <label for="first-name"><p class="mb-1">Prénom</p></label>
                            <input
                                name="first-name"
                                id="first-name"
                                type="text"
                                placeholder="Miku"
                                class="border px-5 py-2 rounded-md w-full "
                            />
                        </div>
                        <div class="w-full">
                            <label for="last-name"><p class="mb-1">Nom</p></label>
                            <input
                                name="last-name"
                                id="last-name"
                                type="text"
                                placeholder="Hatsune"
                                class="border px-5 py-2 rounded-md w-full "
                            />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="adress"><p class="mb-1">Adresse</p></label>
                        <input
                            name="adress"
                            id="adress"
                            type="text"
                            placeholder="10 Avenue de Christophe Maé le grand chanteur"
                            class="border px-5 py-2 rounded-md w-full "
                        />
                    </div>
                    <div class="mb-3">
                        <label for="adress-2"><p class="mb-1">Complément d'adresse</p></label>
                        <input
                            name="adress-2"
                            id="adress-2"
                            type="address-2"
                            placeholder="Appartement N°39"
                            class="border px-5 py-2 rounded-md w-full "
                        />
                    </div>
                    <div class="flex gap-3 w-full mb-3">
                        <div class="w-full">
                            <label for="postal"><p class="mb-1">Code postal</p></label>
                            <input
                                name="postal"
                                id="postal"
                                type="text"
                                placeholder="75000"
                                class="border px-5 py-2 rounded-md w-full "
                            />
                        </div>
                        <div class="w-full">
                            <label for="city"><p class="mb-1">Ville</p></label>
                            <input
                                name="city"
                                id="city"
                                type="text"
                                placeholder="Paris"
                                class="border px-5 py-2 rounded-md w-full "
                            />
                        </div>
                    </div>
                    <h2 class="text-xl font-bold">Paiement</h2>
                    <div class="mb-3">
                        <label for="card-number"><p class="mb-1">Numéro de carte</p></label>
                        <input
                            name="card-number"
                            id="card-number"
                            type="text"
                            placeholder="0000 0000 0000 0000"
                            class="border px-5 py-2 rounded-md w-full "
                        />
                    </div>

                    <div class="flex gap-3">
                        <div class="w-full">
                            <label for="exp-date"><p class="mb-1">Date d'expiration</p></label>
                            <input
                                name="exp-date"
                                id="exp-date"
                                type="text"
                                placeholder="01/39"
                                class="border px-5 py-2 rounded-md w-full "
                            />
                        </div>
                        <div class="w-full mb-3">
                            <label for="mail"><p class="mb-1">Code de sécurité</p></label>
                            <input
                                name="security-code"
                                id="security-code"
                                type="password"
                                placeholder="123"
                                class="border px-5 py-2 rounded-md w-full "
                            />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="card-name"><p class="mb-1">Nom sur la carte</p></label>
                        <input
                            name="card-name"
                            id="card-name"
                            type="text"
                            placeholder="Miku Hatsune"
                            class="border px-5 py-2 rounded-md w-full "
                        />
                    </div>
                    <input
                        type="submit"
                        value="Passer la commande"
                        class="cursor-pointer bg-black rounded-md text-white h-16 my-3"
                    />
                </form>
            </div>
        <?php else: ?>
            <div class="lg:w-[550px] px-3 py-10 max-w-[700px] w-full mx-auto lg:mx-0">
                <h2 class="text-lg font-semibold">Confirmation de la commande</h2>
                <p class="text-sm">Veuillez confirmer les informations avant de valider la commande.</p>
                
                <div class="border border-slate-300 rounded-md p-5 ">
                <div class="flex py-2 gap-20  lg:gap-7">
                    <div>
                        <div class="mb-2">

                            <h3 class="text-md mb-1">Adresse de livraison</h3>
                            <p class="text-sm text-gray-700"><?= $_SESSION['order-info']['first-name'] . ' ' . $_SESSION['order-info']['last-name'] ?></p>
                            <p class="text-sm text-gray-700"><?= $_SESSION['order-info']['adress'] ?></p>
                            <?php if (isset($_SESSION['order-info']['adress-2']) && !empty($_SESSION['order-info']['adress-2'])): ?>
                            <p class="text-sm text-gray-700"><?= $_SESSION['order-info']['adress-2'] ?></p>
                            <?php endif; ?>
                            <p class="text-sm text-gray-700"><?= $_SESSION['order-info']['postal'] . ' ' . $_SESSION['order-info']['city'] ?></p>
                        </div>
                        <div class="mb-2">
                            <h3 class="text-md mb-1">Adresse e-mail de contact</h3>
                            <p class="text-sm text-gray-700"><?= $_SESSION['order-info']['mail'] ?></p>
                        </div>
                        <div class="mb-2">
                            <h3 class="text-md mb-1">Numéro de téléphone</h3>
                            <p class="text-sm text-gray-700"><?= $_SESSION['order-info']['phone'] ?></p>
                        </div>
                    </div>
                    <div>
                        <div class="mb-2">
                            <h3 class="text-md mb-1">Moyen de paiement</h3>
                            <p class="flex items-center gap-1 text-sm text-gray-700"><span class="cb-logo"></span> ••<?= substr($_SESSION['order-info']['card-number'], -2) ?> Carte bleue </p>
                        </div>
                        <div class="mb-2">
                            <h3 class="text-md mb-1">Coût total de la commande</h3>
                            <p class="flex gap-1 text-sm text-gray-700"><?= $params['total'] ?> €</p>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="my-4">
                <a href="/checkout/confirm/">
                    <button class="bg-black text-white w-full py-4 rounded-md mb-2">
                        Confirmer la commande
                    </button>
                </a>
                <a href="/cart">
                    <button class="border border-slate-300 w-full py-4 rounded-md">
                        Annuler
                    </button>
                </a>
            </div>
        </div>
        <?php endif; ?>
        </section>