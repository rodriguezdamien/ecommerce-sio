<section id="title-bar" class="bg-gradient-sunset">
            <div class="py-10 text-center text-white">
                <h2 class="text-4xl font-extrabold">Votre panier</h2>
            </div>
        </section>
        <section
            id="cart"
            class="bg-slate-50 py-10 justify-center gap-6 flex flex-col lg:flex-row items-stretch"
        >
            <div
                id="cart-elements"
                class="bg-white flex flex-col items-center w-full lg:w-[60vw] xl:w-[55vw]"
            >
            <?php if (count($params['cart']) > 0): ?>
                <?php $i = 0 ?>
                <?php foreach ($params['cart'] as $item): ?>
                <div
                    class="cart-product w-full lg:w-[60vw] xl:w-[55vw] relative"
                    data-id="<?= $item['album']->getId() ?>"
                >
                    <div class="removing absolute w-full h-full hidden justify-center items-center bg-[#000000c0]">
                        <div class="flex p-5 text-center gap-3">
                            <p class="text-white my-4">Article supprimé</p>
                            <button class="cancel bg-white text-black font-medium py-3 rounded px-9">Annuler ?</button>
                        </div>
                    </div>
                        <div class="px-5 lg:px-12 flex justify-between items-center py-8">
                            <div class="flex items-center gap-3 sm:gap-10">
                                <a href="/product/<?= htmlspecialchars($item['album']->getId()) ?>">    
                                <img src="<?= CDN_URL . '/images/album/' . htmlspecialchars($item['album']->GetUriImage()) ?>" class="h-32 sm:h-[13rem]" alt="Artifacts Zero" />
                                </a>
                                <div>
                                    <a href="/product/<?= htmlspecialchars($item['album']->getId()) ?>"> 
                                        <h3 class="text-md sm:text-xl font-bold"><?= htmlspecialchars($item['album']->GetNom()) ?></h3>
                                    </a>
                                        <p class="text-sm sm:text-lg"><?= !empty($item['album']->GetArtiste()) ? $item['album']->GetArtiste() : $item['album']->GetLabel() ?></p>
                                    <div class="flex mt-10 mb-2 items-center gap-3">
                                        <div class="quantity-picker h-8 sm:h-12 border border-black flex items-center rounded-full" data-quantity=<?= htmlspecialchars($item['album']->GetQte()) ?>>
                                            <div class="h-full w-8 sm:w-12 justify-center flex items-center cursor-pointer minus">
                                                <i class="ri-subtract-fill"></i>
                                            </div>
                                            <div
                                                class="w-10 sm:w-16 border-black border-r border-l h-full justify-center flex items-center qte"
                                         >
                                                <?= $item['qte'] ?>
                                            </div>
                                            <div class="w-8 sm:w-12 justify-center flex items-center cursor-pointer h-full plus">
                                                <i class="ri-add-fill"></i>
                                            </div>
                                        </div>
                                        <button class="remove">
                                            <i class="ri-delete-bin-5-line text-2xl"></i>
                                        </button>
                                    </div>
                                </div>      
                        </div>
                        <div>
                            <p class="font-bold text-xl sm:text-2xl break-normal break-keep"><?= $item['album']->GetPrix() * $item['qte'] ?> €</p>
                        </div>
                    </div>
                    <?php $i++;
                    if ($i != count($params['cart'])): ?>
                    <span class="separator"></span>
                    <?php endif; ?>
                </div>
                
                <?php endforeach; ?>
            </div>
            <div class="relative">
            <div class="bg-white w-full xl:w-[25vw] sticky top-20">
                <div id="need-update" class="absolute w-full h-full justify-center items-center bg-[#000000c0] hidden">
                    <div class="flex flex-col p-5 text-center">
                        <p class="text-white my-4">Vous devez confirmer les modifications de votre panier avant de pouvoir continuer.</p>
                        <button id="confirm-update" class="bg-white text-black font-medium py-3 rounded">Confirmer</button>
                    </div>
                </div>
                <div class="p-9 flex flex-col">
                <p class="font-bold text-2xl mb-5">Résumé</p>
                <div class="flex justify-between">
                    <p>Sous-total</p>
                    <p class="font-bold"> <?= $params['total'] ?> €</p>
                </div>
                <div class="flex justify-between mb-10">
                    <p>Livraison</p>
                    <p class="font-bold">Gratuite</p>
                </div>
                <span class="separator mb-2"></span>
                <div class="flex justify-between text-2xl">
                    <p>Total</p>
                    <p class="font-bold"><?= $params['total'] ?> €</p>
                </div>
                <button class="bg-black text-white py-3 rounded-md font-semibold mt-5">Passer la commande</button>
                </div>
            </div>
            <div id="update-error" class=" bg-red-600 text-center rounded-md my-2 py-2 w-full hidden">
                <p id="update-error-message" class="text-white"></p>
            </div>
            <?php else: ?>
                <div class="bg-white w-full flex justify-center">
                    <p>Votre panier est vide.</p>
                </div>
            <?php endif; ?>
            
        </section>