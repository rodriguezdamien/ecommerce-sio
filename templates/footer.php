<footer class="bg-black text-white">
            <!-- Footer d'une page e-commerce qui ne soit pas trop large et contenant les informations essentielles-->
            <div class="mx-14 lg:max-w-6xl lg:mx-auto py-12">
                <div class="grid grid-flow-row md:grid-cols-2 lg:grid-cols-3 gap-32">
                    <div>
                        <div class="flex flex-row gap-2">
                            <img src="<?= CDN_URL ?>/svg/kanji_white.svg" alt="" width="50px" />
                            <img src="<?= CDN_URL ?>/svg/gaku_white.svg" width="90px" alt="" />
                        </div>
                        <p class="font-extralight">
                        Gaku! est une plateforme dédiée à la musique indépendante japonaise (doujin). Nous importons nous-mêmes les albums pour faire découvrir cette culture amateur en France.
                        </p>
                        <div class="flex items-center my-2">
                                <a href="#"><i class="ri-facebook-line text-3xl"></i></a>
                                <a href="#"><i class="ri-twitter-line text-3xl"></i></a>
                                <a href="#"><i class="ri-instagram-line text-3xl"></i></a>
                                </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-2xl">Liens</h3>
                        <ul class="font-extralight">
                            <a href="/"><li>Accueil</li>
                            <a href="/results"><li>Produits</li>
                            <a href="/register"><li>Inscription</li></a>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold text-2xl">Nos locaux</h3>
                        <p class="font-extralight">
                            39 Avenue des Voix Turquoises
                            <br />
                            75012 Paris
                            <br />
                            France
                        </p>
                    </div>
                </div>
            </div>
        </footer>
        <script src= <?= CDN_URL . '/js/script.js' ?>></script>
        <?php foreach ($scripts as $script): ?>
        <script src= <?= CDN_URL . '/js/' . $script ?>></script>
        <?php endforeach; ?>
    </body>
</html>
