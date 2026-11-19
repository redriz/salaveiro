            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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