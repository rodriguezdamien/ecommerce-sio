<section id="title-bar" class="bg-gradient-sunset">
            <div class="py-10 text-center text-white">
                <h2 class="text-4xl font-extrabold">Votre panier</h2>
            </div>
        </section>
        <section
            id="cart"
            class="bg-slate-50 py-10 justify-center gap-6 flex flex-col lg:flex-row items-start min-h-[75vh]"
        >
            <div
                id="cart-elements"
                class="bg-white py-10 flex flex-col gap-5 items-center w-full lg:w-[60vw] xl:w-[55vw]"
            >
            <?php if (count($params['cart']) > 0): ?>
                <?php foreach ($params['cart'] as $item): ?>
                <div
                    class="cart-product flex justify-between items-center px-5 lg:px-12 w-full lg:w-[60vw] xl:w-[55vw]"
                >
                    <div class="flex items-center gap-3 sm:gap-10">
                        <img src="<?= CDN_URL . '/images/album/' . htmlspecialchars($item->GetUriImage()) ?>" class="h-32 sm:h-[13rem]" alt="Artifacts Zero" />
                        <div>
                            <h3 class="text-md sm:text-xl font-bold"><?= htmlspecialchars($item->GetNom()) ?></h3>
                            <p class="text-sm sm:text-lg"><?= !empty($item->GetArtiste()) ? $item->GetArtiste() : $item->GetLabel() ?></p>
                            <div class="h-8 sm:h-12 border border-black inline-flex items-center rounded-full mt-10">
                                <div class="h-full w-8 sm:w-12 justify-center flex items-center cursor-pointer">
                                    <i class="ri-subtract-fill"></i>
                                </div>
                                <div
                                    class="w-10 sm:w-16 border-black border-r border-l h-full justify-center flex items-center"
                                >
                                    1
                                </div>
                                <div class="w-8 sm:w-12 justify-center flex items-center cursor-pointer h-full">
                                    <i class="ri-add-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div><p class="font-bold text-xl sm:text-2xl break-normal break-keep"><?= $item->GetPrix() ?> €</p></div>
                </div>
                <span class="separator"></span>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Votre panier est vide.</p>
            <?php endif; ?>
            </div>
            <div class="bg-white w-full xl:w-[25vw] p-9 flex flex-col">
                <p class="font-bold text-2xl mb-5">Résumé</p>
                <div class="flex justify-between">
                    <p>Sous-total</p>
                    <p class="font-bold">
                    </p>
                </div>
                <div class="flex justify-between mb-10">
                    <p>Livraison</p>
                    <p class="font-bold">Gratuite</p>
                </div>
                <span class="separator mb-2"></span>
                <div class="flex justify-between text-2xl">
                    <p>Total</p>
                    <p class="font-bold">29.97 €</p>
                </div>
                <button class="bg-black text-white py-3 rounded-md font-semibold mt-5">Passer la commande</button>
             
            </div>
        </section>