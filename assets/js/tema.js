(function () {
    var raiz = document.documentElement;
    var escuroSistema = window.matchMedia('(prefers-color-scheme: dark)');
    var ordem = ['auto', 'claro', 'escuro'];

    function lerTema() {
        try {
            var t = localStorage.getItem('tema');
            return ordem.indexOf(t) >= 0 ? t : 'auto';
        } catch (e) {
            return 'auto';
        }
    }

    function aplicar(tema) {
        var escuro = tema === 'escuro' || (tema === 'auto' && escuroSistema.matches);
        raiz.setAttribute('data-bs-theme', escuro ? 'dark' : 'light');
        document.querySelectorAll('[data-tema]').forEach(function (b) {
            var ativo = b.dataset.tema === tema;
            b.classList.toggle('active', ativo);
            b.setAttribute('aria-pressed', ativo ? 'true' : 'false');
        });
    }

    function guardar(tema) {
        try {
            localStorage.setItem('tema', tema);
        } catch (e) {}
        aplicar(tema);
    }

    aplicar(lerTema());

    escuroSistema.addEventListener('change', function () {
        if (lerTema() === 'auto') {
            aplicar('auto');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        aplicar(lerTema());
    });

    document.addEventListener('click', function (e) {
        var botao = e.target.closest('[data-tema]');
        if (!botao) {
            return;
        }
        var tema = botao.dataset.tema;
        if (raiz.classList.contains('sidebar-recolhida') && window.matchMedia('(min-width: 992px)').matches && tema === lerTema()) {
            tema = ordem[(ordem.indexOf(tema) + 1) % ordem.length];
        }
        guardar(tema);
    });
})();
