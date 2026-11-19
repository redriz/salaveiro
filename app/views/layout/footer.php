            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            var raiz = document.documentElement;
            var mobile = window.matchMedia('(max-width: 991.98px)');

            document.getElementById('sidebar-toggle').addEventListener('click', function () {
                if (mobile.matches) {
                    raiz.classList.toggle('sidebar-aberta');
                    return;
                }
                var recolhida = raiz.classList.toggle('sidebar-recolhida');
                try {
                    localStorage.setItem('sidebar', recolhida ? 'recolhida' : 'expandida');
                } catch (e) {}
            });

            document.getElementById('sidebar-fundo').addEventListener('click', function () {
                raiz.classList.remove('sidebar-aberta');
            });

            mobile.addEventListener('change', function () {
                raiz.classList.remove('sidebar-aberta');
            });
        })();

        document.querySelectorAll('[data-carregar]').forEach(function (el) {
            fetch(el.dataset.carregar, { headers: { 'X-Requested-With': 'fetch' } })
                .then(function (res) {
                    if (!res.ok || res.redirected) {
                        throw new Error();
                    }
                    return res.text();
                })
                .then(function (html) {
                    el.innerHTML = html;
                })
                .catch(function () {
                    el.innerHTML = '<div class="alert alert-danger mb-0"><i class="bi bi-exclamation-triangle"></i> <strong>Erro de comunicação.</strong> Não foi possível carregar os dados.</div>';
                });
        });
    </script>
</body>
</html>