<footer class="bg-black text-white">
            <!-- Footer d'une page e-commerce qui ne soit pas trop large et contenant les informations essentielles-->
            <div class="mx-14 lg:max-w-6xl lg:mx-auto py-12">
                <div class="grid grid-flow-row md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <div class="flex flex-row gap-2">
                            <img src="<?= CDN_URL ?>/svg/kanji_white.svg" alt="" width="50px" />
                            <img src="<?= CDN_URL ?>/svg/gaku_white.svg" width="90px" alt="" />
                        </div>
                        <p class="font-extralight">
                            gaku! est une plateforme de vente de disques d'artistes indépendants japonais. Nous
                            importons pour vous les dernières sorties.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-bold text-2xl">Liens</h3>
                        <ul class="font-extralight">
                            <li>Accueil</li>
                            <li>Précommandes</li>
                            <li>Labels</li>
                            <li>À propos</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold text-2xl">Contact</h3>
                        <p class="font-extralight">
                            123 rue de la rue
                            <br />
                            75000 Paris
                            <br />
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
