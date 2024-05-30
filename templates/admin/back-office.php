<section id="title-bar" class="bg-gradient-sunset">
            <div class="py-10 text-center text-white">
                <i class="ri-star-fill text-5xl"></i>
                <h2 class="text-4xl font-extrabold mt-2">Back-Office</h2>
            </div>
        </section>
<section
            id="account"
            class="bg-slate-50 py-10 justify-center gap-6 flex flex-row items-start"
        >
            <div class="bg-white flex py-5 pr-2">
                <div id="side-menu" class="border-r border-r-gray-300">
                    <div class="w-[13rem] lg:w-[15rem]">
                    <a href="/back-office/hyper-secret/products">
                    <div class="px-5 py-3 side-menu-button">Gestion des produits</div>
                    </a>
                    <a href="/back-office/hyper-secret/orders">  
                        <div class="px-5 py-3 side-menu-button">Gestion des commandes</div>
                    </a>
                    <a href="/back-office/hyper-secret/users">
                        <div class="px-5 py-3 side-menu-button">Gestion des utilisateurs</div>
                    </a>
                    </div>
                </div>
                <div id="menu" class="px-5 bg-white w-full lg:w-[600px]" data-current-tab="<?= $params['tab'] ?>">
                <?php if ($params['tab'] == 'products'): ?>
                    <div class="mb-3">
                        <h2 class="text-2xl font-bold">Gestion des produits</h2>
                        <p><?= $params['total'] ?> produits</p>
                    </div>
                    <?php $i = 0; ?>
                    <?php foreach ($params['products'] as $product): ?>
                        <?php $i++; ?>
                        <a href="/back-office/hyper-secret/product/<?= $product->GetId() ?>">
                            <div class="px-2 list-row py-2 flex items-center justify-between w-full <?= $i != count($params['products']) ? 'border-b border-b-gray-300' : '' ?>">
                                <div class="flex items-center gap-3">
                                    <img src="<?= CDN_URL . '/images/album/' . htmlspecialchars($product->GetUriImage()) ?>" alt="" class="w-32 h-32 aspect-square rounded-md border border-gray-300" />
                                    <div>
                                    <p class="text-xl font-semibold"><?= $product->GetNom() ?></p>
                                    <p class="text-lg"><?= !empty($product->GetArtiste()) ? $product->GetArtiste() : $product->GetLabel() ?></p>
                                    <p class="text-md"><?= $product->GetPrix() ?> €</p>
                                    <p class="text-sm">Sortie le : <?= $product->GetDateSortie()->format('d/m/Y') ?></p>
                                </div>
                                </div>
                                <div class="text-right whitespace-nowrap">
                                    <?php if ($product->GetQte() == 0): ?>
                                        <p class="text-red-500 text-sm">Rupture de stock</p>
                                    <?php else: ?>
                                    <p class="text-sm">En stock : <?= $product->getQte() ?></p>
                                    <p class="text-sm">En panier : <?= $params['qteInCart'][$product->GetId()] ?? 0 ?></p>
                                    <?php endif; ?>
                                    <p class=" text-xs">Commandé : <?= $params['qteCommandee'][$product->GetId()] ?? 0 ?> fois</p>
                                    
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <div class="flex justify-center">
                            <?php if ($params['page'] > 1): ?>
                                    <a href="/back-office/hyper-secret/products?page=<?= $params['page'] - 1 ?>"><p class="p-3 px-4 page-btn"><i class="ri-arrow-left-s-line"></i></p></a>
                                <?php endif; ?>
                            <?php for ($i = 1; $i <= $params['nbPages']; $i++): ?>
                                <a href="/back-office/hyper-secret/products?page=<?= $i ?>"><p class="p-3 px-4 page-btn <?= $i == $params['page'] ? 'font-bold' : '' ?>"><?= $i ?></p></a>
                            <?php endfor; ?>
                            <?php if ($params['page'] < $params['nbPages']): ?>
                                <a href="/back-office/hyper-secret/products?page=<?= $params['page'] + 1 ?>"><p class="p-3 px-4 page-btn"><i class="ri-arrow-right-s-line"></i></p></a>
                            <?php endif; ?>
                            
                            </div>
                <?php endif; ?>
                <?php if ($params['tab'] == 'orders'): ?>
                    <h2 class="text-2xl font-bold mb-2">Gestion des commandes</h2>
                    <p> <?= $params['total'] ?> commandes</p>
                    <?php $i = 0; ?>
                        <?php foreach ($params['orders'] as $order): ?>
                            <?php $i++; ?>
                            <a href="/order/<?= $order->GetId() ?>">
                            <div class="px-2 list-row <?= $i != count($params['orders']) ? 'border-b border-b-gray-300' : '' ?>">
                                <div class="flex items-center justify-between py-5">
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
                                            <img src="<?= CDN_URL . '/images/album/' . htmlspecialchars($item->GetUriImage()) ?>" alt="<?= (htmlspecialchars($item->GetNom())) ?>" class="border border-slate-400 aspect-square w-16 rounded-md">
                                            <?php endforeach; ?>
                                        </div>
                                        <p>Montant : <?= $order->GetTotal() ?> €</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        <?php endforeach; ?>
                        <div class="flex justify-center">
                            <?php if ($params['page'] > 1): ?>
                                    <a href="/back-office/hyper-secret/orders?page<?= $params['page'] - 1 ?>"><p class="p-3 px-4 page-btn"><i class="ri-arrow-left-s-line"></i></p></a>
                                <?php endif; ?>
                            <?php for ($i = 1; $i <= $params['nbPages']; $i++): ?>
                                <a href="/back-office/hyper-secret/orders?page=<?= $i ?>"><p class="p-3 px-4 page-btn <?= $i == $params['page'] ? 'font-bold' : '' ?>"><?= $i ?></p></a>
                            <?php endfor; ?>
                            <?php if ($params['page'] < $params['nbPages']): ?>
                                <a href="/back-office/hyper-secret/orders?page=<?= $params['page'] + 1 ?>"><p class="p-3 px-4 page-btn"><i class="ri-arrow-right-s-line"></i></p></a>
                            <?php endif; ?>
                            
                            </div>
                <?php endif; ?>
                <?php if ($params['tab'] == 'users'): ?>
                    <h2 class="text-2xl font-bold mb-2">Gestion des utilisateurs</h2>
                    <p> <?= $params['total'] ?> utilisateurs</p>
                    <?php $i = 0; ?>
                    <?php foreach ($params['users'] as $user): ?>
                        <?php $i++; ?>
                        <div class="px-2 list-row flex justify-between items-center py-3 <?= $i != count($params['users']) ? 'border-b border-b-gray-300' : '' ?>">
                            <div class="flex gap-3 items-center">
                                <img src="https://ui-avatars.com/api/?name=<?= $user->GetPrenom() . '+' . $user->GetNom() ?>&size=128&background=random" alt="" class="border border-gray-200 rounded-full w-20 h-20">
                                <div>
                                    <p class="font-semibold"><?= $user->GetPrenom() . ' ' . $user->GetNom() ?></p>
                                    <p><?= $user->GetIdRole() == 1 ? 'Utilisateur' : 'Administrateur' ?></p>
                                    <p class="text-sm"><?= $user->GetMail() ?></p>
                                </div>
                            </div>
                            <div class="text-right text-sm">
                                <p>Commandes : <?= $params['usersOrdersCount'][$user->GetId()] ?></p>
                                <p>Produits en panier : <?= $params['usersCartCount'][$user->GetId()] ?></p>
                                <p class="text-xs">Inscrit(e) depuis le <?= $user->GetDateInscription()->format('d/m/Y') ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <div class="flex justify-center">
                        <?php if ($params['page'] > 1): ?>
                                    <a href="/back-office/hyper-secret/users?page<?= $params['page'] - 1 ?>"><p class="p-3 px-4 page-btn"><i class="ri-arrow-left-s-line"></i></p></a>
                                <?php endif; ?>
                            <?php for ($i = 1; $i <= $params['nbPages']; $i++): ?>
                                <a href="/back-office/hyper-secret/users?page=<?= $i ?>"><p class="p-3 px-4 page-btn <?= $i == $params['page'] ? 'font-bold' : '' ?>"><?= $i ?></p></a>
                            <?php endfor; ?>
                            <?php if ($params['page'] < $params['nbPages']): ?>
                                <a href="/back-office/hyper-secret/users?page=<?= $params['page'] + 1 ?>"><p class="p-3 px-4 page-btn"><i class="ri-arrow-right-s-line"></i></p></a>
                            <?php endif; ?>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
        </section>