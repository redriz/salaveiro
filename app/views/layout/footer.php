            </main>
        </div>
    </div>
    <div class="modal fade" id="modal-sair" tabindex="-1" aria-labelledby="modal-sair-titulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <i class="bi bi-box-arrow-left fs-1 text-danger d-block mb-2"></i>
                    <h5 class="mb-1" id="modal-sair-titulo">Terminar sessão?</h5>
                    <p class="text-muted small mb-0">Terá de voltar a iniciar sessão para aceder à aplicação.</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="index.php?page=auth&action=logout" class="btn btn-danger">Sair</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-confirmar" tabindex="-1" aria-labelledby="modal-confirmar-titulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <i class="bi bi-trash fs-1 text-danger d-block mb-2" id="modal-confirmar-icone"></i>
                    <h5 class="mb-1" id="modal-confirmar-titulo"></h5>
                    <p class="text-muted small mb-0" id="modal-confirmar-texto"></p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" id="modal-confirmar-voltar">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="modal-confirmar-ok">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            var modalEl = document.getElementById('modal-confirmar');
            var modal = new bootstrap.Modal(modalEl);
            var formPendente = null;

            document.addEventListener('click', function (e) {
                var botao = e.target.closest('[data-confirmar]');
                if (!botao || !botao.form) {
                    return;
                }
                e.preventDefault();
                formPendente = botao.form;
                document.getElementById('modal-confirmar-titulo').textContent = botao.dataset.confirmar;
                document.getElementById('modal-confirmar-texto').textContent = botao.dataset.confirmarTexto || '';
                document.getElementById('modal-confirmar-ok').textContent = botao.dataset.confirmarBotao || 'Confirmar';
                document.getElementById('modal-confirmar-voltar').textContent = botao.dataset.confirmarVoltar || 'Cancelar';
                document.getElementById('modal-confirmar-icone').className = 'bi ' + (botao.dataset.confirmarIcone || 'bi-trash') + ' fs-1 text-danger d-block mb-2';
                modal.show();
            });

            document.getElementById('modal-confirmar-ok').addEventListener('click', function () {
                if (formPendente) {
                    this.disabled = true;
                    formPendente.submit();
                }
            });

            modalEl.addEventListener('hidden.bs.modal', function () {
                formPendente = null;
                document.getElementById('modal-confirmar-ok').disabled = false;
            });
        })();

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