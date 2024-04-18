<section id="looking-for" class="bg-gradient-sunset">
            <div class="py-16 text-center text-white">
                <h2 class="text-4xl font-extrabold">
                <?php
                    if (isset($_GET['event']) && $_GET['event'] != 'all') {
                        $i = 0;
                        $found = false;
                        while (!$found && $i < count($params['events'])) {
                            if ($_GET['event'] == $params['events'][$i]->GetId()) {
                                echo htmlspecialchars($params['events'][$i]->GetNom()) . ' ';
                                $found = true;
                            }
                            $i++;
                        }
                        if (isset($_GET['edition']) && $_GET['edition'] != 'all')
                            echo htmlspecialchars($_GET['edition']);
                    }

                ?>
                </h2>
                <?php if (isset($_GET['query']) && !empty($_GET['query'])): ?>
                    <h3 class="text-3xl font-bold">Résultats pour la recherche : "<?= htmlspecialchars($_GET['query']) ?>"</h3>
                <?php endif; ?>
                <h4 class="text-xl font-normal"><?= count($params['results']) ?> résultat<?= count($params['results']) > 1 ? 's' : '' ?></h4>
            </div>
        </section>
        <section id="result" class="bg-white">
            <div class="py-10 bg-slate-50 flex flex-col sm:flex-row justify-center gap-6 items-start">
                <div id="filter" class="w-full md:w-80 bg-white p-6 rounded-md relative h-auto">
                    <form action="" class="h-auto flex flex-col">
                        <h1 class="font-bold mb-5 text-2xl">Filtres</h1>
                        <div id="name_input">
                        <label for="name"><p class="font-bold">Nom</p></label>
                        <input placeholder="Saisissez le nom d'un album..." type="text" name="query" id="name" class="bg-[#E5E7EB] px-3 h-[34px] border-x-8 mb-5 w-full rounded-sm" value="<?= isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '' ?>" />
                        </div>
                        <div id="event_select">
                        <label for="event"><p class="font-bold">Événement</p></label>
                        <select name="event" id="event" class="px-3 h-[34px] border-x-8 mb-5 w-full rounded-sm">
                            <option selected disabled>Sélectionnez un événement...</option>
                            <option value="all">Tous</option>
                            <?php foreach ($params['events'] as $event): ?>
                                <option value="<?= $event->GetId() ?>"
                                <?php if (isset($_GET['event']) && $_GET['event'] == $event->GetId()) echo 'selected' ?>
                                ><?= $event->GetNom() ?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                        <div id="edition_select">
                        <label for="edition"><p class="font-bold">Edition</p></label>
                        <select name="edition" id="edition" class="px-3 h-[34px] border-x-8 mb-5 w-full rounded-sm">
                            <option selected disabled value="">Sélectionnez une édition...</option>
                            <?php foreach ($params['editions'] as $edition): ?>
                                <option class="<?= $edition->getIdEvent() ?>"
                                value="<?= $edition->GetNumEdition() ?>" 
                                ><?= $edition->getIdEvent() . '-' . $edition->GetNumEdition() ?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                        <input type="submit" value="Filtrer" class="bg-black text-white py-3 px-9 cursor-pointer rounded-sm my-5" />
                    </form>
                </div>
                <div
                    class="gap-3 xl:gap-5 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 bg-white p-5 w-full md:w-[80vw] lg:w-[73vw] xl:w-[70vw] 2xl:w-[55vw] 2xl:max-w-[1100px] rounded-md"
                >
                <?php if (empty($params['results'])): ?>
                    <div class="text-center col-span-2 md:col-span-3 lg:col-span-4">
                        <p class="text-2xl font-bold">Aucun résultat</p>
                        <p class="text-lg font-light">Aucun album ne correspond à votre recherche.</p>
                    </div>
                    <?php else: ?>
                <?php foreach ($params['results'] as $album): ?>
                    <div class="product cursor-pointer">
                        <a href="/product/<?= htmlspecialchars($album->getId()) ?>">
                            <div class="relative flex justify-center">
                                <img src="<?= CDN_URL . '/images/album/' . htmlspecialchars($album->GetUriImage()) ?>" alt="<?= $album->GetNom() ?>" />
                                <button class="bg-white text-black absolute px-6 py-1 rounded-md text-sm">
                                    Voir le produit
                                </button>
                            </div>
                            <div class="flex justify-between font-bold">
                                <div class="text-sm">
                                    <p><?= $album->GetNom() ?></p>
                                    <p class="font-light"><?= !empty($album->GetArtiste()) ? $album->GetArtiste() : $album->GetLabel() ?></p>
                                </div>
                                <p class="text-right text-xl whitespace-nowrap"><?= $album->GetPrix() ?> €</p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
                </div>
            </div>
        </section>