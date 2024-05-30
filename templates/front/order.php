<section id="payment" class="bg-gradient-sunset flex flex-col lg:grid lg:grid-cols-2">
            <div class="flex flex-col items-center lg:items-end px-[5vw] gap-5 lg:max-h-[60rem] overflow-scroll py-10">
                <div class="relative w-full lg:w-[500px]">
                    <a href="/account/orders">    
                        <button class="bg-white py-3 px-5 rounded-full my-4 flex items-center gap-2">
                           <i class="ri-arrow-left-line text-xl"></i>
                            <p>Retourner à la liste de vos commandes</p>
                        </button>
                    </a>
                    <div class="flex flex-col gap-3 bg-white rounded-md p-3">
                        <h2 class="font-bold text-[21px]">Récapitulatif de la commande</h2>
                        <div class="flex flex-col gap-3 py-2">
                            <?php foreach ($params['items'] as $item): ?>
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
                                    <p class="font-bold"><?= round($params['order']->GetTotal(), 2) ?> €</p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p>Livraison</p>
                                    <p class="font-bold">Offerte</p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-2xl">Total</p>
                                    <p class="text-2xl font-bold"><?= round($params['order']->GetTotal(), 2) ?> €</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white border-l border-l-slate-300 flex xl:pl-10 pt-5 pb-16">
            <div class="lg:w-[550px] px-3 py-10 max-w-[700px] w-full mx-auto lg:mx-0">
            <h2 class="text-xl font-semibold">Commande #<?= $params['order']->GetId() ?></h2>
                    <p class="text-sm">Merci pour votre commande, <?= $params['order']->GetPrenomDest() ?> !</p>
                    <div class="border border-slate-300 rounded-md p-5 my-3">
                        <h3 class="text-md font-semibold">Suivi de la commande</h3>
                        <p class="text-sm">Votre commande est en cours de préparation. Nous vous tiendrons au courant ici de son avancement.</p>
                    </div>
                <div class="border border-slate-300 rounded-md p-5 ">
                <div class="flex py-2 gap-7">
                    <div>
                        <div class="mb-2">

                            <h3 class="text-md mb-1">Adresse de livraison</h3>
                            <p class="text-sm text-gray-700"><?= $params['order']->GetPrenomDest() . ' ' . $params['order']->GetNomDest() ?></p>
                            <p class="text-sm text-gray-700"><?= $params['order']->GetAdresse() ?></p>
                            <?php if (!empty($params['order']->GetComplementAdresse())): ?>
                            <p class="text-sm text-gray-700"><?= $params['order']->GetComplementAdresse() ?></p>
                            <?php endif; ?>
                            <p class="text-sm text-gray-700"><?= $params['order']->GetCp() . ' ' . $params['order']->GetVille() ?></p>
                        </div>
                        <div class="mb-2">
                            <h3 class="text-md mb-1">Adresse e-mail de contact</h3>
                            <p class="text-sm text-gray-700"><?= $params['order']->GetMailContact() ?></p>
                        </div>
                        <div class="mb-2">
                            <h3 class="text-md mb-1">Numéro de téléphone</h3>
                            <p class="text-sm text-gray-700"><?= $params['order']->GetNumTel() ?></p>
                        </div>
                    </div>
                    <div>
                        <div class="mb-2">
                            <h3 class="text-md mb-1">Moyen de paiement</h3>
                            <p class="flex gap-1 text-sm text-gray-700"><span class="cb-logo"></span> Carte bleue </p>
                        </div>
                        <div class="mb-2">
                            <h3 class="text-md mb-1">Coût total de la commande</h3>
                            <p class="flex gap-1 text-sm text-gray-700"><?= round($params['order']->GetTotal(), 2) ?> €</p>
                        </div>
                    </div>
                </div>
                
                
            </div>
            <a href="/">
                <button class="bg-black text-white px-10 py-4 rounded-md my-5">Retourner sur la boutique</button>
            </a>
        </div>
        </div>
        </section>