<section id="hero" style="background-image: url(<?= CDN_URL . '/images/album/' . htmlspecialchars($params['product']->GetUriImage()) ?>">
            <div
                class="relative z-20 flex justify-center items-center sm:mx-10 md:mx-52 text-white gap-10 md:flex-row flex-col py-10"
            >
                <img src="<?= CDN_URL . '/images/album/' . htmlspecialchars($params['product']->GetUriImage()) ?>" alt="" class="md:min-w-[340px] last:w-[85vw] md:w-96 lg:w-[25rem]" />
                <div id="product-info" data-id="<?= htmlspecialchars($params['product']->GetId()) ?>">
                    <p class="font-bold text-4xl md:text-5xl"><?= $params['product']->getNom() ?></p>
                    <p class="font-extralight text-xl my-2"><?= !empty($params['product']->GetArtiste()) ? $params['product']->GetArtiste() : $params['product']->GetLabel() ?></p>
                    <p class="font-bold text-3xl md:text-4xl my-2"><?= $params['product']->GetPrix() ?> €</p>
                    <div class="mt-12 flex justify-center md:block">
                        <div
                            id="quantity-select"
                            class="h-16 border border-white inline-flex items-center rounded-full"
                        >
                            <div
                                class="h-full w-[15vw] md:w-16 justify-center flex items-center cursor-pointer"
                                id="minus"
                            >
                                <i class="ri-subtract-fill"></i>
                            </div>
                            <div
                                id="quantity" data-quantity=<?= $params['product']->GetQte() ?>
                                class="w-[50vw] md:w-24 border-r border-l h-full justify-center flex items-center"
                            >
                                1
                            </div>
                            <div
                                class="w-[15vw] md:w-16 justify-center flex items-center cursor-pointer h-full"
                                id="plus"
                            >
                                <i class="ri-add-fill"></i>
                            </div>
                        </div>
                    </div>
                            <div id="action-container" class="relative flex items-center gap-1">
                                <button id="action-loading" class="bg-white text-black h-16 w-[80vw] md:w-96 rounded-md mt-7 animate-button" data-id="<?= htmlspecialchars($params['product']->GetId()) ?>">
                                    <span class="loading"></span>
                                </button>
                                <div id="cart-info" class="absolute flex invisible w-full flex-col items-center opacity-1 -bottom-16" style="transform:translateY(-10px)">
                                    <div class="w-3 h-3 bg-white rotate-45 translate-y-[50%]"></div>
                                    <div class="bg-white rounded-md px-3 py-2">
                                        <span id="info-text" class="loading whitespace-nowrap"></span>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="preview" class="bg-[#ED2262] text-white">
            <div class="md:mx-52 py-8">
                <h2 class="font-bold text-3xl">Aperçu</h2>
                <div id="xfd-container" class="flex flex-col lg:flex-row gap-10 items-center justify-center py-5">
                    <div>
                        <?php if (preg_match('/https:\/\/www\.youtube\.com\/watch\?v=/', $params['product']->GetLienXFD())): ?>
                        <iframe
                            class="aspect-video 2xl:w-[35vw] 2xl:min-w-[850px] xl:w-[700px] lg:w-[650px] w-[95vw]"
                            src="https://www.youtube.com/embed/<?= preg_replace('/https:\/\/www\.youtube\.com\/watch\?v=/', '', $params['product']->GetLienXFD()) ?>"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                        ></iframe>
                        <?php elseif (preg_match('/https:\/\/soundcloud\.com\//', $params['product']->GetLienXFD())): ?>
                        <iframe
                         class="aspect-video 2xl:w-[35vw] 2xl:min-w-[850px] xl:w-[700px] lg:w-[650px] w-[95vw]"
                         scrolling="no"
                         frameborder="no"
                         src="https://w.soundcloud.com/player/?visual=true&url=<?= $params['product']->GetLienXFD() ?>">
                        </iframe>
                        <?php else: ?>
                        <p class="text-center">Aucun aperçu disponible.</p>
                        <?php endif; ?>
                    </div>
                    <div class="">
                        <ol id="tracklist" class="max-w-[800px] 2xl:w-[35vw] xl:w-[40vw] lg:w-[35vw] w-[90vw]">
                        <?php
                            $songNumber = 0;
                            foreach ($params['songs'] as $song):
                        ?>   
                        <li>
                            <div class="mx-1 flex justify-between items-center">
                                <div class="flex items-center">
                                    <p class="track-number">
                                       <?php
                                // je suis pas hyper fier de ça
                                $songNumber++;
                                echo $songNumber;
                            ?></p>
                                    <div class="track-info flex">
                                        <p class="track-artist"><?= $song->getArtist() ?></p><i class="track-separator"></i><p class="track-title"><?= $song->getTitle() ?></p>
                                    </div>
                                </div>
                                <div class="w-12 text-center"><?php
                                $fakeDuration = rand(60, 500);
                                $fakeMinutes = floor($fakeDuration / 60);
                                $fakeSeconds = floor($fakeDuration % 60);
                                echo sprintf('%02d:%02d', $fakeMinutes, $fakeSeconds);

                            ?></div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section id="details" class="bg-white">
            <div class="md:mx-44 py-8 mx-8">
                <h2 class="font-bold text-3xl mb-5 text-center">Détails</h2>
                <div class="flex justify-center gap-5 flex-col">
                    <div class="flex flex-col sm:flex-row gap-5 justify-center items-center sm:items-start">
                        <h3 class="font-bold text-xl text-center sm:text-end leading-5">Description</h3>
                        <p class="w-auto sm:w-[800px]">
                            <?= $params['product']->GetDescription(); ?>
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-5 justify-center items-center sm:items-start">
                        <h3 class="font-bold text-xl text-center sm:text-end leading-5">Nombre de pistes</h3>
                        <p class="w-auto sm:w-[800px]">
                        <?php if ($songNumber > 1): ?>
                        "<?= $params['product']->getNom(); ?>" est composé de <?= $songNumber; ?> titres.
                        <?php else: ?>
                        "<?= $params['product']->getNom(); ?>" est un single, il n'y a donc qu'un titre.
                        <?php endif; ?>
                        </p>
                    </div>
                
                </div>
            </div>
        </section>
        <section id="section-recommend" class="bg-slate-100 py-5">
            <div class="lg:mx-44 md:mx-24 mx-10">
                <h2 class="font-bold text-3xl mb-5">Recommandations</h2>
            </div>
            <div class="splide m-auto mt-5" role="group" aria-label="Splide Basic HTML Example" id="recommend">
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
                    <?php foreach ($params['albums'] as $album): ?>        
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
        </section>