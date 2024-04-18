<section id="news">
            <div class="w-full bg-gradient-sunset py-10">
                <div class="flex justify-center items-center gap-x-9 flex-col-reverse md:flex-row">
                    <div class="text-center text-white">
                        <h2 class="font-extrabold text-4xl">Media Music Market 53</h2>
                        <p class="font-extralight text-1xl">Les albums de l'évènements sont arrivés.</p>
                        <button class="rounded-full bg-white text-black font-semibold py-2 px-8 m-4">
                            Découvrir >>
                        </button>
                    </div>
                    <div>
                        <img src="<?= CDN_URL ?>/images/album 3d.png" class="w-auto h-96 object-contain" />
                    </div>
                </div>
            </div>
        </section>
        <section id="introduction" class="text-center bg-white py-6">
            <h2 class="font-bold text-3xl">Bienvenue dans le Dōjin.</h2>
            <p class="mt-2 font-extralight">
                Le Japon a en lui une communauté importante de créateurs indépendants. Découvrez leurs
                créations musicales importés du Japon par nos soins.
            </p>
        </section>
        <section id="section-trending   " class="bg-black w-full text-white py-5">
            <div class="lg:mx-44 md:mx-24 mx-10">
                <h2 class="font-bold text-2xl">En ce moment</h2>
                <p class="font-extralight">
                    Si tout le monde se les arrachent, c'est qu'ils peuvent sûrement t'intéresser.
                </p>
            </div>
            <?php if (isset($params['trending_albums']) && !empty($params['trending_albums'])): ?>
            <div class="splide m-auto mt-5" role="group" aria-label="Splide Basic HTML Example" id="trending">
                <div class="splide__arrows z-40 w-full">
                    <button
                        class="splide__arrow splide__arrow--prev rounded-full bg-white border border-white w-10 h-10 left-3 lg:-left-5 xl:-left-16 top-1/3 absolute z-40 drop-shadow-xl"
                    >
                        <i class="ri-arrow-left-s-line text-black text-3xl"></i>
                    </button>
                    <button
                        class="splide__arrow splide__arrow--next rounded-full bg-white border border-white w-10 h-10 right-3 lg:-right-5 xl:-right-16 top-1/3 absolute z-40 drop-shadow-xl"
                    >
                        <i class="ri-arrow-right-s-line text-black text-3xl"></i>
                    </button>
                </div>
                <div class="splide__track">
                    <ul class="splide__list">
                    <?php foreach ($params['trending_albums'] as $album): ?>    
                    <li class="splide__slide">
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
                        </li>
                        <?php endforeach; ?>
                        
                    </ul>
                </div>
            </div>
            <?php else: ?>
            <div class="flex justify-center items-center py-10">
                <p class="text-xl">Une erreur est survenue lors du chargement des albums.</p>
            </div>            
            <?php endif; ?>
        </section>
        <section id="section-preorder" class="bg-[#ED2262] text-white py-5">
            <div class="lg:mx-44 md:mx-24 mx-10">
                <h2 class="font-bold text-2xl">Précommandes</h2>
                <p class="font-extralight">Ils débarquent très bientôt, réservez vos disques !</p>
            </div>
            <?php if (isset($params['preorder_albums']) && !empty($params['preorder_albums'])): ?>
            <div class="splide m-auto mt-5" role="group" aria-label="Splide Basic HTML Example" id="preorder">
                <div class="splide__arrows z-40 w-full">
                    <button
                        class="splide__arrow splide__arrow--prev rounded-full bg-white border border-white w-10 h-10 left-3 lg:-left-5 xl:-left-16 top-1/3 absolute z-40 drop-shadow-xl"
                    >
                        <i class="ri-arrow-left-s-line text-black text-3xl"></i>
                    </button>
                    <button
                        class="splide__arrow splide__arrow--next rounded-full bg-white border border-white w-10 h-10 right-3 lg:-right-5 xl:-right-16 top-1/3 absolute z-40 drop-shadow-xl"
                    >
                        <i class="ri-arrow-right-s-line text-black text-3xl"></i>
                    </button>
                </div>
                <div class="splide__track">
                    <ul class="splide__list">
                    <?php foreach ($params['preorder_albums'] as $album): ?>        
                        <li class="splide__slide">
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
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php else: ?>
            <div class="flex justify-center items-center py-10">
                <p class="text-xl">Une erreur est survenue lors du chargement des albums.</p>
            </div>            
            <?php endif; ?>
        </section>
        <section id="section-discover" class="bg-white text-black py-5">
            <div class="lg:mx-44 md:mx-24 mx-10 mb-5">
                <h2 class="font-bold text-2xl">Découvrez la scène</h2>
                <p class="font-extralight">
                    Voici les labels que nous recommandons à tout les intéressés. Leurs productions sont toujours
                    attendues et appréciés par la communauté.
                </p>
            </div>
            <div class="mx-auto w-5/6 md:w-3/4 xl:w-4/6 drop-shadow-xl mb-8">
                <div class="bg-black text-white relative rounded-t-xl accordion">
                    <div class="flex justify-center sm:justify-between items-center py-5 px-10">
                        <img src="<?= CDN_URL ?>/images/tanoc.svg" />
                        <i class="ri-arrow-drop-down-line text-5xl hidden sm:block"></i>
                    </div>
                    <div id="tanoc-content" class="accordion-content">
                        <p class="pb-5 px-10">
                            HARDCORE TANO*C est une organisation de hardcore qui regroupe pas moins une dizaine
                            d'artistes de la scène doujin. C'est l'une des plus grosses organisation au Japon. Ils ont
                            un style hardcore très diversifié qui va du gabber à la makina. En raison de leur
                            popularité, il ont ouvert des boîtes de nuit à l'Est et à l'ouest du Japon, dans lesquelles
                            ils organisent leur TANO*C STRIKE.
                        </p>
                    </div>
                </div>
                <div class="bg-megarex text-white relative accordion">
                    <div class="flex justify-center sm:justify-between items-center py-5 px-10">
                        <img src="<?= CDN_URL ?>/images/megarex.png" />
                        <i class="ri-arrow-drop-down-line text-5xl hidden sm:block"></i>
                    </div>
                    <div id="megarex-content" class="accordion-content">
                        <p class="pb-5 px-10">
                            Megarex est un label de musique japonais fondé en 2012 par DJPoyoshi. Même s'ils sont encore
                            moins connus, ils sont déjà en passe de battre les plus grands labels mondiaux. Ils sont
                            l'avenir.
                        </p>
                    </div>
                </div>
                <div class="text-white bg-[#222227] relative accordion">
                    <div class="flex justify-center sm:justify-between items-center py-5 px-10">
                        <img src="<?= CDN_URL ?>/images/nextlight.png" />
                        <i class="ri-arrow-drop-down-line text-5xl hidden sm:block"></i>
                    </div>
                    <div id="nextlight-content" class="accordion-content">
                        <p class="pb-5 px-10">
                            NEXTLIGHT est un label japonais utilisant des voix synthétisées telles que les Vocaloids
                            (Hatsune Miku, Kagamine Rin, GAMU...) pour produire des musiques électros formidables.
                        </p>
                    </div>
                </div>
                <div class="text-black bg-white relative accordion">
                    <div class="flex justify-center sm:justify-between items-center py-5 px-10">
                        <img src="<?= CDN_URL ?>/images/LiliumRecords.png" />
                        <i class="ri-arrow-drop-down-line text-5xl hidden sm:block"></i>
                    </div>
                    <div id="mottomusic-content" class="accordion-content">
                        <p class="pb-5 px-10">
                            NEXTLIGHT est un label japonais utilisant des voix synthétisées telles que les Vocaloids
                            (Hatsune Miku, Kagamine Rin, GAMU...) pour produire des musiques électros formidables.
                        </p>
                    </div>
                </div>
                <div class="text-white bg-[#F97A7A] relative rounded-b-lg accordion">
                    <div class="flex justify-center sm:justify-between items-center py-5 px-10">
                        <img src="<?= CDN_URL ?>/images/mottomusic.png" />
                        <i class="ri-arrow-drop-down-line text-5xl hidden sm:block"></i>
                    </div>
                    <div id="nextlight-content" class="accordion-content">
                        <p class="pb-5 px-10">
                            NEXTLIGHT est un label japonais utilisant des voix synthétisées telles que les Vocaloids
                            (Hatsune Miku, Kagamine Rin, GAMU...) pour produire des musiques électros formidables.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section id="more" class="text-white bg-[#BB5895]">
            <div class="py-5 text-center mx-14 lg:max-w-5xl lg:mx-auto">
                <h3 class="text-2xl font-bold">Vous êtes curieux ?</h3>
                <p class="font-extralight">Découvrez encore plus de la scène.</p>
                <div class="categories my-8 grid grid-flow-row md:grid-cols-2 lg:grid-cols-3 gap-4 mx-auto">
                    <a href="/results?event=C">
                    <div
                        id="comiket"
                        class="border border-white/30 rounded-lg h-60 flex justify-center items-center overflow-hidden cursor-pointer"
                    >
                        <div class="w-full h-full flex justify-center items-center relative">
                            <img src="<?= CDN_URL ?>/images/category/icons/comiket.png" class="h-40 z-50" />
                            <span class="absolute w-full h-16"></span>
                        </div>
                    </div>
                </a>
                    <a href="/results?event=M3">
                    <div id="m3" class="border border-white/30 rounded-lg h-60 relative overflow-hidden cursor-pointer">
                        <div class="w-full h-full flex justify-center items-center">
                            <img src="<?= CDN_URL ?>/images/category/icons/m3.png" class="h-32" />
                            <span class="absolute w-full h-16"></span>
                        </div>
                    </div>
                    </a>
                    <a href="/results?event=REI">
                    <div id="touhou" class="border border-white/30 rounded-lg h-60 relative overflow-hidden cursor-pointer">
                        <div class="w-full h-full flex justify-center items-center">
                            <img src="<?= CDN_URL ?>/images/category/icons/touhou.png" class="h-40" />
                            <span class="absolute w-full h-16"></span>
                        </div>
                    </div>
                    </a>
                    <a class="rounded-lg lg:col-span-3" href="/results?event=MISC">
                    <div
                        id="misc"
                        class="border border-white/30 h-60 lg:h-40 relative overflow-hidden cursor-pointer"
                    >
                        <div class="w-full h-full flex justify-center items-center">
                            <h2 class="font-bold text-2xl">Divers</h2>
                            <span class="absolute w-full h-16"></span>
                        </div>
                    </div>
                    </a>
                </div>
            </div>
        </section>