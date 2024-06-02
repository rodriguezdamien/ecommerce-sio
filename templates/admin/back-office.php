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
                <div id="menu" class="px-5 bg-white w-full <?= $params['tab'] == 'product-form' ? 'lg:w-[700px]' : 'lg:w-[600px]' ?>" data-current-tab="<?= $params['tab'] ?>">
                <?php if ($params['tab'] == 'products'): ?>
                    <div class="mb-3">
                        <h2 class="text-2xl font-bold">Gestion des produits</h2>
                        <p><?= $params['total'] ?> produits</p>
                    </div>
                    <?php $i = 0; ?>
                    <?php foreach ($params['products'] as $product): ?>
                        <?php $i++; ?>
                        <a href="/back-office/hyper-secret/product-form/<?= $product->GetId() ?>">
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
                <?php if ($params['tab'] == 'product-form'): ?>
                    <h2 class="text-2xl font-bold">Modification d'un produit</h2>
                    <?php if (isset($params['success']) && $params['success'] == 200): ?>
                        <div class="bg-green-200 border border-green-300 text-green-800 px-3 py-2 rounded-md my-3">
                            <p>Le produit a bien été mis à jour.</p>
                        </div>
                    <?php endif; ?>
            <form id="form-product" action="" method="post" data-product-id="<?= $params['product']->GetId() ?>">
                <div class="grid grid-cols-3 my-3 justify-center grow">
                    <div class="flex gap-3 col-span-2">
                        <img src="<?= CDN_URL . '/images/album/' . htmlspecialchars($params['product']->GetUriImage()) ?>" class=" w-52 h-52 border rounded-md border-gray-300" id="cover-preview">
                        <div class="flex flex-col justify-center">
                            <p class="text-xl"><?= $params['product']->GetNom() ?></p>
                            <p><?= !empty($params['product']->GetArtiste()) ? $params['product']->GetArtiste() : $params['product']->GetLabel() ?></p>
                            <p>ID : <?= $params['product']->GetId() ?></p>
                            <?php if ($params['product']->GetEventId() == 'ZZISC'): ?>
                            <p>Sortie en <?= $params['product']->GetDateSortie()->format('Y') ?></p>
                            <?php else: ?>
                            <p><?= $params['product']->GetEventId() . '-' . $params['product']->GetEventEdition() ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center items-end">
                        <label for="album-cover" class="whitespace-nowrap bg-black hover:bg-gray-800 text-white py-2 px-4 rounded-full cursor-pointer">Modifier la couverture</label>
                        <p class="text-xs my-1">Format .jpg, 400x400</p>
                        <input name="album-cover" id="album-cover" type="file" class="hidden">
                    </div>
                </div>
                <div>

                    <div class="mb-4">
                        <label for="title">
                            <p>Titre</p>
                        </label>
                        <input type="text" name="title" id="title" class="border px-5 py-2 rounded-md w-full" placeholder="Titre de l'album" value="<?= $params['product']->GetNom() ?>">
                    </div>
                    <div class="flex items-center mb-4 gap-5 ">
                        <?php if (!empty($params['product']->GetArtiste())): ?>
                        <div class="w-full">
                        <label for="creator">
                            <p>Artiste</p>
                        </label>
                        <input type="text" name="creator" id="creator" class=" border px-5 py-2 rounded-md w-full" placeholder="Nom de l'artiste" value="<?= $params['product']->GetArtiste() ?>">
                        </div>
                        <?php else: ?>
                        <div class="w-full">
                        <label for="creator">
                            <p>Label</p>
                        </label>
                        <input type="text" name="creator" id="creator" class=" border px-5 py-2 rounded-md w-full" placeholder="Nom du label" value="<?= $params['product']->GetLabel() ?>">
                        </div>
                        <?php endif; ?>
                        <div class="w-2/3">
                            <label for="creator-type">
                                <p>Type</p>
                            <div class="flex items-center gap-8 py-2">
                            <div>
                                <input type="radio" name="creator-type" id="creator-type-1" value="1" <?= !empty($params['product']->GetArtiste()) ? 'checked' : '' ?>>
                                <label for="creator-type-1">Artiste</label>
                            </div>
                            <div>
                                <input type="radio" name="creator-type" id="creator-type-2" value="0" <?= empty($params['product']->GetArtiste()) ? 'checked' : '' ?>>
                                <label for="creator-type-2">Label</label>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="description">
                            <p class="mb-1">Description</p>
                        </label>
                        <textarea name="description" id="description" placeholder="Description de l'album" class="border px-5 py-2 rounded-md w-full"><?= $params['product']->GetDescription() ?></textarea>
                    </div>
                    <div class="flex items-center mb-4 gap-5 ">
                        <div class="w-full">
                        <label for="event">
                            <p>Evenement</p>
                        </label>
                        <select name="event" id="event" class="bg-white border px-5 py-2 rounded-md w-full">
                            <?php foreach ($params['events'] as $event): ?>
                                <option value="<?= $event->GetId() ?>" <?= $event->GetId() == $params['product']->GetEventId() ? 'selected' : '' ?>><?= $event->GetNom() ?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                        <div class="w-full">
                            <label for="edition">
                                <p>Edition</p>
                                <input type="text" name="edition" id="edition" placeholder="Numéro de l'édition de l'événement" class="bg-white border px-5 py-2 rounded-md w-full" value="<?= $params['product']->GetEventEdition() ?>">
                            </label>
                        </div>
                    </div>
                    <div class="w-full mb-4">
                        <label for="release-date">
                        </label>
                            <p>Date de sortie</p>
                            <input type="date" name="release-date" id="release-date" class="bg-white border px-5 py-2 rounded-md w-full" value="<?= $params['product']->GetDateSortie()->format('Y-m-d') ?>">

                    </div>
                    <div class="mb-4">
                        <label for="xfd">
                            <p>Lien XFD <span class="text-sm">(Youtube ou SoundCloud uniquement)</span></p>
                        </label>
                        <input type="text" name="xfd" id="xfd" class="border px-5 py-2 rounded-md w-full" placeholder="Lien vers le XFD..." value="<?= $params['product']->GetLienXFD() ?>">
                    </div>
                    <div class="flex items-center mb-4 gap-5 ">
                        <div class="w-full">
                        <label for="price">
                            <p>Prix</p>
                        </label>
                        <input type="number" name="price" id="price" class="border px-5 py-2 rounded-md w-full" placeholder="Prix de l'album" value="<?= $params['product']->GetPrix() ?>">
                        </div>
                        <div class="w-full">
                        <label for="quantity">
                            <p>Quantité totale</p>
                        </label>
                        <input type="number" disabled class="border px-5 py-2 rounded-md w-full" placeholder="Quantité en stock" value="<?= $params['product']->GetQte() ?>">
                        </div>
                    </div>  
                    <div class="w-full mb-4">
                            <p>Liste des chansons</p>
                            <div id="song-list" class="bg-gray-100 border rounded-md w-full h-80 overflow-scroll pb-10" data-songs-count="<?= count($params['songs']) ?? 0 ?>">
                            <div id="song-container">
                                <?php $i = 0; ?>
                            <?php foreach ($params['songs'] as $song): ?>
                                <?php $i++; ?>
                                <div class="list-row flex justify-between border-b px-5 py-3 w-full bg-white cursor-pointer" data-song-id="<?= $song->GetId() ?>">
                                    <div class="flex gap-3 text-center">    
                                    <p class="w-5 text-right"><?= $i ?></p>
                                        <p class="w-1">-</p>
                                        <p class="font-bold"><?= $song->getTitle() ?></p>
                                        <p class="w-1">-</p>
                                        <p><?= $song->getArtist() ?></p>
                                    </div>
                                        <p>
                                        <?php
                                        $fakeDuration = rand(60, 500);
                                        $fakeMinutes = floor($fakeDuration / 60);
                                        $fakeSeconds = floor($fakeDuration % 60);
                                        echo sprintf('%02d:%02d', $fakeMinutes, $fakeSeconds);
                                        ?>
                                        </p>
                                </div>
                            <?php endforeach; ?>
                            </div>
                                <div id="new-song-form" class="hidden">
                                <div class="list-row flex justify-between border-b px-5 py-3 w-full">
                                        <div class="flex gap-3 text-center">    
                                        <p class="w-2 ">?</p>
                                            <p class="w-1">-</p>
                                            <input id="title-new" type="text" placeholder="Titre de la chanson" class="border rounded-md px-2">
                                            <p class="w-1">-</p>
                                            <input id="artist-new" type="text" placeholder="Artiste de la chanson" class="border rounded-md px-2">
                                        </div>
                                </div>
                                <div class="flex justify-center pb-10">
                                <button id="confirm-new-song" class=" bg-black text-white py-1 px-4 rounded-full my-2 hover:bg-gray-800 cursor-pointer">Confirmer l'ajout</button>
                                </div>
                            </div>
                            </div>
                            <div class="flex gap-2 items-center py-2">
                                <button id="add-song" class="bg-black text-white px-5 py-2 rounded-md hover:bg-gray-800"><i class="ri-add-fill"></i> Ajouter une chanson</button>
                            </div>

                            <input type="submit" class="bg-black text-white py-5 px-5 text-center rounded-md w-full cursor-pointer hover:bg-gray-800 mt-10" value="Confirmer les modifications">
                            
                    </div>
                </form>
                    <?php endif; ?>
                        </div>
                    </div>
                </div>
        </section>