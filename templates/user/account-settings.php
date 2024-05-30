<section id="title-bar" class="bg-gradient-sunset">
            <div class="py-10 text-center text-white">
                <h2 class="text-4xl font-extrabold">Votre compte</h2>
            </div>
        </section>
<section
            id="account"
            class="bg-slate-50 py-10 justify-center gap-6 flex flex-row items-start"
        >
            <div class="bg-white flex py-5 pr-2">
                <div id="side-menu" class="border-r border-r-gray-300">
                    <div class="w-[13rem] lg:w-[15rem]">
                    <a href="/account/orders">
                    <div class="px-5 py-3 side-menu-button">Vos commandes</div>
                    </a>
                    <a href="/account/info">  
                        <div class="px-5 py-3 side-menu-button">Informations personnelles</div>
                    </a>
                    <a href="/account/change-password">
                        <div class="px-5 py-3 side-menu-button">Changer de mot de passe</div>
                    </a>
                    <a href="/account/logout">
                        <div class="px-5 py-3 side-menu-button">Se déconnecter</div>
                    </a>
                    </div>
                </div>
                <div id="menu" class="px-5 bg-white w-full lg:w-[600px]" data-current-tab="<?= $params['tab'] ?>">
                <?php if ($params['tab'] == 'orders'): ?>
                    <h2 class="text-2xl font-bold mb-2">Vos commandes</h2>
                    <?php if (isset($params['orders']) && !empty($params['orders'])): ?>
                        <p><?= count($params['orders']) . ' commande(s)' ?></p>
                        <?php $i = 0; ?>
                        <?php foreach ($params['orders'] as $order): ?>
                            <?php $i++; ?>
                            <a href="/order/<?= $order->GetId() ?>">
                            <div class="list-row <?= $i != count($params['orders']) ? 'border-b border-b-gray-300' : '' ?>">
                                <div class="flex items-center justify-between py-5 px-2">
                                <div>
                                    <div class="mb-3">
                                        <p>Commande #<?= $order->GetId() ?> - <?= $order->GetDateHeure()->format('d/m/Y') ?></p>
                                        <p>Livré à : <?= $order->GetPrenomDest() . ' ' . $order->GetNomDest() . ', ' . $order->GetCp() . ' ' . $order->GetVille() ?></p>
                                    </div>
                                        <p><span class="bg-black text-white rounded-full px-3 py-1">En cours</span></p>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex items-center gap-2 flex-row-reverse">
                                            <?php foreach ($params['ordersItems'][$order->GetId()] as $item): ?>
                                            <img src="<?= CDN_URL . '/images/album/' . htmlspecialchars($item->GetUriImage()) ?>" alt="<?= (htmlspecialchars($item->GetNom())) ?>" class="border border-gray-300 aspect-square w-16 rounded-md">
                                            <?php endforeach; ?>
                                        </div>
                                        <p>Montant : <?= $order->GetTotal() ?> €</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Vous n'avez pas encore passé de commande.</p>
                    <?php endif; ?>
            <?php elseif ($params['tab'] == 'info'): ?>
                    <h2 class="text-2xl font-bold mb-2">Informations personnelles</h2>
                    <?php if (isset($params['error'])): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5">
                           <?= $params['error'] ?>
                        </div>
                    <?php elseif (isset($params['success'])): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-2">
                            <?= $params['success'] ?>
                        </div>
                    <?php endif; ?>
                        <form id="info" action="/account/info" method="post">
                            <div class="flex gap-5 mb-4">
                                <div class="w-full">
                                    <label for="first-name"><p class="mb-1">Prénom</p></label>
                                    <input
                                        name="first-name"
                                        id="first-name"
                                        type="text"
                                        placeholder="Miku"
                                        class="border px-5 py-2 rounded-md w-full"
                                        value="<?= $_SESSION['prenom'] ?>"
                                    />
                                </div>
                                <div class="w-full">
                                    <label for="last-name"><p class="mb-1">Nom</p></label>
                                    <input
                                        name="last-name"
                                        id="last-name"
                                        type="text"
                                        placeholder="Hatsune"
                                        class="border px-5 py-2 rounded-md w-full"
                                        value="<?= $_SESSION['nom'] ?>"
                                    />
                                </div>
                            </div>
                            <div class="w-full mb-4">
                                <label for="mail"><p class="mb-1">Adresse e-mail</p></label>
                                <input
                                    name="mail"
                                    id="mail"
                                    type="text"
                                    placeholder="miku@hatsune.fr"
                                    class="border px-5 py-2 rounded-md w-full"
                                    value="<?= $_SESSION['mail'] ?>"
                                />
                            </div>
                            <input type="submit" class=" cursor-pointer bg-black text-white rounded-md w-full py-3 mt-5" value="Modifier">
                        </form>
            <?php elseif ($params['tab'] == 'change-password'): ?>
                    <h2 class="text-2xl font-bold mb-5">Changer de mot de passe</h2>
                    <?php if (isset($params['error'])): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5">
                           <?= $params['error'] ?>
                        </div>
                    <?php endif; ?>
                    <form id="change-password" method="post" action="/account/change-password">
                        <div class="w-full mb-4">
                            <label for="old-password"><p class="mb-1">Ancien mot de passe</p></label>
                            <input
                                name="old-password"
                                id="old-password"
                                type="password"
                                class="border px-5 py-2 rounded-md w-full"
                            />
                        </div>
                        <div class="w-full mb-4">
                            <label for="new-password"><p class="mb-1">Nouveau mot de passe</p></label>
                            <input
                                name="new-password"
                                id="new-password"
                                type="password"
                                class="border px-5 py-2 rounded-md w-full"
                            />
                            <p class="text-sm" id="requirements"><span id="maj">1 majuscule,</span> <span id="min">1 minuscule,</span> <span id="digit">1 chiffre,</span> <span id="characters">8 caractères</span></p>
                        </div>
                        
                        <div class="w-full mb-4">
                            <label for="confirm-password"><p class="mb-1">Confirmer le nouveau mot de passe</p></label>
                            <input
                                name="confirm-password"
                                id="confirm-password"
                                type="password"
                                class="border px-5 py-2 rounded-md w-full"
                            />
                        </div>
                        <input type="submit" class="bg-black text-white rounded-md w-full py-3 mt-5" value="Modifier">
                    </form>
            <?php endif; ?>
            </div>

            </div>

            
        </section>